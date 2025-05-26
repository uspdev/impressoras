<?php

namespace App\Http\Controllers;

use App\Models\Printing;
use App\Models\Printer;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Rawilk\Printing\Facades\Printing as CupsPrinting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use App\Helpers\PrintingHelper;
use App\Jobs\PrintingJob;

class PrintingController extends Controller
{
    /**
     * Display a listing of the resource.

     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // printings
        $this->authorize('logado');
        $user = \Auth::user();
        $printings = Printing::where('user_id', '=', $user->id)
                                ->latest()
                                ->when($request->search, function($query) use($request){
                                    return $query->where('filename','LIKE',"%{$request->search}%");
                                })
                                ->where('filepath_original', 'NOT LIKE', '%public/printtest.pdf')    // descarta impressões de teste
                                ->paginate(5);
        $quantities['Mensal'] = PrintingHelper::getPrintingsQuantities($user, null, 'Mensal');
        $quantities['Diario'] = PrintingHelper::getPrintingsQuantities($user, null, 'Diário');
        $quantities['Total'] = PrintingHelper::getPrintingsQuantities($user);
        $auth = true;

        \UspTheme::activeUrl('/printings');
        return view('printings/index',[
            'printings' => $printings,
            'quantities' => $quantities,
            'user' => $user,
            'auth' => $auth
        ]);
    }

    public function show(Request $request){
        $this->authorize('monitor');

        $users = User::where('codpes','LIKE', "%$request->search%")->get();
        $printings = Printing::orderBy('id', 'DESC')
                             ->where('filepath_original', 'NOT LIKE', '%public/printtest.pdf');    // descarta impressões de teste

        if(isset($request->search)) {
            $printings = $printings->where('filename','LIKE',"%{$request->search}%")
                                    ->orWhereIn('user_id', $users->pluck('id')->toArray());
        }

        if(isset($request->status)) {
            $printings =  $printings->where('latest_status',$request->status);
        }

        \UspTheme::activeUrl('/all-printings');
        return view('allprintings.geral_index',[
            'printings' => $printings->paginate(15),
        ]);
    }

    public function status(Printing $printing)
    {
        $this->authorize('admin');

        $statuses = $printing->status->sortByDesc('created_at')->all();

        \UspTheme::activeUrl('/printings');
        return view('printings.status', [
            'printing' => $printing,
            'statuses' => $statuses,
        ]);
    }

    public function action(Request $request, Printing $printing)
    {
        $this->authorize('monitor');

        // Salva quem autorizou
        $user = \Auth::user();
        $printing->authorized_by_user_id = $user->id;
        $printing->save();

        if ($request->action == 'authorized') {
            Status::createStatus('processing_pdf', $printing);
            request()->session()->flash('alert-success', 'Impressão autorizada com sucesso.');
            PrintingJob::dispatch($printing);
        } elseif ($request->action == 'cancelled') {
            Status::createStatus('cancelled_not_authorized', $printing);
            request()->session()->flash('alert-danger', 'Impressão cancelada');
        }

        \UspTheme::activeUrl('/printers');
        return redirect("/printers/auth_queue/{$printing->printer->id}");
    }

    public function refund(Printing $printing)
    {
        $this->authorize('monitor');

        $printer = $printing->printer;
        $user = \Auth::user();
        $printing->authorized_by_user_id = $user->id;
        $printing->save();

        Status::createStatus('printer_problem', $printing);
        request()->session()->flash('alert-success', 'Quota devolvida.');

        \UspTheme::activeUrl('/all-printings');
        return redirect("/all-printings");
    }

    public function listPrinters(){
        $this->authorize('logado');
        $printers = [];

        foreach (Printer::all() as $p) {
            if ($p->allows(\Auth::user())) {
                array_push($printers, $p);
            }
        }

        \UspTheme::activeUrl('/printings/print');
        return view('printings.listPrinters', [
            'printers' => $printers,
        ]);
    }

    public function create(Printer $printer){
        $this->authorize('imprime', $printer);
        // tamanho do upload_max_filesize em MB
        $size = ini_parse_quantity(ini_get('upload_max_filesize'))/1024/1024;

        \UspTheme::activeUrl('/printings/print');
        return view('printings.create', [
            'printer' => $printer,
            'size' => $size,
        ]);
    }

    public function store(Request $request, Printer $printer){
        $this->authorize('imprime', $printer);
        $user = \Auth::user();

        // validação básica
        $request->validate([
            'file' => 'required|mimetypes:application/pdf',
            'sides' => ['required', Rule::in(['one-sided', 'two-sided-long-edge', 'two-sided-short-edge'])],
            'pages_per_sheet' => ['required', Rule::in(['1', '2', '4'])],
            'copies' => 'required|integer|between:1,1000',
            'start_page' => 'nullable|required_with:end_page|integer|min:1|digits_between: 1,5',
            'end_page' =>   'nullable|required_with:start_page|integer|gte:start_page|digits_between:1,5'
        ]);

        // metadados do arquivo
        $filepath = Storage::disk('local')->path($request->file('file')->store('.'));
        $filename = $request->file('file')->getClientOriginalName();
        $filesize = $request->file('file')->getSize();

        // preaccounting
        $pdfinfo = PrintingHelper::pdfinfo($filepath);
        if (!empty($request->start_page)) {
            // end - start pode ser maior que a contagem total por erro de preenchimento
            $pages = min($pdfinfo['pages'], $request->end_page - $request->start_page + 1);
        }
        else {
            $pages = $pdfinfo['pages'];
        }

        $pages = ceil($pages/$request->pages_per_sheet);
        if ($pages < 1) {
            request()->session()->flash('alert-danger','Problema na contagem de páginas');
            Status::createStatus('failed_in_process_pdf', $printing);
            \UspTheme::activeUrl('/printings');
            return redirect("/printings");
        }

        // trunca nome para no máximo 64 caracteres
        $filename = explode('.pdf',$filename);
        $filename = mb_substr($filename[0],0,64).'.pdf';

        // se existir regra do tipo "Folhas", na tabela printings gravamos no campo pages as folhas ao invés das páginas
        if (!empty($printer->rule) && ($printer->rule->quota_type == "Folhas"))
            $pages = ($request->sides == 'one-sided' ? $pages : ceil($pages/2));

        $data = [
            "user_id" => $user->id,
            "pages" => $pages,
            "copies" => $request->copies,
            "printer_id" => $printer->id,
            "jobid" => 0,
            "host" => "127.0.0.1",
            "filename" => $filename,
            "filesize" => $filesize,
            "sides" => $request->sides,
            "start_page" => $request->start_page,
            "end_page" => $request->end_page,
            "shrink" => $request->has('shrink'),
            "filepath_original" => $filepath,
            "pages_per_sheet" => $request->pages_per_sheet
        ];

        // Aqui não temos ainda o jobid de verdade
        $printing = Printing::create($data);
        Status::createStatus('processing_pdf', $printing);

        if (!empty($printer->rule)) {
            // 1. Verifica se ultrapassou da quota disponível ou não
            $quota_period = $printer->rule->quota_period;

            if (!empty($quota_period)) {
                // as impressoras que participam da mesma regra
                $quantities = $printer->used($user);
                $out_of_quota = ($quantities + $printing->pages*$printing->copies) > $printer->rule->quota;
                if ($out_of_quota) {
                    Status::createStatus('cancelled_user_out_of_quota', $printing);
                    \UspTheme::activeUrl('/printings');
                    return redirect("/printings");
                }
            }

            // 3. Verifica se a impressora tem controle de fila
            if ($printer->rule->queue_control) {
                Status::createStatus('waiting_job_authorization', $printing);
                \UspTheme::activeUrl('/printings');
                return redirect("/printings");
            }
        }

        // imprimindo
        PrintingJob::dispatch($printing);
        \UspTheme::activeUrl('/printings');
        return redirect("/printings");
    }
}
