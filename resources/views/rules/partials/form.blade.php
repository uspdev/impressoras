<div class="card">

    <div class="card-header">
        <h4><b>{{ $param }} regra</b></h4>
    </div>

    <hr>

    <div class="card-body">

        <div class="row">
            <div class="col-sm form-group col-sm-8">
                <div class="form-group">
                    <label for="name" class="required"><b>Nome: </b></label>
                    <input type="text" class="form-control" name="name" value="{{old('name',$rule->name)}}">
                </div>
                <div class="form-group">
                    <label class="required"><b>Controle de autorização </b></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="authorization_control" id="check-yes" value="1" @if (isset($rule->authorization_control) and ($rule->authorization_control === 1)) checked @elseif ((old('authorization_control') != null) and (old('fixarip') == 0)) checked @endif> 
                        <label class="form-check-label" for="check-yes">Sim</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="authorization_control" id="check-no" value="0" @if (isset($rule->authorization_control) and ($rule->authorization_control === 0)) checked @elseif ((old('authorization_control') != null) and (old('fixarip') == 0)) checked @endif> 
                        <label class="form-check-label" for="check-no">Não</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="required"><b>Período do controle de quota</b></label>
                    <select class="form-control" name="type_of_control">
                        <option value="" selected="">Selecione uma opção </option>
                        @foreach($rule::types_of_control() as $type)
                            @if (old('type_of_control') == '')
                                <option value="{{ $type }}" {{ ($rule->type_of_control == $type) ? 'selected':'' }}>
                                    {{ $type }}
                                </option>
                            @else
                                <option value="{{ $type }}" {{ (old('type_of_control') == $type) ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quota" class="required"><b>Quota para o período</b></label>
                    <br>
                    <input type="number" name="quota" value="{{old('quota',$rule->quota)}}">
                </div>

                <div class="form-group">
                    <label for="" class=""><b>Categorias permitidas</b></label>
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
