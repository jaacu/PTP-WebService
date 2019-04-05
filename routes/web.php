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

Route::get('/', 'PagoController@index')->name('home');

Route::post('pagos', 'PagoController@store')->name('pagos.store');

Route::get('pagos/{pago}', 'PagoController@show')->name('pagos.show');
