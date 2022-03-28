<th width="5%">Job ID</th>
@can('admin')
<th width="10%">Usuário (N.USP)</th>
<th width="10%">Host</th>
@endcan 
<th width="10%">Data</th>
<th width="5%">Páginas</th>
<th width="5%">Cópias</th>
<th width="10%">Tamanho</th>
<th width="15%">Arquivo</th>
<th width="15%">Status</th>
@if (!$auth)
    <th width="15%">Autorizado por</th>
@endif
