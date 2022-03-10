<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function latest_status()
    {
        return $this->hasOne(Status::class)->latest();
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
    public static function getPrintingsQuantitiesUser($user = null, $printer = null, $period = null)
    {
        $query = DB::table('printings')

            // somente as impressões do usuário em questão
            ->where('printings.user', $user)

            // considerando impressões das impressoras pertencentes a mesma regra
            ->join('printers', 'printings.printer_id', '=', 'printers.id')
            ->where('printers.rule_id', $printer->rule->id)

            // considerando somente impressões com status de impresso
            ->join('status', 'printings.id', '=', 'status.printing_id')
            ->where('status.name', 'print_success');

        // somente impressões do mês ou do dia
        if ($period == 'Mensal') {
            $query->whereMonth('printings.created_at', '=', date('n'));
        }

        if ($period == 'Diário') {
            $query->whereDate('printings.created_at', Carbon::today());
        }

        return $query->sum(DB::raw('printings.pages*printings.copies'));
    }

    public static function getPrintingsFromScope($printer, $status)
    {
        $printings = DB::table('printings')

            // considerando somente as impressões da impressora em questão
            ->join('printers', 'printings.printer_id', '=', 'printers.id')
            ->where('printers.id', $printer->id)

            // considerando somente impressões com $status
            ->join('status', 'printings.id', '=', 'status.printing_id')
            ->where('status.name', $status)
            ->select('printings.id')

            ->get();

        $printings = $printings->pluck('id');

        return $printings;
    }
}
