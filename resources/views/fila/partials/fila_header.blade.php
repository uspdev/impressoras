@can('monitor')
<th>Usuário</th>
@endcan
<th>Data</th>
<th width="5%">Páginas</th>
<th width="5%">Cópias</th>
<th>Tamanho</th>
<th>Arquivo</th>
@if ($auth)
    @can('admin')
    <th width="14%">Foto</th>
    <th width="14%">Ações</th>
    @endcan
@endif
