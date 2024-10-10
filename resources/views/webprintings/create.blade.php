@extends('master')

@section('title', 'Enviar impressão')

@section('content')

    <form method="POST" action="/webprintings/{{ $printer->id }}" enctype="multipart/form-data">
        @csrf
        <div class="card">

            <div class="card-header">
                <h4><b>{{ $printer->name }}</h4>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="form-group col-sm-8">
                        <div class="custom-file">
                            <input class="custom-file-input" type="file" id="file" name="file" accept="application/pdf">
                            <label for="file" class="custom-file-label">Selecionar arquivo</label>
                        </div>
                        <small class="form-text text-muted">Somente arquivos pdf são permitidos. Limite de tamanho: 10MB.</small>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-8">
                        <label for="sides" class="form-label">Tipo de impressão</label>
                        <select class="custom-select" name="sides" aria-label="Lado" id="sides">
                            <option value="one-sided">Um lado</option>
                            <option value="two-sided-long-edge" selected>Frente e verso na borda maior</option>
                            <option value="two-sided-short-edge">Frente e verso na borda menor</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-8">
                        <label for="pages_per_sheet">Páginas por folha</label>
                        <select class="custom-select" name="pages_per_sheet" id="pages_per_sheet">
                            <option value="1" selected>Padrão</option>
                            <option value="2">2</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="start_page" class="form-label">Página inicial </label>
                        <input class="form-control" type="text" name="start_page" id="start_page" value="{{ old('start_page') }}"/>
                        <small class="form-text text-muted">Não preencher caso for todo o documento.</small>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="end_page" class="form-label">Página final</label>
                        <input class="form-control" type="text" name="end_page" id="end_page" value="{{ old('end_page') }}" />
                        <small class="form-text text-muted">Não preencher caso for todo o documento.</small>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="copies" class="form-label">Cópias</label>
                        <input class="form-control" type="text" name="copies" id="copies" value="{{ old('copies',1) }}" />
                        <small class="form-text text-muted">Quantidade de cópias do mesmo documento</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-block btn-primary"> Enviar </button>
                    </div>
                </div>
        </div>

    </form>
@endsection
