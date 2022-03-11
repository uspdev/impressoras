<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrinterRequest;
use App\Models\Printer;
use App\Models\Printing;
use Illuminate\Http\Request;

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
        $printings = $printer->printings()->get();
        $name = $printer->name;

        return view('printings.fila', [
            'printings' => $printings,
            'name' => $name,
            'auth' => false,
        ]);
    }

    public function authorization_queue(Printer $printer)
    {
        if (!$printer->rule || !$printer->rule->queue_control) {
            return response('', 403);
        }

        $this->authorize('admin');

        $printings_id = Printing::getPrintingsFromScope($printer, 'waiting_job_authorization');
        $printings = Printing::findMany($printings_id);
        $name = $printer->name;

        return view('printings.fila', [
            'printings' => $printings,
            'name' => $name,
            'auth' => true,
        ]);
    }
}
