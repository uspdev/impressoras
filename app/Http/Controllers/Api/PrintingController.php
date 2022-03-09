<?php

namespace App\Http\Controllers\Api;

use DB;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrintingRequest;
use App\Models\Printer;
use App\Models\Printing;
use App\Models\Status;

use Uspdev\Replicado\Pessoa;


class PrintingController extends Controller
{
    public function store(PrintingRequest $request){
        
        if($request->header('Authorization') != env('API_KEY') ){
            return response('Acesso nao autorizado',403);
        }

        // Carregamento da impressora
        $printer = $this->loadPrinter($request->printer);

        // Registra printing
        $validated = $request->validated();
        unset($validated['printer']);
        $validated['printer_id'] = $printer->id;
        $printing = Printing::create($validated);

        // 1. Se a impressora não tem regra, então qualquer impressao esta liberada
        if(!$printer->rule) {
            $this->createStatus("sent_to_printer_queue", $printing->id);
            return response()->json(["yes", $printing->id, $printing->latest_status->name]);
        }

        // 2. Usuário está em uma categoria que permite impressão
        if(!empty($printer->rule->categories)){
            $codpes = filter_var($request->user, FILTER_SANITIZE_NUMBER_INT);
            $vinculos = Pessoa::obterSiglasVinculosAtivos($codpes);
            $permissao = array_intersect($vinculos, $printer->rule->categories) ? true : false;
            if (!$permissao) {
                $this->createStatus("cancelled_not_authorized", $printing->id);
                return response()->json(["no", $printing->id, $printing->latest_status->name]);
            }
        }
        
        // 3. Cálculo da quantidade que a pessoa imprimiu no mês
        $quantidade = 0;
        if ($printer->rule->type_of_control == "Mensal") {

            $quantidade = Printing::where('user', $request->user)
                                   ->whereMonth('created_at','=' , date('n'))
                                   ->sum(DB::raw('pages*copies'));

        }                       

        // 4. Cálculo da quantidade que a pessoa imprimiu no dia
        if ($printer->rule->type_of_control == "Diário") {
            $quantidade = Printing::where('user', $request->user)
                                    ->whereDate('created_at', Carbon::today())
                                    ->sum(DB::raw('pages*copies'));

        }

        // 5. Verifica se ultrapassou da quota disponível ou não
        if (!empty($printer->rule->type_of_control)) {
            $ultrapassou = $quantidade + $request->pages*$request->copies > $printer->rule->quota;
        } else {
            $ultrapassou = false;
        }
        
        if ($ultrapassou){
            $this->createStatus("cancelled_user_out_of_quota", $printing->id);
            return response()->json(["no", $printing->id, $printing->latest_status->name, $quantidade]);
        }
       
        // 6. Verifica se a impressora tem controle de fila
        if($printer->rule->authorization_control) {
            $this->createStatus("waiting_job_authorization", $printing->id);
            return response()->json(["no", $printing->id, $printing->latest_status->name]);
        } else {

        }

    }

    /************* Métodos privados auxiliares ***************/

    private function loadPrinter($request_printer)
    {
        $printer = Printer::where('machine_name', $request_printer)->first();
        if(!$printer) {
            $printer = new Printer;
            $printer->machine_name = $request_printer;
            $printer->name = $request_printer;
            $printer->save();
        }
        return $printer;
    }

    private function createStatus($name, $printing_id)
    {
        $status = new Status;
        $status->name = $name;
        $status->printing_id = $printing_id; 
        $status->save();
    }
}
