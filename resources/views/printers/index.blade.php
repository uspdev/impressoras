@extends('master')

@section('title', 'Impressoras')

@section('content')

    <style>

        #actions
        {
            display: flex;
            justify-content: start;
        }

        #i-trash
        {
            margin-left: 80%;
        }

    </style>

	<div class="card-header">
		<h4><b>Impressoras</b></h4>
        @can('admin')
        <a href="/printers/create"><i class="fas fa-plus"></i> Adicionar impressora</a>
        @endcan
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="20%">Nome</th>
                    <th width="20%">Acessar fila</th>
                    @can('admin')
					<th width="20%">Nome de Máquina</th>
					<th width="20%">Regra</th>
                    <th widht="20%">Ações</th>
                    @endcan
				</tr>
			</thead>
			<tbody>
				@forelse ($printers as $printer)
					<tr>
						<td>{{ $printer->name }}</td>
                        <td>
                            <div class="d-grid gap-2 d-md-block">
                                <a href="/printers/queue/{{ $printer->id }}"><button class="btn btn-primary" type="button">Impressora</button></a>
                                @can('admin')
                                @if ($printer->rule)
                                    @if ($printer->rule->queue_control)
                                        <a href="/printers/auth_queue/{{ $printer->id }}"><button class="btn btn-secondary" type="button">Autorização</button></a>
                                    @endif
                                @endif
                                @endcan
                            </div>
                        </td>
                        @can('admin')
						<td>{{ $printer->machine_name }}</td>
						<td>{{ $printer->rule->name ?? '' }}</td>
                        <td>
                            <div id="actions">
                                <a href="/printers/{{$printer->id}}/edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="/printers/{{ $printer->id }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');" style="background-color: transparent; border: none;">
                                        <a><i class="fas fa-trash" color="#007bff" id="i-trash"></i></a>
                                    </button>    
                                </form>
                            </div>
                        </td>
                        @endcan
					</tr>
                @empty
                    <tr>
                        <td colspan="4">Não há impressoras cadastradas</td>
                    </tr>
				@endforelse
			</tbody>
		</table>

@endsection

