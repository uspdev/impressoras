@extends('master')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
@parent

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">Job ID</th>
                <th width="10%">Pessoa</th>
                <th width="10%">Data</th>
                <th width="5%">Páginas</th>
                <th width="5%">Cópias</th>
                <th width="30%">Impressora</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
@forelse ($printings as $printing)
    <tr>
    <td>{{ $printing->jobid }}</td>
    <td>{{ $printing->user }}</td>
    <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->created_at)->format('d/m/Y H:i') }} </td>
    <td>{{ $printing->pages }}</td>
    <td>{{ $printing->copies }}</td>
    <td>{{ $printing->printer }}</td>
    <td>{{ $printing->status }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7">Sem documentos na fila</td>
    </tr>
@endforelse
</tbody>
</table>

</div>
@stop
