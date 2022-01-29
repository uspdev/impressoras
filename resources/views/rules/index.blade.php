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
		<h4><b>Regras</b></h4>
        <a href="/rules/create"><i class="fas fa-plus"></i> Adicionar regra</a>
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="20%">Nome</th>
					<th width="20%">Controle de autorização</th>
                    <th width="20%">Período do controle de quota</th>
                    <th widht="20%">Quota</th>
                    <th widht="20%">Restrito para</th>
                    <th widht="20%">Ações</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($rules as $rule)
					<tr>
						<td>{{ $rule->name }}</td>
                        <td>
                            @if ($rule->authorization_control  == 0)
                                Não
                            @else
                                Sim
                            @endif
                        </td>
						<td>{{ $rule->type_of_control }}</td>
						<td>{{ $rule->quota }}</td>
                        <td>{{ $rule->categorias ? implode(", ", $rule->categorias) : "Sem restrições" }}</td>
                        <td>
                            <div id="actions">
                                <a href="/rules/{{$rule->id}}/edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="/rules/{{ $rule->id }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');" style="background-color: transparent; border: none;">
                                        <a><i class="fas fa-trash" color="#007bff "id="i-trash"></i></a>
                                    </button>    
                                </form>
                            </div>
                        </td>
					</tr>
				@endforeach
			</tbody>
		</table>

@endsection

