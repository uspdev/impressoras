<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class LoginController extends Controller
{
    function index() {
        return view('login');
    }

    /* veio daqui:
       https://www.positronx.io/laravel-custom-authentication-login-and-registration-tutorial/ */
    function login(Request $request) {
        $request->validate([
            'codpes' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('codpes', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/');
        }

        request()->session()->flash('alert-danger', 'Usuário e senha incorretos.');
        return redirect('/login/local');
    }
}
