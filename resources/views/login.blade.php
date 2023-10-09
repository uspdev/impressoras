@extends('master')

@section('title', 'Bilhetagem')

@section('content_header')
    <h1>Impressões</h1>
@endsection

@section('content')
    @auth
        <script>window.location = "/printings";</script>
    @else
        <div class="d-flex justify-content-center">
            <form method="POST" action="/login/local">
                @csrf
                    <h1 class="h3 mb-3 font-weight-normal">Login local</h1>
                    <label for="codpes" class="sr-only">Usuário</label>
                    <input class="form-control" type="text" name="codpes" id="codpes" placeholder="Usuário" autofocus/>
                    <label for="password" class="sr-only">Senha</label>
                    <input class="form-control mb-4" type="password" name="password" id="password" placeholder="Senha" />
                    <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
            </form>
        </div>
    @endauth
@endsection

