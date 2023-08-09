@extends('master')

@section('title', 'Fila')

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
  <h4><b>Fila de autorização de {{ $name }}</b></h4>
</div>

<div class="table-responsive" id="fila">
  @include('fila.partials.fila_body')
</div>

@endsection

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
        success: function( data ) {
          $("#fila").html(data);
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
