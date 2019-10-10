@extends('master')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
@parent
    <!--form method="get" action="/printings">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Impressora ..." name="printer">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-success"> Buscar </button>
            </span>
        </div><!-- /input-group -->
    </form-->
<br>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="10%">Job ID</th>
                <th width-"10%">Páginas</th>
                <th width="10%">Cópias</th>
                <th width="35%">Arquivo</th>
                <th width="35%">Impressora</th>
            </tr>
        </thead>
        <tbody>
@foreach ($printings as $printing)
    @include('printings/partials/printing')
@endforeach
</tbody>
</table>
</div>
@stop
