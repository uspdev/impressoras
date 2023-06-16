<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printer;

use Rawilk\Printing\Facades\Printing;    

class WebprintingController extends Controller
{
    public function create(){
        $this->authorize('admin');
        return view('webprintings.create');
    }

    public function store(Request $request){
        $this->authorize('admin');

        // trocando id pelo nome da impressora
        $printer_local = Printer::find($request->printer_id);
        $id = 'ipp://192.168.8.43:631/printers/' . $printer_local->machine_name;

        // fazendo do jeito lento, melhorar!
        $printers = Printing::printers();
        $printer = null;
        foreach ($printers as $p) {
            if($p->id() === $id){
                $printer = $p;
                break;
            }
        }

        if(!$printer) dd('impressora não encontrada');

        $printJob = Printing::newPrintTask()
            ->printer($printer->id())
            ->jobTitle('will2.pdf')
            ->option('sides', 'two-sided-long-edge')
            #->jobUsername('willfromime')
            ->file('/home/thiago/teste5.pdf')
            ->send();

        dd($printJob->id()); // the id number returned from the print server



        
        dd('Thiago e o will vão testar');
    }
}
