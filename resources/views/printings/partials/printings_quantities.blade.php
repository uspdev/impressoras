<table width="100%" border="0">

  <tr style="border-bottom: 1px solid #cdd0d4;">
    <td width="33%"><b>Total de impressões:</b> {{ $quantities['Total'] ?? '' }}</td>
    <td width="34%" align="center"><b>Impressões de Hoje:</b>
       {{ $quantities['Diário'] ?? '' }}
       <p style="color:red;"> 
         Hoje você ainda pode imprimir na próaluno: {{ 30 - $quantities['Diário'] }} páginas
       </p>
    </td>
    <td width="33%" align="right"><b>Impressões neste mês:</b> {{ $quantities['Mensal'] ?? '' }}</td>
  </tr>
</table>