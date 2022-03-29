<?php

use App\Http\Controllers\Api\PrintingController;

// Registrar uma nova tentativa impressão
Route::post('/printings', [PrintingController::class, 'store']);
Route::get('/printings/{printing}', [PrintingController::class, 'showStatus']);
Route::get('/printings', [PrintingController::class, 'getFromJobId']);
Route::post('/printings/{printing}', [PrintingController::class, 'updateStatus']);
