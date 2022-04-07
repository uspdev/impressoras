<th width="5%">Job ID</th>
@can('admin')
<th>Usuário (N.USP)</th>
<th>Host</th>
@endcan 
<th>Data</th>
<th width="5%">Páginas</th>
<th width="5%">Cópias</th>
<th>Tamanho</th>
<th>Arquivo</th>
<th>Status</th>

@if (!empty($auth))
    <th>Autorizado por</th>
@endif
