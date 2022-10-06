<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Uspdev\Replicado\Pessoa;

class Printing extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at'];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function status()
    {
        return $this->hasMany(Status::class);
    }

    public function authorizedByUserId()
    {
        return $this->belongsTo(User::class, 'authorized_by_user_id');
    }

    /**
     * Função para retornar a quantidade de impressões em determinado contexto.
     *
     * @param string  $user    N.USP
     * @param Printer $printer objeto Printer
     * @param string  $period  Mensal ou diário
     *
     * @return int quantidade de impressões para o contexto
     */
    public static function getPrintingsQuantities($user = null, $printer = null, $period = null)
    {
        $query = DB::table('printings');
        $query->where('printings.latest_status', 'print_success');

        // somente as impressões do usuário em questão
        if ($user) {
            $query->where('printings.user', $user);
        }

        if ($printer) {
            // contabiliza todas as impressões em todas as impressoras do respectivo usuário
            $query->join('printers', 'printings.printer_id', '=', 'printers.id');

            // considerando impressões das impressoras pertencentes a mesma regra
            if ($printer->rule) {
                $query->where('printers.rule_id', $printer->rule->id);
            }
        }

        // somente impressões do mês ou do dia
        if ($period == 'Mensal') {
            $query->whereMonth('printings.created_at', '=', date('n'));
        } elseif ($period == 'Diário') {
            $query->whereDate('printings.created_at', Carbon::today());
        }

        return $query->sum(DB::raw('printings.pages*printings.copies'));
    }

    public function getNomeAttribute() {
        $codpes = (int) $this->user;
        if(!empty($codpes)){
            return Pessoa::nomeCompleto($codpes);
        } else {
            return '';
        } 
    }
}
