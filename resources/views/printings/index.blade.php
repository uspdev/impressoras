@extends('master')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')

<!--
<form method="get" action="/printings">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Impressora ..." name="user">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-success"> Buscar </button>
            </span>
        </div>
</form>
-->

<br>

@include('printings.partials.printing_quantities')

<br>
{{ $printings->links() }}
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">Job ID</th>
                @can('admin') <th width="10%">Pessoa</th> @endcan
                @can('admin') <th width="10%">Host</th> @endcan
                <th width="10%">Data</th>
                <th width="5%">Páginas</th>
                <th width="5%">Cópias</th>
                <th width="10%">Tamanho</th>
                <th width="15%">Arquivo</th>
                <th width="15%">Impressora</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
    @include('printings/partials/printing')
</tbody>
</table>
{{ $printings->links() }}
</div>
@stop

@section('javascripts_bottom')
<script type="text/javascript">
  $(document).ready(function(){
    function verificaStatus(route) {
      $.ajax({
        url: route,
        type: 'get',
        dataType: "html",
        data: {
          route: route,
        },
        beforeSend: function() {
          var loading = '<div class="spinner-border spinner-border-sm text-muted"></div>';
          $("td.Fila,td.Processando").html(loading);
        },
        success: function( data ) {
          $('.table tbody').html(data);
        }
      });
    };

    setInterval(function(){
      var route = $(location).attr("pathname");
      verificaStatus(route)
    }, 5000);

  });
</script>
@endsection
