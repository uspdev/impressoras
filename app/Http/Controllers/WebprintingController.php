<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Rawilk\Printing\Facades\Printing;    
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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

        // contagem de páginas usando o pdfinfo
        $pdfinfo = "/usr/bin/pdfinfo";
        if (!File::exists($pdfinfo))
            throw new \Exception("Instalar pdfinfo: apt install poppler-utils.");
        $process = new Process([$pdfinfo, $filepath]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        if (preg_match('/^Pages:\s+(\d+)/m', $process->getOutput(), $matches) != 1)
            throw new \Exception("Problema na contagem: número não encontrado.");

        $pages = $matches[1];
        if ($pages < 1)
            throw new \Exception("Problema na contagem: contagem errada.");

        // aqui roda a lógica de barrar, ou não, o job

        // trocando id pelo nome da impressora
        $printer_local = Printer::find($request->printer_id);
        $id = 'ipp://'.config('printing.drivers.cups.ip').':631/printers/' . $printer_local->machine_name;

        $printJob = Printing::newPrintTask()
            ->printer($id)
            ->jobTitle($filename)
            ->sides($request->sides)
            ->file($filepath)
            ->send();
        Storage::delete($relpath);

        // finaliza
        dd($printJob);
    }
}
