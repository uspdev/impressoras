#!/bin/sh

# sobe o cupsd em background
/usr/sbin/cupsd -f &
CUPSD_PID=$!
trap 'kill $CUPSD_PID' TERM INT

# espera o CUPS responder (no máximo ~60s)
for i in $(seq 1 60); do
  lpstat -r >/dev/null 2>&1 && break
  sleep 1
done

# procura o PPD do cups-pdf (impressora virtual); se não achar, usa o genérico
PPD=$(lpinfo -m 2>/dev/null | grep -i 'cups-pdf' | head -n1 | awk '{print $1}')
echo "init-printers: PPD escolhido = ${PPD:-genérico}"

# cria e habilita a impressora de teste (nome = machine_name no sistema)
if lpadmin -p ImpressoraTesteDusk001 -E -v cups-pdf:/ -m "${PPD:-drv:///sample.drv/generic.ppd}"; then
  echo "init-printers: impressora ImpressoraTesteDusk001 criada"
else
  echo "init-printers: ERRO ao criar a impressora (o CUPS continua no ar)"
fi

wait $CUPSD_PID