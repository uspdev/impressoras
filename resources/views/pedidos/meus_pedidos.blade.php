@extends('master')

@section('content')

<div class="card-header">
    <h4><b>Meus pedidos</b></h4>
</div>

{{ $pedidos->appends(request()->query())->links() }}

<div class="card">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">Registrado por:</th>
        <th scope="col">Folhas pedidas:</th>
        <th scope="col">Quota gasta:</th>
        <th scope="col">Motivo:</th>
        <th scope="col">Status:</th>
      </tr>
    </thead>
    <tbody>
    @foreach($pedidos as $pedido)
      <tr>
        <td>{{ $pedido->user->name }}</td>
        <td>{{ $pedido->quantidade }}</td>
        <td>{{ $pedido->quantidade_usada }}</td>
        <td>{{ $pedido->motivo }}</td>
        <td>{{ $pedido->status }}</td>  
      </tr>
    @endforeach
    </tbody>
  </table>  
</div>

@endsection
