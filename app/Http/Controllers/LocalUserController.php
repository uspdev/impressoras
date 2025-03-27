<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class LocalUserController extends Controller
{
    function index()
    {
        $this->authorize('admin');

        $users = User::where('local', '1')->get();

        \UspTheme::activeUrl('/local');
        return view('local.index', [
            'users' => $users,
        ]);
    }

    function create()
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('/local');
        return view('local.create', [
            'user' => new User(),
        ]);
    }

    function store(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'codpes' => 'required',
            'password' => 'required|min:6',
        ]);

        /* garante que existe a permission para locais
           TODO consertar para usar Permissions em geral */
        $p = Permission::findOrCreate('Outros', 'senhaunica');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'codpes' => $request->codpes,
            'password' => Hash::make($request->password),
            'local' => '1',
        ]);

        $user->givePermissionTo($p);

        \UspTheme::activeUrl('/local');
        return redirect('/local');
    }

    function edit(User $user)
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('/local');
        return view('local.edit', [
            'user' => $user,
        ]);
    }

    function update(Request $request, User $user)
    {
        $this->authorize('admin');

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'codpes' => 'required',
            'password' => 'required|min:6',
        ]);

        $request->merge(['password' => Hash::make($request->password)]);
        $user->update($request->all());

        \UspTheme::activeUrl('/local');
        return redirect('/local');
    }

    function destroy(User $user)
    {
        $this->authorize('admin');

        if ($user->local == false) {
            request()->session()->flash('alert-danger', 'Usuário senha única não pode ser apagado.');

            \UspTheme::activeUrl('/local');
            return redirect('/local');
        }

        $user->delete();

        \UspTheme::activeUrl('/local');
        return redirect('/local');
    }
}
