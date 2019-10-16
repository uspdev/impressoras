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

<div><b>Impressões:</b> 
<b>Total:</b> {{ $quantidades['total'] }}
<b>Hoje:</b> {{ $quantidades['hoje'] }} 
<b>Neste mês:</b> {{ $quantidades['mes'] }} 

 </div>
<br>
{{ $printings->links() }}
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="10%">Job ID</th>
                @can('admin') <th width="10%">Pessoa</th> @endcan
                <th width="10%">Data</th>
                <th width="10%">Páginas</th>
                <th width="10%">Cópias</th>
                <th width="30%">Arquivo</th>
                <th width="30%">Impressora</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
@forelse ($printings as $printing)
    @include('printings/partials/printing')
@empty
    <tr>
        <td colspan="6">Não há impressões</td>
    </tr>
@endforelse
</tbody>
</table>
{{ $printings->links() }}
</div>
@stop
