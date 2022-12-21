@extends('master')

@section('content')

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
      <tr>
        <td>{{ $pedido->user->name }}</td>
        <td>{{ $pedido->quantidade }}</td>
        <td>{{ $pedido->quantidade_usada }}</td>
        <td>{{ $pedido->motivo }}</td>
        <td>{{ $pedido->status }}</td>  
      </tr>
    </tbody>
  </table>  
</div>

@endsection
