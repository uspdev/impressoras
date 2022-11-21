<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrinterRequest;
use App\Models\Printer;
use App\Models\Printing;
use Illuminate\Http\Request;
use App\Services\PhotoService;

class PrinterController extends Controller
{
    public function index()
    {
        $this->authorize('monitor');

        $printers = Printer::all();

        return view('printers.index', [
            'printers' => $printers,
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        return view('printers.create', [
            'printer' => new Printer(),
        ]);
    }

    public function store(PrinterRequest $request)
    {
        $this->authorize('admin');

        $printer = Printer::create($request->validated());

        return redirect('/printers');
    }

    public function show(Printer $printer, Request $request)
    {

        $this->authorize('admin');

        $printings_all = Printing::where('printer_id', $printer->id);            
        
        if(isset($request->search)) 
        {
            $printings_all = $printings_all->where('filename','LIKE',"%{$request->search}%")
                                            ->orWhere('user', 'LIKE', "%{$request->search}%");
        }  

        $printings_all = $printings_all->orderBy('id', 'DESC')->paginate(15);                             

        return view('printers.show', [
            'printer' => $printer,
            'printings_all' => $printings_all
        ]);
    }

    public function edit(Printer $printer)
    {
        $this->authorize('admin');

        return view('printers.edit', [
            'printer' => $printer,
        ]);
    }

    public function update(PrinterRequest $request, Printer $printer)
    {
        $this->authorize('admin');

        $printer->update($request->validated());

        return redirect('/printers');
    }

    public function destroy(Printer $printer)
    {
        $this->authorize('admin');

        if ($printer->printings->isNotEmpty()) {
            request()->session()->flash('alert-danger', 'Há impressões nessa Impressora. Não é possível deletar.');

            return redirect('/printers');
        }

        $printer->delete();

        return redirect('/printers');
    }

    public function printer_queue(Printer $printer)
    {
        $this->authorize('monitor');

        $printings = $printer->printings()->paginate(10);
        $quantities['Mensal'] = Printing::getPrintingsQuantities(null, $printer, 'Mensal');
        $quantities['Diário'] = Printing::getPrintingsQuantities(null, $printer, 'Diário');
        $quantities['Total'] = Printing::getPrintingsQuantities(null, $printer);

        return view('fila.fila', [
            'printings' => $printings,
            'name' => $printer->name,
            'quantities' => $quantities,
            'auth' => false,
        ]);
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
            $fotos[$printing->user] = $photos->obterFoto($printing->user);
        }

        if($request->has('route')) {
            return view('fila/partials/fila_body', [
                'printings' => $printings,
                'fotos'     => $fotos,
                'auth'      => true,
            ]);
        }

        $printings_queue = Printing::where('printer_id', '=', $printer->id)
                                     ->where('latest_status','!=','waiting_job_authorization');
                            
        if(isset($request->search)) {
            $printings_queue = $printings_queue->where('filename','LIKE',"%{$request->search}%")
                                                ->Orwhere('user', 'LIKE', "%{$request->search}%");
        }                           

        $printings_queue = $printings_queue->orderBy('id', 'DESC')->take(100)->get();                             

        return view('fila.fila', [
            'printings' => $printings,
            'name' => $printer->name,
            'auth' => true, # não entendi a necessidade... ?
            'fotos' => $fotos,
            'printings_queue' => $printings_queue
            ]);
        }
}
