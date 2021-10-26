<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PrintingController;

// A pessoa em questão pode imprimir nessa impressora? 
Route::post('/check', [PrintingController::class, 'check']);

// Registrar uma nova impressão
Route::post('/printings', [PrintingController::class, 'store']);




