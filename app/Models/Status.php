<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Printing;

class Status extends Model
{
    use HasFactory;
    protected $table = "status";

    public static function names() 
    {
        return collect([
            'waiting_job_authorization',
            'checking_user_quota',
            'cancelled_user_out_of_quota',
            'cancelled_not_authorized',
            'sent_to_printer_queue',
            'print_success',
            'printer_problem',
        ]); 
    }

    public function printing()
    {
    	return $this->belongsTo(Printing::class);
    }
}
