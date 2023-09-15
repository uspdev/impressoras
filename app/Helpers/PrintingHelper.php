<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PrintingHelper
{
    public static function pdfinfo($file) {
        $info = [];

        $pdfinfo = "/usr/bin/pdfinfo";
        if (!File::exists($pdfinfo)) {
            throw new \Exception("Instalar pdfinfo: apt install poppler-utils.");
        }

        $process = new Process([$pdfinfo, $file]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        if (preg_match('/^Pages:\s+(\d+)/m', $process->getOutput(), $matches) != 1)
            throw new \Exception("Problema no PDF: número de páginas não encontrado.");
        $info['pages'] = $matches[1];

        if (preg_match('/^Page size:\s+(\d+\.?\d+)\sx\s(\d+\.?\d+)/m', $process->getOutput(), $matches) != 1)
            throw new \Exception("Problema no PDF: tamanho indefinido.");

        $info['width'] = $matches[1];
        $info['height'] = $matches[2];

        return $info;
    }

    public static function pdfjam($file) {
        $pdfinfo = PrintingHelper::pdfinfo($file);

        $pdfjam = "/usr/bin/pdfjam";
        if (!File::exists($pdfjam)) {
            throw new \Exception("Instalar pdfjam: apt install texlive-extra-utils.");
        }

        if ($pdfinfo['width'] > $pdfinfo['height']) {
            $mode = "--landscape";
        }
        else {
            $mode = "--portrait";
        }

        $pdf = File::dirname($file) . "/" . File::name($file) . "pdfjam.pdf";
        $process = new Process([$pdfjam, $mode, "--a4paper", "--outfile", $pdf, $file]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        if (!File::exists($pdf))
            throw new \Exception("PDF não encontrado>");

        return $pdf;
    }

    public static function pdfx($file) {
        $ghostscript = "/usr/bin/gs";

        if (!File::exists($ghostscript)) {
            throw new \Exception("Instalar ghostscript: apt install ghostscript.");
        }

        $base = "/home/kotas/kotas/resources";
        $pdf = File::dirname($file) . "/" . File::name($file) . "pdfx.pdf";
        $process = new Process([
            $ghostscript,
            '-dBATCH', '-dNOPAUSE', '-dQUIET',
            '-dPDFX',
            '-sDEVICE=pdfwrite',
            '-sColorConversionStrategy=Gray',
            '-sPDFSETTINGS=prepress',
            '-sOutputFile='.$pdf,
            '-I', $base, $base.'/PDFX_def.ps',
            $file
        ]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $pdf;
    }
}
