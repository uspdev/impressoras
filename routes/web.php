<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\PrintingController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/printings', [PrintingController::class, 'index']);
Route::get('/printings/admin', [PrintingController::class, 'admin']);
Route::get('/printings/user/{user}', [PrintingController::class, 'user']);
Route::get('/printings/jobid/{jobid}', [PrintingController::class, 'jobid']);
Route::get('/printings/{printer}', [PrintingController::class, 'printer']);

# Senha única USP
Route::get('/senhaunica/login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('/callback', [LoginController::class, 'handleProviderCallback']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::get('/check/{user}/{printer}/{pages}', [PrintingController::class, 'check']);
Route::get('/pages/today/{user}/', [PrintingController::class, 'pagesToday']);

/* Fila de impressão */
Route::get('/pendentes', [PrintingController::class, 'pendentes']);
Route::get('/pendentes/{printer}/', [PrintingController::class, 'pendentes']);

