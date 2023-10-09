@extends('master')

@section('content')
    <form method="POST" action="/local/{{ $user->id }}">
        @csrf
        @method('patch')
        @include('local.partials.form', ['param' => 'Editar'])
    </form>
@endsection
