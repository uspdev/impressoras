<?php

use App\Http\Controllers\Api\PrintingController;

// Registrar uma nova tentativa impressão
Route::post('/printings', [PrintingController::class, 'store']);
Route::get('/printings/{printing}', [PrintingController::class, 'show']);
Route::post('/printings/{printing}', [PrintingController::class, 'update']);
