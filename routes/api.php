<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PrintingController;

Route::post('/printings', [PrintingController::class, 'store']);



