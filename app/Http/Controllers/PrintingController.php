<?php

namespace App\Http\Controllers;

use Auth;

use App\Models\Printing;
use App\Models\User;
use App\Models\Status;
use App\Rules\Numeros_USP;

use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

use Uspdev\Replicado\Pessoa;


class PrintingController extends Controller
{
    /**
     * Display a listing of the resource.
     
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        # printings
        $user = \Auth::user();
        $printings = Printing::where('user', '=', $user->codpes);
        $printings = $printings->orderBy('jobid','DESC')->paginate(10);
        if($request->has('route')) {
          return view('printings/partials/printing',
                       compact('printings'));
        }
        return view('printings/index', compact('printings'));
    }

    public function admin(Request $request)
    {
        $this->authorize('admin');
        sleep(5);
        $printings =  Printing::orderBy('created_at','DESC')->paginate(30);
        $quantidades = Printing::quantidades("impressas");
        if($request->has('route')) {
          return view('printings/partials/printing',
                       compact('printings', 'quantidades'));
        }
        return view('printings/index', compact('printings','quantidades'));
    }  
    
    public function status(Printing $printing)
    {
        $this->authorize('admin');
        $statuses = $printing->status->all();
        return view('printings.status', [
            'printing' => $printing,
            'statuses' => $statuses,
        ]);
    }

    public function fila()
    {
        $allPrintings = Printing::all();
        $printings = collect();
        foreach ($allPrintings as $printing) {
            if (in_array($printing->latest_status()->first()->name, ['waiting_job_authorization', 'sent_to_printer_queue', 'checking_user_quota'])) {
                    $printings->push($printing);
                }
        }
        $printings = $printings->sortByDesc('created_at');
        return view('printings.fila', [
            'printings'=> $printings,
        ]);
    }

    public function acao(Request $request, Printing $printing)
    {
        $this->authorize('admin');
        $status = new Status;
        if ($request->acao == "autorizada") {
            $status->name = 'sent_to_printer_queue';
        } elseif ($request->acao == "cancelada") {
            $status->name = 'cancelled_not_authorized';
        }
        $status->printing_id = $printing->id;    
        $status->save();
        request()->session()->flash('alert-success', 'Impressão ' . $request->acao . ' com sucesso.');
        return redirect("/printings/fila");
    }

    public function pendentes()
    {
    
    }
}
