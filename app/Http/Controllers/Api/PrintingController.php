<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Printing;
use App\Models\Printer;
use Uspdev\Replicado\Pessoa;

class PrintingController extends Controller
{
    // A pessoa em questão pode imprimir nessa impressora? 
    public function check(Request $request){
        
        if($request->header('Authorization') != env('API_KEY') ){
            return response('Acesso não autorizado',403);
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
        
        return response()->json($request); 

        // 0. Se a impressora não estiver em nenhuma regra, então qualquer impressão está liberada
        
        if(!$printer->rule)
            return response()->json(true);

        
        // 1. usuário pode imprimir nessa impressora?

        $permissao = array_intersect(Pessoa::obterSiglasVinculosAtivos($request->user),$printer->rule->categorias) ? true : false;

        if ($permissao){

        // 2. Ele tem quota suficiente para a quantidade de páginas requerida dada a regra da impressora?
        $impressoes = Printing::where('user', $request->user)->get();
        
        // Detalhe:
        // 1. Se a regra for mensal, pegar só as desse mês de $ja_impressas
        if ($printer->rule->type_of_control == "Mensal") {
            $impressoes_mes = $impressoes->whereMonth('created_at','=' , date('n'))->sum(DB::raw('pages*copies'));
            return $impressoes_mes;
            }
        
        // 2. Se a regra for diária, pegar só as de hoje $ja_impressas
        elseif ($printer->rule->type_of_control == "Diário") {
            $impressoes_dia = $impressoes->whereDate('created_at', Carbon::today())->sum(DB::raw('pages*copies'));
            return $impressoes_dia;
            }

        } else return response()->json(false);
    }

    public function store(Request $request){

        if($request->header('Authorization') != env('API_KEY') ){
            return response('Acesso não autorizado',403);
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
        
        
        return response()->json(['teste']);

        dd('ok');
    }
}
