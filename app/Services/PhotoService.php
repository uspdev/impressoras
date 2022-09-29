<?php

namespace App\Services;

use Uspdev\Wsfoto;

class PhotoService {

    public function obterFoto($codpes){

        $foto = Wsfoto::obter($codpes);

        return $foto;

    }
}