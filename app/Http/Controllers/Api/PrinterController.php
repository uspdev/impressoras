<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Printer;

use Illuminate\Http\Request;


class PrinterController extends Controller
{
    public function printers_without_rules(Request $request)
    {
        $printers = Printer::whereNull('rule_id')->pluck('machine_name')->toArray();
        return response()->json($printers);
    }
}
