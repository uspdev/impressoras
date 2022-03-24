@extends('master')

@section('title', 'Dashboard')
@section('content_header')
@stop

@section('content')

  <div class="card-header">
    <h4><b>Impressões de {{ $user->name }}</b></h4>
  </div>
  <br>
  @include('printings.partials.printings_quantities')
  <br>
  {{ $printings->links() }}
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          @include('printings.partials.printings_header')
        </tr>
      </thead>
      <tbody>
        @forelse ($printings as $printing)
          <tr>
            @include('printings.partials.printings_body')
          </tr>
        @empty
          <tr>
            <td colspan="7">Não há impressões</td>
          </tr>
        @endforelse
      </tbody>
    </table>
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