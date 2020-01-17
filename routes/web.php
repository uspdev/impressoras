<?php

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

Route::get('/', 'IndexController@index')->name('home');
Route::get('/printings', 'PrintingController@index');
Route::get('/printings/admin', 'PrintingController@admin');
Route::get('/printings/{printer}', 'PrintingController@printer');

# Senha única USP
Route::get('/senhaunica/login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
Route::post('/logout', 'Auth\LoginController@logout');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/check/{user}/{printer}/{pages}', 'PrintingController@check');
Route::get('/pages/today/{user}/', 'PrintingController@pagesToday');

/* Fila de impressão */
//Route::get('/fila/printers/{printer}/', 'PrintingController@fila');

