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
use App\Jobs\PrintingJob;

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

        // Salva quem autorizou
        $user = \Auth::user();
        $printing->authorized_by_user_id = $user->id;
        $printing->save();

        if ($request->action == 'authorized') {
            Status::createStatus('processing_pdf', $printing);
            request()->session()->flash('alert-success', 'Impressão autorizada com sucesso.');
            PrintingJob::dispatch($printing);
        } elseif ($request->action == 'cancelled') {
            Status::createStatus('cancelled_not_authorized', $printing);
            request()->session()->flash('alert-danger', 'Impressão cancelada');
        }

        return redirect("/printers/auth_queue/{$printing->printer->id}");
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
