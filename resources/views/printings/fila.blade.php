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
		<h4><b>Fila de {{ $name }}</b></h4>
	</div>
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
                    @can('admin')
                        <th width="14%">Ação</th>
                    @endcan
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
						<td>{{ $printing->latest_status()->first()->name }}</td>
                        @can('admin')
                            <td>
                                <div id="actions">
                                    <a href="/printings/acao/{{ $printing->id }}?acao=autorizada" onclick="return confirm('Tem certeza que deseja autorizar?');"><i class="fas fa-check"></i></a>
                                    <a href="/printings/acao/{{ $printing->id }}?acao=cancelada" onclick="return confirm('Tem certeza que deseja cancelar?');"><i class="fas fa-ban" id="i-ban"></i></a>
                                    </form>
                                </div>
                            </td>
                        @endcan 
					</tr>
                @empty
                    <tr>
                        <td colspan="7">Não há impressões</td>
                    </tr>
				@endforelse
			</tbody>
		</table>
@endsection
