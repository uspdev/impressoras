@php
  $has_total_paginas = (!empty($quantities['Total_Páginas']) && $quantities['Total_Páginas'] > 0);
  $has_total_folhas = (!empty($quantities['Total_Folhas']) && $quantities['Total_Folhas'] > 0);
  $has_diario_paginas = (!empty($quantities['Diário_Páginas']) && $quantities['Diário_Páginas'] > 0);
  $has_diario_folhas = (!empty($quantities['Diário_Folhas']) && $quantities['Diário_Folhas'] > 0);
  $has_mensal_paginas = (!empty($quantities['Mensal_Páginas']) && $quantities['Mensal_Páginas'] > 0);
  $has_mensal_folhas = (!empty($quantities['Mensal_Folhas']) && $quantities['Mensal_Folhas'] > 0);
@endphp

<div align="center">
  <b>Total de impressões:</b>
  @if ($has_total_paginas) {{ $quantities['Total_Páginas'] }} Páginas @endif
  @if ($has_total_paginas && $has_total_folhas) e @endif
  @if ($has_total_folhas) {{ $quantities['Total_Folhas'] }} Folhas @endif
  <br>
  <b>Impressões de hoje:</b>
  @if ($has_diario_paginas) {{ $quantities['Diário_Páginas'] }} Páginas @endif
  @if ($has_diario_paginas && $has_diario_folhas) e @endif
  @if ($has_diario_folhas) {{ $quantities['Diário_Folhas'] }} Folhas @endif
  <br>
  <p style="color:red;">
    <b>Impressões neste mês:</b>
    @if ($has_mensal_paginas) {{ $quantities['Mensal_Páginas'] }} Páginas @endif
    @if ($has_mensal_paginas && $has_mensal_folhas) e @endif
    @if ($has_mensal_folhas) {{ $quantities['Mensal_Folhas'] }} Folhas @endif
  </p>
</div>
