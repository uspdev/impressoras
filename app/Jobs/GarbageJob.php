<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Printing;
use App\Models\Status;
use Carbon\Carbon;

class GarbageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Job para mudar o status dos arquivos que deram problema no processamento
     * Está fixo para 30 minutos, mas podemos parametrizar.
     *
     * @return void
     */
    public function handle()
    {
        // Todos o printings que estão com o status: processing_pdf
        $printings = Printing::where('latest_status', 'processing_pdf')
                                ->where('updated_at', '<', Carbon::now()->subMinutes(30))
                                ->get();

        foreach($printings as $printing){
            Status::createStatus('failed_in_process_pdf', $printing);
        }

        // Todos o printings que estão com o status: sent_to_printer_queue
        $printings = Printing::where('latest_status', 'sent_to_printer_queue')
                                ->where('updated_at', '<', Carbon::now()->subMinutes(30))
                                ->get();

        foreach($printings as $printing){
            Status::createStatus('printer_problem', $printing);
        }
    }
}