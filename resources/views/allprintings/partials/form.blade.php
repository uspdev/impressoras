<td>{{ $printing->user }}</td>
<td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
<td>{{ $printing->pages }}</td>
<td>{{ $printing->copies }}</td>
<td>{{ round((float)$printing->filesize/1024) }} KB</td>
<td>{{ $printing->filename }}</td>
<td>{{ $printing->printer->name }}</td>
<td>{{ $printing->id }}</td>
@if ($printing->latest_status == 'print_success')
  <td>
    <span style="color:green; margin-right: 5px"><b>Sucesso</b></span><a href="{{ url('printings/refund', [ 'id' => $printing->id ]) }}">Devolver quota</a>
  </td>
@else
  <td><b><p style="color:red;">{{ \App\Models\Status::statusName($printing->latest_status) }}</p></b></td>
@endif
@if (!empty($auth))
<td>{{ $printing->authorizedByUserId->name ?? '' }}</td>
@endif
