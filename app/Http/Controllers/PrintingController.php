<?php

namespace App\Http\Controllers;

use App\Models\Printing;
use App\Models\Printer;
use App\Models\Status;
use Illuminate\Http\Request;
use Rawilk\Printing\Facades\Printing as CupsPrinting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\PrintingHelper;

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
        $printings = Printing::where('user', '=', $user->codpes)
                                ->latest()
                                ->when($request->search, function($query) use($request){
                                    return $query->where('filename','LIKE',"%{$request->search}%");
                                })
                                ->paginate(5);
        $quantities['Mensal'] = Printing::getPrintingsQuantities($user->codpes, null, 'Mensal');
        $quantities['Diário'] = Printing::getPrintingsQuantities($user->codpes, null, 'Diário');
        $quantities['Total'] = Printing::getPrintingsQuantities($user->codpes);
        $auth = true;

        return view('printings/index',[
            'printings' => $printings, 
            'quantities' => $quantities, 
            'user' => $user, 
            'auth' => $auth
        ]);
    }

    public function show(Request $request){
        $this->authorize('monitor');
        
        if(isset($request->search)) {
            $printings = Printing::where('filename','LIKE',"%{$request->search}%")
                                    ->Orwhere('user', 'LIKE', "%{$request->search}%")
                                    ->latest()
                                    ->paginate(15);
        } else {
            $printings = Printing::latest()->paginate(15);
        }

        return view('allprintings.geral_index',[
            'printings' => $printings,
        ]);
    }

    public function status(Printing $printing)
    {
        $this->authorize('admin');
        
        $statuses = $printing->status->sortByDesc('created_at')->all();

        return view('printings.status', [
            'printing' => $printing,
            'statuses' => $statuses,
        ]);
    }

    public function action(Request $request, Printing $printing)
    {
        $this->authorize('monitor');

        $printer = $printing->printer;
        $user = \Auth::user();
        $printing->authorized_by_user_id = $user->id;
        $printing->save();

        if ($request->action == 'authorized') {
            Status::createStatus('sent_to_printer_queue', $printing);
            request()->session()->flash('alert-success', 'Impressão autorizada com sucesso.');

            // trocando id pelo nome da impressora
            $printer = Printer::find($printing->printer_id);
            $id = 'ipp://'.config('printing.drivers.cups.ip').':631/printers/' . $printer->machine_name;

            $filepath = Storage::disk('local')->path($printing->tmp_relpath);

            // preaccounting
            $pdfinfo = PrintingHelper::pdfinfo($filepath);

            // REPETIDO - Trata o PDF antes de mandá-lo para a impressora
            $pps = $printing->pages_per_sheet;
            if (!empty($request->start_page)) {
                $start = $printing->start_page;
                // trata possível erro de preenchimento
                $end = min($pdfinfo['pages'], $printing->end_page);
                $tmp_pdf = PrintingHelper::pdfjam($filepath, $pps, $start, $end);
            }
            else
                $tmp_pdf = PrintingHelper::pdfjam($filepath, $pps);

            $pdfx = PrintingHelper::pdfx($tmp_pdf);
            // pode ser interessante implementar uma validação da contagem

            $printJob = CupsPrinting::newPrintTask()
                ->printer($id)
                ->jobTitle($printing->filename)
                ->sides($printing->sides)
                ->file($pdfx)
                ->send();
            Storage::delete($printing->tmp_relpath);
            File::delete($tmp_pdf);
            File::delete($pdfx);

            $printing->jobid = $printJob->id();
            $printing->save();
            Status::createStatus('print_success', $printing);
            

        } elseif ($request->action == 'cancelled') {
            Status::createStatus('cancelled_not_authorized', $printing);
            request()->session()->flash('alert-danger', 'Impressão cancelada');
        }

        

        return redirect("/printers/auth_queue/{$printer->id}");
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

        return redirect("/all-printings");
    }
}
