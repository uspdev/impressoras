<?php

namespace App\Services;

use Uspdev\Replicado\DB;

class ReplicadoTemp
{
    // 31/05/2022 - ECAdev @alecosta: Parametrizado o código da sala de monitoria Pró-Aluno
    public static function listarMonitores($codslamon)
    {
        $query = "SELECT DISTINCT t1.codpes
                FROM BENEFICIOALUCONCEDIDO t1
                INNER JOIN BENEFICIOALUNO t2
                ON t1.codbnfalu = t2.codbnfalu
                AND t1.dtafimccd > GETDATE()
                AND t1.dtacanccd IS NULL
                AND t2.codbnfalu = 32
                AND t1.codslamon = $codslamon
                ";

        $result = DB::fetchAll($query);

        if(!empty($result)) return array_column($result,'codpes');
        return [];
    }
}