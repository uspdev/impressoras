@extends('master')

@section('content')
    <form method="POST" action="/rules/{{ $rule->id }}">
        @csrf
        @method('patch')
        @include('rules.partials.form', ['param' => 'Editar'])
    </form>
@endsection
