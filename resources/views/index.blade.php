@extends('master')

@section('title', 'Bilhetagem')

@section('content_header')
    <h1>Impressões</h1>
@endsection

@section('content')
    @auth
        <script>window.location = "/printings";</script>
    @else
        Você ainda não fez seu login com a senha única USP <a href="/login"> Faça seu Login! </a>
    @endauth
@endsection

