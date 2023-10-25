<?php

use App\Http\Controllers\AlientechTestController;
use App\Http\Controllers\CombinationsController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentAccountsController;
use App\Http\Controllers\ReminderManagerController;
use App\Http\Controllers\SubdealerGroupsController;
use App\Models\Comment;
use App\Models\File;
use App\Models\FileFeedback;
use App\Models\Key;
use App\Models\ReminderManager;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Service;
use App\Models\ServiceSubdealerGroup;
use App\Models\Tool;
use App\Models\User;
use App\Models\UserTool;
use Faker\Provider\ar_EG\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
// use Danielebarbaro\LaravelVatEuValidator\Facades\VatValidatorFacade as VatValidator;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Twilio\Rest\Client;

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
    if(Auth::user()){
        return redirect()->route('home');
    }
    else{
        
        return view('welcome');
    }

});

Route::get('/info', function () {
    dd(phpinfo());
});


Route::get('/manager', function () {
    $manager = (new ReminderManagerController())->getAllManager();
    dd($manager);
});

Route::get('/send_email', function () {

    Mail::to('xrkalix@gmail.com')
    ->send(new \App\Mail\AllMails([ 'html' => 'testing mail.', 'subject' => 'testing', 'front_end_id' => 2 ]));

});


// Route::get('/test_sms', function () {
//     try {
            
//         // $accountSid = env("TWILIO_SID");
//         // $authToken = env("TWILIO_AUTH_TOKEN");
//         // $twilioNumber = env("TWILIO_NUMBER"); 

//         $accountSid = Key::whereNull('subdealer_group_id')
//         ->where('key', 'twilio_sid')->first()->value;

//         $authToken = Key::whereNull('subdealer_group_id')
//         ->where('key', 'twilio_token')->first()->value;

//         $twilioNumber = Key::whereNull('subdealer_group_id')
//         ->where('key', 'twilio_number')->first()->value;


//         $client = new Client($accountSid, $authToken);

//         $message = $client->messages
//               ->create('+923218612198', // to
//                        ["body" => 'testing from local backend', "from" => "ecutech"]
//         );

//         dd($message);

//         \Log::info('message sent to:'.'+923218612198');

//     } catch (\Exception $e) {
//         dd($e->getMessage());
//         \Log::info($e->getMessage());
//     }
// });

 Route::get('/tasks', function () {

    // $response = Http::withHeaders([
    //     'authorization' => 'Token 32fd4c0b90ac267da4c548ea4410b126db2eaf53',
    //     'x-elorus-organization' => '2772131882920314815',
    // ])
    // ->get('https://api.elorus.com/v1.1/contacts/');

    // $response = Http::withHeaders([
    //     'authorization' => 'Token 32fd4c0b90ac267da4c548ea4410b126db2eaf53',
    //     'x-elorus-organization' => '1357060486331368800',
    //     // 'X-Elorus-Demo' => true,
    // ])
    // ->post('https://api.elorus.com/v1.1/contacts/', [
    //     'custom_id' => '5678',
    //     'active' => true,
    //     'first_name' => 'second',
    //     'last_name' => 'customer',
    //     'company' => 'testing',
    //     'vat_number' => '1234',
    //     'is_client' => true,
    //     'is_suplier' => false,
    //     'default_language' => 'en',
    //     'client_type' => 4,  

    // ]);
    
    // $client = json_decode($response->body());

    // dd($client);
    
    // $res = Http::withHeaders([
    //     'authorization' => 'Token 32fd4c0b90ac267da4c548ea4410b126db2eaf53',
    //     'x-elorus-organization' => '2772131882920314815',
    //     'X-elorus-demo' => true,
    // ])
    // ->post('https://api.elorus.com/v1.1/invoices/', [
    //     'custom_id' => '5678',
    //     "draft" => true,
    //     'documenttype' => '2772131884178605198',
    //     'sequence_flat' => 'T',
    //     'date' => '2023-06-23',
    //     'client' => '2772582140070593687',
    //     "currency_code"=> "EUR",
    //     "exchange_rate"=> "1.000000",
    //     "total" => "136.40",
	// 	"payable" => "136.40",
    //     "calculator_mode"=> "initial",
    //     'billing_address' => [
    //         'address_line' => 'testing',
    //         'city' => 'testing',
    //         'zip' => 'testing',
    //         'country' => 'NL',
    //     ],
    //     'items' => array([
    //         'product' => '2772809614431881006',
    //         'title' => 'Tuning Credit',
    //         'quantity' => '3.0',
    //         'unit_measure' => '0',
    //         'unit_value' => '10.00',
    //         'unit_total' => '10.00',
    //         'mydata_classification_category' => 'category1_3',
    //         'mydata_classification_type' => 'E3_561_001',
    //         'taxes' => ['2772131887844427342'],
    //     ]),
    //     'mydata_document_type' => '2.1',
        
    // ]);

    // dd($res->body());
    
    abort(404);

    // $customer = new Buyer([
    //     'name'          => 'John Doe',
    //     'custom_fields' => [
    //         'email' => 'test@example.com',
    //     ],
    // ]);
    
    // $item = (new InvoiceItem())->title('Service 1')->pricePerUnit(2);

    //     $invoice = Invoice::make()
    //         ->buyer($customer)
    //         ->discountByPercent(10)
    //         ->taxRate(15)
    //         ->shipping(1.99)
    //         ->addItem($item);

    //     return $invoice->stream();

//     var_dump(extension_loaded('soap'));
//     var_dump( get_cfg_var('cfg_file_path') );
// exit;
    // $test = VatValidator::validateExistence('EL998413602');
    // dd($test);
    phpinfo();
    // abort(404);

    // $services = Service::all();

    // foreach($services as $service){
    //     $service->label = $service->name;
    //     $service->save();
    // }

    // dd('end');



    // $feedbacks = FileFeedback::all();

    // foreach($feedbacks as $f){
    //     $stage = File::findOrFail($f->file_id)->stage;
    //     $f->stage = $stage;
    //     $f->save();
    // }

    // $files = File::all();

    // foreach($files as $file){

    //     $file->revisions = $file->files->count();
    //     $file->save();
        
    // }

   

    // $services = Service::all();

    // foreach($services as $s){
    //     $record = new ServiceSubdealerGroup();
    //     $record->service_id = $s->id;
    //     $record->subdealer_group_id = 1;
    //     $record->credits = $s->credits;
    //     $record->save();
    // }

    // $topCountriesObj = User::join('roles_users', 'roles_users.user_id', '=', 'users.id')
    //     ->where('roles_users.role_id', 4)
    //     ->where('front_end_id', 1)
    //     ->groupBy('country')
    //     ->selectRaw('count(*) as count,country')
    //     ->get();

        // dd($topCountriesObj);

    // $engineerRole = Role::where('name', 'engineer')->first();

    // $userRoles = RoleUser::where('role_id', $engineerRole->id)->get();

    // $engineers = User::where('is_engineer', 1)->get();

    // $engineers = [];
    // foreach($userRoles as $user){
            
    //         $engineers []=  User::findOrFail($user->user_id);
    // }

//    dd($engineers);
    
    // dd(Auth::user()->is_admin());

    // $admin = get_admin();

    // dd($admin);

    // $users = User::all();

    // foreach($users as $user){
        
    //     RoleUser::where('user_id', $user->id)->delete();

    //     if($user->is_admin){
    //         $new = new RoleUser();
    //         $new->user_id = $user->id;
    //         $new->role_id = 1;
    //         $new->save();
    //     }

    //     if($user->is_head){
    //         $new = new RoleUser();
    //         $new->user_id = $user->id;
    //         $new->role_id = 2;
    //         $new->save();
    //     }

    //     if($user->is_engineer){
    //         $new = new RoleUser();
    //         $new->user_id = $user->id;
    //         $new->role_id = 3;
    //         $new->save();
    //     }

    //     if($user->is_customer){
    //         $new = new RoleUser();
    //         $new->user_id = $user->id;
    //         $new->role_id = 4;
    //         $new->save();
    //     }
    // } 

    // $comments = Comment::all();

    // foreach($comments as $comment){
    //     $service = Service::where('name', $comment->option)->first();

    //     if($service){
    //         $comment->service_id = $service->id;
    //         $comment->save();
    //     }
    //     else{
    //         \Log::info('service missing: '.$comment->option);
    //     }
    // }
     
    // $files = File::all();

    // foreach($files as $file){

    //     $tool = $tool = Tool::where('label', $file->tool)->first();
        
    //     if($tool){
    //         $file->tool_id = $tool->id;
    //         $file->save();
    //     }
    //     else{
    //         \Log::info('tool missing in File: '.$file->id.' Tool: '.$file->tool);
    //     }
    // }

    // dd($files);

//     foreach(User::where('is_customer', 1)->get() as $user){

//     $slaveTools = explode(',', $user->slave_tools);
//     $masterTools = explode(',', $user->master_tools);

//     UserTool::where('user_id', $user->id)->where('type', 'master')->delete();

//     foreach($masterTools as $m){

//     $tool = Tool::where('label', $m)->where('type', 'master')->first();

//         if($tool){
//             $record = new UserTool();
//             $record->type = 'master';
//             $record->user_id = $user->id;
//             $record->tool_id = $tool->id;
//             $record->save();
//         }
//         else{
//         \Log::info('missing: '.$m);
//         }
//     }

//     UserTool::where('user_id', $user->id)->where('type', 'slave')->delete();

//     foreach($slaveTools as $s){

//     $tool = Tool::where('label', $s)->where('type', 'slave')->first();
//         if($tool){
//             $record = new UserTool();
//             $record->type = 'slave';
//             $record->user_id = $user->id;
//             $record->tool_id = $tool->id;
//             $record->save();
//         }
//         else{
//         \Log::info('missing: '.$s);
//         }
//     }

//     echo "---------------------------------------------------------------------------<br>";
//     echo 'user: "'.$user->name. '" id: '.$user->id.'<br><br>';

//     foreach($user->tools_slave as $sl){
//     echo Tool::findOrFail($sl->tool_id)->label.'(slave)<br>';
//     }

//     foreach($user->tools_master as $ms){
//     echo Tool::findOrFail($ms->tool_id)->label.'(master)<br>';
//     }
//     echo "---------------------------------------------------------------------------<br>";
//     echo "<br><br>";

//  }

 });



Route::match(['get', 'post'], '/makelua', [App\Http\Controllers\makelua::class, 'Makelua']);



Route::post('/change_status', [App\Http\Controllers\ServicesController::class, 'changeStatus'])->name('change-status');
Route::post('/change_tuningx_status', [App\Http\Controllers\ServicesController::class, 'changeTuningxStatus'])->name('change-tuningx-status');
Route::post('/get_total_proposed_credits', [App\Http\Controllers\ServicesController::class, 'getTotalProposedCredits'])->name('get-total-proposed-credits');
Route::post('/only_total_proposed_credits', [App\Http\Controllers\ServicesController::class, 'onlyTotalProposedCredits'])->name('only-total-proposed-credits');
Route::post('/force_only_total_proposed_credits', [App\Http\Controllers\ServicesController::class, 'forceOnlyTotalProposedCredits'])->name('force-only-total-proposed-credits');

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/get_files_chart', [App\Http\Controllers\HomeController::class, 'getFilesChart'])->name('get-files-chart');
Route::post('/get_autotunned_files_chart', [App\Http\Controllers\HomeController::class, 'getAutotunnedFilesChart'])->name('get-autotunned-files-chart');
Route::post('/get_frontend_data', [App\Http\Controllers\HomeController::class, 'getFrontendData'])->name('get-frontend-data');
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
Route::post('/set-credit-prices', [App\Http\Controllers\ServicesController::class, 'setCreditPrice'])->name('set-credit-prices');

// Route::get('/files', [App\Http\Controllers\FilesController::class, 'index'])->name('files');
Route::get('/files', [App\Http\Controllers\FilesController::class, 'liveFiles'])->name('files');
Route::get('/file/{id}', [App\Http\Controllers\FilesController::class, 'show'])->name('file');
Route::post('/get_download_button', [App\Http\Controllers\FilesController::class, 'getDownloadButton'])->name('get-download-button');
Route::post('/search', [App\Http\Controllers\FilesController::class, 'search'])->name('search');
Route::post('get_change_status', [App\Http\Controllers\FilesController::class, 'changeCheckingStatus'])->name('get-change-status');
Route::post('flip_decoded_mode', [App\Http\Controllers\FilesController::class, 'flipDecodedMode'])->name('flip-decoded-mode');
Route::post('add_options_offer', [App\Http\Controllers\FilesController::class, 'addOptionsOffer'])->name('add-options-offer');
Route::post('force_options_offer', [App\Http\Controllers\FilesController::class, 'forceOptionsOffer'])->name('force-options-offer');
Route::get('multi_delete', [App\Http\Controllers\FilesController::class, 'multiDelete'])->name('multi-delete');
Route::post('delete_files', [App\Http\Controllers\FilesController::class, 'deleteFiles'])->name('delete-files');

Route::get('/download/{id}/{file}/{autoDelete}', [App\Http\Controllers\FilesController::class,'download'])->name('download');
Route::get('/support/{id}', [App\Http\Controllers\FilesController::class,'support'])->name('support');
Route::get('/download-encrypted/{id}/{file}', [App\Http\Controllers\FilesController::class,'downloadEncrypted'])->name('download-encrypted');
Route::get('/download_decoded/{id}/{file}', [App\Http\Controllers\FilesController::class,'downloadDecoded'])->name('download-decoded');
Route::get('/get_access_token', [App\Http\Controllers\FilesController::class,'getAccessToken'])->name('get-access-token');
Route::get('/decode_file', [App\Http\Controllers\FilesController::class,'decodeFile'])->name('decode-file');
Route::post('/callback/kess3', [App\Http\Controllers\FilesController::class,' callbackKess3'])->name('callback-kess3');
Route::post('/callback/kess3/complete', [App\Http\Controllers\FilesController::class,' callbackKess3Complete'])->name('callback-kess3-complete');
Route::get('/file_copy_path', [App\Http\Controllers\FilesController::class,'fileCopyAndPath'])->name('file-copy-path');
Route::post('/file-engineers-notes', [App\Http\Controllers\FilesController::class,'fileEngineersNotes'])->name('file-engineers-notes');
Route::get('/edit_file/{id}', [App\Http\Controllers\FilesController::class,'editFile'])->name('edit-file');
Route::post('/update-file-vehicle', [App\Http\Controllers\FilesController::class,'updateFileVehicle'])->name('update-file-vehicle');
Route::post('/request-file-upload', [App\Http\Controllers\FilesController::class,'uploadFileFromEngineer'])->name('request-file-upload');
Route::post('/encoded-file-upload', [App\Http\Controllers\FilesController::class,'uploadFileFromEngineer'])->name('encoded-file-upload');
Route::post('/delete-request-file', [App\Http\Controllers\FilesController::class,'deleteUploadedFile'])->name('delete-request-file');
Route::post('/delete-message', [App\Http\Controllers\FilesController::class,'deleteMessage'])->name('delete-message');
Route::post('/assign-engineer', [App\Http\Controllers\FilesController::class,'assignEngineer'])->name('assign-engineer');
Route::post('/change-status', [App\Http\Controllers\FilesController::class,'changeStatus'])->name('change-status-file');
Route::post('/change-support-status', [App\Http\Controllers\FilesController::class,'changSupportStatus'])->name('change-support-status');
Route::post('/edit-message', [App\Http\Controllers\FilesController::class,'editMessage'])->name('edit-message');
Route::get('/feedback_emails', [App\Http\Controllers\FilesController::class,'feedbackEmails'])->name('feedback-emails');
Route::post('/save_feedback_email_template', [App\Http\Controllers\FilesController::class,'saveFeedbackEmailTemplate'])->name('save-feedback-email-template');
Route::post('/save_feedback_email_schedual', [App\Http\Controllers\FilesController::class,'saveFeedbackEmailSchedual'])->name('save-feedback-email-schedual');
Route::post('/get_models', [App\Http\Controllers\FilesController::class, 'getModels'])->name('get-models');
Route::post('/get_versions', [App\Http\Controllers\FilesController::class, 'getVersions'])->name('get-versions');
Route::post('/get_engines', [App\Http\Controllers\FilesController::class, 'getEngines'])->name('get-engines');
Route::post('/get_ecus', [App\Http\Controllers\FilesController::class, 'getECUs'])->name('get-ecus');
Route::post('/delete_file', [App\Http\Controllers\FilesController::class, 'delete'])->name('delete-file');
Route::post('/fill_stage_options', [App\Http\Controllers\FilesController::class, 'fillStageOptions'])->name('fill-stage-options');

// Route::get('/feedback_reports', [App\Http\Controllers\FilesController::class,'feedbackReports'])->name('feedback-reports');
Route::get('/feedback_reports', [App\Http\Controllers\FilesController::class,'feedbackReportsLive'])->name('feedback-reports');
// Route::get('/engineers_reports', [App\Http\Controllers\FilesController::class,'reports'])->name('reports');
Route::get('/engineers_reports', [App\Http\Controllers\FilesController::class,'reportsEngineerLive'])->name('reports');
Route::post('/get_engineers_files', [App\Http\Controllers\FilesController::class,'getEngineersFiles'])->name('get-engineers-files');
Route::post('/get_engineers_report', [App\Http\Controllers\FilesController::class,'getEngineersReport'])->name('get-engineers-report');
Route::post('/get_feedback_report', [App\Http\Controllers\FilesController::class,'getFeedbackReport'])->name('get-feedback-report');

Route::get('/credits_reports', [App\Http\Controllers\CreditsController::class,'creditsReports'])->name('credits-reports');
Route::post('/get_credits_report', [App\Http\Controllers\CreditsController::class,'getCreditsReport'])->name('get-credits-report');

// Route::get('/vehicles', [ App\Http\Controllers\VehiclesController::class,'index'])->name('vehicles');
Route::get('/vehicles', [ App\Http\Controllers\VehiclesController::class,'liveVehicles'])->name('vehicles');

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
// Route::post('/delete_group', [App\Http\Controllers\GroupsController::class,'delete'])->name('delete-group');

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
Route::get('/engineers_permissions/{id}', [App\Http\Controllers\UsersController::class,'engineersPermissions'])->name('engineers-permissions');
Route::post('/change_engineer_permission', [App\Http\Controllers\UsersController::class,'changeEngineerPermission'])->name('change-engineer-permission');

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
Route::get('/default_elorus_template', [App\Http\Controllers\CreditsController::class, 'defaultTemplate'])->name('default-elorus-template');
Route::post('/udpate_default_elorus_template', [App\Http\Controllers\CreditsController::class, 'updateDefaultTemplate'])->name('update-default-eloru-template-id');
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

// Route::get('/frontends', [App\Http\Controllers\FrontEndController::class, 'index'])->name('frontends');
// Route::get('/create_frontend', [App\Http\Controllers\FrontEndController::class, 'create'])->name('create-frontend');
// Route::post('/post_frontend', [App\Http\Controllers\FrontEndController::class, 'store'])->name('post-frontend');
// Route::post('/update_frontend', [App\Http\Controllers\FrontEndController::class, 'update'])->name('update-frontend');
// Route::post('/delete_frontend', [App\Http\Controllers\FrontEndController::class, 'destroy'])->name('delete-frontend');
// Route::get('/edit_frontend/{id}', [App\Http\Controllers\FrontEndController::class, 'edit'])->name('edit-frontend');


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
// Route::get('/test_email', [App\Http\Controllers\FilesController::class, 'testEmail'])->name('test-feedback');

    /*
* This is the main app route [Chatify Messenger]
*/

// Route::get('/chat', [MessagesController::class, 'index'])->name(config('chatify.routes.prefix'))->middleware(['auth']);

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

Route::post('chatify/getContactsMain', [MessagesController::class, 'getContactsMain'])->name('contacts.get.main');

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

Route::get('myspace', function(){
    dd(User::get(['id', 'name'])->pluck('name','id')->toArray());
});

// alientech testing ... 

Route::get('upload_customer_file/{folder_path}/{file_name}', [AlientechTestController::class, 'uploadCustomersFileAndSaveGUID'])->name('upload-customer-file');
Route::get('upload_engineers_file/{folder_path}/{file_name}', [AlientechTestController::class, 'uploadEngineersFileAndSaveGUID'])->name('upload-engineers-file');
Route::get('download_encoded_file/{folder_id}', [AlientechTestController::class, 'downloadEncodedFile'])->name('download-encoded-file');
Route::get('close_all', [AlientechTestController::class, 'closeAllSlots'])->name('close-all');
Route::get('show_all', [AlientechTestController::class, 'showAllSlots'])->name('show-all');

Route::get('subdealers_groups', [SubdealerGroupsController::class, 'groups'])->name('subdealer-groups');


Route::get('subdealers_entity', [SubdealerGroupsController::class, 'index'])->name('subdealers-entity');
Route::get('create_subdealer_entity', [SubdealerGroupsController::class, 'create'])->name('create-subdealer-entity');
Route::post('delete_subdealer_entity', [SubdealerGroupsController::class, 'delete'])->name('delete-subdealer-entity');
Route::post('add_subdealer_entity', [SubdealerGroupsController::class, 'add'])->name('add-subdealer-entity');
Route::post('update_subdealer_entity', [SubdealerGroupsController::class, 'update'])->name('update-subdealer-entity');
Route::get('edit_subdealer_entity/{id}', [SubdealerGroupsController::class, 'edit'])->name('edit-subdealer-entity');

Route::get('subdealer_groups', [SubdealerGroupsController::class, 'groups'])->name('subdealer-groups');
Route::get('create_subdealer_group', [SubdealerGroupsController::class, 'createGroup'])->name('create-subdealer-group');
Route::post('delete_subdealer_group', [SubdealerGroupsController::class, 'deleteGroup'])->name('delete-subdealer-group');
Route::post('add_subdealer_group', [SubdealerGroupsController::class, 'addGroup'])->name('add-subdealer-group');
Route::post('update_subdealer_group', [SubdealerGroupsController::class, 'updateGroup'])->name('update-subdealer-group');
Route::get('edit_subdealer_group/{id}', [SubdealerGroupsController::class, 'editGroup'])->name('edit-subdealer-group');

Route::get('set_group_price/{id}', [SubdealerGroupsController::class, 'setPrice'])->name('set-group-price');

Route::get('create_subdealer_customer/{id}', [SubdealerGroupsController::class, 'createCustomer'])->name('create-subdealer-customer');
Route::post('add_subdealer_customer', [SubdealerGroupsController::class, 'addCustomer'])->name('add-subdealer-customer');
Route::get('edit_subdealer_customer/{id}', [SubdealerGroupsController::class, 'editCustomer'])->name('edit-subdealer-customer');
Route::post('update_subdealer_customer', [SubdealerGroupsController::class, 'updateCustomer'])->name('update-subdealer-customer');
Route::post('delete_subdealer_customer', [SubdealerGroupsController::class, 'deleteUser'])->name('delete-subdealer-customer');

Route::get('create_subdealer_engineer/{id}', [SubdealerGroupsController::class, 'createEngineer'])->name('create-subdealer-engineer');
Route::post('add_subdealer_engineer', [SubdealerGroupsController::class, 'addEngineer'])->name('add-subdealer-engineer');
Route::get('edit_subdealer_engineer/{id}', [SubdealerGroupsController::class, 'editEngineer'])->name('edit-subdealer-engineer');
Route::post('update_subdealer_engineer', [SubdealerGroupsController::class, 'updateEngineer'])->name('update-subdealer-engineer');
Route::post('delete_subdealer_engineer', [SubdealerGroupsController::class, 'deleteUser'])->name('delete-subdealer-engineer');

Route::get('create_subdealer/{id}', [SubdealerGroupsController::class, 'createSubdealer'])->name('create-subdealer-single');
Route::post('add_subdealer', [SubdealerGroupsController::class, 'addSubdealer'])->name('add-subdealer');
Route::get('edit_subdealer/{id}', [SubdealerGroupsController::class, 'editSubdealer'])->name('edit-subdealer');
Route::post('update_subdealer', [SubdealerGroupsController::class, 'updateSubdealer'])->name('update-subdealer');
Route::post('delete_subdealer', [SubdealerGroupsController::class, 'deleteUser'])->name('delete-subdealer');
Route::get('edit_permissions/{id}', [SubdealerGroupsController::class, 'editPermissions'])->name('edit-permissions');
Route::get('edit_tokens/{id}', [SubdealerGroupsController::class, 'editTokens'])->name('edit-tokens');
Route::get('edit_tokens_master', [SubdealerGroupsController::class, 'editMasterTokens'])->name('edit-master-tokens');
Route::post('change_permission', [SubdealerGroupsController::class, 'changePermission'])->name('change-permission');
Route::post('add_subdealer_group_price', [SubdealerGroupsController::class, 'addSubdealerGroupPrice'])->name('add-subdealer-group-price');
Route::post('get_credits_from_service_group', [SubdealerGroupsController::class, 'getCreditsServiceGroup'])->name('get-credits-from-service-group');

Route::get('create_subdealer_egnineer/{id}', [SubdealerGroupsController::class, 'createEngineer'])->name('create-subdealer-engineer');
Route::get('create_subdealer_subdealer/{id}', [SubdealerGroupsController::class, 'createSubdealer'])->name('create-subdealer');
Route::post('update_tokens', [SubdealerGroupsController::class, 'updateTokens'])->name('update-tokens');
Route::post('update_master_tokens', [SubdealerGroupsController::class, 'updateMasterTokens'])->name('update-master-tokens');

Route::get('fms_subdealer_packages', [PackageController::class, 'fmsPackages'])->name('fms-packages');
Route::get('fms_edit_package/{id}', [PackageController::class, 'fmsEdit'])->name('fms-edit-package');
Route::get('fms_create_package', [PackageController::class, 'fmsCreate'])->name('fms-create-package');

Route::get('packages', [PackageController::class, 'index'])->name('packages');
Route::get('create_package', [PackageController::class, 'create'])->name('create-package');
Route::get('edit_package/{id}', [PackageController::class, 'edit'])->name('edit-package');
Route::post('store_package', [PackageController::class, 'store'])->name('store-package');
Route::post('update_package', [PackageController::class, 'update'])->name('update-package');
Route::post('delete_package', [PackageController::class, 'delete'])->name('delete-package');
Route::post('change_status_package', [PackageController::class, 'changeStatus'])->name('change-status-package');

Route::get('combinations', [CombinationsController::class, 'index'])->name('combinations');
Route::get('edit_combination/{id}', [CombinationsController::class, 'edit'])->name('edit-combination');
Route::get('create_combination', [CombinationsController::class, 'create'])->name('create-combination');
Route::post('add_combination', [CombinationsController::class, 'store'])->name('add-combination');
Route::post('update_combination', [CombinationsController::class, 'update'])->name('update-combination');
Route::post('delete_combination', [CombinationsController::class, 'delete'])->name('delete-combination');

Route::get('payment_accounts', [PaymentAccountsController::class, 'index'])->name('payment-accounts');
Route::get('create_payment_account', [PaymentAccountsController::class, 'create'])->name('create-payment-account');
Route::get('edit_payment_account/{id}', [PaymentAccountsController::class, 'edit'])->name('edit-payment-account');
Route::post('add_account', [PaymentAccountsController::class, 'store'])->name('add-account');
Route::post('update_account', [PaymentAccountsController::class, 'update'])->name('update-account');
Route::post('delete_payment_account', [PaymentAccountsController::class, 'destroy'])->name('delete-payment-account');
