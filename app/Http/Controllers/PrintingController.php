<?php

namespace App\Http\Controllers;

use App\Models\Printing;
use App\Models\Status;
use Illuminate\Http\Request;

class PrintingController extends Controller
{
    /**
     * Display a listing of the resource.

     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // printings
        $user = \Auth::user();
        $printings = Printing::where('user', '=', $user->codpes);
        $printings = $printings->orderBy('jobid', 'DESC')->paginate(10);
        if ($request->has('route')) {
            return view('printings/partials/printing',
                       compact('printings'));
        }

        return view('printings/index', compact('printings'));
    }

    public function admin(Request $request)
    {
        $this->authorize('admin');
        sleep(5);
        $printings = Printing::orderBy('created_at', 'DESC')->paginate(30);
        $quantidades = Printing::quantidades('impressas');
        if ($request->has('route')) {
            return view('printings/partials/printing',
                       compact('printings', 'quantidades'));
        }

        return view('printings/index', compact('printings', 'quantidades'));
    }

    public function status(Printing $printing)
    {
        $this->authorize('admin');
        $statuses = $printing->status->all();

        return view('printings.status', [
            'printing' => $printing,
            'statuses' => $statuses,
        ]);
    }

    public function acao(Request $request, Printing $printing)
    {
        $this->authorize('admin');

        $printer = $printing->printer;
        $status = new Status();
        if ($request->acao == 'autorizada') {
            $status->name = 'sent_to_printer_queue';
        } elseif ($request->acao == 'cancelada') {
            $status->name = 'cancelled_not_authorized';
        }
        $status->printing_id = $printing->id;
        $status->save();
        request()->session()->flash('alert-success', 'Impressão '.$request->acao.' com sucesso.');

        return redirect("/printers/fila/{$printer->id}");
    }

    public function pendentes()
    {
    }
}
