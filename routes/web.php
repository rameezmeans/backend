<?php

use Illuminate\Support\Facades\Auth;
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

Route::post('/change_status', [App\Http\Controllers\ServicesController::class, 'changeStatus'])->name('change-status');

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/services', [App\Http\Controllers\ServicesController::class, 'index'])->name('services');
Route::get('/create_service', [App\Http\Controllers\ServicesController::class, 'create'])->name('create-service');
Route::get('/edit_service/{id}', [App\Http\Controllers\ServicesController::class, 'edit'])->name('edit-service');
Route::post('/add_service', [App\Http\Controllers\ServicesController::class, 'add'])->name('add-service');
Route::post('/update_service', [App\Http\Controllers\ServicesController::class, 'update'])->name('update-service');
Route::post('/delete_service', [App\Http\Controllers\ServicesController::class, 'delete'])->name('delete-service');


Route::get('/files', [App\Http\Controllers\FilesController::class, 'index'])->name('files');
Route::get('/file/{id}', [App\Http\Controllers\FilesController::class, 'show'])->name('file');

Route::get('/download/{file}', [App\Http\Controllers\FilesController::class,'download'])->name('download');
Route::post('/file-engineers-notes', [App\Http\Controllers\FilesController::class,'fileEngineersNotes'])->name('file-engineers-notes');
Route::post('/request-file-upload', [App\Http\Controllers\FilesController::class,'uploadFileFromEngineer'])->name('request-file-upload');
Route::post('/delete-request-file', [App\Http\Controllers\FilesController::class,'deleteUploadedFile'])->name('delete-request-file');
Route::post('/delete-message', [App\Http\Controllers\FilesController::class,'deleteMessage'])->name('delete-message');
Route::post('/assign-engineer', [App\Http\Controllers\FilesController::class,'assignEngineer'])->name('assign-engineer');
Route::post('/change-status', [App\Http\Controllers\FilesController::class,'changeStatus'])->name('change-status');


Route::get('/vehicles', [ App\Http\Controllers\VehiclesController::class,'index'])->name('vehicles');
Route::get('/vehicle/{id}', [App\Http\Controllers\VehiclesController::class,'show'])->name('vehicle');
Route::get('/create_vehicle', [App\Http\Controllers\VehiclesController::class,'create'])->name('create-vehicle');
Route::post('/add-vehicle', [App\Http\Controllers\VehiclesController::class,'add'])->name('add-vehicle');
Route::post('/update-vehicle', [App\Http\Controllers\VehiclesController::class,'update'])->name('update-vehicle');
Route::post('/delete_vehicle', [App\Http\Controllers\VehiclesController::class,'delete'])->name('delete-vehicle');

Route::get('/groups', [App\Http\Controllers\GroupsController::class,'index'])->name('customer.groups');

Route::get('/groups', [App\Http\Controllers\GroupsController::class,'index'])->name('groups');
Route::get('/group/{id}', [App\Http\Controllers\GroupsController::class,'show'])->name('group');
Route::get('/create_group', [App\Http\Controllers\GroupsController::class,'create'])->name('create-group');
Route::get('/edit-group/{id}', [App\Http\Controllers\GroupsController::class,'edit'])->name('edit-group');
Route::post('/add-group', [App\Http\Controllers\GroupsController::class,'add'])->name('add-group');
Route::post('/update-group', [App\Http\Controllers\GroupsController::class,'update'])->name('update-group');
Route::post('/delete_group', [App\Http\Controllers\GroupsController::class,'delete'])->name('delete-group');


Route::get('/customers', [App\Http\Controllers\UsersController::class,'Customers'])->name('customers');
Route::get('/create_customer', [App\Http\Controllers\UsersController::class,'createCustomer'])->name('create-customer');
Route::get('/edit_customer/{id}', [App\Http\Controllers\UsersController::class,'editCustomer'])->name('edit-customer');
Route::post('/add-customer', [App\Http\Controllers\UsersController::class,'addCustomer'])->name('add-customer');
Route::post('/update-customer', [App\Http\Controllers\UsersController::class,'updateCustomer'])->name('update-customer');
Route::post('/delete_customer', [App\Http\Controllers\UsersController::class,'deleteCustomer'])->name('delete-customer');

Route::get('/engineers', [App\Http\Controllers\UsersController::class,'Engineers'])->name('engineers');
Route::get('/create_engineer', [App\Http\Controllers\UsersController::class,'createEngineer'])->name('create-engineer');
Route::get('/edit_engineer/{id}', [App\Http\Controllers\UsersController::class,'editEngineer'])->name('edit-engineer');
Route::post('/add-engineer', [App\Http\Controllers\UsersController::class,'addEngineer'])->name('add-engineer');
Route::post('/update-engineer', [App\Http\Controllers\UsersController::class,'updateEngineer'])->name('update-engineer');
Route::post('/delete_engineer', [App\Http\Controllers\UsersController::class,'deleteEngineer'])->name('delete-engineer');

Route::get('/tools', [App\Http\Controllers\ToolsController::class, 'index'])->name('tools');
Route::get('/create_tool', [App\Http\Controllers\ToolsController::class, 'create'])->name('create-tool');
Route::get('/edit_tool/{id}', [App\Http\Controllers\ToolsController::class, 'edit'])->name('edit-tool');
Route::post('/add_tool', [App\Http\Controllers\ToolsController::class, 'add'])->name('add-tool');
Route::post('/update_tool', [App\Http\Controllers\ToolsController::class, 'update'])->name('update-tool');
Route::post('/delete_tool', [App\Http\Controllers\ToolsController::class, 'delete'])->name('delete-tool');

Route::get('/bosch_numbers', [App\Http\Controllers\BoschECUNumbersController::class, 'index'])->name('numbers');
Route::get('/create_bosch_number', [App\Http\Controllers\BoschECUNumbersController::class, 'create'])->name('create-number');
Route::get('/edit_bosch_number/{id}', [App\Http\Controllers\BoschECUNumbersController::class, 'edit'])->name('edit-number');
Route::post('/add_bosch_number', [App\Http\Controllers\BoschECUNumbersController::class, 'add'])->name('add-number');
Route::post('/update_bosch_number', [App\Http\Controllers\BoschECUNumbersController::class, 'update'])->name('update-number');
Route::post('/delete_bosch_number', [App\Http\Controllers\BoschECUNumbersController::class, 'delete'])->name('delete-number');