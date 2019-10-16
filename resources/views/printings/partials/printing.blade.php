<tr>
<td>{{ $printing->jobid }}</td>
@can('admin')
  <td>{{ $printing->user }}</td>
@endcan
<td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
<td>{{ $printing->pages }}</td>
<td>{{ $printing->copies }}</td>
<td>@php echo substr(explode(' ', $printing->filename, 2)[1],0,25);  @endphp</td>

@can('admin')
<td><a href="/printings/{{ $printing->printer }}">{{ $printing->printer }}</a></td>
@else
<td>{{ $printing->printer }}</td>
@endcan
<td>{{ $printing->status }}</td>
</tr>

