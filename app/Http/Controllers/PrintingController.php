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
        $printings = Printing::all();
        dd($printings);
   
        return view('printings.autorizacao', [
            'printings'=> $printings,
        ]);
    }
}
