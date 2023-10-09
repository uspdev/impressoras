@extends('master')

@section('content')
    <form method="POST" action="/local">
        @csrf
        @include('local.partials.form', ['param' => 'Adicionar'])
    </form>
@endsection
