@extends('master')

@section('title', 'Bilhetagem')

@section('content_header')
    <h1>Impressões</h1>
@endsection

@section('content')
    @auth
        <script>window.location = "/printings";</script>
    @else
        <form method="POST" action="/login/local">
            @csrf
            <div class="card">
                <h1>Login local</h1>
                <div class="card-body">
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
                    <button type="submit" class="btn btn-success">Login</button>
                </div>
            </div>
    @endauth
@endsection

