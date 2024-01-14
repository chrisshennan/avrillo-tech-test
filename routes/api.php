<?php

use App\Http\Middleware\ApiKeyValidation;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(ApiKeyValidation::class)
    ->prefix('quotes')
    ->group(function () {
        Route::get('/show', 'App\Http\Controllers\Api\QuoteController@show');
        Route::post('/refresh', 'App\Http\Controllers\Api\QuoteController@refresh');
    }
);
