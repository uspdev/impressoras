<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PedidoRequest;
use App\Models\Pedido;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('logado');

        $pedidos = Pedido::paginate(10);
        
        return view('pedidos.index', [
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('logado');

        return view('pedidos.create', [
            'pedido' => new Pedido,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePedidoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PedidoRequest $request)
    {
        $this->authorize('logado');

        $validated = $request->validated();
        $validated['user_id'] = auth()->user()->id;
        $pedido = Pedido::create($validated);        
        request()->session()->flash('alert-info','Pedido registrado com sucesso - aguarde o processamento.');

        return redirect("/pedidos/{$pedido->id}");
    }

    
    public function meusPedidos(){

        $this->authorize('logado');

        $user = \Auth::user();
        $pedidos = Pedido::where('user_id', $user->id)->paginate(10);

        return view('pedidos.meus_pedidos', [
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        $this->authorize('logado');

        return view('pedidos.show', [
            'pedido' => $pedido
        ]);
    }

    public function accepted(Pedido $pedido)
    {
        $this->authorize('monitor');

        $pedido->status = 'Aceito';
        $pedido->save();
        
        return back();
    }

    public function refused(Pedido $pedido){

        $this->authorize('monitor');

        $pedido->status = 'Recusado';
        $pedido->save();

        return back();
    }

}
