@extends('master')

@section('title', 'Minhas Impressões')
@section('content_header')

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
            <td colspan="10">Não há impressões</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
