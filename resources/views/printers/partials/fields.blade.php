<ul>
  <li>{{ $printer->name }}</li>
  <li>{{ $printer->rule }}</li>
  <li>
    <form action="/printers/{{ $printer->id }} " method="post">
      @csrf
      @method('delete')
      <button type="submit" onclick="return confirm('Tem certeza?');">Apagar</button> 
    </form>
  </li> 
</ul>