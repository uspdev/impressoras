@extends('master')

@section('title', 'Controle de autorização das impressões')

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
		<h4><b>Impressões aguardando autorização</b></h4>
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="17%">Nome do arquivo</th>
					<th width="17%">Tamanho</th>
					<th width="17%">Páginas</th>
					<th width="17%">Usuário (N.USP)</th>
                    <th widht="16%">Host</th>
                    <th widht="16%">Ação</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($printings as $printing)
					<tr>
						<td>{{ $printing->filename }}</td>
						<td>{{ $printing->filesize }}</td>
						<td>{{ (int)$printing->pages*(int)$printing->copies }}</td>
						<td>{{ $printing->user }}</td>
						<td>{{ $printing->host }}</td>
                        <td>
                            <div id="actions">
                                <a href="/printings/autorizar/{{ $printing->id }}" onclick="return confirm('Tem certeza que deseja autorizar?');"><i class="fas fa-check"></i></a>
                                <a href="/printings/cancelar/{{ $printing->id }}" onclick="return confirm('Tem certeza que deseja cancelar?');"><i class="fas fa-ban" id="i-ban"></i></a>
                                </form>
                            </div>
                        </td>
					</tr>
				@endforeach
			</tbody>
		</table>
@endsection
