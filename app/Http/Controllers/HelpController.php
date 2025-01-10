<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function raster()
    {
        return view('help.raster');
    }
}
