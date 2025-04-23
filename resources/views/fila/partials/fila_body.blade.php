@can('monitor')
<table class="table table-striped">
  <thead>
    <tr>
      <th>Usuário</th>
      <th>Data</th>
      <th width="5%">Páginas</th>
      <th width="5%">Folhas</th>
      <th width="5%">Cópias</th>
      <th>Tamanho</th>
      <th>Arquivo</th>
        @can('admin')
          <th width="14%">Foto</th>
          <th width="14%">Ações</th>
        @endcan
    </tr>
  </thead>
  <tbody id="fila">
    @forelse ($printings as $printing)
      <tr>
      <td>
        {{ $printing->user }} - {{ $printing->nome }}
      </td>
      <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
      <td>{{ $printing->pages }}</td>
      <td>{{ $printing->sheets }}</td>
      <td>{{ $printing->copies }}</td>
      <td>{{ round((float)$printing->filesize/1024) }} KB</td>
      <td>{{ $printing->filename }}</td>
          <td>
            <img src="data:image/png;base64, {{ $fotos[$printing->user] }} " width="170px" height="220px"/>
          </td>
          <td>
            <div id="actions">
              <form>
                <a href="/printings/action/{{ $printing->id }}?action=authorized" onclick="return confirm('Tem certeza que deseja autorizar?');"><i class="fas fa-check"></i></a>
                <a href="/printings/action/{{ $printing->id }}?action=cancelled" onclick="return confirm('Tem certeza que deseja cancelar?');"><i class="fas fa-ban"></i></a>
              </form>
            </div>
          </td>
      </tr>
      @empty
        <tr>
          <td colspan=11>Não há impressões</td>
        </tr>
    @endforelse
  </tbody>
</table>

  <div class="card-header">
    <h4>
      <b>Histórico (últimos 100 arquivos mandados esta impressora)</b>
     </h4>
  </div>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
           <th>Usuário</th>
           <th>Data</th>
           <th width="5%">Páginas</th>
           <th width="5%">Folhas</th>
           <th width="5%">Cópias</th>
           <th>Tamanho</th>
           <th>Arquivo</th>
           <th>Status</th>
           <th>Autorizado por</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($printings_queue as $printing)
          <tr>
            <td>{{ $printing->user }} - {{ $printing->nome }}</td>
            <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
            <td>{{ $printing->pages }}</td>
            <td>{{ $printing->sheets }}</td>
            <td>{{ $printing->copies }}</td>
            <td>{{ round((float)$printing->filesize/1024) }} KB</td>
            <td>{{ $printing->filename }}</td>
            <td><b><p style="color:red;">{{ \App\Models\Status::statusName($printing->latest_status) }}</p></b></td>
            <td>{{ $printing->authorizedByUserId->name ?? '' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endcan
