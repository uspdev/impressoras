<?php

namespace App\Http\Controllers;

//use App\Http\Requests\PrintingRequest;
use App\Models\Printer;
use App\Models\Printing;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Rawilk\Printing\Facades\Printing as CupsPrinting;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Uspdev\Replicado\Pessoa;

class WebprintingController extends Controller
{
    public function index(){
        $printers = Printer::all();

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

        // validação básica
        $request->validate([
            'file' => 'required|mimetypes:application/pdf',
            'sides' => ['required', Rule::in(['one-sided', 'two-sided-long-edge', 'two-sided-short-edge'])],
        ]);

        // metadatas do arquivo
        $relpath = $request->file('file')->store('.');
        $filepath = Storage::disk('local')->path($relpath);
        $filename = $request->file('file')->getClientOriginalName();
        $filesize = $request->file('file')->getSize();

        // contagem de páginas usando o pdfinfo
        $pdfinfo = "/usr/bin/pdfinfo";
        if (!File::exists($pdfinfo))
            throw new \Exception("Instalar pdfinfo: apt install poppler-utils.");
        $process = new Process([$pdfinfo, $filepath]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        if (preg_match('/^Pages:\s+(\d+)/m', $process->getOutput(), $matches) != 1)
            throw new \Exception("Problema na contagem: número não encontrado.");

        $pages = $matches[1];
        if ($pages < 1)
            throw new \Exception("Problema na contagem: contagem errada.");

        $id = 'ipp://'.config('printing.drivers.cups.ip').':631/printers/' . $printer->machine_name;

        $codpes = \Auth::user()->codpes;
        $data = [
            "user" => $codpes,
            "pages" => $pages,
            "copies" => 1,
            "printer_id" => $printer->id,
            "jobid" => 0,
            "host" => "127.0.0.1",
            "filename" => $filename,
            "filesize" => $filesize,
            "sides" => $request->sides,
            "tmp_relpath" => $relpath,
        ];

        // OBS: aqui não temos ainda o jobid de verdade
        $printing = Printing::create($data);

        if (!empty($printer->rule)) {
            // 1. Verificar se usuário está em uma categoria que permite impressão
            if (!empty($printer->rule->categories)) {
                $permissao = false;
                foreach($printer->rule->categories as $c) {
                    if (\Auth::user()->hasPermissionTo($c, 'senhaunica')) {
                        $permissao = true;
                        break;
                    }
                }
                if (!$permissao) {
                    Status::createStatus('cancelled_not_allowed', $printing);
                    return redirect("/printings");
                }
            }

            // 2. Verifica se ultrapassou da quota disponível ou não
            $quota_period = $printer->rule->quota_period;

            if (!empty($quota_period)) {
                // as impressoras que participam da mesma regra
                $quantities = Printing::getPrintingsQuantities($codpes, $printer, $quota_period);
                $out_of_quota = $quantities + $pages > $printer->rule->quota;
                if ($out_of_quota) {
                    Status::createStatus('cancelled_user_out_of_quota', $printing);
                    return redirect("/printings");
                }
            }

            // 3. Verifica se a impressora tem controle de fila
            if ($printer->rule->queue_control) {
                Status::createStatus('waiting_job_authorization', $printing);
                return redirect("/printings");
            }
        }
        // 4. Se a impressora não tem regra, então qualquer impressão esta liberada
        Status::createStatus('sent_to_printer_queue', $printing);

        $printJob = CupsPrinting::newPrintTask()
            ->printer($id)
            ->jobTitle($filename)
            ->sides($request->sides)
            ->file($filepath)
            ->send();
        Storage::delete($relpath);

        $printing->jobid = $printJob->id();
        $printing->save();
        Status::createStatus('print_success', $printing);

        return redirect("/printings");
    }
}
