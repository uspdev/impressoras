<?php

namespace App\Http\Controllers;


use App\Models\Printer;
use App\Models\Printing;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Uspdev\Replicado\Pessoa;
use App\Jobs\PrintingJob;
use App\Helpers\PrintingHelper;

class WebprintingController extends Controller
{
    public function index(){
        $this->authorize('logado');
        $printers = [];

        foreach (Printer::all() as $p) {
            if ($p->allows(\Auth::user())) {
                array_push($printers, $p);
            }
        }

        \UspTheme::activeUrl('/webprintings');
        return view('webprintings.index', [
            'printers' => $printers,
        ]);
    }

    public function create(Printer $printer){
        $this->authorize('imprime', $printer);
        // tamanho do upload_max_filesize em MB
        $size = ini_parse_quantity(ini_get('upload_max_filesize'))/1024/1024;

        \UspTheme::activeUrl('/webprintings');
        return view('webprintings.create', [
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
