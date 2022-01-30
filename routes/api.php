<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', [App\Http\Controllers\API\AuthController::class, 'signin']);
Route::post('register', [App\Http\Controllers\API\AuthController::class, 'signup']);



//using sanctum as to protect api routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/units_balance', [App\Http\Controllers\API\SMSController::class, 'unit_balance'])->name('units_balance');
    Route::get('/send_sms', [App\Http\Controllers\API\SMSController::class, 'send_sms'])->name('send_sms');
    Route::get('/batch_sms', [App\Http\Controllers\API\SMSController::class, 'batch_sms'])->name('batch_sms');
    Route::get('/fetch_sms', [App\Http\Controllers\API\SMSController::class, 'fetch_sms'])->name('fetch_sms');
});
// Route::post('/token', function (Request $request) {
//     $token = $request->bearerToken();
// });