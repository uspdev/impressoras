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
        <h4><b>Monitores</b></h4>
        <form method="POST" id="form-adicionar-codpes">
            @csrf
            @include('assistants.partials.btn-adicionar-codpes')
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="20%">Número USP</th>
                    <th width="40%">Nome</th>
                    <th width="20%">E-mail</th>
                    <th width="20%">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assistants as $assistant)
                    <tr>
                        <td>{{ $assistant->codpes }}</td>
                        <td>{{ $assistant->name }}</td>
                        <td>{{ $assistant->email }}</td>
                        <td>
                            <div id="actions">
                                @if (!$assistant->fromReplicado)
                                    <form method="POST" action="/assistants/{{ $assistant->id }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');" style="background-color: transparent; border: none;">
                                            <a><i class="fas fa-trash" color="#007bff" id="i-trash"></i></a>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Não há monitores.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
