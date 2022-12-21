@extends('master')

@section('content')

<div class="card-header">
    <h4><b>Pedido</b></h4>
</div>
<br>
<div class="form-group">
    <form method="POST" action="/pedidos">
    @csrf
        <div class="input-group input-group-sm mb-3">
            <label><b>Número USP: </b></label>
            <br>
            <input name="codpes" type="text" aria-label="Small">
        </div>

        <div class="input-group input-group-sm mb-3">
            <label><b>Quantidade de folhas a pedir: </b></label>
            <br>
            <input name="quantidade" type="number" aria-label="Small">
        </div>
        
        <div class="input-group input-group-sm mb-3">
            <label><b>Quantidade de folhas usadas (quota gasta): </b></label>
            <br>
            <input name="quantidade_usada" type="number" aria-label="Small">
        </div>

        <b>Motivo: </b><br>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="motivo"
            placeholder="Digite o motivo...">{{ old('motivo', $pedido->motivo) }}</textarea>
        <br>

        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>

</div>
@endsection