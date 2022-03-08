<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Printing;
use App\Models\Printer;
use App\Models\Status;
use App\Http\Requests\PrintingRequest;
use Uspdev\Replicado\Pessoa;
use Carbon\Carbon;
use DB;

class PrintingController extends Controller
{
    public function check(PrintingRequest $request){
        
        if($request->header('Authorization') != env('API_KEY') ){
            return response('Acesso nao autorizado',403);
        }
        // o que esperamos no $request: {user}, {printer}, {pages}, {copies}

        // Carregamento da impressora
        $printer = $this->loadPrinter($request->printer);
        
        $printing = new Printing;
        $printing->pages      = $request->pages;
        $printing->copies     = $request->copies;
        $printing->filename   = $request->filename;
        $printing->filesize   = $request->filesize;
        $printing->user       = $request->user;
        $printing->host       = $request->host;
        $printing->printer_id = $printer->id;
        $printing->jobid      = $request->jobid;
        $printing->save();

        /*  - Criar um Status numa variável $status;
            - colocar o printing criado na chave estrangeira dessa variável $status
            - Primeiro status: checking_user_quota
            - Verificar se impressora tem controle de fila. Se sim:
                - Em todas as opções true, colocar status como waiting_job_authorization
            - Se não, em todas as opções true, colocar status como sent_to_printer_queue
        */


        // 0. Se a impressora nao estiver em nenhuma regra, entao qualquer impressao esta liberada
        
        if(!$printer->rule) {
            $this->createStatus("sent_to_printer_queue", $printing->id);
            return response()->json(["yes", $printing->id, $printing->latest_status->name]);
        }

        // 1. usuario pode imprimir nessa impressora?
        // se nenhuma categoria estiver selecionada na Regra, todas estão permitidas:
        if(empty($printer->rule->categories)){
            $permissao = true;
        } else {
            $vinculos = Pessoa::obterSiglasVinculosAtivos($request->user);
            $permissao = array_intersect($vinculos, $printer->rule->categories) ? true : false;
        }
        
        if (!$permissao) {
            $this->createStatus("cancelled_not_authorized", $printing->id);
            return response()->json(["no", $printing->id, $printing->latest_status->name]);
        }

        // 2. Cálculo da quantidade que a pessoa imprimiu no mês
        // 2.1. Se a regra for mensal, pegar só as desse mês 
        $quantidade = 0;
        if ($printer->rule->type_of_control == "Mensal") {

            $quantidade = Printing::where('user', $request->user)
                                   ->whereMonth('created_at','=' , date('n'))
                                   ->sum(DB::raw('pages*copies'));

        }                       

        // Cálculo da quantidade que a pessoa imprimiu no dia
        // 2.2 Se a regra for diaria, pegar só as de hoje 
        if ($printer->rule->type_of_control == "Diário") {
            $quantidade = Printing::where('user', $request->user)
                                    ->whereDate('created_at', Carbon::today())
                                    ->sum(DB::raw('pages*copies'));

        }

        if (!empty($printer->rule->type_of_control)) {
            $ultrapassou = $quantidade + $request->pages*$request->copies > $printer->rule->quota;
        } else {
            $ultrapassou = false;
        }
        
        if ($ultrapassou){
            $this->createStatus("cancelled_user_out_of_quota", $printing->id);
            return response()->json(["no", $printing->id, $printing->latest_status->name, $quantidade]);
        }
       
        if($printer->rule->authorization_control) {
            $this->createStatus("waiting_job_authorization", $printing->id);
            return response()->json(["no", $printing->id, $printing->latest_status->name]);
        }

        $this->createStatus("sent_to_printer_queue", $printing->id);
        return response()->json(["yes", $printing->id, $printing->latest_status->name]);

    }

    public function store(Request $request){

        if($request->header('Authorization') != env('API_KEY') ){
            return response('Acesso nao autorizado',403);
        }

        $printing = new Printing;
        $printing->jobid = $request->jobid;
        $printing->pages = $request->pages;
        $printing->copies = $request->copies;
        $printing->filename = $request->filename;
        $printing->filesize = $request->filesize;
        $printing->user = $request->user;
        $printing->host = $request->host;
        $printing->save();

        $this->createStatus("sent_to_printer_queue", $printing->id);

        return response()->json(true);

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
