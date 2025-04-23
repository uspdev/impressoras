@extends('master')

@section('title', 'Impressoras')

@section('content')
    <div class="card-header">
        <h4><b>Impressoras</b></h4>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="20%">Nome</th>
                    <th width="20%">Local</th>
                    <th width="20%">Impressões</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($printers as $printer)
                    <tr>
                        <td>
                            <div class="d-grid gap-2 d-md-block">
                                <a href="/webprintings/{{ $printer->id }}"><button class="btn btn-primary col-sm-4" type="button">{{ $printer->name }}</button></a>
                            </div>
                        </td>
                        <td>{{ $printer->location ?? '' }}</td>
                        <td>
                            @if ($printer->rule)
                                {{ $printer->used(\Auth::user()) }} de {{ $printer->rule ? $printer->rule->quota : ''}} {{ $printer->rule->quota_type }}
                            @endif
                        </td>
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

