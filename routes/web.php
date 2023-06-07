<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\PrintingController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\WebprintingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Printings
Route::get('/', [IndexController::class, 'index'])->name('home');

Route::get('/all-printings', [PrintingController::class, 'show']);
Route::get('/printings', [PrintingController::class, 'index']);
Route::get('/printings/admin', [PrintingController::class, 'admin']);
Route::get('/printings/foto/{codpes}', [PrintingController::class, 'obterFoto']);
Route::get('/printings/status/{printing}', [PrintingController::class, 'status']);
Route::get('/printings/action/{printing}', [PrintingController::class, 'action']);

// Printers
Route::resource('/printers', PrinterController::class);
Route::get('/printers/queue/{printer}', [PrinterController::class, 'printer_queue']);
Route::get('/printers/auth_queue/{printer}', [PrinterController::class, 'authorization_queue']);
Route::get('/printers/{printer}', [PrinterController::class, 'show']);

// webprintings
Route::get('/webprintings', [WebprintingController::class, 'create']);
Route::post('/webprintings', [WebprintingController::class, 'store']);

// Rules
Route::resource('/rules', RuleController::class);

// Logs
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('can:admin');
