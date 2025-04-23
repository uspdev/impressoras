<div class="card">

    <div class="card-header">
        <h4><b>{{ $param }} regra</b></h4>
    </div>

    <hr>

    <div class="card-body">

        <div class="row">
            <div class="col-sm form-group col-sm-8">
                <div class="form-group">
                    <label for="name" class="required"><b>Nome</b></label>
                    <input type="text" class="form-control" name="name" value="{{old('name',$rule->name)}}">
                </div>
                <div class="form-group">
                    <label class="required"><b>Controle da fila para autorização de impressões</b></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="queue_control" id="check-yes" value="1" @if (isset($rule->queue_control) and ($rule->queue_control === 1)) checked @elseif ((old('queue_control') != null) and (old('fixarip') == 0)) checked @endif>
                        <label class="form-check-label" for="check-yes">Sim</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="queue_control" id="check-no" value="0" @if (isset($rule->queue_control) and ($rule->queue_control === 0)) checked @elseif ((old('queue_control') != null) and (old('fixarip') == 0)) checked @endif>
                        <label class="form-check-label" for="check-no">Não</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="required"><b>Período da quota</b></label>
                    <select class="form-control" name="quota_period">
                        <option value="" selected="">Selecione uma opção </option>
                        @foreach($rule::quota_period_options() as $type)
                            @if (old('quota_period') == '')
                                <option value="{{ $type }}" {{ ($rule->quota_period == $type) ? 'selected':'' }}>
                                    {{ $type }}
                                </option>
                            @else
                                <option value="{{ $type }}" {{ (old('quota_period') == $type) ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quota" class="required"><b>Valor da quota</b></label>
                    <br>
                    <input type="number" name="quota" value="{{old('quota',$rule->quota)}}">
                </div>
                <div class="form-group">
                    <label class="required"><b>Tipo da quota</b></label>
                    <select class="form-control" name="quota_type">
                        <option value="" selected="">Selecione uma opção </option>
                        @foreach($rule::quota_type_options() as $type)
                            @if (old('quota_type') == '')
                                <option value="{{ $type }}" {{ ($rule->quota_type == $type) ? 'selected':'' }}>
                                    {{ $type }}
                                </option>
                            @else
                                <option value="{{ $type }}" {{ (old('quota_type') == $type) ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="" class=""><b>Categorias permitidas: </b></label>
                    <small> Se nenhuma opção for selecionada, as impressoras nessa regra estão liberadas para todos </small>

                    @foreach($rule::categories() as $category)
                        <div class="form-check">
                            <input class="form-check-input" name="categories[]" type="checkbox" value="{{$category}}"

                                @if(in_array($category, $rule->categories))
                                    checked
                                @endif
                                >
                                <label class="form-check-label" for="">
                                {{$category}}
                                </label>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    <button type="submit" class="btn btn-success"> Enviar </button>

    </div>

</div>
