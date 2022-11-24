<div class="card">   
    <table class="table">
      <tr>
        <td>
          <b>Nome:</b> {{ $printer->name }} <br>
          <b>Regra:</b> {{ $printer->rule ? $printer->rule->name : 'Impressora não possui regra' }} <br>
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

<br>
<div class="card-header">
		<h4>
      <b>Impressões registradas na impressora:</b>
    </h4>
</div>

  {{ $printings_all->appends(request()->query())->links() }}

  <div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
          @can('admin')
          <form method="get">
            <div class="row">
              <div class=" col-sm input-group">
                <input type="text" class="form-control" name="search" value="{{ request()->search }}" placeholder="Insira o nome do arquivo ou número USP">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-success"> Buscar </a></button>
                  </span>
              </div>
            </div>
          </form>
          @endcan 
          <th>Usuário</th>
          <th>Data</th>
          <th width="5%">Páginas</th>
          <th width="5%">Cópias</th>
          <th>Tamanho</th>
          <th>Arquivo</th>
          <th>Status</th>
          @if(!empty($auth))
          <th>Autorizado por</th>
          @endif
				</tr>
			</thead>
			<tbody>
        @foreach ($printings_all as $printing)
          <tr>
              @can('admin')
              <td>{{ $printing->user }} - {{ $printing->nome }}</td>
              @endcan
              <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
              <td>{{ $printing->pages }}</td>
              <td>{{ $printing->copies }}</td>
              <td>{{ round((float)$printing->filesize/1024) }} KB</td>
              <td>{{ $printing->filename }}</td>
              <td><b><p style="color:red;">{{ \App\Models\Status::statusName($printing->latest_status) }}</p></b></td>
              @if (!empty($auth))
              <td>{{ $printing->authorizedByUserId->name ?? '' }}</td>
              @endif
            </tr>
			  @endforeach
      </tbody>
		</table>
  </div>