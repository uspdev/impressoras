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
        $quantities['Mensal_Páginas'] = Printing::getPrintingsQuantities($user->codpes, null, 'Mensal', 'Páginas');
        $quantities['Diário_Páginas'] = Printing::getPrintingsQuantities($user->codpes, null, 'Diário', 'Páginas');
        $quantities['Total_Páginas'] = Printing::getPrintingsQuantities($user->codpes, null, null, 'Páginas');
        $quantities['Mensal_Folhas'] = Printing::getPrintingsQuantities($user->codpes, null, 'Mensal', 'Folhas');
        $quantities['Diário_Folhas'] = Printing::getPrintingsQuantities($user->codpes, null, 'Diário', 'Folhas');
        $quantities['Total_Folhas'] = Printing::getPrintingsQuantities($user->codpes, null, null, 'Folhas');
        $auth = true;

        \UspTheme::activeUrl('/printings');
        return view('printings/index',[
            'printings' => $printings,
            'quantities' => $quantities,
            'user' => $user,
            'auth' => $auth
        ]);
    }

    public function show(Request $request){
        $this->authorize('monitor');

        $printings = Printing::orderBy('id', 'DESC');

        if(isset($request->search)) {
            $printings = $printings->where('filename','LIKE',"%{$request->search}%")
                                    ->Orwhere('user', 'LIKE', "%{$request->search}%");
        }

        if(isset($request->status)) {
            $printings =  $printings->where('latest_status',$request->status);
        }

        \UspTheme::activeUrl('/all-printings');
        return view('allprintings.geral_index',[
            'printings' => $printings->paginate(15),
        ]);
    }

    public function status(Printing $printing)
    {
        $this->authorize('admin');

        $statuses = $printing->status->sortByDesc('created_at')->all();

        \UspTheme::activeUrl('/printings');
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

        \UspTheme::activeUrl('/printers');
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

        \UspTheme::activeUrl('/all-printings');
        return redirect("/all-printings");
    }
}
