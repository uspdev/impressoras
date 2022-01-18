<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Printing;
use App\Models\Printer;
use App\Models\Status;
use Uspdev\Replicado\Pessoa;
use Carbon\Carbon;
use DB;

class PrintingController extends Controller
{
    public function check(Request $request){
        
        if($request->header('Authorization') != env('API_KEY') ){
            return response('Acesso nao autorizado',403);
        }
        // o que esperamos no $request: {user}, {printer}, {pages}, {copies}
        
        // Carregamento da impressora
        $printer = Printer::where('machine_name',$request->printer)->first();
        if(!$printer) {
            $printer = new Printer;
            $printer->machine_name = $request->printer;
            $printer->name = $request->printer;
            $printer->save();
        }
        
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

            return response()->json([true, $printing->id]);
        }
        
        // 1. usuario pode imprimir nessa impressora?

        $permissao = array_intersect(Pessoa::obterSiglasVinculosAtivos($request->user), $printer->rule->categorias) ? true : false;
        
        if ($permissao) {

            // 2. Ele tem quota suficiente para a quantidade de paginas requerida dada a regra da impressora?
            
            $this->createStatus("checking_user_quota", $printing->id);

            if (empty($printer->rule->type_of_control)) {
                
                if($printer->rule->authorization_control) {

                    $this->createStatus("waiting_job_authorization", $printing->id);
                    return response()->json([true, $printing->id]);
                }                    

                $this->createStatus("sent_to_printer_queue", $printing->id);
                return response()->json([true, $printing->id]);
                

                return response()->json([true, 'Autorizado. Impressora sem controle de quota']);

            }
                
            // 2.1. Se a regra for mensal, pegar só as desse mês 
            if ($printer->rule->type_of_control == "Mensal") {

                $quantidade = Printing::where('user', $request->user)->whereMonth('created_at','=' , date('n'))->sum(DB::raw('pages*copies'));

            }                       

            // 2.2 Se a regra for diaria, pegar só as de hoje 
            elseif ($printer->rule->type_of_control == "Diário") {

                $quantidade = Printing::where('user', $request->user)->whereDate('created_at', Carbon::today())->sum(DB::raw('pages*copies'));

            }

            if ($quantidade + $request->pages <= $printer->rule->quota) {

                $status = new Status;
                $status->name = "waiting_job_authorization";
                $status->printing_id = $printing->id; 
                $status->save();

                return response()->json([true, 'Autorizado; Razao: Usuario possui quota disponivel. Quantidade de impressoes ja feitas: ' . $quantidade . '; Impressoes solicitadas: ' . $request->pages]);

            } else {

            
            $status = new Status;
            $status->name = "cancelled_user_out_of_quota";
            $status->printing_id = $printing->id; 
            $status->save();
            
            return response()->json([false, 'Nao autorizado. Razao: Quota excedida. Quantidade de impressoes ja feitas: ' . $quantidade . '; Impressoes solicitadas: ' . $request->pages]);

            }
        }

        $status = new Status;
        $status->name = "cancelled_not_authorized";
        $status->printing_id = $printing->id; 
        $status->save();
        
        return response()->json([false,'Nao autorizado. Razao: Usuario sem permissao para utilizar esta impressora.']);

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

        # printer machine_name 
        # $printing->printer_id = null;

        $status = new Status;
        $status->name = "sent_to_printer_queue";
        $status->printing_id = $printing->id; 
        $status->save();
        
        return response()->json(true);

    }

    private function createStatus($name, $printing_id)
    {
        $status = new Status;
        $status->name = $name;
        $status->printing_id = $printing_id; 
        $status->save();
    }
}
