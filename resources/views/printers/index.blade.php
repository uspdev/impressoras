@extends('master')

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
        <a href="/printers/create"><i class="fas fa-plus"></i> Adicionar impressora</a>
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="30%">Nome</th>
					<th width="30%">Nome de Máquina</th>
					<th width="30%">Regra</th>
                    <th widht="30%">Ações</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($printers as $printer)
					<tr>
						<td>{{ $printer->name }}</td>
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
					</tr>
                @empty
                    <tr>
                        <td colspan="4">Não há impressoras cadastradas</td>
                    </tr>
				@endforelse
			</tbody>
		</table>

@endsection

