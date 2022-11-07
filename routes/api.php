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

Route::get('get_stages', [App\Http\Controllers\ServicesController::class, 'getStages']);
Route::get('get_options', [App\Http\Controllers\ServicesController::class, 'getOptions']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
