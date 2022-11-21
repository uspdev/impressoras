<div class="card">   
    <table class="table">
      <tr>
        <td>
          <b>Nome:</b> {{ $printer->name }} <br>
          <b>Regra:</b> {{ $printer->rule->name }} <br>
          <b>Nome de máquina:</b> {{ $printer->machine_name }}
        </td>
      </tr>
    </table>
</div>
<br>
<div>
  <table>
    <tr>
      <td>
        <form action="/printers/{{ $printer->id }} " method="post">
          @csrf
          @method('delete')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?');">Apagar</button> 
        </form>
      </td>
      <td>
        <form method="POST" action="printers/{{$printer->id}}/edit">
          @csrf
          @method('get')
            <button type="submit" class="btn btn-success"> Editar </a>
        </form>
      </td>
    </tr>
  </table>
</div>