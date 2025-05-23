@extends('master')

@section('title', 'Impressões')

@section('content')

  <div class="card-header">
    <h4><b>Impressões</b></h4>
  </div>

  {{ $printings->appends(request()->query())->links() }}

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          @can('monitor')
            <form method="get">
              <div class="row">
                  <div class=" col-sm input-group">
                  <input type="text" class="form-control" name="search" value="{{ request()->search }}"         placeholder="Insira o nome do arquivo ou número USP">

                  <select name="status" class="form-control">
                      <option value="" selected=""> Selecione o status </option>
                      @foreach(\App\Models\Status::getStatus() as $key=>$status)

                        <option value="{{$key}}"
                            @if($key == Request()->status) selected @endif>
                            {{$status}}
                        </option>

                      @endforeach
                  </select>

                  <span class="input-group-btn">
                      <button type="submit" class="btn btn-success"> Buscar </a></button>
                  </span>

                  </div>
              </div>
            </form>
            <th>Usuário</th>
          @endcan
            <th>Data</th>
            <th width="5%">Impressões</th>
            <th width="5%">Cópias</th>
            <th>Tamanho</th>
            <th>Arquivo</th>
            <th>Impressora</th>
            <th>Status</th>

          @if(!empty($auth))
              <th>Autorizado por</th>
          @endif
        </tr>
      </thead>
      <tbody>
      @foreach($printings as $printing)
        <tr>
          @include('allprintings.partials.form')
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
@endsection
