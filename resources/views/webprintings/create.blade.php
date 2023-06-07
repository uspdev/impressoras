@extends('master')

@section('content')
    <form method="POST" action="/webprintings" enctype="multipart/form-data">
        @csrf
        <div class="card">

            <div class="card-body">


                <div class="mb-3">
                    <label for="formFile" class="form-label">Arquivo</label>
                    <input class="form-control" type="file" id="formFile">
                    <small>Somente arquivos pdf são permitidos</small>
                </div>

                <div class="row">
                    <div class="col-sm form-group col-sm-8">

                    
                        <div class="form-group">
                            <label for="printer_id" class="required"><b>Selecione impressora</b></label>
                            <select class="form-control" name="printer_id">
                                <option value="" selected="">Selecione uma impressora </option>
                                @foreach(App\Models\Printer::all() as $printer)
                                    @if (old('printer_id') == '')
                                        <option value="{{$printer->id}}" {{ ($printer->printer_id == $printer->id) ? 'selected' : '' }}>
                                            {{ $printer->name }}
                                        </option>
                                    @else
                                        <option value="{{$printer->id}}" {{ (old('printer_id')) ? 'selected' : '' }}>
                                            {{ $printer->name }}
                                        </option> 
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            <button type="submit" class="btn btn-success"> Enviar </button>

        </div>

    </form>
@endsection
