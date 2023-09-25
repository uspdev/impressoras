@extends('master')
@section('content')

    <div class="card-header">
        <h4><b>Histórico de status do job {{ $printing->jobid }}</b></h4>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="34%">Status</th>
                    <th width="33%">Data de criação</th>
                    <th widht="33%">Data da última edição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statuses as $status)
                    <tr>
                        <td>{{ $status->name }}</td>
                        <td>{{ $status->created_at }}</td>
                        <td>{{ $status->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
