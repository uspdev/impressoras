@extends('master')

@section('title', 'Bilhetagem')

@section('content_header')
    <h1>Impressões</h1>
@endsection

@section('content')
    @auth
        <script>window.location = "/printings";</script>
    @else
        <p>Você ainda não fez seu login com a senha única USP. <a href="/login">Login com senha única</a>.</p>
        <p>Caso você não possua senha única, é possível logar com uma senha local. <a href="/login/local">Login local</a>.</p>
    @endauth
@endsection

