<tr>
<td>{{ $printing->jobid }}</td>
<td>{{ $printing->user }}</td>
<td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
<td>{{ $printing->pages }}</td>
<td>{{ $printing->copies }}</td>
<td>@php echo substr(explode(' ', $printing->filename, 2)[1],0,40);  @endphp</td>
<td>{{ $printing->printer }}</td>
</tr>

