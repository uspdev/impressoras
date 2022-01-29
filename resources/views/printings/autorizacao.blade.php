@extends('master')

@section('title', 'Controle de autorização das impressões')

@section('content')

    @foreach ($printings as $printing)

    {{ $printings->filename }}

    @endforeach

@endsection
