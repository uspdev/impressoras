<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use Rawilk\Printing\Facades\Printing;    

class WebprintingController extends Controller
{
    public function create(){
        $this->authorize('admin');
        return view('webprintings.create');
    }

    public function store(Request $request){
        $this->authorize('admin');

        // validação básica
        $request->validate([
            'file' => 'required|mimetypes:application/pdf',
            'printer_id' => 'required|integer', // da para melhorar...
            'sides' => ['required', Rule::in(['one-sided', 'two-sided-long-edge', 'two-sided-short-edge'])],
        ]);

        // metadatas do arquivo
        $relpath = $request->file('file')->store('.');
        $filepath = Storage::disk('local')->path($relpath);
        $filename = $request->file('file')->getClientOriginalName();

        # WILL vai melhorar
        $pages = system("/usr/bin/pdfinfo $filepath| grep Pages | awk '{print $2}'");

        
        dd($pages);


        // trocando id pelo nome da impressora
        $printer_local = Printer::find($request->printer_id);
        $id = 'ipp://'.config('printing.drivers.cups.ip').':631/printers/' . $printer_local->machine_name;

        $printJob = Printing::newPrintTask()
            ->printer($id)
            ->jobTitle($filename)
            ->sides($request->sides)
            ->file($filepath)
            ->send();

        // depois de impresso, deletar arquivo no laravel ?
        Storage::delete($relpath);

        dd($printJob->id()); // the id number returned from the print server
    }
}
