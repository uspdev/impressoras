<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebprintingController extends Controller
{
    public function create(){
        $this->authorize('logado');
        return view('webprintings.create');
    }

    public function store(){
        $this->authorize('logado');
        
        dd('Thiago e o will vão testar');
    }
}
