@extends('master')

@section('title', 'Impressoras')

@section('content')
    <style>
    #actions {
        display: flex;
        justify-content: start;
    }

    #i-trash {
        margin-left: 80%;
    }
    </style>

    <div class="card-header">
        <h4><b>Usuários locais</b></h4>
        <a href="/local/create"><i class="fas fa-plus"></i> Adicionar usuário local</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="20%">Nome de usuário</th>
                    <th width="40%">Nome</th>
                    <th width="20%">E-mail</th>
                    <th width="20%">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->codpes }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <div id="actions">
                                <a href="/local/{{$user->id}}/edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="/local/{{ $user->id }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');" style="background-color: transparent; border: none;">
                                        <a><i class="fas fa-trash" color="#007bff" id="i-trash"></i></a>
                                    </button>    
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Não há usuários locais.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
