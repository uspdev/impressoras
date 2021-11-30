<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Printing;
use App\Models\Printer;
use Uspdev\Replicado\Pessoa;
use Carbon\Carbon;
use DB;

class PrintingController extends Controller
{
    public function check(Request $request){
        
        if($request->header('Authorization') != env('API_KEY') ){
            return response('Acesso nao autorizado',403);
        }
        // o que esperamos no $request: {user}, {printer} e {pages}
        
        // Carregamento da impressora
        $printer = Printer::where('machine_name',$request->printer)->first();
        if(!$printer) {
            $printer = new Printer;
            $printer->machine_name = $request->printer;
            $printer->name = $request->printer;
            $printer->save();
        }
        
        // 0. Se a impressora nao estiver em nenhuma regra, entao qualquer impressao esta liberada
        
        if(!$printer->rule)
            return response()->json([true, 'Autorizado. Impressora sem regra de impressao.']);

        
        // 1. usuario pode imprimir nessa impressora?

        $permissao = array_intersect(Pessoa::obterSiglasVinculosAtivos($request->user), $printer->rule->categorias) ? true : false;
        
        if ($permissao){

            // 2. Ele tem quota suficiente para a quantidade de paginas requerida dada a regra da impressora?

            if (empty($printer->rule->type_of_control)) {

                return response()->json([true, 'Autorizado. Impressora sem controle de quota']);

            }
                
            // 2.1. Se a regra for mensal, pegar só as desse mês 
            if ($printer->rule->type_of_control == "Mensal") {

                $quantidade = Printing::where('user', $request->user)->whereMonth('created_at','=' , date('n'))->sum(DB::raw('pages*copies'));

            }                       

            // 2.2 Se a regra for diaria, pegar só as de hoje 
            elseif ($printer->rule->type_of_control == "Diario") {

                $quantidade = Printing::where('user', $request->user)->whereDate('created_at', Carbon::today())->sum(DB::raw('pages*copies'));

            }

            if ($quantidade + $request->pages <= $printer->rule->quota) {

                return response()->json([true, 'Autorizado; Razao: Usuario possui quota disponivel. Quantidade de impressoes ja feitas: ' . $quantidade . '; Impressoes solicitadas: ' . $request->pages]);

            } else return response()->json([false, 'Nao autorizado. Razao: Quota excedida. Quantidade de impressoes ja feitas: ' . $quantidade . '; Impressoes solicitadas: ' . $request->pages]);

        }

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

        # status?
        
        
        return response()->json(true);

    }
}
