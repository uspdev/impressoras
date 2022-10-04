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
  <h4><b>Fila de
          @if ($auth)
              autorização de
          @endif
          {{ $name }}</b>
      </h4>
</div>
  <br>
  @if(!$auth)
  @include('printings.partials.printings_quantities')
  <br>
  {{ $printings->links() }}
  @endif
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        @include('fila.partials.fila_header')
      </tr>
    </thead>
    <tbody id="fila">
        @include('fila.partials.fila_body')
    </tbody>
  </table>
</div>

@can('admin')
  @include('printings.historico')
@endcan

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
