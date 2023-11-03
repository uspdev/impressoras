<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';

    public static function statusName($status)
    {
        $list = [
            'waiting_job_authorization' => 'Aguardando autorização',
            'cancelled_user_out_of_quota' => 'Cancelado - excedeu a quota',
            'cancelled_not_authorized' => 'Cancelado - não autorizado pelo monitor',
            'cancelled_not_allowed'   => 'Cancelado - pessoa não possui vínculo necessário para usar essa impressora',
            'cancelled_timeout' => 'Cancelado - Tempo de resposta a solicitação expirado',
            'failed_in_process_pdf' => 'Erro ao processar o arquivo pdf',
            'pdf_processed_successfully' => 'Arquivo pdf processado com sucesso',
            'processing_pdf' => 'Processando arquivo pdf',
            'sent_to_printer_queue' => 'Enviado para a impressora',
            'print_success' => 'Impresso com sucesso',
            'printer_problem' => 'Cancelado - Problema na impressora',
        ];

        if(empty($status)){
            return 'Sem status';
        } else {
            return $list[$status];
        }
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
