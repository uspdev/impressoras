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
    public function __construct()
    {
        $this->middleware('auth')->except(['check']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # printings
        $user = \Auth::user();
        $printings = Printing::where('user', '=', $user->codpes);
        $printings = $printings->orderBy('jobid','DESC')->paginate(10);

        return view('printings/index', compact('printings'));
    }

    public function admin(Request $request)
    {
        $this->authorize('admin');
        $printings =  Printing::orderBy('jobid','DESC')->paginate(10);
        return view('printings/index', compact('printings'));
    }

    public function admin($user, $pages) {

    }
}
