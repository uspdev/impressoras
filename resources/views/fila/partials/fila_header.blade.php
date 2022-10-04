@can('monitor')
<th>Usuário (N.USP)</th>
<th>Host</th>
@endcan
<th>Data</th>
<th width="5%">Páginas</th>
<th width="5%">Cópias</th>
<th>Tamanho</th>
<th>Arquivo</th>
<th>Status</th>
@if ($auth)
    @can('admin')
    <th width="14%">Foto</th>
    <th width="14%">Ações</th>
    @endcan
@endif
