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
        return view('login.index');
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

        request()->session()->flash('alert-danger', 'E-mail e senha incorretos.');
        return redirect('/login/local');
    }

    function create() {
        $this->authorize('admin');
        return view('login.create');
    }

    function store(Request $request) {
        $this->authorize('admin');

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'codpes' => 'required',
            'password' => 'required|min:6'
        ]);

        /* garante que existe a permission para locais
           TODO consertar para usar Permissions em geral */
        $p = Permission::findOrCreate('Outros', 'senhaunica');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'codpes' => $request->codpes,
            'password' => Hash::make($request->password),
        ]);

        $user->givePermissionTo($p);

        request()->session()->flash('alert-success', 'Usuário criado com sucesso.');
        return view('login.create');
    }
}
