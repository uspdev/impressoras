<?php

namespace App\Services;

use Uspdev\Replicado\DB;

use Uspdev\Replicado\Beneficio;

class ReplicadoTemp
{
    // 31/05/2022 - ECAdev @alecosta: Parametrizado o código da sala de monitoria Pró-Aluno
    public static function listarMonitores($codslamon)
    {
        $result = Beneficio::listarMonitoresProAluno($codslamon);
        if(!empty($result)) return array_column($result,'codpes');
        return [];
    }
}