@extends('master')

@section('title', 'Bilhetagem')

@section('content_header')
    <h1>Impressões</h1>
@stop

@section('content')
    @parent
        @auth
            <script>window.location = "/printings";</script>
        @else
            Você ainda não fez seu login com a senha única USP <a href="/senhaunica/login"> Faça seu Login! </a>
            <br><br>
            Consulte a <a href="/pendentes"> fila de impressão</a> 
        @endauth
@stop
