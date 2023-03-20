<?php

use App\Http\Controllers\ApiController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [ApiController::class, 'login']);
    Route::put('token/{id}', [ApiController::class, 'updatetoken'])->middleware('auth:sanctum');
    Route::get('logout', [ApiController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('forgot-password', [ApiController::class, 'forgotPassword']);
    Route::post('password/{id}', [ApiController::class, 'passwordupdate'])->middleware('auth:sanctum');
    Route::get('listbooking/{id}', [ApiController::class, 'listBooking'])->middleware('auth:sanctum');
    Route::get('detailbooking/{id}', [ApiController::class, 'detailBooking'])->middleware('auth:sanctum');
    Route::put('selesai/{id}', [ApiController::class, 'selesai'])->middleware('auth:sanctum');
});
