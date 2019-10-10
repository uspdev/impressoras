<?php

namespace App\Http\Controllers;

use App\Printing;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Gate;
use App\Rules\Numeros_USP;
use Illuminate\Support\Str;

class PrintingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # todos printings
        //$printings = Printing::allowed();
        $printings = Printing::all();

        // 1. query com a busca
        if(isset($request->printer)) {
            $printings->where('printer', 'LIKE', '%'.$request->printer.'%');
        }

        // Dica de ouro para debugar SQL gerado:
	//dd($printings->toSql());
	
	$codpesuser = \Auth::user();
	$codpesuser = $codpesuser->codpes;
        // Executa a query
        //$printings = $printings->orderBy('jobid')->paginate(10);
	$printings = $printings->where('user', '=', $codpesuser)->sortBy('jobid')/*->paginate(10)*/;
	//$printings = $printings->sortBy('jobid')/*->paginate(10)*/;

        return view('printings/index', compact('printings'));
    }
}
