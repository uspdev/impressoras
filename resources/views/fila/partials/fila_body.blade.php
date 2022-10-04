@forelse ($printings as $printing)
<tr>
@can('admin')
<td>{{ $printing->user }}</td>
<td>{{ $printing->host }}</td>
@endcan
<td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
<td>{{ $printing->pages }}</td>
<td>{{ $printing->copies }}</td>
<td>{{ round((float)$printing->filesize/1024) }} MB</td>
<td>{{ $printing->filename }}</td>
<td>{{ $printing->latest_status ?? '' }}</td>
@if ($auth)
  @can('admin')
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
  @endcan
@endif
</tr>
@empty
  <tr>
    <td colspan= @if ($auth) "11" @else "10" @endif>Não há impressões</td>
  </tr>
@endforelse
