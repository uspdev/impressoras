<?php

namespace App\Http\Controllers;

use App\Models\Printing;
use App\Models\User;
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
     *
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
}
