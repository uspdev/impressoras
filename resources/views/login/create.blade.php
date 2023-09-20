@extends('master')

@section('title', 'Bilhetagem')

@section('content_header')
    <h1>Login</h1>
@endsection

@section('content')
    <form method="POST" action="/login/local/create">
        @csrf
        <div class="card">
            <h1>Criar login local</h1>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm form-group col-sm-8">
                        <label for="name" class="form-label">Nome</label>
                        <input class="form-control" type="text" name="name" id="name" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm form-group col-sm-8">
                        <label for="codpes" class="form-label">Nome de usuário</label>
                        <input class="form-control" type="text" name="codpes" id="codpes" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm form-group col-sm-8">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="text" name="email" id="email" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm form-group col-sm-8">
                        <label for="password" class="form-label">Senha</label>
                        <input class="form-control" type="password" name="password" id="password" />
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Criar</button>
            </div>
        </div>
@endsection

