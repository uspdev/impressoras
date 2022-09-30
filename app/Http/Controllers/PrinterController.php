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

            return redirect('/rules');
        }

        $printer->delete();

        return redirect('/printers');
    }

    public function printer_queue(Printer $printer)
    {
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

    public function authorization_queue(Printer $printer, PhotoService $photos)
    {
        $this->authorize('admin');

        if (!$printer->rule || !$printer->rule->queue_control) {
            return response('', 403);
        }

        $printings = $printer->printings->where('latest_status', 'waiting_job_authorization');

        $fotos = array();

         foreach($printings as $printing){
             $fotos[$printing->user] = $photos->obterFoto($printing->user);
         }

        return view('fila.fila', [
            'printings' => $printings,
            'name' => $printer->name,
            'auth' => true,
            'fotos' => $fotos,
            'printings_success' => $this->historico()
            ]);

    }

    public function historico() {
        $printings_success = Printing::where('latest_status', '=', 'sent_to_printer_queue')
                                ->orderBy('id', 'DESC')->take(20)->get();
        return $printings_success;
    }

}
