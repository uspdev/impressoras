@extends('master')

@section('content')

<div class="card">   
    <table class="table">
        <tr>
            <td>
                <b>Nome:</b> {{ $rule->name }} <br>
                <b>Controle de fila para autorização de impressões:</b> 
                @if ($rule->queue_control  == 0)
                    Não
                @else
                    Sim
                @endif<br>
                <b>Período da quota:</b> {{ $rule->quota_period }} <br>
                <b>Valor da quota:</b> {{ $rule->quota }} <br>
                <b>Impressoras dentro dessa regra:</b>
                @foreach($printers as $printer)
                    <br> {{ $printer->name }}
                @endforeach <br>
                <b>Restrições:</b>
                {{ $rule->categories ? implode(", ", $rule->categories) : "Sem restrições" }}
            </td>
        </tr>
     </table>
</div>
<br>
<div>
  <table>
    <tr>
      <td>
        <form action="/rules/{{ $rule->id }} " method="post">
          @csrf
          @method('delete')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?');">Apagar</button> 
        </form>
      </td>
      <td>
        <form method="POST" action="rules/{{$rule->id}}/edit">
          @csrf
          @method('get')
            <button type="submit" class="btn btn-success"> Editar </a>
        </form>
      </td>
    </tr>
  </table>
</div>

@endsection