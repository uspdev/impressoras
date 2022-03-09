<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PrintingController;

// Registrar uma nova tentativa impressão
Route::post('/printings', [PrintingController::class, 'store']);
Route::post('/printings/{printing}', [PrintingController::class, 'update']);




