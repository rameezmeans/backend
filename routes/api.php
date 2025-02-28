<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

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

Route::post('register_user', [App\Http\Controllers\Api\AuthController::class, 'registerUser']);
Route::post('login_user', [App\Http\Controllers\Api\AuthController::class, 'loginUser']);
Route::post('logout_user', [App\Http\Controllers\Api\AuthController::class, 'logoutUser']);



Route::post('get_account', [App\Http\Controllers\FilesAPIController::class, 'getUser'])->middleware('auth:sanctum');

Route::post('add_user_credits', [App\Http\Controllers\PaymentControllerAPI::class, 'addCreditsAPI']);

Route::post('get_tools', [App\Http\Controllers\FilesAPIController::class, 'tools']);
Route::post('get_files', [App\Http\Controllers\FilesAPIController::class, 'usersFiles']);
Route::post('get_credits', [App\Http\Controllers\FilesAPIController::class, 'usersCredits']);
Route::post('get_invoices', [App\Http\Controllers\FilesAPIController::class, 'usersInvoices']);
Route::post('create_temporary_file', [App\Http\Controllers\FilesAPIController::class, 'createTemporaryFile']);
Route::post('add_information_in_temporary_file', [App\Http\Controllers\FilesAPIController::class, 'addStep1InforIntoTempFile']);

Route::post('get_stages', [App\Http\Controllers\ServicesController::class, 'getStages']);
Route::post('get_options', [App\Http\Controllers\ServicesController::class, 'getOptions']);

Route::post('/search_bosch_number', [App\Http\Controllers\DTCLookupController::class, 'searchBoschAPI']);
Route::post('/search_dtc_record', [App\Http\Controllers\DTCLookupController::class, 'searchDTCAPI']);

Route::post('get_brands', [App\Http\Controllers\FilesAPIController::class, 'brands']);
Route::post('get_models', [App\Http\Controllers\FilesAPIController::class, 'models']);
Route::post('get_versions', [App\Http\Controllers\FilesAPIController::class, 'versions']);
Route::post('get_engines', [App\Http\Controllers\FilesAPIController::class, 'engines']);
Route::post('get_ecus', [App\Http\Controllers\FilesAPIController::class, 'ecus']);

Route::post('submit_file', [App\Http\Controllers\FilesAPIController::class, 'submitFile']);

// Route::post('get_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersCredits']);
Route::post('get_total_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersTotalCredits']);
Route::post('add_credits', [App\Http\Controllers\FilesAPIController::class, 'addSubdealersCredits']);
Route::post('subtract_credits', [App\Http\Controllers\FilesAPIController::class, 'subtractSubdealersCredits']);

Route::get('lua/files/{frontend_id}', [App\Http\Controllers\FilesAPIController::class, 'files'])->name('api-get-files');
Route::get('lua/filesversions', [App\Http\Controllers\FilesAPIController::class, 'filesversions'])->name('api-get-files');

Route::post('lua/file/set_checking_status', [App\Http\Controllers\FilesAPIController::class, 'setCheckingStatus'])->name('api-set-checking-status');
Route::post('lua/file/set_status_and_email', [App\Http\Controllers\FilesAPIController::class, 'setStatusAndEmail'])->name('set-status-and-email');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
