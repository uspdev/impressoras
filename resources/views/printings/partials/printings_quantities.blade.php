<table width="100%" border="0">
  <tr>
    <td width="100%" align="center" colspan="3"><b><font size="+1">Impressões</font></b></td>
  </tr>
  <tr style="border-bottom: 1px solid #cdd0d4;">
    <td width="33%"><b>Total:</b> {{ $quantities['Total'] ?? '' }}</td>
    <td width="34%" align="center"><b>Hoje:</b> {{ $quantities['Diário'] ?? '' }}</td>
    <td width="33%" align="right"><b>Neste mês:</b> {{ $quantities['Mensal'] ?? '' }}</td>
  </tr>
</table>