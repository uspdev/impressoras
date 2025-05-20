@can('monitor')
<form method="get">
<div class="row">
    <div class=" col-sm input-group">
    <input type="text" class="form-control" name="search" value="{{ request()->search }}" placeholder="Insira o nome do arquivo ou número USP">

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
