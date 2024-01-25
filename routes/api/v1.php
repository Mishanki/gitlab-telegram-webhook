<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'v1\Action'], static function () {
    Route::group(['namespace' => 'Webhook', 'prefix' => 'webhook'], static function () {
        Route::post('/{hash}', 'SendAction');
    });
    Route::group(['namespace' => 'Native', 'prefix' => 'native'], static function () {
        Route::get('/get-updates', 'GetUpdateAction');
    });
});
