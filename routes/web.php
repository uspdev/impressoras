<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IndexController;
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

Route::get('/printings', [PrintingController::class, 'index']);
Route::get('/printings/admin', [PrintingController::class, 'admin']);
Route::get('/printings/status/{printing}', [PrintingController::class, 'status']);
Route::get('/printings/acao/{printing}', [PrintingController::class, 'acao']);

// Printers
Route::resource('/printers', PrinterController::class);
Route::get('/printers/fila/{printer}', [PrinterController::class, 'fila']);

// Rules
Route::resource('/rules', RuleController::class);

// Senha única USP
Route::get('/login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('/callback', [LoginController::class, 'handleProviderCallback']);
Route::post('/logout', [LoginController::class, 'logout']);

// Logs
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('can:admin');
