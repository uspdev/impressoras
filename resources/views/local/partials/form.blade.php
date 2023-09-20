<div class="card">

    <div class="card-header">
        <h4><b>{{ $param }} usuário local</b></h4>
    </div>

    <hr>

    <div class="card-body">

        <div class="row">
            <div class="col-sm form-group col-sm-8">
                <div class="form-group">
                    <label for="name" class="required"><b>Nome</b></label>
                    <input type="text" class="form-control" name="name" value="{{old('name',$user->name)}}">
                </div>
                <div class="form-group">
                    <label for="codpes" class="required"><b>Nome de Usuário</b></label>
                    <input type="text" class="form-control" name="codpes" value="{{old('codpes',$user->codpes)}}">
                </div>
                <div class="form-group">
                    <label for="email" class="required"><b>E-mail</b></label>
                    <input type="text" class="form-control" name="email" value="{{old('email',$user->email)}}">
                </div>
                <div class="form-group">
                    <label for="senha" class="required"><b>Senha</b></label>
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
        </div>

    <button type="submit" class="btn btn-success"> Enviar </button>

    </div>

</div>
