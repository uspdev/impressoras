<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printer;
use App\Http\Requests\PrinterRequest;

class PrinterController extends Controller
{
   
	public function index()
	{
        $this->authorize('admin');

	    $printers =  Printer::all();
        return view('printers.index',[
            'printers' => $printers
        ]);
	}

	public function create()
    {
        $this->authorize('admin');

	    return view('printers.create',[
			'printer' => new Printer,
	    ]);
	}

	public function store(PrinterRequest $request)
    {
        $this->authorize('admin');

		$printer = Printer::create($request->validated());

        return redirect("/printers");
	}

	public function edit(Printer $printer)
    {
        $this->authorize('admin');

        return view('printers.edit',[
            'printer' => $printer
        ]);
	}

	public function update(PrinterRequest $request, Printer $printer)
    {
        $this->authorize('admin');

		$printer->update($request->validated());
        return redirect("/printers");
	}

	public function destroy(Printer $printer)
    {
        $this->authorize('admin');

        $printer->delete();
        return redirect('/printers');
	}
}
