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

Route::prefix('v1')->middleware('force_json')->group(function() {
    Route::prefix('auth')->namespace('Auth')->group(function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login')->name('login');
    });

    Route::middleware('auth:api')->group(function() {
        Route::prefix('crm')->namespace('CRM')->group(function() {
            Route::apiResource('friend', 'FriendController');
        });
    });
});
