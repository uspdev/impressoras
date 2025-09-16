<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\LocalUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\PrintingController;
use App\Http\Controllers\RuleController;

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

Route::get('/printings/print', [PrintingController::class, 'listPrinters']);
Route::get('/printings/print/{printer}', [PrintingController::class, 'create']);
Route::post('/printings/print/{printer}', [PrintingController::class, 'store']);

Route::get('/all-printings', [PrintingController::class, 'show']);
Route::get('/printings', [PrintingController::class, 'index']);
Route::get('/printings/admin', [PrintingController::class, 'admin']);
Route::get('/printings/foto/{codpes}', [PrintingController::class, 'obterFoto']);
Route::get('/printings/status/{printing}', [PrintingController::class, 'status']);
Route::get('/printings/action/{printing}', [PrintingController::class, 'action']);
Route::get('/printings/refund/{printing}', [PrintingController::class, 'refund']);

// Printers
Route::resource('/printers', PrinterController::class);
Route::get('/printers/queue/{printer}', [PrinterController::class, 'printer_queue']);
Route::get('/printers/auth_queue/{printer}', [PrinterController::class, 'authorization_queue']);
Route::get('/printers/{printer}', [PrinterController::class, 'show']);
Route::get('/printers/{printer}/printtest', [PrinterController::class, 'printTest']);

// Rules
Route::resource('/rules', RuleController::class);

// local login
Route::get('/login/local', [LoginController::class, 'index']);
Route::post('/login/local', [LoginController::class, 'login']);

// local user
Route::resource('/local', LocalUserController::class)->parameters([
    'local' => 'user'
]);

// assistants
Route::resource('/assistants', AssistantController::class);

// help
Route::get('/help/raster', [HelpController::class, 'raster']);

// Logs
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('can:admin');
