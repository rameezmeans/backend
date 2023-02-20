<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessagesController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
// Route::post('/get_files_chart', [App\Http\Controllers\HomeController::class, 'getFilesChart'])->name('get-files-chart');
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
Route::post('/change-support-status', [App\Http\Controllers\FilesController::class,'changSupportStatus'])->name('change-support-status');
Route::post('/edit-message', [App\Http\Controllers\FilesController::class,'editMessage'])->name('edit-message');
Route::get('/feedback_emails', [App\Http\Controllers\FilesController::class,'feedbackEmails'])->name('feedback-emails');
Route::post('/save_feedback_email_template', [App\Http\Controllers\FilesController::class,'saveFeedbackEmailTemplate'])->name('save-feedback-email-template');
Route::post('/save_feedback_email_schedual', [App\Http\Controllers\FilesController::class,'saveFeedbackEmailSchedual'])->name('save-feedback-email-schedual');


Route::get('/feedback_reports', [App\Http\Controllers\FilesController::class,'feedbackReports'])->name('feedback-reports');
Route::get('/engineers_reports', [App\Http\Controllers\FilesController::class,'reports'])->name('reports');
Route::post('/get_engineers_files', [App\Http\Controllers\FilesController::class,'getEngineersFiles'])->name('get-engineers-files');
Route::post('/get_engineers_report', [App\Http\Controllers\FilesController::class,'getEngineersReport'])->name('get-engineers-report');
Route::post('/get_feedback_report', [App\Http\Controllers\FilesController::class,'getFeedbackReport'])->name('get-feedback-report');

Route::get('/credits_reports', [App\Http\Controllers\CreditsController::class,'creditsReports'])->name('credits-reports');
Route::post('/get_credits_report', [App\Http\Controllers\CreditsController::class,'getCreditsReport'])->name('get-credits-report');

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
Route::post('/mass_delete', [App\Http\Controllers\VehiclesController::class,'massDelete'])->name('mass-delete');
Route::get('/import_vehicles', [App\Http\Controllers\VehiclesController::class,'importVehiclesView'])->name('import-vehicles');
Route::post('/import_vehicles_post', [App\Http\Controllers\VehiclesController::class,'importVehicles'])->name('import-vehicles-post');
Route::post('/add_engineer_comment', [App\Http\Controllers\VehiclesController::class,'addEngineerComment'])->name('add-engineer-comment');


Route::get('/work_hours', [App\Http\Controllers\WorkHoursController::class,'index'])->name('work-hours');
Route::get('/edit_work_hour/{id}', [App\Http\Controllers\WorkHoursController::class,'edit'])->name('edit-work-hour');
Route::post('/update_work_hour', [App\Http\Controllers\WorkHoursController::class,'update'])->name('update-work-hour');

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
Route::get('/update_credit/{id}', [App\Http\Controllers\CreditsController::class, 'UpdateIndividualCredit'])->name('update-credit');
Route::post('/set_credit_information', [App\Http\Controllers\CreditsController::class, 'setCreditInformation'])->name('set-credit-information');
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

Route::get('/frontends', [App\Http\Controllers\FrontEndController::class, 'index'])->name('frontends');
Route::get('/create_frontend', [App\Http\Controllers\FrontEndController::class, 'create'])->name('create-frontend');
Route::post('/post_frontend', [App\Http\Controllers\FrontEndController::class, 'store'])->name('post-frontend');
Route::post('/update_frontend', [App\Http\Controllers\FrontEndController::class, 'update'])->name('update-frontend');
Route::post('/delete_frontend', [App\Http\Controllers\FrontEndController::class, 'destroy'])->name('delete-frontend');
Route::get('/edit_frontend/{id}', [App\Http\Controllers\FrontEndController::class, 'edit'])->name('edit-frontend');


Route::get('/email_templates', [App\Http\Controllers\EmailTemplatesController::class, 'index'])->name('email-templates');
Route::get('/add_template', [App\Http\Controllers\EmailTemplatesController::class, 'add'])->name('add-template');
Route::get('/edit_template/{id}', [App\Http\Controllers\EmailTemplatesController::class, 'edit'])->name('edit-template');
Route::post('/post_template', [App\Http\Controllers\EmailTemplatesController::class, 'post'])->name('post-template');
Route::post('/update_template', [App\Http\Controllers\EmailTemplatesController::class, 'update'])->name('update-template');
Route::post('/delete_template', [App\Http\Controllers\EmailTemplatesController::class, 'delete'])->name('delete-template');

Route::get('/reminder_manager', [App\Http\Controllers\ReminderManagerController::class, 'index'])->name('reminder-manager');
Route::post('/set_status_for_reminder_manager', [App\Http\Controllers\ReminderManagerController::class, 'setStatus'])->name('set-status-for-reminder-manager');

Route::get('/message_templates', [App\Http\Controllers\MessageTemplatesController::class, 'index'])->name('message-templates');
Route::get('/add_message_template', [App\Http\Controllers\MessageTemplatesController::class, 'add'])->name('add-message-template');
Route::get('/edit_message_template/{id}', [App\Http\Controllers\MessageTemplatesController::class, 'edit'])->name('edit-message-template');
Route::post('/post_message_template', [App\Http\Controllers\MessageTemplatesController::class, 'post'])->name('post-message-template');
Route::post('/update_message_template', [App\Http\Controllers\MessageTemplatesController::class, 'update'])->name('update-message-template');
Route::post('/delete_message_template', [App\Http\Controllers\MessageTemplatesController::class, 'delete'])->name('delete-message-template');

// Route::get('/test_html', [App\Http\Controllers\EmailTemplatesController::class, 'test'])->name('test-html');
// Route::get('/test_message', [App\Http\Controllers\FilesController::class, 'testMessage'])->name('test-message');
// Route::get('/test_feedback', [App\Http\Controllers\FilesController::class, 'testFeedbackEmail'])->name('test-feedback');

    /*
* This is the main app route [Chatify Messenger]
*/

Route::get('/chat', [MessagesController::class, 'index'])->name(config('chatify.routes.prefix'))->middleware(['auth', 'adminOnly']);

/**
 *  Fetch info for specific id [user/group]
 */

Route::post('chatify/idInfo', [MessagesController::class,'idFetchData']);

/**
 * Send message route
 */
Route::post('chatify/sendMessage', [MessagesController::class,'send'])->name('send.message');

/**
 * Fetch messages
 */
Route::post('chatify/fetchMessages', [MessagesController::class, 'fetch'])->name('fetch.messages');

/**
 * Download attachments route to create a downloadable links
 */
Route::get('chatify/download/{fileName}/{type}', [MessagesController::class, 'download'])->name(config('chatify.attachments.download_route_name'));

/**
 * Authentication for pusher private channels
 */
Route::post('chatify/chat/auth', [MessagesController::class, 'pusherAuth'])->name('pusher.auth');

/**
 * Make messages as seen
 */
Route::post('chatify/makeSeen', [MessagesController::class, 'seen'])->name('messages.seen');

/**
 * Get contacts
 */
Route::get('chatify/getContacts', [MessagesController::class, 'getContacts'])->name('contacts.get');

/**
 * Update contact item data
 */
Route::post('chatify/updateContacts', [MessagesController::class,'updateContactItem'])->name('contacts.update');


/**
 * Star in favorite list
 */
Route::post('chatify/star', [MessagesController::class,'favorite'])->name('star');

/**
 * get favorites list
 */
Route::post('chatify/favorites', [MessagesController::class,'getFavorites'])->name('favorites');

/**
 * Search in messenger
 */
Route::get('chatify/search', [MessagesController::class,'search'])->name('search');

/**
 * Get shared photos
 */
Route::post('chatify/shared', [MessagesController::class,'sharedPhotos'])->name('shared');

/**
 * Delete Conversation
 */
Route::post('chatify/deleteConversation', [MessagesController::class,'deleteConversation'])->name('conversation.delete');

/**
 * Delete Message
 */
Route::post('chatify/deleteMessage', [MessagesController::class,'deleteMessage'])->name('message.delete');

/**
 * Update setting
 */
Route::post('chatify/updateSettings', [MessagesController::class,'updateSettings'])->name('avatar.update');

/**
 * Set active status
 */
Route::post('chatify/setActiveStatus', [MessagesController::class, 'setActiveStatus'])->name('activeStatus.set');






/*
* [Group] view by id
*/
// Route::get('/group/{id}', [MessagesController::class,'index'])->name('group');

/*
* user view by id.
* Note : If you added routes after the [User] which is the below one,
* it will considered as user id.
*
* e.g. - The commented routes below :
*/
// Route::get('/route', function(){ return 'Munaf'; }); // works as a route
Route::get('chatify/{id}', function(){
    abort('404');
})->name('user');
// Route::get('/route', function(){ return 'Munaf'; }); // works as a user id