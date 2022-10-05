    <div class="card-header">
		<h4>
            <b>Histórico (últimos 20 arquivos mandados para impressão)</b>
        </h4>
	</div>
    <div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
                    @include('printings.partials.printings_header')
				</tr>
			</thead>
			<tbody>
            @foreach ($printings_success as $printing)
            <tr>
                @can('monitor')
                <td>{{ $printing->user }}</td>
                <td>{{ $printing->host }}</td>
                @endcan
                <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
                <td>{{ $printing->pages }}</td>
                <td>{{ $printing->copies }}</td>
                <td>{{ round((float)$printing->filesize/1024) }} MB</td>
                <td>{{ $printing->filename }}</td>
                <td>{{ $printing->latest_status ?? '' }}</td>
                @if (!empty($auth))
                <td>{{ $printing->authorizedByUserId->name ?? '' }}</td>
                @endif
            </tr>
			@endforeach
        	</tbody>
		</table>
    </div>