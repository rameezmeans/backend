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
Route::post('/get_files_chart', [App\Http\Controllers\HomeController::class, 'getFilesChart'])->name('get-files-chart');
Route::post('/get_files_chart', [App\Http\Controllers\HomeController::class, 'getFilesChart'])->name('get-files-chart');
Route::post('/get_credits_chart', [App\Http\Controllers\HomeController::class, 'getCreditsChart'])->name('get-credits-chart');
Route::post('/get_support_chart', [App\Http\Controllers\HomeController::class, 'getSupportChart'])->name('get-support-chart');
Route::post('/get_response_time_chart', [App\Http\Controllers\HomeController::class, 'getResponseTimeChart'])->name('get-response-time-chart');

Route::get('/services', [App\Http\Controllers\ServicesController::class, 'index'])->name('services');
Route::get('/create_service', [App\Http\Controllers\ServicesController::class, 'create'])->name('create-service');
Route::get('/edit_service/{id}', [App\Http\Controllers\ServicesController::class, 'edit'])->name('edit-service');
Route::post('/add_service', [App\Http\Controllers\ServicesController::class, 'add'])->name('add-service');
Route::post('/update_service', [App\Http\Controllers\ServicesController::class, 'update'])->name('update-service');
Route::post('/delete_service', [App\Http\Controllers\ServicesController::class, 'delete'])->name('delete-service');
Route::get('/sorting_services', [App\Http\Controllers\ServicesController::class, 'sortingServices'])->name('sorting-services');
Route::post('/sort_services', [App\Http\Controllers\ServicesController::class, 'saveSorting'])->name('sort-services');

Route::get('/files', [App\Http\Controllers\FilesController::class, 'index'])->name('files');
Route::get('/file/{id}', [App\Http\Controllers\FilesController::class, 'show'])->name('file');

Route::get('/download/{file}', [App\Http\Controllers\FilesController::class,'download'])->name('download');
Route::post('/file-engineers-notes', [App\Http\Controllers\FilesController::class,'fileEngineersNotes'])->name('file-engineers-notes');
Route::post('/request-file-upload', [App\Http\Controllers\FilesController::class,'uploadFileFromEngineer'])->name('request-file-upload');
Route::post('/delete-request-file', [App\Http\Controllers\FilesController::class,'deleteUploadedFile'])->name('delete-request-file');
Route::post('/delete-message', [App\Http\Controllers\FilesController::class,'deleteMessage'])->name('delete-message');
Route::post('/assign-engineer', [App\Http\Controllers\FilesController::class,'assignEngineer'])->name('assign-engineer');
Route::post('/change-status', [App\Http\Controllers\FilesController::class,'changeStatus'])->name('change-status');
Route::post('/edit-message', [App\Http\Controllers\FilesController::class,'editMessage'])->name('edit-message');
Route::get('/reports', [App\Http\Controllers\FilesController::class,'reports'])->name('reports');
Route::post('/get_engineers_files', [App\Http\Controllers\FilesController::class,'getEngineersFiles'])->name('get-engineers-files');
Route::post('/get_engineers_report', [App\Http\Controllers\FilesController::class,'getEngineersReport'])->name('get-engineers-report');

Route::get('/vehicles', [ App\Http\Controllers\VehiclesController::class,'index'])->name('vehicles');
Route::get('/vehicle/{id}', [App\Http\Controllers\VehiclesController::class,'show'])->name('vehicle');
Route::get('/create_vehicle', [App\Http\Controllers\VehiclesController::class,'create'])->name('create-vehicle');
Route::post('/add-vehicle', [App\Http\Controllers\VehiclesController::class,'add'])->name('add-vehicle');
Route::post('/update-vehicle', [App\Http\Controllers\VehiclesController::class,'update'])->name('update-vehicle');
Route::post('/delete_vehicle', [App\Http\Controllers\VehiclesController::class,'delete'])->name('delete-vehicle');
Route::get('/add_comments/{id}', [App\Http\Controllers\VehiclesController::class,'addComments'])->name('add-comments');
Route::post('/add-option-comments', [App\Http\Controllers\VehiclesController::class,'addOptionComments'])->name('add-option-comments');
Route::post('/edit-option-comment', [App\Http\Controllers\VehiclesController::class,'editOptionComment'])->name('edit-option-comment');
Route::post('/delete_comment', [App\Http\Controllers\VehiclesController::class,'deleteComment'])->name('delete-comment');

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

Route::get('/unit_price', [App\Http\Controllers\CreditsController::class, 'unitPrice'])->name('unit-price');
Route::get('/credits', [App\Http\Controllers\CreditsController::class, 'Credits'])->name('credits');
Route::get('/edit_credit/{id}', [App\Http\Controllers\CreditsController::class, 'EditCredit'])->name('edit-credit');
Route::post('/update_price', [App\Http\Controllers\CreditsController::class, 'updatePrice'])->name('update-price');
Route::post('/update_credits', [App\Http\Controllers\CreditsController::class, 'updateCredits'])->name('update-credits');
Route::get('/pdfview', [App\Http\Controllers\CreditsController::class, 'makePDF'])->name('pdfview');

Route::get('/feeds', [App\Http\Controllers\NewsFeedsController::class, 'index'])->name('feeds');
Route::get('/add-feeds', [App\Http\Controllers\NewsFeedsController::class, 'add'])->name('add-feed');
Route::post('/post-feeds', [App\Http\Controllers\NewsFeedsController::class, 'post'])->name('post-feed');
Route::post('/update-feeds', [App\Http\Controllers\NewsFeedsController::class, 'update'])->name('update-feed');
Route::post('/delete-feeds', [App\Http\Controllers\NewsFeedsController::class, 'delete'])->name('delete-feed');
Route::get('/edit-feeds/{id}', [App\Http\Controllers\NewsFeedsController::class, 'edit'])->name('edit-feed');
Route::post('/change_status_feeds', [App\Http\Controllers\NewsFeedsController::class, 'changeStatus'])->name('change-status-feeds');
Route::post('/delete_feed', [App\Http\Controllers\NewsFeedsController::class, 'delete'])->name('delete-feed');

Route::get('/email_templates', [App\Http\Controllers\EmailTemplatesController::class, 'index'])->name('email-templates');
Route::get('/add_template', [App\Http\Controllers\EmailTemplatesController::class, 'add'])->name('add-template');
Route::get('/edit_template/{id}', [App\Http\Controllers\EmailTemplatesController::class, 'edit'])->name('edit-template');
Route::post('/post_template', [App\Http\Controllers\EmailTemplatesController::class, 'post'])->name('post-template');
Route::post('/update_template', [App\Http\Controllers\EmailTemplatesController::class, 'update'])->name('update-template');
Route::post('/delete_template', [App\Http\Controllers\EmailTemplatesController::class, 'delete'])->name('delete-template');
Route::get('/test_html', [App\Http\Controllers\EmailTemplatesController::class, 'test'])->name('test-html');

Route::get('/test_message', [App\Http\Controllers\FilesController::class, 'testMessage'])->name('test-message');
