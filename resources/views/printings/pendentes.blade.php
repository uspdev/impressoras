@extends('master')
@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
    @parent

<h2>Documentos na Fila de Processamento </h2>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">JobID</th>
                <th width="10%">Pessoa</th>
                <th width="10%">Data</th>
                <th width="5%">Páginas</th>
                <th width="5%">Cópias</th>
                <th width="30%">Impressora</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
@forelse ($processando->sortBy('updated_at') as $printing)
    <tr>
    <td>{{ $printing->jobid }}</td>
    <td>{{ $printing->user }}</td>
    <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->updated_at)->format('d/m/Y H:i') }} </td>
    <td>{{ $printing->pages }}</td>
    <td>{{ $printing->copies }}</td>
    <td><a href="/pendentes/{{ $printing->printer }}">{{ $printing->printer }}</a></td>
    <td>{{ $printing->status }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7">Sem documentos na fila</td>
    </tr>
@endforelse
</tbody>
</table>


<h2>Documentos na Fila de Impressão </h2>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">JobID</th>
                <th width="10%">Pessoa</th>
                <th width="10%">Data</th>
                <th width="5%">Páginas</th>
                <th width="5%">Cópias</th>
                <th width="30%">Impressora</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
@forelse ($fila->sortBy('updated_at') as $printing)
    <tr>
    <td>{{ $printing->jobid }}</td>
    <td>{{ $printing->user }}</td>
    <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $printing->updated_at)->format('d/m/Y H:i') }} </td>
    <td>{{ $printing->pages }}</td>
    <td>{{ $printing->copies }}</td>
    <td><a href="/pendentes/{{ $printing->printer }}">{{ $printing->printer }}</a></td>
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
