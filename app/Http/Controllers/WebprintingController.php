<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebprintingController extends Controller
{
    public function create(){
        $this->authorize('admin');
        return view('webprintings.create');
    }

    public function store(){
        $this->authorize('admin');
        
        dd('Thiago e o will vão testar');
    }
}
