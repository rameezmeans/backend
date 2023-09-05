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

Route::post('get_brands', [App\Http\Controllers\FilesAPIController::class, 'brands']);
Route::post('get_models', [App\Http\Controllers\FilesAPIController::class, 'models']);
Route::post('get_versions', [App\Http\Controllers\FilesAPIController::class, 'versions']);
Route::post('get_engines', [App\Http\Controllers\FilesAPIController::class, 'engines']);
Route::post('get_ecus', [App\Http\Controllers\FilesAPIController::class, 'ecus']);

Route::post('get_tools', [App\Http\Controllers\FilesAPIController::class, 'tools']);

Route::post('get_files', [App\Http\Controllers\FilesAPIController::class, 'subdealersFiles']);
Route::post('submit_file', [App\Http\Controllers\FilesAPIController::class, 'submitFile']);

Route::post('get_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersCredits']);
Route::post('get_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersCredits']);
Route::post('get_total_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersTotalCredits']);
Route::post('add_credits', [App\Http\Controllers\FilesAPIController::class, 'addSubdealersCredits']);
Route::post('subtract_credits', [App\Http\Controllers\FilesAPIController::class, 'subtractSubdealersCredits']);

Route::get('lua/files/{frontend_id}', [App\Http\Controllers\FilesAPIController::class, 'files'])->name('api-get-files');
Route::post('lua/file/set_checking_status', [App\Http\Controllers\FilesAPIController::class, 'setCheckingStatus'])->name('api-set-checking-status');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
