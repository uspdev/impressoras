@extends('master')

@section('content')

<div class="card-header font-weight-bold">
  <h4><b>Pedidos</b></h4>
</div>

{{ $pedidos->appends(request()->query())->links() }}

<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">Registrado por:</th>
      <th scope="col">Folhas pedidas:</th>
      <th scope="col">Quota gasta:</th>
      <th scope="col">Motivo:</th>
      <th scope="col">Situação:</th>
    </tr>
  </thead>
  <tbody>
    @foreach($pedidos as $pedido)
    <tr>
      <td>{{ $pedido->user->name ?? '' }}</td>
      <td>{{ $pedido->quantidade }}</td>
      <td>{{ $pedido->quantidade_usada }}</td>
      <td>{{ $pedido->motivo }}</td>
        <td>
          Status do pedido - {{ $pedido->status }}
          @if($pedido->status == 'Aguardando')
            <div id="actions">
              <form method="POST" action="pedidos/{{$pedido->id}}/accepted">
                @csrf
                  <button type="submit" class="btn btn-success">Aceitar</button>
              </form>
              <br>
              <form method="POST" action="pedidos/{{$pedido->id}}/refused">
                @csrf
                  <button type="submit" class="btn btn-danger">Recusar</button>
              </form>
            </div>
          @endif
        </td>  
    </tr>
  @endforeach
  </tbody>
</table>  
</div>

@endsection