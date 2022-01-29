<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});



Route::get('/gateway_login', [App\Http\Controllers\SMSController::class, 'gateway_login'])->name('gateway_login');
Route::get('/units_balance', [App\Http\Controllers\SMSController::class, 'units_balance'])->name('units_balance');
Route::get('/send_sms', [App\Http\Controllers\SMSController::class, 'send_sms'])->name('send_sms');
Route::get('/batch_sms', [App\Http\Controllers\SMSController::class, 'batch_sms'])->name('batch_sms');