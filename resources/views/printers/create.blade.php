@extends('master')

@section('content')
    <form method="POST" action="/printers">
        @csrf
        @include('printers.partials.form', ['param' => 'Adicionar'])
    </form>
@endsection
