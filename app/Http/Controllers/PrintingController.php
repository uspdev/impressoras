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
        $quantities['Mensal'] = Printing::getPrintingsQuantitiesUser($user->codpes, null, 'Mensal');
        $quantities['Diário'] = Printing::getPrintingsQuantitiesUser($user->codpes, null, 'Diário');
        $quantities['Total'] = Printing::getPrintingsQuantitiesUser($user->codpes);

        if ($request->has('route')) {
            return view('printings/partials/printing',
                       compact('printings', 'quantities'));
        }

        return view('printings/index', compact('printings', 'quantities'));
    }

    public function status(Printing $printing)
    {
        $this->authorize('admin');
        $statuses = $printing->status->sortByDesc('created_at')->all();

        return view('printings.status', [
            'printing' => $printing,
            'statuses' => $statuses,
        ]);
    }

    public function action(Request $request, Printing $printing)
    {
        $this->authorize('admin');

        $printer = $printing->printer;
        $user = \Auth::user();
        $printing->authorized_by_user_id = $user->id;
        $printing->save();

        if ($request->action == 'authorized') {
            Status::createStatus('sent_to_printer_queue', $printing);
            $action = 'autorizada';
        } elseif ($request->action == 'cancelled') {
            Status::createStatus('cancelled_not_authorized', $printing);
            $action = 'cancelada';
        }

        request()->session()->flash('alert-success', 'Impressão '.$action.' com sucesso.');

        return redirect("/printers/auth_queue/{$printer->id}");
    }
}
