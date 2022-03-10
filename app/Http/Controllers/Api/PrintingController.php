<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrintingRequest;
use App\Models\Printer;
use App\Models\Printing;
use App\Models\Status;
use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;

class PrintingController extends Controller
{
    public function store(PrintingRequest $request)
    {
        if ($request->header('Authorization') != env('API_KEY')) {
            return response('Acesso nao autorizado', 403);
        }

        // Carregamento da impressora
        $printer = $this->loadPrinter($request->printer);

        // Registra printing
        $validated = $request->validated();
        unset($validated['printer']);
        $validated['printer_id'] = $printer->id;
        $printing = Printing::create($validated);

        if (!empty($printer->rule)) {
            // 1. Verificar se usuário está em uma categoria que permite impressão
            if (!empty($printer->rule->categories)) {
                $codpes = filter_var($request->user, FILTER_SANITIZE_NUMBER_INT);
                $vinculos = Pessoa::obterSiglasVinculosAtivos($codpes);
                $permissao = array_intersect($vinculos, $printer->rule->categories) ? true : false;
                if (!$permissao) {
                    $this->createStatus('cancelled_not_authorized', $printing->id);

                    return response()->json(['no', $printing->id, $printing->latest_status->name]);
                }
            }

            // 2. Verifica se ultrapassou da quota disponível ou não
            $quota_period = $printer->rule->quota_period;

            if (!empty($quota_period)) {
                $quantities = Printing::getPrintingsQuantitiesUser($request->user, $printer, $quota_period);
                $out_of_quota = $quantities + $request->pages * $request->copies > $printer->rule->quota;

                if ($out_of_quota) {
                    $this->createStatus('cancelled_user_out_of_quota', $printing->id);

                    return response()->json(['no', $printing->id, $printing->latest_status->name, $quantities]);
                }
            }

            // 3. Verifica se a impressora tem controle de fila
            if ($printer->rule->queue_control) {
                $this->createStatus('waiting_job_authorization', $printing->id);

                return response()->json(['no', $printing->id, $printing->latest_status->name]);
            }
        }

        // 4. Se a impressora não tem regra, então qualquer impressão esta liberada
        $this->createStatus('sent_to_printer_queue', $printing->id);

        return response()->json(['yes', $printing->id, $printing->latest_status->name]);
    }

    public function update(Request $request, Printing $printing)
    {
        $this->createStatus('print_success', $printing->id);

        return response()->json(['ok']);
    }

    /************* Métodos privados auxiliares ***************/

    private function loadPrinter($request_printer)
    {
        $printer = Printer::where('machine_name', $request_printer)->first();
        if (!$printer) {
            $printer = new Printer();
            $printer->machine_name = $request_printer;
            $printer->name = $request_printer;
            $printer->save();
        }

        return $printer;
    }

    private function createStatus($name, $printing_id)
    {
        $status = new Status();
        $status->name = $name;
        $status->printing_id = $printing_id;
        $status->save();
    }
}
