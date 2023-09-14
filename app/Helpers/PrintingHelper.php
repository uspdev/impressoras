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
        if (!File::exists($pdfinfo))
            throw new \Exception("Instalar pdfinfo: apt install poppler-utils.");

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
}
