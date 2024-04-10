<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Helpers\PrintingHelper;
use Illuminate\Support\Facades\File;
use Rawilk\Printing\Facades\Printing as CupsPrinting;
use App\Models\Printing;
use App\Models\Status;

class PrintingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $printing;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Printing $printing)
    {
        $this->printing = $printing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pdfinfo = PrintingHelper::pdfinfo($this->printing->filepath_original);

        if (!empty($this->printing->start_page)) {
            // trata possível erro de preenchimento
            $end = min($pdfinfo['pages'], $this->printing->end_page);
            $filepath_pdfjam = PrintingHelper::pdfjam($this->printing->filepath_original,
                                                      $this->printing->pages_per_sheet, 
                                                      $this->printing->start_page, 
                                                     $end);
        }
        else {
            $filepath_pdfjam = PrintingHelper::pdfjam($this->printing->filepath_original,
                                                      $this->printing->pages_per_sheet);
        }

        // salvando caminho filepath_pdfjam
        if(empty($filepath_pdfjam)) {
            Status::createStatus('failed_in_process_pdf', $this->printing);
        } else {
            $this->printing->filepath_pdfjam = $filepath_pdfjam;
            $this->printing->save();
        }

        // salvando caminho filepath_processed processado com ghostscript
        $filepath_processed = PrintingHelper::pdfx($this->printing->filepath_pdfjam, $this->printing->printer->color);
        if(empty($filepath_processed) ) {
            Status::createStatus('failed_in_process_pdf', $this->printing);
        } else {
            $this->printing->filepath_processed = $filepath_processed;
            $this->printing->save();
            Status::createStatus('pdf_processed_successfully', $this->printing);
        }
        
        // Enviando para impressora
        if(!empty($this->printing->filepath_processed)) {
            // Podemos mandar para impressão
            Status::createStatus('sent_to_printer_queue', $this->printing);

            $id = 'ipp://'.config('printing.drivers.cups.ip').':631/printers/' . $this->printing->printer->machine_name;

            $printJob = CupsPrinting::newPrintTask()
                ->printer($id)
                ->jobTitle($this->printing->filename)
                ->sides($this->printing->sides)
                ->copies($this->printing->copies)
                ->file($this->printing->filepath_processed)
                ->send();
            $this->printing->jobid = $printJob->id();
            $this->printing->save();
            Status::createStatus('print_success', $this->printing);
        }

        // deletando arquivos
        if(!empty($this->printing->filepath_original)) {
            File::delete($this->printing->filepath_original);
        }

        if(!empty($this->printing->filepath_pdfjam)) {
            File::delete($this->printing->filepath_pdfjam);
        }

        if(!empty($this->printing->filepath_processed)) {
            File::delete($this->printing->filepath_processed);
        }
    }
}
