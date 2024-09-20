#! /bin/bash
# DEPS: pdftk parallel

BASE_DIR=$1
SOURCE=$2
COLOR=$3
NAME=$4
if [ "$BASE_DIR" == "" -o "$SOURCE" == "" -o "$COLOR" == "" -o "$NAME" == "" ]
then
    echo faltam parâmetros
    exit 1
fi

TEMP=$(/bin/mktemp -d)
PREFIX=a

# resize vem do PHP

# split
/usr/bin/pdftk $SOURCE burst output $TEMP/$PREFIX%04d.pdf

# process
ls $TEMP/$PREFIX*.pdf | /usr/bin/parallel /usr/bin/ghostscript -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -sColorConversionStrategy=$COLOR -sPDFSETTINGS=prepress -sOutputFile=$TEMP/{/}gs.pdf -I $BASE_DIR $BASE_DIR/PDFX_def.ps {}

# merge
/usr/bin/pdftk $TEMP/$PREFIX*gs.pdf cat output $NAME

# clean
rm $TEMP -rf
