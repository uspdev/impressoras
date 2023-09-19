<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    function index() {
        return view('login');
    }

    /* veio daqui
       https://www.positronx.io/laravel-custom-authentication-login-and-registration-tutorial/
    */
    function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/');
        }

        request()->session()->flash('alert-danger', 'E-mail e senha incorretos.');
        return redirect('/login/local');
    }
}
