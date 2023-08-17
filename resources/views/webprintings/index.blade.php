@extends('master')

@section('title', 'Impressoras')

@section('content')
	<div class="card-header">
		<h4><b>Impressoras</b></h4>
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="20%">Nome</th>
                    <th width="20%">Páginas impressas</th>
                    <th width="20%">Quota</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($printers as $printer)
					<tr>
                        <td>
                            <div class="d-grid gap-2 d-md-block">
                                <a href="/webprintings/{{ $printer->id }}"><button class="btn btn-primary" type="button">{{ $printer->name }}</button></a>
                            </div>
                        </td>
						<td>0</td>
						<td>{{ $printer->rule->quota }}</td>
					</tr>
                @empty
                    <tr>
                        <td colspan="4">Não há impressoras cadastradas</td>
                    </tr>
				@endforelse
			</tbody>
		</table>

@endsection

