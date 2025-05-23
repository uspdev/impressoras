@can('admin')
<td>{{ $printing->user->codpes }}</td>
@endcan
<td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
<td>{{ $printing->pages }}</td>
<td>{{ $printing->copies }}</td>
<td>{{ round((float) $printing->filesize/1024) }} KB</td>
<td>{{ $printing->filename }}</td>
<td>{{ $printing->printer->name }}</td>
<td>
<b>
  <p style="color:red;">{{ \App\Models\Status::statusName($printing->latest_status) }}</p>
  @if ($printing->latest_status == 'failed_in_process_pdf')
    <a href="/help/raster">Como resolver?</a>
  @endif
</b>
</td>
@if (!empty($auth))
<td>{{ $printing->authorizedByUserId->name ?? '' }}</td>
@endif
