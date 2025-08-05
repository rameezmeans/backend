<?php

use App\Http\Controllers\AlientechTestController;
use App\Http\Controllers\BrandECUCommentsController;
use App\Http\Controllers\CombinationsController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentAccountsController;
use App\Http\Controllers\ReminderManagerController;
use App\Http\Controllers\SampleMessageController;
use App\Http\Controllers\SubdealerGroupsController;
use App\Http\Controllers\WhatsappController;
use App\Http\Livewire\NewPaymentLog;
use App\Models\Comment;
use App\Models\CommentFileService;
use App\Models\Credit;
use App\Models\File;
use App\Models\FileFeedback;
use App\Models\FileReplySoftwareService;
use App\Models\FileService;
use App\Models\Group;
use App\Models\Key;
use App\Models\NewsFeed;
use App\Models\PaymentLog;
use App\Models\PaypalRecord;
use App\Models\ReminderManager;
use App\Models\RequestFile;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Service;
use App\Models\ServiceSubdealerGroup;
use App\Models\StripeRecord;
use App\Models\Tool;
use App\Models\User;
use App\Models\UserTool;
use App\Models\Vehicle;
use App\Models\VehiclesNote;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Address;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
// use Danielebarbaro\LaravelVatEuValidator\Facades\VatValidatorFacade as VatValidator;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use PSpell\Config;
use Twilio\Rest\Client;

use SevenSpan\WhatsApp\WhatsApp;

use Chatify\Facades\ChatifyMessenger as Chatify;
// use Excel;

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

Route::get('/all_files/{id}/{service_id}', function ($id, $serviceID) {
    $file = File::findOrFail($id);
    dd(all_files_with_this_ecu_brand_and_service($file->ecu, $file->brand, $serviceID));

});


Route::get('/all_files_with_software/{id}/{service_id}/{software_id}', function ($id, $serviceID, $softwareID) {
    $file = File::findOrFail($id);
    dd(all_files_with_this_ecu_brand_and_service_and_software($file->ecu, $file->brand, $serviceID, $softwareID));

});

Route::get('/autotuner', function () {

    $host ='https://api.autotuner-tool.com/v2/api/v1/master/metadata';

    $request = array("type" => "slave", "slave_id" => "20220958");

    $ch = curl_init($host);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'X-Autotuner-Id: 20220959',
        'X-Autotuner-API-Key: AsHPN3R2tDCnFwVDHbbcZDP1shPlKRDkJMJR1Kaa3M/owhJFYRhsF7VqR7mw2T6b',
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

    $response = curl_exec($ch);

    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($response_code == 200) {
        print_r(json_decode($response, true));
    }
    else{
        print_r(json_decode($response, true));
    }

    curl_close($ch);


});

Route::get('/tasks', function () {

    $credits = Credit::all();

    foreach($credits as $credit){
        $country = User::findOrFail($credit->user_id)->country;
        if($country != NULL){
            $credit->country = code_to_country($country);
            $credit->save();
        }
    }

    dd("countries save");
    
    $files = File::all();

    foreach($files as $file){
        if($file->status == 'rejected'){
            $file->rejected = 1;
            $file->save();
        }
    }

    dd('done');

    // $credits = Credit::whereDate('created_at', '2025-01-17')->where('credits', '>', 0)->toSql();

    // dd($credits);
    

    dd('report');

    $credits = Credit::where('price_payed', '>', 0)->get();

    foreach($credits as $credit){
        $credit->customer = User::findOrFail($credit->user_id)->name;
        $credit->email = User::findOrFail($credit->user_id)->email;

        if($credit->group_id == 0){
            $credit->group = "Not Recorded";
        }
        else{
            $credit->group = Group::findOrFail($credit->group_id)->name;
        }

        $credit->save();
    }

    dd('here');

    // dd(Carbon::now());

    // $frontendID = 3;
    // $activeFeed = NewsFeed::where('active', 1)->where('front_end_id', $frontendID)->get();
    // dd($activeFeed);

    // $frontendID = 2;
    // $files = File::where('status', 'submitted')->where('front_end_id', 2)->limit(1)->get();
    // // dd($files);
    // $activeFeed = NewsFeed::where('active', 1)->where('front_end_id', $frontendID)->first();

    //     $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
    //     $fsat = Key::where('key', 'file_submitted_alert_time')->first()->value;
    //     $foat = Key::where('key', 'file_open_alert_time')->first()->value;
    //     $fodt = Key::where('key', 'file_open_delay_time')->first()->value;

    //     if($activeFeed){
    //         foreach($files as $file){

    //             if($file->timer == NULL){

    //                 $file->timer = Carbon::now();
    //                 $file->save();
    //             }

    //             if($file->timer != NULL){

    //                 if($file->red == 0){

    //                     if($file->support_status == 'open') {

    //                         if( (strtotime($file->timer)+$foat*60)  <= strtotime(now())){
    //                             $file->red = 1;
    //                             $file->save();
    //                         }   
    //                     }
                        
    //                     if($file->status == 'submitted') {

    //                         // // dd($file);
    //                         // print_r( strtotime($file->timer) .'<br>' );
    //                         // print_r( $fsat*60000 .'<br>' ); 
    //                         // print_r( strtotime($file->timer) + $fsat*60000 );

    //                         // dd(strtotime(now()));

    //                         if( (strtotime($file->timer)+($fsat*60))  <= strtotime(now())){
    //                             $file->red = 1;
    //                             $file->save();
    //                         } 
    //                     }
    //                 }

    //                 if($file->delay == 0){

    //                     if($file->support_status == 'open') {

    //                         if( (strtotime($file->timer)+$fodt*60)  <= strtotime(now())){
    //                             $file->delayed = 1;
    //                             $file->save();
    //                         }   
    //                     }
                        
    //                     if($file->status == 'submitted') {

    //                         if( (strtotime($file->timer)+$fsdt*60)  <= strtotime(now())){
    //                             $file->delayed = 1;
    //                             $file->save();
    //                         } 

    //                     }
    //                 }

    //             }
    //         }
    //     }


    dd('testing');

    // $credits = Credit::all();

    // foreach($credits as $c){

    //     $user = User::findOrFail($c->user_id);

    //     if(!$c->country){
    //         $c->country = code_to_country($user->country);
    //         $c->save();
    //     }
        
    // }

    // dd('here');

    $paypalRecords = PaypalRecord::all();

    foreach($paypalRecords as $record){
        $credit = Credit::where('stripe_id', $record->paypal_id)->first();
        if($credit){
            $record->credit_id = $credit->id;
            $record->save();
        }
    }

    dd('here');

	
	//  $stripeRecords = StripeRecord::all();

    // foreach($stripeRecords as $record){
    //     $credit = Credit::where('stripe_id', $record->stripe_id)->first();
    //     $record->credit_id = $credit->id;
    //     $record->save();
    // }

    // $stripeRecords = Credit::where('id', '>', 8791)->where('type', 'stripe')->get();

    // foreach($stripeRecords as $record){
    //     $new = new StripeRecord();
    //     $new->stripe_id = $record->stripe_id;
    //     $new->amount = $record->price_payed;
    //     $new->tax = 0;
    //     $new->desc = $record->stripe_id;
    //     $new->credit_id = $record->id;
    //     $new->save();
    // }


    dd('done deal');


    // $allSoftwareRecs = FileReplySoftwareService::all();

    // $big = [];
    // foreach($allSoftwareRecs as $r){
    //     $count = FileReplySoftwareService::where('file_id', $r->file_id)->whereNotNull('reply_id')->count();
    //     // $big []= $allMultiples;

    //     if($count > 1){
    //         $multilple = FileReplySoftwareService::where('file_id', $r->file_id)->whereNotNull('reply_id')->first();
    //         $big []= $multilple->file_id;
    //     }

    //     // if($count>1){
    //     //     $multiple = FileReplySoftwareService::where('file_id', $r->file_id)->whereNotNull('reply_id')->get();

    //     //     $inner = 1;
    //     //     foreach($multiple as $m){

    //     //         $inner++;

    //     //         $m->revised = 1;
    //     //         $m->save();

    //     //         if($inner == $count){
    //     //             break;
    //     //         }
    //     //     }
    //     }
    
    // $uniques = array_unique($big);

    // // dd(count($uniques));

    // foreach($uniques as $u){

    //     // if($u == 3229){

    //     $serviceIDs = FileReplySoftwareService::select('service_id')->where('file_id', $u)->distinct('service_id')->get();
        
    //     $sArrays = [];
        
    //     foreach($serviceIDs as $i){
    //         $sArrays []= $i->service_id;
    //     }

    //     // dd($sArrays);

    //     foreach($sArrays as $s){

    //      $count = FileReplySoftwareService::where('file_id', $u)->where('service_id',$s)->count();
    //     $mu = FileReplySoftwareService::where('file_id', $u)->where('service_id',$s)->get();
            
    //     // dd($mu);
        
    //         $inner = 1;
    //             foreach($mu as $m){

    //                 $inner++;

    //                 $m->revised = 1;
    //                 $m->save();

    //                 if($inner == $count){
    //                     break;
    //                 }
    //             }

    //         }
    //     // }

    // }

    // dd('finals');

    // abort(404);

    $files = File::all();

    foreach($files as $file){
        $user = User::findOrFail($file->user_id);
        $file->name = $user->name;
        $file->phone = $user->phone;
        $file->email = $user->email;
        $file->save();
    }

    dd('files updated');

    // $excel = Excel::load(public_path('codes.xlsx'), function($reader) {})->get();
    // dd($excel);

    // $creditsWithoutZohoID = Credit::whereNull('zohobooks_id')
    //     ->where('credits','>', 0)
    //     ->where('gifted', 0)
    //     ->whereDate('created_at', Carbon::today())
    //     ->where('created_at', '<', Carbon::now()->subMinutes(5)->toDateTimeString())
    //     ->get();

    //     dd($creditsWithoutZohoID);

    // dd('usernames updated in files');

    // $groupsOSS = Group::where('name', 'like', '%' . 'OSS' . '%')->get();
    
    // foreach($groupsOSS as $group){

    //     $newGroup = New Group();

    //     $newGroup->name = substr($group->name, 0, -3).'EFT';
    //     $newGroup->tax = $group->tax;
    //     $newGroup->discount = $group->discount;
    //     $newGroup->bonus_credits = $group->bonus_credits;
    //     $newGroup->raise = $group->raise;
    //     $newGroup->subdealer_group_id = NULL;
    //     $newGroup->stripe_payment_account_id = $group->stripe_payment_account_id;
    //     $newGroup->slug = $group->slug;
    //     $newGroup->paypal_payment_account_id = $group->paypal_payment_account_id;

    //     $newGroup->elorus_template_id = '3044674633593783892';

    //     $newGroup->elorus_tax_id = $group->elorus_tax_id;
    //     $newGroup->zohobooks_tax_id = NULL;
    //     $newGroup->viva_payment_account_id = $group->viva_payment_account_id;
    //     $newGroup->stripe_active = $group->stripe_active;
    //     $newGroup->paypal_active = $group->paypal_active;
    //     $newGroup->viva_active = $group->viva_active;

    //     $newGroup->save();

    // }

    // dd("groups duplicated");
    // $file = File::findOrFail(2051);

    // dd($file->softwares->isNotEmpty());

    // // dd($file->files->count());

    // $recs = FileReplySoftwareService::all();

    // foreach($recs as $r){
        
    //     $record = RequestFile::where('id', $r->reply_id)->first();

    //     if($record == NULL){
    //         $r->delete();
    //     }
    // }

    // dd('extra records from software replies table deleted');

    // foreach($recs as $r){
    //     $c = RequestFile::where('id',$r->reply_id)->first();

    //     if($c == NULL){
    //         $r->delete();
    //     }
    // }

    $files = File::all();
    $credits = Credit::all();

    foreach($files as $f){
        $user = User::findOrFail($f->user_id);

        if($user->test == 1){
            $f->test = 1;
            $f->save();
        }
    }

    foreach($credits as $c){
        $user = User::findOrFail($c->user_id);

        if($user->test == 1){
            $c->test = 1;
            $c->save();
        }
    }

    dd("test updated");

    // $file = File::findOrFail(3317);

    // $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();

    // dd(!$file->decoded_files->isEmpty());

    // $allRecs = FileReplySoftwareService::all();

    // foreach($allRecs as $r){
    //     if($r->reply_id == NULL){
    //         $r->delete();
    //     }

    //     if(File::where('id', $r->file_id)->first() == NULL){
    //         $r->delete();
    //     }
    // }

    // dd('clear recs');

    // $files = File::all();

    // foreach($files as $f){

    //     if($f->dtc_off_comments){

    //     if($f->front_end_id == 1){
    //         $comment = new CommentFileService();
    //         $comment->comment = $f->dtc_off_comments;
    //         $comment->service_id = 15;
    //         $comment->file_id = $f->id;
    //         $comment->save();
    //     }
    //     else{

    //         $comment = new CommentFileService();
    //         $comment->comment = $f->dtc_off_comments;
    //         $comment->service_id = 70;
    //         $comment->file_id = $f->id;
    //         $comment->save();

    //     }
    //     }
    // }

    // dd('file comments');

    abort(404);

    // $creditsWithoutZohoID = Credit::
    //     where('credits','>', 0)
    //     ->where('gifted', 0)
    //     ->whereYear('created_at','>=', 2024)
    //     ->get();

    //     dd($creditsWithoutZohoID);

    // $kess3 = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();

    // $reqFiles = RequestFile::all();

    // foreach($reqFiles as $r){

    //     $file = File::findOrFail($r->file_id);

    //     if($file->tool_id == $kess3->id){
    //         $r->is_kess3_slave = 1;
    //         $r->uploaded_successfully = 1;
    //         $r->encoded = 1;
    //         $r->save();
    //     }
    // }

    // dd('kess 3 handled');

    $allCredits = Credit::all();
    
    foreach($allCredits as $c){

        $frontEndID = User::findOrFail($c->user_id)->front_end_id;

        if($frontEndID){
            $c->front_end_id = User::findOrFail($c->user_id)->front_end_id;
            $c->save();
        }
    }

    dd('front end id settled');

    // $creditsWithoutElorusID = Credit::whereNull('elorus_id')
    // ->where('credits','>', 0)
    // ->where('gifted', 0)
    // ->whereYear('created_at','>=', 2024)
    // ->get();

    // // dd($$creditsWithoutElorusID);

    // foreach($creditsWithoutElorusID as $c){
    //     if($c->elorus_able()){
    //         if($c->log){

    //             $logInstance = $c->log;
    //             $logInstance->payment_id = $c->id;
    //             $logInstance->user_id = $c->user_id;
    //             $logInstance->elorus_id = NULL;
    //             $logInstance->email_sent = 1;
    //             $logInstance->reason_to_skip_elorus_id = "elorus invoice did not went through.";
    //             $logInstance->save();
    //             send_error_email($c->id, 'Transaction happened without elorus id', $c->front_end_id);

    //         }
    //         else{

    //             $logInstance = new PaymentLog();
    //             $logInstance->payment_id = $c->id;
    //             $logInstance->user_id = $c->user_id;
    //             $logInstance->elorus_id = NULL;
    //             $logInstance->email_sent = 1;
    //             $logInstance->reason_to_skip_elorus_id = "elorus invoice did not went through.";
    //             $logInstance->save();
    //             send_error_email($c->id, 'Transaction happened without elorus id', $c->front_end_id);
    //         }
    //     }
    // }

    // dd('elorus able settled');

    // $creditsWithoutZohoID = Credit::whereNull('zohobooks_id')
    // ->where('credits','>', 0)
    // ->where('gifted', 0)
    // ->whereYear('created_at','>=', 2024)
    // ->get();

    // // dd($creditsWithoutZohoID);

    // foreach($creditsWithoutZohoID as $c){

    //     if(!$c->log){
    //         $logInstance = new PaymentLog();
    //         $logInstance->payment_id = $c->id;
    //         $logInstance->user_id = $c->user_id;
    //         $logInstance->zohobooks_payment = false;
    //         $logInstance->zohobooks_id = NULL;
    //         $logInstance->email_sent = 1;
    //         $logInstance->reason_to_skip_zohobooks_payment_id = "zohobooks invoice did not went through.";
    //         $logInstance->save();
    //         send_error_email($c->id, 'Transaction happened without zoho id', $c->front_end_id);
    //     }
    // }

    // dd('email went');

    // $options = FileService::where('service_id', 109)->get();
    
    // foreach($options as $o){
    //     $o->service_id = 54;
    //     $o->save();
    // }

    // dd('done');

    // $allRequestFile = RequestFile::all();

    // foreach($allRequestFile as $r){
    //     // dd($r->file_id);
    // }

    abort(404);
    // $new = new ReminderManager();
    // $new->type = 'eng_assign_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'eng_assign_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'eng_assign_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'eng_assign_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'eng_assign_cus_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'eng_assign_cus_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'file_upload_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'file_upload_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'file_upload_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'file_upload_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'eng_file_upload_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'eng_file_upload_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'eng_file_upload_cus_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'eng_file_upload_cus_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'file_new_req_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'file_new_req_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'file_new_req_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'file_new_req_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'msg_cus_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'msg_cus_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'msg_cus_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'msg_cus_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'msg_eng_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'msg_eng_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'msg_eng_cus_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'msg_eng_cus_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'status_change_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'status_change_admin_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'status_change_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'status_change_eng_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

    // $new = new ReminderManager();
    // $new->type = 'status_change_cus_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 1; 
    // $new->save();
    // $new = new ReminderManager();
    // $new->type = 'status_change_cus_whatsapp';
    // $new->active = 0; 
    // $new->front_end_id = 2; 
    // $new->save();

});

Route::get('/send_on_channel', function () {

    $t = Chatify::push("private-chatify-download-portal-24", 'download-button', [
        'status' => 'download',
        'file_id' => 762
    ]);


    dd($t);
    
});

Route::get('/test_whatsapp', function () {

    $accessToken = config('whatsApp.access_token');
    $fromPhoneNumberId = config('whatsApp.from_phone_number_id');

    $components  = 
    [
        [
            "type" => "header",
            "parameters" => array(
                array("type"=> "text","text"=> "ECU Tech"),
            )
        ],
        [
            "type" => "body",
            "parameters" => array(
                array("type"=> "text","text"=> "dear Kostas"),
                array("type"=> "text","text"=> "Mr. Rameez"),
                array("type"=> "text","text"=> "Honda"),
                array("type"=> "text","text"=> "DPF OFF"),
            )
        ]
    ];

    $whatappObj = new WhatsappController();
    $response = $whatappObj->sendTemplateMessage('+923218612198','portal_messages', 'en', $accessToken, $fromPhoneNumberId, $components, $messages = 'hello I am here.');
    dd($response);

});

// Route::get('/tasks', function () {
//     abort(404);
//     exit;
// });

Route::match(['get', 'post'], '/makelua', [App\Http\Controllers\makelua::class, 'Makelua']);

Route::post('/change_status', [App\Http\Controllers\ServicesController::class, 'changeStatus'])->name('change-status');
Route::post('/change_tuningx_status', [App\Http\Controllers\ServicesController::class, 'changeTuningxStatus'])->name('change-tuningx-status');
Route::post('/change_efiles_status', [App\Http\Controllers\ServicesController::class, 'changeEfilesStatus'])->name('change-efiles-status');
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

Route::get('/payment_and_customers', [App\Http\Controllers\PaymentLogController::class, 'customers'])->name('payment-and-customers');
Route::get('/payment_logs/{id}', [App\Http\Controllers\PaymentLogController::class, 'paymentLogs'])->name('payment-logs');
Route::get('/payments/{id}', [App\Http\Controllers\PaymentLogController::class, 'payments'])->name('payments');
Route::get('/all_payment_logs', [App\Http\Controllers\PaymentLogController::class, 'allPaymentLogs'])->name('all-payment-logs');
Route::post('/payment_logs_table', [App\Http\Controllers\PaymentLogController::class, 'paymentLogsTable'])->name('payment-logs-table');
Route::get('/all_payments_admin', [App\Http\Controllers\PaymentLogController::class, 'allPaymentsAdmin'])->name('all-payments-admin');
Route::get('/all_payments', [App\Http\Controllers\PaymentLogController::class, 'allPayments'])->name('all-payments');
Route::post('/payment_table', [App\Http\Controllers\PaymentLogController::class, 'paymentsTable'])->name('payment-table');
// Route::get('/all_payments', NewPaymentLog::class)->name('all-payments');
Route::get('/payment_details/{id}', [App\Http\Controllers\PaymentLogController::class, 'paymentDetails'])->name('payment-details');

Route::get('/processing_softwares', [App\Http\Controllers\ProcessingSoftwaresController::class, 'index'])->name('processing-softwares');
Route::get('/add_edit_processing_softwares', [App\Http\Controllers\ProcessingSoftwaresController::class, 'add'])->name('add-processing-softwares');
Route::get('/edit_processing_softwares/{id}', [App\Http\Controllers\ProcessingSoftwaresController::class, 'edit'])->name('edit-processing-softwares');
Route::post('/delete_processing_softwares', [App\Http\Controllers\ProcessingSoftwaresController::class, 'delete'])->name('delete-processing-softwares');
Route::post('/create_processing_softwares', [App\Http\Controllers\ProcessingSoftwaresController::class, 'create'])->name('create-processing-softwares');
Route::post('/update_processing_softwares', [App\Http\Controllers\ProcessingSoftwaresController::class, 'update'])->name('update-processing-softwares');
Route::post('/update_processing_softwares', [App\Http\Controllers\ProcessingSoftwaresController::class, 'update'])->name('update-processing-softwares');
Route::get('/softwares_report', [App\Http\Controllers\ProcessingSoftwaresController::class, 'softwareReport'])->name('softwares-report');

Route::get('/database_import', [App\Http\Controllers\ProcessingSoftwaresController::class, 'databaseImport'])->name('database-import');
Route::post('/database_import', [App\Http\Controllers\ProcessingSoftwaresController::class, 'databaseImport'])->name('database-import');
Route::post('/get_external_sourced', [App\Http\Controllers\ProcessingSoftwaresController::class, 'getExternalSourced'])->name('get-external-sourced');
Route::post('/change_ps_external_source', [App\Http\Controllers\ProcessingSoftwaresController::class, 'changePsExternalSource'])->name('change-ps-external-source');
Route::post('/change_ps_external_source_software', [App\Http\Controllers\ProcessingSoftwaresController::class, 'changePsExternalSourceSoftware'])->name('change-ps-external-source-software');
Route::post('/update_processing_softwares', [App\Http\Controllers\ProcessingSoftwaresController::class, 'update'])->name('update-processing-softwares');

Route::get('/countires_report', [App\Http\Controllers\UsersController::class, 'countriesReport'])->name('countries-report');
Route::get('/services_report', [App\Http\Controllers\ServicesController::class, 'servicesReport'])->name('services-report');
Route::get('/options_comments', [App\Http\Controllers\ServicesController::class, 'optionsComments'])->name('options-comments');
Route::get('/edit_options_comments/{id}', [App\Http\Controllers\ServicesController::class, 'editOptionsComments'])->name('edit-options-comments');
Route::post('/update_option_comment', [App\Http\Controllers\ServicesController::class, 'updateOptionComment'])->name('update-option-comment');
Route::post('/set_options_comments', [App\Http\Controllers\ServicesController::class, 'setOptionsComments'])->name('set-options-comments');
Route::post('/get_comments_ecus', [App\Http\Controllers\ServicesController::class, 'getECUComments'])->name('get-comments-ecus');
Route::post('/delete_option_comment', [App\Http\Controllers\ServicesController::class, 'deleteOptionComment'])->name('delete-option-comment');
Route::post('/get_services_report', [App\Http\Controllers\ServicesController::class, 'getServicesReport'])->name('get-services-report');
Route::post('/fetch_softwares_report', [App\Http\Controllers\ProcessingSoftwaresController::class, 'ajaxSoftwareReport'])->name('get-software-report');
Route::post('/get_country_report', [App\Http\Controllers\UsersController::class, 'getCountriesReport'])->name('get-country-report');
Route::post('/update_tools', [App\Http\Controllers\UsersController::class, 'updateTools'])->name('update-tools');

Route::get('/services', [App\Http\Controllers\ServicesController::class, 'index'])->name('services');
Route::get('/create_service', [App\Http\Controllers\ServicesController::class, 'create'])->name('create-service');
Route::get('/edit_service/{id}', [App\Http\Controllers\ServicesController::class, 'edit'])->name('edit-service');
Route::post('/add_service', [App\Http\Controllers\ServicesController::class, 'add'])->name('add-service');
Route::post('/update_service', [App\Http\Controllers\ServicesController::class, 'update'])->name('update-service');
Route::post('/delete_service', [App\Http\Controllers\ServicesController::class, 'delete'])->name('delete-service');
Route::get('/sorting_services', [App\Http\Controllers\ServicesController::class, 'sortingServices'])->name('sorting-services');
Route::post('/sort_services', [App\Http\Controllers\ServicesController::class, 'saveSorting'])->name('sort-services');
Route::post('/set-credit-prices', [App\Http\Controllers\ServicesController::class, 'setCreditPrice'])->name('set-credit-prices');
Route::post('/set-customers-comments', [App\Http\Controllers\ServicesController::class, 'setCustomersComments'])->name('set-customers-comments');

// Route::get('/files', [App\Http\Controllers\FilesController::class, 'index'])->name('files');

Route::post('/my_ajax_files', [App\Http\Controllers\FilesController::class, 'myAjaxFiles'])->name('my-ajax-files');
Route::post('/ajax_files', [App\Http\Controllers\FilesController::class, 'ajaxFiles'])->name('ajax-files');
Route::get('/files', [App\Http\Controllers\FilesController::class, 'liveFiles'])->name('files');
Route::get('/my_files', [App\Http\Controllers\FilesController::class, 'myLiveFiles'])->name('my-files');
Route::get('/engineers_assignment', [App\Http\Controllers\FilesController::class, 'engineersAssignment'])->name('engineers-assignment');
Route::post('/tasks_rules_set', [App\Http\Controllers\FilesController::class, 'tasksRulesSet'])->name('tasks-rules-set');
Route::post('/assign_to_another', [App\Http\Controllers\FilesController::class, 'assignToAnother'])->name('assign-to-another');
Route::post('/flip_engineer_status', [App\Http\Controllers\FilesController::class, 'flipEngineerStatus'])->name('flip-engineer-status');

Route::get('/file/{id}', [App\Http\Controllers\FilesController::class, 'show'])->name('file');
Route::post('/get_download_button', [App\Http\Controllers\FilesController::class, 'getDownloadButton'])->name('get-download-button');
Route::post('/search', [App\Http\Controllers\FilesController::class, 'search'])->name('search');
Route::post('get_change_status', [App\Http\Controllers\FilesController::class, 'changeCheckingStatus'])->name('get-change-status');
Route::post('flip_decoded_mode', [App\Http\Controllers\FilesController::class, 'flipDecodedMode'])->name('flip-decoded-mode');
Route::post('add_options_offer', [App\Http\Controllers\FilesController::class, 'addOptionsOffer'])->name('add-options-offer');
Route::post('force_options_offer', [App\Http\Controllers\FilesController::class, 'forceOptionsOffer'])->name('force-options-offer');
Route::get('multi_delete', [App\Http\Controllers\FilesController::class, 'multiDelete'])->name('multi-delete');
Route::post('delete_files', [App\Http\Controllers\FilesController::class, 'deleteFiles'])->name('delete-files');
Route::post('flip_show_comments', [App\Http\Controllers\FilesController::class, 'flipShowComments'])->name('flip-show-comments');
Route::post('flip_show_file', [App\Http\Controllers\FilesController::class, 'flipShowFile'])->name('flip-show-file');
Route::post('decline_show_file', [App\Http\Controllers\FilesController::class, 'declineShowFile'])->name('decline-show-file');
Route::post('enable_download', [App\Http\Controllers\FilesController::class, 'enableDownload'])->name('enable-download');
Route::post('decline_comments', [App\Http\Controllers\FilesController::class, 'declineComments'])->name('decline-comments');
Route::post('update_processing_software', [App\Http\Controllers\FilesController::class, 'updateProcessingSoftware'])->name('update-processing-software');
Route::post('fill_null_software_records', [App\Http\Controllers\FilesController::class, 'fillProcessingSoftware'])->name('fill-null-software-records');

Route::post('set_new_request_comment', [App\Http\Controllers\FilesController::class, 'setNewRequestComment'])->name('set-new-request-comment');
Route::post('add_resellers_text', [App\Http\Controllers\NewsFeedsController::class, 'addResellersText'])->name('add-resellers-text');

Route::get('original_files', [App\Http\Controllers\OriginalFilesController::class, 'index'])->name('original-files');
// Route::get('original_files_live', [App\Http\Controllers\OriginalFilesController::class, 'live'])->name('original-files-live');
Route::get('filter_original_files', [App\Http\Controllers\OriginalFilesController::class, 'filterOriginalFiles'])->name('filter-original-files');
Route::get('download_original_file/{id}', [App\Http\Controllers\OriginalFilesController::class, 'download'])->name('download-original-file');
Route::get('renaming', [App\Http\Controllers\OriginalFilesController::class, 'renaming'])->name('renaming-original-files');
Route::post('/get_series', [App\Http\Controllers\OriginalFilesController::class, 'getSeries'])->name('get-series');
Route::post('/get_models_orignal_files', [App\Http\Controllers\OriginalFilesController::class, 'getModels'])->name('get-models-original-files');
Route::post('delete_original_files', [App\Http\Controllers\OriginalFilesController::class, 'deleteOriginalFiles'])->name('delete-files');
Route::get('edit_original_file/{id}', [App\Http\Controllers\OriginalFilesController::class, 'edit'])->name('edit-original-file');
Route::post('update_original_file', [App\Http\Controllers\OriginalFilesController::class, 'update'])->name('update-original-file');
Route::post('delete_original_file', [App\Http\Controllers\OriginalFilesController::class, 'delete'])->name('delete-original-file');

Route::get('/download/{id}/{file}/{autoDelete}', [App\Http\Controllers\FilesController::class,'download'])->name('download');
Route::get('/support/{id}', [App\Http\Controllers\FilesController::class,'support'])->name('support');
Route::get('/download-encrypted/{id}/{file}', [App\Http\Controllers\FilesController::class,'downloadEncrypted'])->name('download-encrypted');
Route::get('/download-magic/{id}/{file}', [App\Http\Controllers\FilesController::class,'downloadMagic'])->name('download-magic');
Route::get('/download-autotuner/{id}/{file}', [App\Http\Controllers\FilesController::class,'downloadAutotuner'])->name('download-autotuner');
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
Route::post('/delete_acm_file', [App\Http\Controllers\FilesController::class, 'deleteACMFile'])->name('delete-acm-file');
Route::post('/fill_stage_options', [App\Http\Controllers\FilesController::class, 'fillStageOptions'])->name('fill-stage-options');
Route::post('/upload_acm_reply', [App\Http\Controllers\FilesController::class, 'uploadACMReply'])->name('upload-acm-reply');
Route::get('/show_files/{id}', [App\Http\Controllers\FilesController::class, 'showFiles'])->name('show-files');
Route::get('/show_rejected_files/{id}', [App\Http\Controllers\FilesController::class, 'showRejectedFiles'])->name('show-rejected-files');
Route::post('/edit_customer_message', [App\Http\Controllers\FilesController::class, 'editCustomerMessage'])->name('edit-customer-message');
Route::post('/get_customer_message', [App\Http\Controllers\FilesController::class, 'getCustomerMessage'])->name('get-customer-message');
Route::post('/send_message_to_customer', [App\Http\Controllers\FilesController::class, 'sendMessageToCustomer'])->name('send-message-to-customer');
Route::post('/send_customer_file', [App\Http\Controllers\FilesController::class, 'sendCustomerFile'])->name('send-customer-file');
Route::post('/add_later_message', [App\Http\Controllers\FilesController::class, 'addLaterMessage'])->name('add-later-message');
Route::get('/download_terms_docs', [App\Http\Controllers\FilesController::class, 'downloadTerms'])->name('download-terms');
Route::post('/download_terms_table', [App\Http\Controllers\FilesController::class, 'downloadTermsTable'])->name('download-terms-table');

Route::get('/message_search', [App\Http\Controllers\FilesController::class, 'messageSearch'])->name('message-search');
Route::any('/get_search_results', [App\Http\Controllers\FilesController::class, 'getSearchResults'])->name('get-search-results');

Route::get('/reasons_to_reject', [App\Http\Controllers\ReasonsController::class, 'reasonsToReject'])->name('reasons-to-reject');
Route::get('/create_reason_to_cancel', [App\Http\Controllers\ReasonsController::class, 'create'])->name('create-reason-to-cancel');
Route::post('/add_reason_to_cancel', [App\Http\Controllers\ReasonsController::class, 'add'])->name('add-reason-to-cancel');
Route::get('/edit_reason_to_cancel/{id}', [App\Http\Controllers\ReasonsController::class, 'edit'])->name('edit-reason-to-cancel');
Route::post('/update_reason_to_reject', [App\Http\Controllers\ReasonsController::class, 'update'])->name('update-reason-to-reject');
Route::post('/delete_reason_to_reject', [App\Http\Controllers\ReasonsController::class, 'destroy'])->name('delete-reason-to-reject');

Route::post('/add_softwares_services', [App\Http\Controllers\FilesController::class, 'addSoftwares'])->name('add-softwares-services');
Route::post('/remove_null_software_records', [App\Http\Controllers\FilesController::class, 'removeNullSoftwares'])->name('remove-null-software-records');
Route::post('/remove_null_message_records', [App\Http\Controllers\FilesController::class, 'removeNullMessages'])->name('remove-null-message-records');
Route::post('/remove_null_upload_later_records', [App\Http\Controllers\FilesController::class, 'removeNullUploadLaterRecords'])->name('remove-null-upload-later-records');
Route::post('/add_upload_later_record', [App\Http\Controllers\FilesController::class, 'addUploadLaterRecord'])->name('add-upload-later-record');

// Route::get('/feedback_reports', [App\Http\Controllers\FilesController::class,'feedbackReports'])->name('feedback-reports');
Route::get('/feedback_reports', [App\Http\Controllers\FilesController::class,'feedbackReportsLive'])->name('feedback-reports');
// Route::get('/engineers_reports', [App\Http\Controllers\FilesController::class,'reports'])->name('reports');
Route::get('/engineers_reports', [App\Http\Controllers\FilesController::class,'reportsEngineerLive'])->name('reports');
Route::post('/engineers_reports_table', [App\Http\Controllers\FilesController::class,'engineersReportsTable'])->name('engineers-reports-table');
Route::post('/get_engineers_files', [App\Http\Controllers\FilesController::class,'getEngineersFiles'])->name('get-engineers-files');
Route::post('/get_engineers_report', [App\Http\Controllers\FilesController::class,'getEngineersReport'])->name('get-engineers-report');
Route::post('/get_feedback_report', [App\Http\Controllers\FilesController::class,'getFeedbackReport'])->name('get-feedback-report');

Route::get('/credits_reports', [App\Http\Controllers\CreditsController::class,'creditsReports'])->name('credits-reports');
Route::post('/get_credits_report', [App\Http\Controllers\CreditsController::class,'getCreditsReport'])->name('get-credits-report');
Route::post('/change_online_search_status', [App\Http\Controllers\CreditsController::class,'changeOnlineStatus'])->name('change-online-search-status');
Route::post('/change_maintenance_mode', [App\Http\Controllers\CreditsController::class,'changeMaintenanceMode'])->name('change-maintenance-mode');

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
Route::post('/delete_note', [App\Http\Controllers\VehiclesController::class,'deleteNote'])->name('delete-note');
Route::post('/mass_delete', [App\Http\Controllers\VehiclesController::class,'massDelete'])->name('mass-delete');
Route::get('/import_vehicles', [App\Http\Controllers\VehiclesController::class,'importVehiclesView'])->name('import-vehicles');
Route::post('/import_vehicles_post', [App\Http\Controllers\VehiclesController::class,'importVehicles'])->name('import-vehicles-post');
Route::post('/add_engineer_comment', [App\Http\Controllers\VehiclesController::class,'addEngineerComment'])->name('add-engineer-comment');

Route::get('/work_hours', [App\Http\Controllers\WorkHoursController::class,'index'])->name('work-hours');
Route::get('/edit_work_hour/{id}', [App\Http\Controllers\WorkHoursController::class,'edit'])->name('edit-work-hour');
Route::post('/update_work_hour', [App\Http\Controllers\WorkHoursController::class,'update'])->name('update-work-hour');

// Route::get('/groups', [App\Http\Controllers\GroupsController::class,'index'])->name('customer.groups');

Route::get('/groups', [App\Http\Controllers\GroupsController::class,'index'])->name('groups');
Route::get('/group/{id}', [App\Http\Controllers\GroupsController::class,'show'])->name('group');
Route::get('/create_group', [App\Http\Controllers\GroupsController::class,'create'])->name('create-group');
Route::get('/edit-group/{id}', [App\Http\Controllers\GroupsController::class,'edit'])->name('edit-group');
Route::post('/add-group', [App\Http\Controllers\GroupsController::class,'add'])->name('add-group');
Route::post('/update-group', [App\Http\Controllers\GroupsController::class,'update'])->name('update-group');
// Route::post('/delete_group', [App\Http\Controllers\GroupsController::class,'delete'])->name('delete-group');

Route::get('/customers', [App\Http\Controllers\UsersController::class,'Customers'])->name('customers');
Route::post('/customers_table', [App\Http\Controllers\UsersController::class,'customersTable'])->name('customers-table');
Route::get('/create_customer', [App\Http\Controllers\UsersController::class,'createCustomer'])->name('create-customer');
Route::get('/edit_customer/{id}', [App\Http\Controllers\UsersController::class,'editCustomer'])->name('edit-customer');
Route::get('/user_init/{id}', [App\Http\Controllers\UsersController::class,'userInit'])->name('user-init');
Route::get('/changes/{id}', [App\Http\Controllers\UsersController::class,'changes'])->name('changes');
Route::get('/file_logs/{id}', [App\Http\Controllers\UsersController::class,'fileLogs'])->name('file-logs');
Route::get('/change/{id}', [App\Http\Controllers\UsersController::class,'change'])->name('change');
Route::post('/add-customer', [App\Http\Controllers\UsersController::class,'addCustomer'])->name('add-customer');
Route::post('/update-customer', [App\Http\Controllers\UsersController::class,'updateCustomer'])->name('update-customer');
Route::post('/delete_customer', [App\Http\Controllers\UsersController::class,'deleteCustomer'])->name('delete-customer');
Route::post('/update_test_status', [App\Http\Controllers\UsersController::class,'updateTestStatus'])->name('update-test-status');
Route::post('/add_customer_comment_and_flag', [App\Http\Controllers\UsersController::class,'addCommentAndFlag'])->name('add-customer-comment-and-flag');
Route::post('/edit_customer_comment_and_flag', [App\Http\Controllers\UsersController::class,'editCommentAndFlag'])->name('edit-customer-comment-and-flag');

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
// Route::post('/update_price', [App\Http\Controllers\CreditsController::class, 'updatePrice'])->name('update-price');
Route::post('/update_price_ecutech', [App\Http\Controllers\CreditsController::class, 'updatePriceECUTech'])->name('update-price-ecutech');
Route::post('/update_price_tuningx', [App\Http\Controllers\CreditsController::class, 'updatePriceTuningX'])->name('update-price-tuningx');
Route::post('/update_price_efiles', [App\Http\Controllers\CreditsController::class, 'updatePriceEFiles'])->name('update-price-efiles');
Route::post('/update_credits', [App\Http\Controllers\CreditsController::class, 'updateCredits'])->name('update-credits');
Route::get('/pdfview', [App\Http\Controllers\CreditsController::class, 'makePDF'])->name('pdfview');

Route::post('/add_caution_text', [App\Http\Controllers\NewsFeedsController::class, 'addCautionText'])->name('add-caution-text');
Route::get('/feeds', [App\Http\Controllers\NewsFeedsController::class, 'index'])->name('feeds');
Route::get('/add-feeds', [App\Http\Controllers\NewsFeedsController::class, 'add'])->name('add-feed');
Route::post('/post-feeds', [App\Http\Controllers\NewsFeedsController::class, 'post'])->name('post-feed');
Route::post('/update-feeds', [App\Http\Controllers\NewsFeedsController::class, 'update'])->name('update-feed');
Route::post('/delete-feeds', [App\Http\Controllers\NewsFeedsController::class, 'delete'])->name('delete-feed');
Route::get('/edit-feeds/{id}', [App\Http\Controllers\NewsFeedsController::class, 'edit'])->name('edit-feed');
Route::post('/change_status_feeds', [App\Http\Controllers\NewsFeedsController::class, 'changeStatus'])->name('change-status-feeds');
Route::post('/delete_feed', [App\Http\Controllers\NewsFeedsController::class, 'delete'])->name('delete-feed');

Route::post('/update_timers', [App\Http\Controllers\NewsFeedsController::class, 'updateTimers'])->name('update-timers');
Route::get('/timers', [App\Http\Controllers\NewsFeedsController::class, 'timers'])->name('timers');

Route::get('/send_test_message', [App\Http\Controllers\FilesController::class, 'sendTestMessage'])->name('send-test-message');

Route::get('/brand_ecu_comments', [App\Http\Controllers\BrandECUCommentsController::class, 'index'])->name('brand-ecu-comments');
Route::get('/create_brand_ecu_comments', [App\Http\Controllers\BrandECUCommentsController::class, 'create'])->name('create-brand-ecu-comment');
Route::get('/edit_brand_ecu_comments/{id}', [App\Http\Controllers\BrandECUCommentsController::class, 'edit'])->name('edit-brand-ecu-comment');
Route::post('/add_brand_ecu_comments', [App\Http\Controllers\BrandECUCommentsController::class, 'add'])->name('add-brand-ecu-comment');
Route::post('/update_brand_ecu_comments', [App\Http\Controllers\BrandECUCommentsController::class, 'update'])->name('update-brand-ecu-comment');
Route::delete('/brand-ecu-comments/delete/{id}', [BrandECUCommentsController::class, 'destroy'])
    ->name('delete-brand-ecu-comment');
Route::get('/get_ecus_by_brand/{brand}', [App\Http\Controllers\BrandECUCommentsController::class, 'getECUForComments'])->name('get-ecus-by-brand');

Route::get('/logs', [App\Http\Controllers\LogsController::class, 'index'])->name('logs');
Route::get('/all_logs', [App\Http\Controllers\LogsController::class, 'all'])->name('all-logs');

Route::get('/alientech_logs', [App\Http\Controllers\LogsController::class, 'alientechLogs'])->name('alientech-logs');
Route::get('/autoturner_logs', [App\Http\Controllers\LogsController::class, 'autoturnerLogs'])->name('autoturner-logs');
Route::get('/magic_logs', [App\Http\Controllers\LogsController::class, 'magicLogs'])->name('magic-logs');
Route::get('/elorus_logs', [App\Http\Controllers\LogsController::class, 'elorusLogs'])->name('elorus-logs');
Route::get('/stripe_logs', [App\Http\Controllers\LogsController::class, 'stripeLogs'])->name('stripe-logs');
Route::get('/paypal_logs', [App\Http\Controllers\LogsController::class, 'paypalLogs'])->name('paypal-logs');
Route::get('/elorus_details/{id}', [App\Http\Controllers\LogsController::class, 'elorusDetails'])->name('elorus-details');
Route::get('/zoho_details/{id}', [App\Http\Controllers\LogsController::class, 'zohoDetails'])->name('zoho-details');
Route::get('/paypal_details/{id}', [App\Http\Controllers\LogsController::class, 'paypalDetails'])->name('paypal-details');
Route::get('/magic_details/{id}', [App\Http\Controllers\LogsController::class, 'magicDetails'])->name('magic-details');
Route::get('/alientech_details/{id}', [App\Http\Controllers\LogsController::class, 'alientechDetails'])->name('alientech-details');
Route::get('/zoho_logs', [App\Http\Controllers\LogsController::class, 'zohoLogs'])->name('zoho-logs');
Route::get('/sms_logs', [App\Http\Controllers\LogsController::class, 'smsLogs'])->name('sms-logs');
Route::get('/email_logs', [App\Http\Controllers\LogsController::class, 'emailLogs'])->name('email-logs');

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

Route::get('/modifications', [App\Http\Controllers\ModificationsController::class, 'index'])->name('modifications');
Route::get('/create_modification', [App\Http\Controllers\ModificationsController::class, 'create'])->name('create-modification');
Route::get('/edit_modification/{id}', [App\Http\Controllers\ModificationsController::class, 'edit'])->name('edit-modification');
Route::post('/add_modification', [App\Http\Controllers\ModificationsController::class, 'add'])->name('add-modification');
Route::post('/update_modification', [App\Http\Controllers\ModificationsController::class, 'update'])->name('update-modification');
Route::post('/delete_modification', [App\Http\Controllers\ModificationsController::class, 'delete'])->name('delete-modification');

Route::get('/dtc_lookup', [App\Http\Controllers\DTCLookupController::class, 'index'])->name('dtc-lookup');
// Route::get('/create_dtc_lookup', [App\Http\Controllers\DTCLookupController::class, 'create'])->name('create-dtclookup');

Route::get('/bosch_lookup', [App\Http\Controllers\DTCLookupController::class, 'bosch'])->name('bosch-lookup');
Route::get('/create_bosch_numbers', [App\Http\Controllers\DTCLookupController::class, 'createBosch'])->name('create-bosch-numbers');
Route::get('/create_dtc_records', [App\Http\Controllers\DTCLookupController::class, 'createDTC'])->name('create-dtc-records');
Route::post('/add_bosch_numbers', [App\Http\Controllers\DTCLookupController::class, 'addBosch'])->name('add-bosch');
Route::post('/add_dtc', [App\Http\Controllers\DTCLookupController::class, 'addDTC'])->name('add-dtc');
Route::get('/edit_bosch_numbers/{id}', [App\Http\Controllers\DTCLookupController::class, 'editBosch'])->name('edit-bosch-numbers');
Route::get('/edit_dtc_records/{id}', [App\Http\Controllers\DTCLookupController::class, 'editDTC'])->name('edit-dtc-records');
Route::post('/update_bosch_numbers', [App\Http\Controllers\DTCLookupController::class, 'updateBosch'])->name('update-bosch');
Route::post('/update_dtc_records', [App\Http\Controllers\DTCLookupController::class, 'updateDTC'])->name('update-dtc');
Route::post('/search_bosch_number', [App\Http\Controllers\DTCLookupController::class, 'searchBosch'])->name('search-bosch-number');
Route::post('/search_dtc_record', [App\Http\Controllers\DTCLookupController::class, 'searchDTC'])->name('search-dtc-record');
Route::post('/delete_bosch', [App\Http\Controllers\DTCLookupController::class, 'deleteBosch'])->name('delete-bosch');
Route::post('/delete_dtc', [App\Http\Controllers\DTCLookupController::class, 'deleteDTC'])->name('delete-dtc');
Route::get('/import_bosch_numbers', [App\Http\Controllers\DTCLookupController::class, 'importBosch'])->name('import-bosch-numbers');
Route::get('/import_dtc_records', [App\Http\Controllers\DTCLookupController::class, 'importDTC'])->name('import-dtc-records');
Route::post('/import_bosch', [App\Http\Controllers\DTCLookupController::class, 'importBoschPost'])->name('import-bosch-post');
Route::post('/import_dtc', [App\Http\Controllers\DTCLookupController::class, 'importDTCPost'])->name('import-dtc-post');

Route::get('/message_templates', [App\Http\Controllers\MessageTemplatesController::class, 'index'])->name('message-templates');
Route::get('/add_message_template', [App\Http\Controllers\MessageTemplatesController::class, 'add'])->name('add-message-template');
Route::get('/edit_message_template/{id}', [App\Http\Controllers\MessageTemplatesController::class, 'edit'])->name('edit-message-template');
Route::post('/post_message_template', [App\Http\Controllers\MessageTemplatesController::class, 'post'])->name('post-message-template');
Route::post('/update_message_template', [App\Http\Controllers\MessageTemplatesController::class, 'update'])->name('update-message-template');
Route::post('/delete_message_template', [App\Http\Controllers\MessageTemplatesController::class, 'delete'])->name('delete-message-template');

// Route::get('/test_html', [App\Http\Controllers\EmailTemplatesController::class, 'test'])->name('test-html');
Route::get('/test_message', [App\Http\Controllers\FilesController::class, 'sendTestMessage'])->name('test-message');
Route::post('/translate_message', [App\Http\Controllers\FilesController::class, 'translateMessage'])->name('translate-message');
Route::post('/set_file_on_hold', [App\Http\Controllers\FilesController::class, 'setFileOnHold'])->name('set-file-on-hold');
Route::post('/assigned_to_me', [App\Http\Controllers\FilesController::class, 'assignedToMe'])->name('assigned-to-me');

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
// Route::get('chatify/search', [MessagesController::class,'search'])->name('search');

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
Route::get('token_request', [AlientechTestController::class, 'requestToken'])->name('token-request');

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


Route::get('sample-messages', [SampleMessageController::class, 'index'])->name('sample-messages.index');
Route::get('sample-messages/create', [SampleMessageController::class, 'create'])->name('sample-messages.create');
Route::post('sample-messages', [SampleMessageController::class, 'store'])->name('sample-messages.store');
Route::get('sample-messages/{id}/edit', [SampleMessageController::class, 'edit'])->name('sample-messages.edit');
Route::put('sample-messages/{id}', [SampleMessageController::class, 'update'])->name('sample-messages.update');
Route::delete('sample-messages/{id}', [SampleMessageController::class, 'destroy'])->name('sample-messages.destroy');