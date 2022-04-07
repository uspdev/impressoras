<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';

    public static function getStatusName($value)
    {
        return array([
            'waiting_job_authorization' => 'Aguardando autorização',
            'cancelled_user_out_of_quota' => 'Cancelado: excedeu a quota',
            'cancelled_not_authorized' => 'Cancelado: não autorizado',
            'cancelled_timeout' => 'Cancelado: timeout', 
            'sent_to_printer_queue' => 'Enviado para a impressora',
            'print_success' => 'Impresso',
            'printer_problem' => 'Problema na impressora',
        ]);
    }

    public function printing()
    {
        return $this->belongsTo(Printing::class);
    }

    public static function createStatus($name, $printing)
    {
        $status = new Status();
        $status->name = $name;
        $status->printing_id = $printing->id;
        $status->save();

        $printing->latest_status = $name;
        $printing->save();
    }
}
