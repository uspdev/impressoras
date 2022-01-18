<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printing;

class StatusController extends Controller
{
    public function index()
    {
        $this->authorize('admin');

        $printings = new Printing;

        $printings = $printings->where(function( $query ) use ( $request ){

            // Model: Status  campo: name
            $query->orWhereHas('status', function (Builder $query) use ($request){
                $query->where('name','LIKE',"waiting_job_authorization");
            })
        });

        $printings->orderBy('timestamps', 'desc')->paginate(10);

        return view('status.index', [
           'printings' => $printings 
        ]);
    } 
}
