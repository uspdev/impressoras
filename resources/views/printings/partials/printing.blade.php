@forelse ($printings as $printing)
    <tr>
        @can('admin')
        <td><a href="/printings/status/{{ $printing->id }}">{{ $printing->jobid }}</a></td>
        <td>{{ $printing->user }}</td>
        <td>{{ $printing->host }}</td>
        @else
        <td>{{ $printing->jobid }}</td>      
        @endcan
        <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
        <td>{{ $printing->pages }}</td>
        <td>{{ $printing->copies }}</td>
        <td>{{ round((float)$printing->filesize/1024) }} MB</td>
        <td>{{ $printing->filename }}</td>
        <td>{{ $printing->printer->name ?? '' }}</td>
        <td>{{ $printing->latest_status ?? '' }}</td>

    </tr>
@empty
        <tr>
            <td colspan="10">Não há impressões</td>
        </tr>
@endforelse

