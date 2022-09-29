@extends('master')

@section('title', 'Impressões')
@section('content_header')

@section('content')

  <div class="card-header">
    <h4><b>Impressões</b></h4>
  </div>
  <br>
  @include('printings.partials.printings_quantities')
  <br>
  {{ $printings->appends(request()->query())->links() }}
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          @include('printings.partials.printings_header')
        </tr>
      </thead>
      <tbody>
      @foreach($printings as $printing)
        <tr>
          @include('allprintings.partials.form')
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
@endsection