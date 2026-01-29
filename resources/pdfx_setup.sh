#!/bin/bash
set -e

BASE_DIR="$(pwd)/resources"
mkdir -p "$BASE_DIR"

PDFX=$(dpkg -S PDFX_def.ps 2>/dev/null | sed 's/^[^:]*:[^:]*: *//' | xargs)
if [ -z "$PDFX" ] || [ ! -f "$PDFX" ]; then
    echo "ERRO: não encontrei o arquivo PDFX_def.ps via dpkg"
    echo "Tentando localizar manualmente..."
    PDFX=$(find /usr/share/ghostscript -name PDFX_def.ps 2>/dev/null | head -n1)
    if [ -z "$PDFX" ] || [ ! -f "$PDFX" ]; then
        echo "ERRO: PDFX_def.ps não encontrado"
        exit 1
    fi
fi

ICC=$(dpkg -S ISOuncoated.icc 2>/dev/null | sed 's/^[^:]*:[^:]*: *//' | xargs)
if [ -z "$ICC" ] || [ ! -f "$ICC" ]; then
    echo "ERRO: não encontrei o arquivo ISOuncoated.icc via dpkg"
    echo "Tentando localizar manualmente..."
    ICC=$(find /usr/share/color/icc -name ISOuncoated.icc 2>/dev/null | head -n1)
    if [ -z "$ICC" ] || [ ! -f "$ICC" ]; then
        echo "ERRO: ISOuncoated.icc não encontrado"
        exit 1
    fi
fi

echo "Copiando PDFX_def.ps de: $PDFX"
cp "$PDFX" "$BASE_DIR" || exit 1

echo "Copiando ISOuncoated.icc de: $ICC"
cp "$ICC" "$BASE_DIR" || exit 1

ICCPATH="$BASE_DIR/ISOuncoated.icc"
echo "Atualizando PDFX_def.ps com caminho: $ICCPATH"
sed -i "s|^/ICCProfile.*|/ICCProfile ($ICCPATH) def|g" "$BASE_DIR/PDFX_def.ps" || exit 1

echo "Setup do PDFX concluído com sucesso!"
