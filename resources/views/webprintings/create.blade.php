@extends('master')

@section('content')
    <form method="POST" action="/webprintings/{{ $printer->id }}" enctype="multipart/form-data">
        @csrf
        <div class="card">

            <h1>{{ $printer->name }}</h1>
            <div class="card-body">


                <div class="row">
                    <div class="col-sm form-group col-sm-8">
                        <label for="file" class="form-label">Arquivo</label>
                        <input class="form-control" type="file" id="file" name="file">
                        <small>Somente arquivos pdf são permitidos</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm form-group col-sm-8">
                        <label for="sides" class="form-label">Tipo de impressão</label>
                        <select class="form-select" name="sides" aria-label="Lado" id="sides">
                            <option selected>Selecione o tipo</option>
                            <option value="one-sided">Um lado</option>
                            <option value="two-sided-long-edge" selected>Frente e verso na borda maior</option>
                            <option value="two-sided-short-edge">Frente e verso na borda menor</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success"> Enviar </button>

        </div>

    </form>
@endsection
