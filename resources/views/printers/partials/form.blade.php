<div class="card">

    <div class="card-header">
        <h4><b>{{ $param }} impressora</b></h4>
    </div>

    <hr>

    <div class="card-body">

        <div class="row">
            <div class="col-sm form-group col-sm-8">
                <div class="form-group">
                    <label for="name" class="required"><b>Nome</b></label>
                    <input type="text" class="form-control" name="name" value="{{old('name',$printer->name)}}">
                </div>
                <div class="form-group">
                    <label for="machine_name" class="required"><b>Nome de Máquina</b></label>
                    <input type="text" class="form-control" name="machine_name" value="{{old('machine_name',$printer->machine_name)}}">
                </div>
                <div class="form-group">
                    <label for="rule_id" class="required"><b>Regra</b></label>
                    <select class="form-control" name="rule_id">
                        <option value="" selected="">Selecione uma opção </option>
                        @foreach(App\Models\Rule::all() as $rule)
                            @if (old('rule_id') == '')
                                <option value="{{$rule->id}}" {{ ($printer->rule_id == $rule->id) ? 'selected' : '' }}>
                                    {{ $rule->name }}
                                </option>
                            @else
                                 <option value="{{$rule->id}}" {{ (old('rule_id')) ? 'selected' : '' }}>
                                    {{ $rule->name }}
                                 </option> 
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    <button type="submit" class="btn btn-success"> Enviar </button>

    </div>

</div>
