@extends('master')

@section('title', 'Fila')

@section('content')

    <style>

        #actions
        {
            display: flex;
            justify-content: start;
        }

        #i-ban
        {
            margin-left: 80%;
        }

        button
        {
            background-color: transparent; 
            border: none;
        }

    </style>

	<div class="card-header">
		<h4><b>Fila de 
            @if ($auth)
                autorização de
            @endif
            {{ $name }}</b>
        </h4>
	</div>

    <br>
    
    @if(!$auth)
    @include('printings.partials.printing_quantities')
    @endif

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="15%">Nome do arquivo</th>
					<th width="14%">Tamanho</th>
					<th width="14%">Páginas</th>
					<th width="14%">Usuário (N.USP)</th>
                    <th width="14%">Host</th>
                    <th width="15%">Status</th>
                    @if ($auth)
                        @can('admin')
                        <th width="14%">Ação</th>
                        @endcan
                    @endif
				</tr>
			</thead>
			<tbody>
				@forelse ($printings as $printing)
					<tr>
						<td>{{ $printing->filename }}</td>
						<td>{{ $printing->filesize }}</td>
						<td>{{ (int)$printing->pages*(int)$printing->copies }}</td>
						<td>{{ $printing->user }}</td>
						<td>{{ $printing->host }}</td>
						<td>
                            @can('admin')
                            <a href="/printings/status/{{ $printing->id }}">{{ $printing->latest_status }}</a>
                            @else
                            {{ $printing->latest_status }}
                            @endcan
                        </td>
                        @if ($auth)
                            @can('admin')
                                <td>
                                    <div id="actions">
                                        <a href="/printings/action/{{ $printing->id }}?action=authorized" onclick="return confirm('Tem certeza que deseja autorizar?');"><i class="fas fa-check"></i></a>
                                        <a href="/printings/action/{{ $printing->id }}?action=cancelled" onclick="return confirm('Tem certeza que deseja cancelar?');"><i class="fas fa-ban" id="i-ban"></i></a>
                                        </form>
                                    </div>
                                </td>
                            @endcan 
                        @endif
					</tr>
                @empty
                    <tr>
                        <td colspan="7">Não há impressões</td>
                    </tr>
				@endforelse
			</tbody>
		</table>
@endsection
