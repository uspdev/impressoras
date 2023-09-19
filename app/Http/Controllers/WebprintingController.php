<?php

namespace App\Http\Controllers;

//use App\Http\Requests\PrintingRequest;
use App\Helpers\PrintingHelper;
use App\Models\Printer;
use App\Models\Printing;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Rawilk\Printing\Facades\Printing as CupsPrinting;
use Uspdev\Replicado\Pessoa;

class WebprintingController extends Controller
{
    public function index(){
        $this->authorize('logado');
        $printers = [];

        foreach (Printer::all() as $p) {
            if ($p->allows(\Auth::user())) {
                array_push($printers, $p);
            }
        }

        return view('webprintings.index', [
            'printers' => $printers,
        ]);
    }

    public function create(Printer $printer){
        $this->authorize('imprime', $printer);
        return view('webprintings.create', [
            'printer' => $printer
        ]);
    }

    public function store(Request $request, Printer $printer){
        $this->authorize('imprime', $printer);
        $user = \Auth::user();

        // validação básica
        // TODO validar paginação
        $request->validate([
            'file' => 'required|mimetypes:application/pdf',
            'sides' => ['required', Rule::in(['one-sided', 'two-sided-long-edge', 'two-sided-short-edge'])],
            'start_page' => 'nullable|required_with:end_page|integer|min:1|digits_between: 1,5',
            'end_page' =>   'nullable|required_with:start_page|integer|gte:start_page|digits_between:1,5'
        ]);

        // metadatas do arquivo
        $relpath = $request->file('file')->store('.');
        $filepath = Storage::disk('local')->path($relpath);
        $filename = $request->file('file')->getClientOriginalName();
        $filesize = $request->file('file')->getSize();

        $pdfinfo = PrintingHelper::pdfinfo($filepath);
        $pages = $pdfinfo['pages'];
        if ($pages < 1)
            throw new \Exception("Problema na contagem: contagem errada.");

        // TODO refatorar
        if (!empty($request->start_page))
            $pages = $request->end_page - $request->start_page + 1;

        $id = 'ipp://'.config('printing.drivers.cups.ip').':631/printers/' . $printer->machine_name;

        // Trucando o nome para no máximo 64 caracteres
        $filename = explode('.pdf',$filename);
        $filename = substr($filename[0],0,64).'.pdf';

        $data = [
            "user" => $user->codpes,
            "pages" => $pages,
            "copies" => 1,
            "printer_id" => $printer->id,
            "jobid" => 0,
            "host" => "127.0.0.1",
            "filename" => $filename,
            "filesize" => $filesize,
            "sides" => $request->sides,
            "start_page" => $request->start_page,
            "end_page" => $request->end_page,
            "tmp_relpath" => $relpath,
        ];

        // OBS: aqui não temos ainda o jobid de verdade
        $printing = Printing::create($data);

        if (!empty($printer->rule)) {
            // 1. Verifica se ultrapassou da quota disponível ou não
            $quota_period = $printer->rule->quota_period;

            if (!empty($quota_period)) {
                // as impressoras que participam da mesma regra
                $quantities = $printer->used($user);
                $out_of_quota = $quantities + $pages > $printer->rule->quota;
                if ($out_of_quota) {
                    Status::createStatus('cancelled_user_out_of_quota', $printing);
                    return redirect("/printings");
                }
            }

            // 2. Verifica se a impressora tem controle de fila
            if ($printer->rule->queue_control) {
                Status::createStatus('waiting_job_authorization', $printing);
                return redirect("/printings");
            }
        }
        // 3. Se a impressora não tem regra, então qualquer impressão esta liberada
        Status::createStatus('sent_to_printer_queue', $printing);

        // 4. Trata o PDF antes de mandá-lo para a impressora
        $tmp_pdf = PrintingHelper::pdfjam($filepath);
        $pdfx = PrintingHelper::pdfx($tmp_pdf);

        $printJob = CupsPrinting::newPrintTask()
            ->printer($id)
            ->range($request->start_page, $request->end_page)
            ->jobTitle($filename)
            ->sides($request->sides)
            ->file($pdfx)
            ->send();
        Storage::delete($relpath);
        File::delete($tmp_pdf);
        File::delete($pdfx);

        $printing->jobid = $printJob->id();
        $printing->save();
        Status::createStatus('print_success', $printing);

        return redirect("/printings");
    }
}
