<?php

use App\Http\Controllers\Api\PrintingController;
use App\Http\Controllers\Api\PrinterController;

// Registrar uma nova tentativa impressão
Route::post('/printings', [PrintingController::class, 'store']);
Route::get('/printings/{printing}', [PrintingController::class, 'showStatus']);
Route::post('/printings/{printer}/{jobid}', [PrintingController::class, 'updateStatus']);

Route::get('/printers-without-rules', [PrinterController::class, 'printers_without_rules']);
