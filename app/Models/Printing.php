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
     * @param string  $type    Páginas ou Folhas
     *
     * @return int quantidade de impressões para o contexto
     */
    public static function getPrintingsQuantities($user = null, $printer = null, $period = null, $type = null)
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
            $query->whereYear('printings.created_at', '=', date('Y'));
            $query->whereMonth('printings.created_at', '=', date('n'));
        } elseif ($period == 'Diário') {
            $query->whereDate('printings.created_at', Carbon::today());
        }

        // somente impressões de páginas ou de folhas
        if ($printer && $printer->rule && !empty($type))
            $query->whereExists(function ($subQuery) use ($type) {
                $subQuery->select(DB::raw(1))
                    ->from('printers')
                    ->join('rules', 'printers.rule_id', '=', 'rules.id')
                    ->join('printings', 'printings.printer_id', '=', 'printers.id')
                    ->where('rules.quota_type', $type);
            });

        return $query->sum(DB::raw('printings.' . (($type ?? 'Páginas') == 'Páginas' ? 'pages' : 'sheets') . '*printings.copies'));
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
