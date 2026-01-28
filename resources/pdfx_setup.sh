#! /bin/bash

BASE_DIR="$(pwd)/resources"

mkdir -p "$BASE_DIR"

PDFX=$(dpkg -S PDFX_def.ps | cut -d: -f2)
if [ "$PDFX" == "" ]
then
    echo "não encontrei o pacote libgs9-common"
fi

ICC=$(dpkg -S ISOuncoated.icc | cut -d: -f2)
if [ "$ICC" == "" ]
then
    echo "não encontrei o pacote icc-profiles"
fi

cp "$PDFX" "$BASE_DIR"
cp "$ICC" "$BASE_DIR"

ICCPATH="$BASE_DIR/ISOuncoated.icc"
sed -i "s|^/ICCProfile.*|/ICCProfile ($ICCPATH) def|g" "$BASE_DIR/PDFX_def.ps"