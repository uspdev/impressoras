<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Printing;

class PrintingController extends Controller
{
    public function store(Request $request){
        if($request->header('Authorization') != env('API_KEY') ){
            return response('Acesso não autorizado',403);
        }

        $printing = new Printing;

        $printing->jobid = $request->jobid;
        $printing->pages = $request->pages;
        $printing->copies = $request->copies;
        $printing->filename = $request->filename;
        $printing->filesize = $request->filesize;
        $printing->user = $request->user;
        $printing->host = $request->host;
        $printing->save();

        # printer machine_name 
        # $printing->printer_id = null;

        # status?
        
        
        return response()->json(['teste']);

        dd('ok');
    }
}
