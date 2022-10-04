<?php

namespace App\Services;

use App\Models\Printing;

class HistoricoService {

    public function historico(){
        
    $printings_success = Printing::where('latest_status', '=', 'sent_to_printer_queue')
                            ->orderBy('id', 'DESC')->take(20)->get();
                                
        return $printings_success;
    }

}