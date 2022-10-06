    <div class="card-header">
		<h4>
            <b>Histórico (últimos 100 arquivos mandados esta impressora)</b>
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
            @foreach ($printings_queue as $printing)
            <tr>
                @can('monitor')
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