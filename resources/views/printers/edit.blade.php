@extends('master')

@section('content')
    <form method="POST" action="/printers/{{ $printer->id }}">
        @csrf
        @method('patch')
        @include('printers.partials.form', ['param' => 'Editar'])
    </form>
@endsection
