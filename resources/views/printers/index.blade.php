@extends('master')

@section('title', 'Impressoras')

@section('content')

    <style>

        #actions
        {
            display: flex;
            justify-content: start;
        }

        #i-trash
        {
            margin-left: 80%;
        }

    </style>

    <div class="card-header">
        <h4><b>Impressoras</b></h4>
        @can('admin')
        <a href="/printers/create"><i class="fas fa-plus"></i> Adicionar impressora</a>
        @endcan
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="15%">Nome</th>
                    <th width="15%">Acessar fila</th>
                    <th width="15%">Localização</th>
                    <th width="15%">Teste de Impressão</th>
                    @can('admin')
                    <th width="10%">Nome de Máquina</th>
                    <th width="5%">Tipo</th>
                    <th width="5%">Ativa</th>
                    <th width="15%">Regra</th>
                    <th width="15%">Ações</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @forelse ($printers as $printer)
                    <tr>
                        <td>{{ $printer->name }}</td>
                        <td>
                            <div class="d-grid gap-2 d-md-block">
                                @can('admin')
                                    <a href="/printers/{{ $printer->id }}"><button class="btn btn-primary" type="button">Impressora</button></a>
                                @endcan
                                @can('monitor')
                                    @if ($printer->rule)
                                        @if ($printer->rule->queue_control)
                                            <a href="/printers/auth_queue/{{ $printer->id }}"><button class="btn btn-secondary" type="button">Autorização</button></a>
                                        @endif
                                    @endif
                                @endcan
                            </div>
                        </td>
                        <td>{{ $printer->location ?? '' }}</td>
                        <td><a href="/printers/{{$printer->id}}/printtest" title="Imprimir página de teste"><i class="fas fa-print"></i></a></td>
                        @can('admin')
                        <td>{{ $printer->machine_name }}</td>
                        <td>@if($printer->color) Colorida @else Preto e Branca @endif</td>
                        <td>@if($printer->active) Sim @else Não @endif</td>
                        <td>{{ $printer->rule->name ?? '' }}</td>
                        <td>
                            <div id="actions">
                                <a href="/printers/{{$printer->id}}/edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="/printers/{{ $printer->id }}" style="margin-right: 24px;">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');" style="background-color: transparent; border: none;">
                                        <a><i class="fas fa-trash" color="#007bff" id="i-trash"></i></a>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Não há impressoras cadastradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

