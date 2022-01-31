<?php

namespace App\Http\Controllers;

use App\Models\Printing;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Gate;
use App\Rules\Numeros_USP;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Uspdev\Replicado\Pessoa;
use Illuminate\Support\Facades\DB;

class PrintingController extends Controller
{
    /**
     * Display a listing of the resource.
     
     * @return \Illuminate\Http\Response
     */
   
    public function autorizacao()
    {
        $this->authorize('admin');
        $allPrintings = Printing::all();
        $printings = collect();
        foreach ($allPrintings as $printing) {
            if ($printing->latest_status()->first()->name == 'waiting_job_authorization') {
                    $printings->push($printing);
                }
        }
        return view('printings.autorizacao', [
            'printings'=> $printings,
        ]);
    }

    public function cancelar(Printing $printing)
    {
        $this->authorize('admin');
        $status = new Status;
        $status->name = 'cancelled_not_authorized';
        $status->printing_id = $printing->id;    
        $status->save();
        request()->session()->flash('alert-success', 'Impressão cancelada com sucesso.');
        return redirect("/printings/autorizacao");
    }

    public function autorizar(Printing $printing)
    {
        $this->authorize('admin');
        $status = new Status;
        $status->name = 'sent_to_printer_queue';
        $status->printing_id = $printing->id;    
        $status->save();
        request()->session()->flash('alert-success', 'Impressão autorizada com sucesso.');
        return redirect("/printings/autorizacao");
    }
}
