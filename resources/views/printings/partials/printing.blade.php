@forelse ($printings as $printing)
    <tr>
        <td>{{ $printing->jobid }}</td>
        @can('admin')
          <td>{{ $printing->user }}</td>
          <td>{{ $printing->host }}</td>
        @endcan
        <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
        <td>{{ $printing->pages }}</td>
        <td>{{ $printing->copies }}</td>
        <td>{{ round((float)$printing->filesize/1024) }} MB</td>
        <td>{{ $printing->filename }}</td>
        <td>{{ $printing->printer->name ?? '' }}</td>
        <td>@can('admin')<a href="/printings/status/{{ $printing->id }}">{{ $printing->latest_status->name ?? '' }}</a>@else{{ $printing->latest_status->name ?? '' }} @endcan</td>

    </tr>
@empty
        <tr>
            <td colspan="10">Não há impressões</td>
        </tr>
@endforelse

