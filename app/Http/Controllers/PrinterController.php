<?php

namespace App\Http\Controllers;

use App\Helpers\PrintingHelper;
use App\Http\Requests\PrinterRequest;
use App\Jobs\PrintingJob;
use App\Models\Printer;
use App\Models\Printing;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\PhotoService;
use App\Models\User;

class PrinterController extends Controller
{
    public function index()
    {
        $this->authorize('monitor');

        $printers = Printer::all();

        \UspTheme::activeUrl('/printers');
        return view('printers.index', [
            'printers' => $printers,
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('/printers');
        return view('printers.create', [
            'printer' => new Printer(),
        ]);
    }

    public function store(PrinterRequest $request)
    {
        $this->authorize('admin');

        $printer = Printer::create($request->validated());

        \UspTheme::activeUrl('/printers');
        return redirect('/printers');
    }

    public function show(Printer $printer, Request $request)
    {

        $this->authorize('admin');
        $users = User::where('codpes','LIKE', "%$request->search%")->get();

        $query = Printing::where('printer_id', $printer->id);

        $query->when($request->search, function ($q) use ($request) {
            return $q->where( function ($q) use ($request) {
                return $q->orWhere('filename', 'LIKE', "%$request->search%")
                         ->orWhereIn('user_id', $users->pluck('id')->toArray());
            });
        });
        $printings_all = $query->orderBy('id', 'DESC')->paginate();

        \UspTheme::activeUrl('/printers');
        return view('printers.show', [
            'printer' => $printer,
            'printings_all' => $printings_all
        ]);
    }

    public function edit(Printer $printer)
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('/printers');
        return view('printers.edit', [
            'printer' => $printer,
        ]);
    }

    public function update(PrinterRequest $request, Printer $printer)
    {
        $this->authorize('admin');

        $printer->color = isset($request->color);
        $printer->active = isset($request->active);
        $printer->save();

        $printer->update($request->validated());

        \UspTheme::activeUrl('/printers');
        return redirect('/printers');
    }

    public function destroy(Printer $printer)
    {
        $this->authorize('admin');

        if ($printer->printings->isNotEmpty()) {
            request()->session()->flash('alert-danger', 'Há impressões nessa Impressora. Não é possível deletar.');

            \UspTheme::activeUrl('/printers');
            return redirect('/printers');
        }

        $printer->delete();

        \UspTheme::activeUrl('/printers');
        return redirect('/printers');
    }

    public function authorization_queue(Printer $printer, PhotoService $photos, Request $request)
    {
        $this->authorize('monitor');

        if (!$printer->rule || !$printer->rule->queue_control) {
            return response('', 403);
        }

        $printings = $printer->printings->where('latest_status', 'waiting_job_authorization');

        $fotos = array();

        foreach($printings as $printing){
            // Como a função obterFoto espera um inteiro, fizemos um cast do campo
            // $printing->user->codpes para inteiro para contemplar o caso de usuários
            // locais, que possuem username no campo codpes
            $fotos[$printing->user->id] = $photos->obterFoto((int)$printing->user->codpes);
        }

        $printings_queue = Printing::where('printer_id', '=', $printer->id)
                                     ->where('latest_status','!=','waiting_job_authorization')
                                     ->orderBy('id', 'DESC')
                                     ->take(100)->get();

        if($request->has('route')) {
            \UspTheme::activeUrl('/printers');
            return view('fila/partials/fila_body', [
                'printings_queue' => $printings_queue,
                'printings' => $printings,
                'fotos'     => $fotos,
                'auth'      => true,
            ]);
        }

        \UspTheme::activeUrl('/printers');
        return view('fila.fila', [
            'printings' => $printings,
            'name' => $printer->name,
            'fotos' => $fotos,
            'printings_queue' => $printings_queue
        ]);
    }

    public function printTest(Printer $printer)
    {
        $this->authorize('monitor');
        $user = \Auth::user();

        // metadados do arquivo
        $filepath = public_path('printtest.pdf');
        $filesize = File::size($filepath);

        // preaccounting
        $pdfinfo = PrintingHelper::pdfinfo($filepath);

        $data = [
            "user_id" => $user->id,
            "pages" => 1,
            "copies" => 1,
            "printer_id" => $printer->id,
            "jobid" => 0,
            "host" => "127.0.0.1",
            "filename" => "printtest.pdf",
            "filesize" => $filesize,
            "sides" => "one-sided",
            "start_page" => 1,
            "end_page" => 1,
            "shrink" => false,
            "filepath_original" => $filepath,
            "pages_per_sheet" => 1
        ];

        // Aqui não temos ainda o jobid de verdade
        $printing = Printing::create($data);
        Status::createStatus('processing_pdf', $printing);

        // imprimindo
        PrintingJob::dispatch($printing);

        request()->session()->flash('alert-success', 'Teste de impressão enviado com sucesso.');
        \UspTheme::activeUrl('/printers');
        return view('printers.index', [
            'printers' => Printer::all(),
        ]);
    }
}
