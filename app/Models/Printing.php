<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Printer;
use App\Models\Status;


class Printing extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at'];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function status()
    {
    	return $this->hasMany(Status::class);
    }

    public function latest_status()
    {
    	return $this->hasOne(Status::class)->latest();
    }
    /**
     * $period: month or day
     */
    
    public static function getPrintings($user, $printer, $period){
        $query = DB::table('printings')

            // somente as impressões do usuário em questão
            ->where('printings.user',$user)

            // considerando impressões das impressoras pertencentes a mesma regra
            ->join('printers', 'printings.printer_id', '=', 'printers.id')
            ->where('printers.rule_id',$printer->rule->id)

            // considerando somente impressões com status de impresso
            ->join('status', 'printings.id', '=', 'status.printing_id')
            ->where('status.name','print_success');
        
            // somente impressões do mês ou do dia
            if($period == 'month')
                $query->whereMonth('printings.created_at','=', date('n'));
            
            if($period == 'day')
                $query->whereDate('printings.created_at', Carbon::today());

        return $query->sum(DB::raw('printings.pages*printings.copies'));
    }   
}

