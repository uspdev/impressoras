@extends('master')

@section('content')
    <form method="POST" action="/rules">
        @csrf
        @include('rules.partials.form', ['param' => 'Adicionar'])
    </form>
@endsection
