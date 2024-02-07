<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EmailReminder;
use App\Models\EmailTemplate;
use App\Models\EngineerFileNote;
use App\Models\File;
use App\Models\MessageTemplate;
use App\Models\NewsFeed;
use App\Models\ReminderManager;
use App\Models\RequestFile;
use App\Models\Schedualer;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\ReminderManagerController;
use App\Models\AlientechFile;
use App\Models\Credit;
use App\Models\EngineerOptionsOffer;
use App\Models\FileFeedback;
use App\Models\FileInternalEvent;
use App\Models\FileService;
use App\Models\FileUrl;
use App\Models\Key;
use App\Models\Log;
use App\Models\ProcessedFile;
use App\Models\RoleUser;
use App\Models\Service;
use App\Models\Tool;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PDF;
use SebastianBergmann\Template\Template;
use Svg\Tag\Rect;
use Twilio\Rest\Client;
use Yajra\DataTables\DataTables;

use PDO;
use stdClass;
use Symfony\Component\Mailer\Exception\TransportException;
use Twilio\Serialize;

class FilesController extends Controller
{
    private $manager;
    private $alientechObj;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->alientechObj = new AlientechController();
        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();
        $this->middleware('auth',['except' => ['recordFeedback']]);
    }
    
    public function enableDownload(Request $request){

        $file = File::findOrFail($request->id);

        // $aFile = AlientechFile::where('file_id', $file->id);
        // $aFile->delete();

        $file->disable_customers_download = 0;
        $file->status = 'completed';
       

        $file->support_status = "closed";
        $file->checked_by = 'engineer';
       

        $file->reupload_time = Carbon::now();
        
        $file->response_time = $this->getResponseTime($file);
        $file->save();

        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();
    
        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
        $tunningType = $this->emailStagesAndOption($file);
        
        $html1 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html1);
        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
        $tunningType = $this->emailStagesAndOption($file);

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html2);
        $html2 = str_replace("#status", $file->status,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
        $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);
        
        if($file->front_end_id == 1){
            $subject = "ECU Tech: Engineer uploaded a file in reply.";
        }
        else{
            $subject = "TuningX: Engineer uploaded a file in reply.";
        }

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        

        if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

            try{
                \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
            }

        }
        if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){

            try{
                \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
            }
        }
        
        if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
            $this->sendMessage($admin->phone, $message1, $file->front_end_id);
        }

        if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
            $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
        }

        if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id);
        }

        if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
            $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
        }

    
        
        return redirect()->back()->with(['success' => 'Now Engineers can download files which are in revision section!']);
    }

    public function flipShowComments(Request $request){

        $file = RequestFile::findOrFail($request->id);
        $file->show_comments = ($request->showCommentsOnFile == 'false') ? 0 : 1;
        $file->save();
        return response('comments flipped', 200);

    }

    public function declineShowFile(Request $request){

        $reqfile = RequestFile::findOrFail($request->id);
        $reqfile->show_file_denied = 1;
        $reqfile->save();
        return response('file declined', 200);
    }

    public function declineComments(Request $request){

        $reqfile = RequestFile::findOrFail($request->id);
        $reqfile->comments_denied = 1;
        $reqfile->save();
        return response('comments declined', 200);

    }

    public function flipShowFile(Request $request){
        
        $reqfile = RequestFile::findOrFail($request->id);
        $reqfile->is_kess3_slave = ($request->showFile == 'false') ? 1 : 0;
        $reqfile->show_file_denied = 0;
        $reqfile->save();
        
        if($reqfile->is_kess3_slave == 0){

            $file = File::findOrFail($reqfile->file_id);

            if($file->status == 'submitted'){

                $file->status = 'completed';
                $file->reupload_time = Carbon::now();
                $file->response_time = $this->getResponseTime($file);

                $file->save();

            }

            $customer = User::findOrFail($file->user_id);
            $admin = get_admin();
        
            // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
            $template = EmailTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

            $html1 = $template->html;

            $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
            $html1 = str_replace("#customer_name", $customer->name ,$html1);
            $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
            
            $tunningType = $this->emailStagesAndOption($file);
            
            $html1 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html1);
            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url", route('file', $file->id),$html1);

            $html2 = $template->html;

            $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
            $html2 = str_replace("#customer_name", $file->name ,$html2);
            $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
            
            $tunningType = $this->emailStagesAndOption($file);

            $html2 = str_replace("#tuning_type", $tunningType,$html2);
            $html2 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html2);
            $html2 = str_replace("#status", $file->status,$html2);

            if($file->front_end_id == 1){
                $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
            }
            else{
                $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
            }

            $optionsMessage = "";
            if($file->options){
                foreach($file->options() as $option) {
                    $optionsMessage .= ",".$option." ";
                }
            }

            // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
            $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

            $message = $messageTemplate->text;

            $message1 = str_replace("#customer", $customer->name ,$message);
            $message2 = str_replace("#customer", $file->name ,$message);
            
            if($file->front_end_id == 1){
                $subject = "ECU Tech: Engineer uploaded a file in reply.";
            }
            else{
                $subject = "TuningX: Engineer uploaded a file in reply.";
            }

            $reminderManager = new ReminderManagerController();
            $this->manager = $reminderManager->getAllManager();

        

            if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

                try{
                    \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                }

            }
            if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){

                try{
                    \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));

                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                }
            }
            
            if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
                $this->sendMessage($admin->phone, $message1, $file->front_end_id);
            }

            if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
                $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
            }

            if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
                $this->sendMessage($customer->phone, $message2, $file->front_end_id);
            }

            if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
                $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
            }

        }
        
        return response('file flipped', 200);

    }

    public function deleteFiles(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $ids = $request->ids;
        $files = File::whereIn('id', $ids)->get();
        
        foreach($files as $file){

            FileService::where('file_id', $file->id)->delete();
            ProcessedFile::where('file_id', $file->id)->delete();
            RequestFile::where('file_id', $file->id)->delete();
            FileInternalEvent::where('file_id', $file->id)->delete();
            FileFeedback::where('file_id', $file->id)->delete();
            AlientechFile::where('file_id', $file->id)->delete();
            EngineerFileNote::where('file_id', $file->id)->delete();
            FileUrl::where('file_id', $file->id)->delete();
            Log::where('file_id', $file->id)->delete();
            
            $file->delete();
        }


    }

    public function forceOptionsOffer(Request $request){
        
        $fileID = $request->file_id;
        $forceProposedOptions = $request->force_proposed_options;

        $file = File::findOrFail($fileID);

        foreach($file->options as $service){
            $service->delete();
        }

        $proposedCredits = 0;

        if($file->front_end_id == 1){
            $proposedCredits += Service::findOrFail($file->stage_services->service_id)->credits;
        }
        else{
            if($file->tool_type == 'master'){

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->tuningx_credits;

            }
            else{

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->tuningx_slave_credits;
            }
        }

        if($forceProposedOptions){

            foreach($forceProposedOptions as $offer){

                    $option = new FileService();
                    $option->service_id = $offer; 
                    $option->type = 'option'; 

                    if($file->front_end_id == 1){

                        $proposedCredits += Service::findOrFail($offer)->credits;
                        $option->credits = Service::findOrFail($offer)->credits;

                    }
                    else{

                        $service = Service::findOrFail($offer);

                        if($file->tool_type == 'master'){

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->master_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->master_credits;
        
                        }
                        else{

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                        }

                    }

                    $option->file_id = $fileID;
                    $option->save();
            }
        }

        $differece = $file->credits - $proposedCredits;

        $user = User::findOrfail($file->user_id);

        if( $differece > 0 ){

            $credit = new Credit();
            $credit->credits = $differece;
            $credit->user_id = $user->id;
            $credit->front_end_id = $user->front_end_id;
            $credit->file_id = $file->id;
            $credit->stripe_id = NULL;

            $credit->gifted = 1;
            $credit->price_payed = 0;
            
            $credit->message_to_credit = 'File options accepted and credits returned!';
            
            $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);
            $credit->save();
        }

        $file->credits = $proposedCredits;
        
        $file->save();

        return redirect()->back()->with(['success' => 'options adjusted and credits returned!']);
    }

    public function multiDelete(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $files = File::orderBy('created_at', 'desc')->get();

        return view('files.multi_delete', [ 'files' => $files ]);
    }

    public function addOptionsOffer(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $file = File::findOrFail($request->file_id);
        
        $proposed = new EngineerOptionsOffer();
        $proposed->file_id = $request->file_id;
        $proposed->type = 'stage';
        $proposed->service_id = $request->proposed_stage;
        $proposed->save();

        $proposedOptions = $request->proposed_options;
        
        if($proposedOptions){
            foreach($proposedOptions as $o){
                $proposed = new EngineerOptionsOffer();
                $proposed->file_id = $request->file_id;
                $proposed->type = 'option';
                $proposed->service_id = $o;
                $proposed->save();
            }
        }

        $file->status = 'on_hold';
        $file->save();
    
        return redirect()->back()->with(['success' => 'New stages and options proposed!']);
    }

    public function flipDecodedMode(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $file = File::findOrFail($request->file_id);

        if($file->decoded_mode == 1){
            
            $file->file_attached = $file->file_attached_backup;
            $file->file_attached_backup = null;
            $file->decoded_mode = 0;
            $file->save();

        }

        else if ($file->decoded_mode == 0){

            $file->file_attached_backup = $file->file_attached;
            $file->file_attached = $file->final_decoded_file();
            $file->decoded_mode = 1;
            $file->save();

        }

        return redirect()->back()
        ->with('success', 'File Decoded mode is updated!');
    }
    public function support($id){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $requestFile = RequestFile::findOrFail($id);
        $file = File::findOrFail($requestFile->file_id);

        foreach($file->engineer_file_notes as $f){
            $f->sent_by = NULL;
            $f->save();
        }

        if(($file->front_end_id == 1 && $file->subdealer_group_id == NULL)){
            abort(404);
        }

        return view('files.support', ['requestFile' => $requestFile, 'file' => $file]);
    }

    public function changeCheckingStatus(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $file = File::findOrFail($request->file_id);

        if($file->checking_status == 'unchecked'){
            $file->checking_status = 'fail';
            $file->save();
            return  response()->json( ['msg' => 'File was not found.', 'fail' => 1, 'file_id' => $file->id] );
        }
        if($file->checking_status == 'completed'){
            return response()->json( ['msg' => 'File found.', 'fail' => 2, 'file_id' => $file->id] );
        }
        return response()->json( ['msg' => 'File Status does not change.', 'fail' => 0, 'file_id' => $file->id] );
    }

    public function search(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $file = File::findOrFail($request->file_id);
        
        if($request->download_directly == 'download' && $request->custom_stage ){
            
            $file->custom_stage = $request->custom_stage;
            $file->save();
        }
        else{
            $file->custom_stage = NULL;
            $file->save();
        }
        
        if($request->download_directly == 'download' && $request->custom_options ){
            
            $file->custom_options = implode(',', $request->custom_options);
            $file->save();
        }
        else{
            $file->custom_options = '';
            $file->save();
        }
        
        if($request->file('decrypted_file')){

            $attachment = $request->file('decrypted_file');
            $fileName = $attachment->getClientOriginalName();

            if($file->front_end_id == 1)
                $attachment->move(public_path('/../../portal/public/'.$file->file_path),$fileName);
            else
                $attachment->move(public_path('/../../tuningX/public/'.$file->file_path),$fileName);
            
        }

        $processFile = new ProcessedFile();
        $processFile->file_id = $file->id;
        $processFile->type = 'decoded';
        $processFile->name = explode(".", $fileName)[0];

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        if($ext != ""){
            $processFile->extension = $ext;
        }

        $processFile->save();

        $file->checking_status = 'unchecked';
        $file->save();

        return view('files.search', ['file' => $file]);

    }
    
    public function delete(Request $request){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-file')) {
            
            $file = File::findOrFail($request->id);

            FileService::where('file_id', $file->id)->delete();
            ProcessedFile::where('file_id', $file->id)->delete();
            RequestFile::where('file_id', $file->id)->delete();
            FileInternalEvent::where('file_id', $file->id)->delete();
            FileFeedback::where('file_id', $file->id)->delete();
            AlientechFile::where('file_id', $file->id)->delete();
            EngineerFileNote::where('file_id', $file->id)->delete();
            FileUrl::where('file_id', $file->id)->delete();
            Log::where('file_id', $file->id)->delete();
            
            $file->delete();

            return response()->json( 'deleted' );
        }
        else{
            return abort(404);
        }

    }

    // public function getAccessToken(){

    //     $apiURL = 'https://encodingapi.alientech.to/api/access-tokens/request';
    //     $postInput = [
    //         'clientApplicationGUID' => 'f8b0f518-8de7-4528-b8db-3995e1b787e9',
    //         'secretKey' => "#5!/ThmmM*?;D\\jvjQ6%9/",
    //     ];
  
    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];
  
    //     $response = Http::withHeaders($headers)->post($apiURL, $postInput);
  
    //     $statusCode = $response->status();
    //     $responseBody = json_decode($response->getBody(), true);
     
    //     if(isset($responseBody['accessToken'])){
    //         $key = new Key();
    //         $key->key = 'alientech_access_token';
    //         $key->value = $responseBody['accessToken'];
    //         $key->save();
    //         return $responseBody['accessToken'];
    //     }

    //     return null;
    // }

    public function callbackKess3(Response $response){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        \Log::info($response);
    }

    public function decodeFile(){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $token = Key::where('key', 'alientech_access_token')->first()->value;

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots";

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $token,
        ];
  
        $response = Http::withHeaders($headers)->get($url);
        $responseBody = json_decode($response->getBody(), true);

        foreach($responseBody as $row){

            if($row['isClosed'] == false){

                $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$row['guid']."/close";

                $headers = [
                'X-Alientech-ReCodAPI-LLC' => $token,
                ];

                $response = Http::withHeaders($headers)->post($url, []);
            }

        }

        dd('all_closed');
        exit;
    }

    public function deactivateAllExceptThisFeed($ThatFeed) {
        
        $allOtherFeed = NewsFeed::where('id', '!=', $ThatFeed->id)->get();

        foreach($allOtherFeed as $feed){
            if($feed->active == 1){
                // \Log::info("deactivating feed inner:".$feed->title);
                $feed->active = 0;
                $feed->save();
            }
        }
    }

    public function feedadjustment(){

        $feeds = NewsFeed::all();

        $dateCheck = date('l');
        $timeCheck = date('H:i');

        $deactiveAll = false;

        $thatFeed = '';

        foreach($feeds as $feed) {

            // \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));
            // \Log::info("Feed Activation Day: ".strtotime(date('d-m-y h:i:s')));
            // \Log::info("activation date time: ".$feed->activate_at);
            // \Log::info("activation date time: ".strtotime($feed->activate_at));

            if($feed->activation_weekday == null &&  $feed->deactivation_weekday == null){

                if( strtotime(now()) >= strtotime($feed->activate_at) && strtotime(now()) <= strtotime($feed->deactivate_at)){
                    // if($feed->active == 0){
                            // \Log::info("activating feed:".$feed->title);
                            $feed->active = 1;
                            $feed->save();
    
                            $deactiveAll = true;
                            $thatFeed = $feed;
                        // }
                    }
                    else {
                        if($feed->active == 1){
                            // \Log::info("deactivating feed:".$feed->title);
                            $feed->active = 0;
                            $feed->save();
                        }
                    }
                }

            if(!$deactiveAll) {

                if($feed->activate_at == null &&  $feed->deactivate_at == null) {

                    if($this->getDayNumber($dateCheck) >= $this->getDayNumber($feed->activation_weekday) && $this->getDayNumber($dateCheck) <= $this->getDayNumber($feed->deactivation_weekday) ) {
                        
                        if ( strtotime($timeCheck) > strtotime($feed->daily_activation_time) && strtotime($timeCheck) < strtotime($feed->daily_deactivation_time) ) {

                            if($feed->active == 0){
                                
                                // \Log::info("activating feed at:".date('l').$feed->title);
                                $feed->active = 1;
                                $feed->save();
                            }
                        }

                        else {
                            
                            if($feed->active == 1){
                                
                                // \Log::info("deactivating feed at:".date('l').$feed->title);
                                $feed->active = 0;
                                $feed->save();
                            }
                        }
                    }

                    else{

                            if($feed->active == 1){

                                // \Log::info("deactivating feed:".date('l').$feed->title);
                                $feed->active = 0;
                                $feed->save();
                            }
                        
                        }
                    }
                }
                if($deactiveAll){
                    $this->deactivateAllExceptThisFeed($thatFeed);
                }
        }
    }

    function recursiveChmod($path, $filePerm=0777, $dirPerm=0777) {
        // Check if the path exists
        if (!file_exists($path)) {
            return(false);
        }
 
        // See whether this is a file
        if (is_file($path)) {
            // Chmod the file with our given filepermissions
            chmod($path, $filePerm);
 
        // If this is a directory...
        } elseif (is_dir($path)) {
            // Then get an array of the contents
            $foldersAndFiles = scandir($path);
 
            // Remove "." and ".." from the list
            $entries = array_slice($foldersAndFiles, 2);
 
            // Parse every result...
            foreach ($entries as $entry) {
                // And call this function again recursively, with the same permissions
                $this->recursiveChmod($path."/".$entry, $filePerm, $dirPerm);
            }
 
            // When we are done with the contents of the directory, we chmod the directory itself
            chmod($path, $dirPerm);
        }
 
        // Everything seemed to work out well, return true
        return(true);
    }

    public function getDayNumber($day) {

        $days = [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday'
        ];

        foreach($days as $key => $row){
            if($row == $day)
            return $key;
        }

    }
    
    public function liveFiles(){

        $this->feedadjustment();

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files')){
            
            return view('files.live_files');    
        }
        else{
            return abort(404);
        }
    }
    
    public function updateFileVehicle(Request $request) {

    if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-file')){
        
        $this->validate($request, [
            'engine' => 'required',
            'version' => 'required'
        ]);

        $file = File::findOrFail($request->id);

        $file->version = $request->version;
        $file->engine = $request->engine;
        $file->ecu = $request->ecu;
        $file->save();

        return redirect()->back()
        ->with('success', 'File successfully Edited!');

    }

    else{
        return abort(404);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getModels(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $brand = $request->brand;
        
        $models = Vehicle::OrderBy('model', 'asc')->select('model')->whereNotNull('model')->distinct()->where('make', '=', $brand)->get();
        
        return response()->json( [ 'models' => $models ] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getVersions(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $model = $request->model;
        $brand = $request->brand;

        $versions = Vehicle::OrderBy('generation', 'asc')->whereNotNull('generation')->select('generation')->distinct()
        ->where('Make', '=', $brand)
        ->where('Model', '=', $model)
        ->get();

        return response()->json( [ 'versions' => $versions ] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEngines(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $model = $request->model;
        $brand = $request->brand;
        $version = $request->version;

        $engines = Vehicle::OrderBy('engine', 'asc')->whereNotNull('engine')->select('engine')->distinct()
        ->where('Make', '=', $brand)
        ->where('Model', '=', $model)
        ->where('Generation', '=', $version)
        ->get();

        return response()->json( [ 'engines' => $engines ] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getECUs(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $model = $request->model;
        $brand = $request->brand;
        $version = $request->version;
        $engine = $request->engine;
       
        $ecus = Vehicle::OrderBy('Engine_ECU', 'asc')->whereNotNull('Engine_ECU')->select('Engine_ECU')->distinct()
        ->where('Make', '=', $brand)
        ->where('Model', '=', $model)
        ->where('Generation', '=', $version)
        ->where('Engine', '=', $engine)
        ->get();

        $ecusArray = [];

        foreach($ecus as $e){
            $temp = explode(' / ', $e->Engine_ECU);
            $ecusArray = array_merge($ecusArray,$temp);
        }

        $ecusArray = array_values(array_unique($ecusArray));

        return response()->json( [ 'ecus' => $ecusArray ]);
    }

    public function editFile($id) {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-file')){
        
            $file = File::findOrFail($id);

            $brandsObjects = Vehicle::OrderBy('make', 'asc')->select('make')->distinct()->get();

            $brands = [];
            foreach($brandsObjects as $b){
                if($b->make != '')
                $brands []= $b->make;
            }

            $modelsObjects = Vehicle::OrderBy('model', 'asc')->select('model')->whereNotNull('model')->distinct()->where('make', '=', $file->brand)->get();

            $models = [];
            foreach($modelsObjects as $m){
                if($m->model != '')
                $models []= $m->model;
            }

            $versionsObjects = Vehicle::OrderBy('generation', 'asc')->whereNotNull('generation')->select('generation')->distinct()
            ->where('Make', '=', $file->brand)
            ->where('Model', '=', $file->model)
            ->get();

            $versions = [];
            foreach($versionsObjects as $v){
                if($v->generation != '')
                $versions []= $v->generation;   
            }        

            $enginesObjects = Vehicle::OrderBy('engine', 'asc')->whereNotNull('engine')->select('engine')->distinct()
            ->where('Make', '=', $file->brand)
            ->where('Model', '=', $file->model)
            ->where('Generation', '=', $file->version)
            ->get();

            $engines = [];
            foreach($enginesObjects as $e){
                if($e->engine != '')
                $engines []= $e->engine;   
            }   

            $ecus = Vehicle::OrderBy('Engine_ECU', 'asc')->whereNotNull('Engine_ECU')->select('Engine_ECU')->distinct()
            ->where('Make', '=', $file->brand)
            ->where('Model', '=', $file->model)
            ->where('Generation', '=', $file->version)
            ->where('Engine', '=', $file->engine)
            ->get();

            $ecusArray = [];

            foreach($ecus as $e){
                $temp = explode(' / ', $e->Engine_ECU);
                $ecusArray = array_merge($ecusArray,$temp);
            }

            $ecusArray = array_values(array_unique($ecusArray));

                return view('files.edit', [ 'file' => $file, 
                'brands' => $brands, 
                'models' => $models, 
                'versions' => $versions,
                'engines' => $engines,
                'ecus' => $ecusArray
            ]);
        }
        else{

            return abort(404);
        }
    }

    public function saveFeedbackEmailSchedual(Request $request) {

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $this->validate($request, [
            'days' => 'required|min:1',
            'time_of_day' => 'required',
            'cycle' => 'required|min:1'
        ]);

        $schedual = Schedualer::take(1)->first();

        if( !$schedual ) {
            $new = new Schedualer();
            $new->days = $request->days; 
            $new->time_of_day = $request->time_of_day; 
            $new->cycle = $request->cycle; 
            $new->save(); 
        }
        else{
           
            $schedual->days = $request->days; 
            $schedual->time_of_day = $request->time_of_day; 
            $schedual->cycle = $request->cycle;
            $schedual->save(); 
        }

        $filesObject = File::Orderby('files.created_at', 'desc')
        ->where('is_credited', 1)->select('*')
        ->addSelect('files.id as id')
        ->addSelect('request_files.id as req_id');
        $filesObject = $filesObject->join('request_files', 'files.id', '=' , 'request_files.file_id');
        $filesObject = $filesObject->join('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id', 'left outer')->whereNull('type');
        $reminderFiles = $filesObject->get();
        
        foreach($reminderFiles as $file){

            $alreadyadded = EmailReminder::where('file_id', $file->id)
            ->where('user_id', $file->user_id)
            ->where('request_file_id', $file->req_id)
            ->first();

            if(!$alreadyadded){
                $reminder = new EmailReminder();
                $reminder->file_id = $file->id;
                $reminder->user_id = $file->user_id;
                $reminder->request_file_id = $file->req_id;
                $reminder->set_time = Carbon::now();
                $reminder->cycle =  $request->cycle;
                $reminder->save();
            }
            else{
                $alreadyadded->set_time = Carbon::now();
                $alreadyadded->cycle =  $request->cycle;
                $alreadyadded->save();
            }
        }

        return redirect()->route('feedback-emails')->with(['success' => 'Schedual udpated, successfully.']);
    }

    public function feedbackEmails() {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        //email template
        $feebdackTemplate = EmailTemplate::findOrFail(9);
        $schedual = Schedualer::take(1)->first();
        return view('files.feedback_page', [ 'feebdackTemplate' => $feebdackTemplate, 'schedual' => $schedual ]);
    }

    public function saveFeedbackEmailTemplate(Request $request) {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $feebdackTemplate = EmailTemplate::findOrFail(9);
        $feebdackTemplate->html = $request->new_template;
        $feebdackTemplate->save();

        return redirect()->route('feedback-emails')->with(['success' => 'Template udpated, successfully.']);

    }

    public function editMessage( Request $request ) {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $message = EngineerFileNote::findOrFail($request->id);
        $message->egnineers_internal_notes = $request->message;
        $message->save();
        
        return redirect()->back()
        ->with('success', 'Engineer note successfully Edited!')
        ->with('tab','chat');
    }
    
    public function downloadEncrypted( $id,$fileName ) {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $file = File::findOrFail($id); 

        $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();
        
        if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id){

            $notProcessedAlientechFile = AlientechFile::where('file_id', $file->id)
            ->where('purpose', 'decoded')
            ->where('type', 'download')
            ->where('processed', 0)
            ->first();

            if($notProcessedAlientechFile){
               
                $fileNameEncoded = $this->alientechObj->downloadEncodedFile($id, $notProcessedAlientechFile, $fileName);
                $notProcessedAlientechFile->processed = 1;
                $notProcessedAlientechFile->save();

                if($file->front_end_id == 1){

                    $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;
                }
                else{

                    $file_path = public_path('/../../tuningX/public/'.$file->file_path).$fileNameEncoded;
                }
                
                
                return response()->download($file_path);
            }
            else{
                $encodedFileNameToBe = $fileName.'_encoded_api';
                $processedFile = ProcessedFile::where('name', $encodedFileNameToBe)->first();

                if($processedFile){

                if($processedFile->extension != ''){
                    $finalFileName = $processedFile->name;
                    // $finalFileName = $processedFile->name.'.'.$processedFile->extension;
                    // dd($finalFileName);

                }
                else{
                    $finalFileName = $processedFile->name;
                }
            }else{
                $finalFileName = $fileName;
            }

            if($file->front_end_id == 1){

                $file_path = public_path('/../../portal/public/'.$file->file_path).$finalFileName;
            }
            else{

                $file_path = public_path('/../../tuningX/public/'.$file->file_path).$finalFileName;
            }
                return response()->download($file_path);

            }

        }
        else{
            $file_path = public_path('/../../portal/public/'.$file->file_path).$fileName;
            return response()->download($file_path);
        }
    }

    public function download($id,$file_name, $deleteFile = false) {

        $file = File::findOrFail($id);

        if($file->front_end_id == 1){
            if($file->subdealer_group_id){
                $path = public_path('/../../subportal/public'.$file->file_path);
            }
            else{
                $path = public_path('/../../portal/public'.$file->file_path);
            }
        }
        else{
            $path = public_path('/../../tuningX/public'.$file->file_path);
        }

        $file_path = $path.$file_name;

        if($deleteFile){
            return response()->download($file_path)->deleteFileAfterSend(true);
        }
        else{
            return response()->download($file_path);
        }

    }

    public function deleteMessage(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }
        $note = EngineerFileNote::findOrFail($request->note_id);
        $note->delete();
        return response('Note deleted', 200);
    }

    public function deleteUploadedFile(Request $request)
    {

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $file = RequestFile::findOrFail($request->request_file_id);
        $file->delete();
        return response('File deleted', 200);
    }

    public function sendMessage($receiver, $message, $frontendID)
    {
        try {
            
            $accountSid = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_sid')->first()->value;

            $authToken = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_token')->first()->value;

            $twilioNumber = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_number')->first()->value;


            $client = new Client($accountSid, $authToken);

            if($frontendID == 2)
            {
                $message = $client->messages
                    ->create($receiver, // to
                            ["body" => $message, "from" => "TuningX"]
                );
            }
            else{

                $message = $client->messages
                    ->create($receiver, // to
                            ["body" => $message, "from" => "ECUTech"]
                );

            }

            \Log::info('message sent to:'.$receiver);

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function assignEngineer(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }
    
        $file = File::findOrFail($request->file_id);
        $file->assigned_to = $request->assigned_to;
        $file->assignment_time = Carbon::now();
        $file->save();

        $engineer = User::findOrFail($request->assigned_to);
        $customer = User::findOrFail($file->user_id);
        
        //    $template = EmailTemplate::where('name', 'Engineer Assignment Email')->first();
        $template = EmailTemplate::where('slug', 'eng-assign')->where('front_end_id', $file->front_end_id)->first();

        $html = $template->html;

        $html = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html);
        $html = str_replace("#customer_name", $customer->name ,$html);
        $html = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html);
       

        $tunningType = $this->emailStagesAndOption($file);
        
        $html = str_replace("#tuning_type", $tunningType,$html);
        $html = str_replace("#status", $file->status,$html);
        $html = str_replace("#file_url", route('file', $file->id),$html);

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Engineer Assignment')->first();

        $messageTemplate = MessageTemplate::where('slug', 'eng-assign')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message = str_replace("#customer", $customer->name ,$message);

        if($file->front_end_id == 1){
            $subject = "ECU Tech: Task Assigned!";
        }
        else{
            $subject = "TuningX: Task Assigned!";
        }

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if($this->manager['eng_assign_eng_email'.$file->front_end_id]){

            try{

                \Mail::to($engineer->email)->send(new \App\Mail\AllMails(['engineer' => $engineer, 'html' => $html, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
            
            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
            }
        }

        if($this->manager['eng_assign_eng_sms'.$file->front_end_id]){
        
            $this->sendMessage($engineer->phone, $message, $file->front_end_id);
        }

        if($this->manager['eng_assign_eng_whatsapp'.$file->front_end_id]){
        
            $this->sendWhatsappforEng($engineer->name,$engineer->phone, 'admin_assign', $file);
        }
        
        return Redirect::back()->with(['success' => 'Engineer Assigned to File.']);

    }

    public function sendWhatsappforEng($name, $number, $template, $file, $supportMessage = null){

        $accessToken = config('whatsApp.access_token');
        $fromPhoneNumberId = config('whatsApp.from_phone_number_id');

        $optionsMessage = $file->stage;

        if($file->options){
            foreach($file->options()->get() as $option) {
                $optionName = Service::findOrFail($option->service_id)->name;
                $optionsMessage .= ", ".$optionName."";
            }
        }

        $customer = User::findOrFail($file->user_id)->name;

        if($file->front_end_id == 1){
            $frontEnd = "ECUTech";
        }
        else{
            $frontEnd = "Tuning-X";
        }

        if($supportMessage){
            $components  = 
            [
                [
                    "type" => "header",
                    "parameters" => array(
                        array("type"=> "text","text"=> $frontEnd),
                    )
                ],
                [
                    "type" => "body",
                    "parameters" => array(
                        array("type"=> "text","text"=> "dear ".$name),
                        array("type"=> "text","text"=> "Mr. ".$customer),
                        array("type"=> "text","text"=> $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard),
                        array("type"=> "text","text"=> $optionsMessage),
                        array("type"=> "text","text"=> $supportMessage),
                    )
                ]
            ];
        }
        else{
            $components  = 
            [
                [
                    "type" => "header",
                    "parameters" => array(
                        array("type"=> "text","text"=> $frontEnd),
                    )
                ],
                [
                    "type" => "body",
                    "parameters" => array(
                        array("type"=> "text","text"=> "dear ".$name),
                        array("type"=> "text","text"=> "Mr. ".$customer),
                        array("type"=> "text","text"=> $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard),
                        array("type"=> "text","text"=> $optionsMessage),
                    )
                ]
            ];
        }

        $whatappObj = new WhatsappController();

        try {
            $response = $whatappObj->sendTemplateMessage($number,$template, 'en', $accessToken, $fromPhoneNumberId, $components, $messages = 'messages');
            // dd($response);
        }
        catch(Exception $e){
            \Log::info($e->getMessage());
        }

    }

    public function sendWhatsapp($name, $number, $template, $file, $supportMessage = null){

        $accessToken = config('whatsApp.access_token');
        $fromPhoneNumberId = config('whatsApp.from_phone_number_id');

        $optionsMessage = $file->stage;

        if($file->options){
            foreach($file->options()->get() as $option) {
                $optionName = Service::findOrFail($option->service_id)->name;
                $optionsMessage .= ", ".$optionName."";
            }
        }

        $customer = 'Task Customer';

        if($file->name){
            $customer = $file->name; 
        }

        if($file->front_end_id == 1){
            $frontEnd = "ECUTech";
        }
        else{
            $frontEnd = "Tuning-X";
        }

        if($supportMessage){
            $components  = 
            [
                [
                    "type" => "header",
                    "parameters" => array(
                        array("type"=> "text","text"=> $frontEnd),
                    )
                ],
                [
                    "type" => "body",
                    "parameters" => array(
                        array("type"=> "text","text"=> "dear ".$name),
                        array("type"=> "text","text"=> "Mr. ".$customer),
                        array("type"=> "text","text"=> $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard),
                        array("type"=> "text","text"=> $optionsMessage),
                        array("type"=> "text","text"=> $supportMessage),
                    )
                ]
            ];
        }
        else{
            $components  = 
            [
                [
                    "type" => "header",
                    "parameters" => array(
                        array("type"=> "text","text"=> $frontEnd),
                    )
                ],
                [
                    "type" => "body",
                    "parameters" => array(
                        array("type"=> "text","text"=> "dear ".$name),
                        array("type"=> "text","text"=> "Mr. ".$customer),
                        array("type"=> "text","text"=> $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard),
                        array("type"=> "text","text"=> $optionsMessage),
                    )
                ]
            ];
        }

        $whatappObj = new WhatsappController();

        try {
            $response = $whatappObj->sendTemplateMessage($number,$template, 'en', $accessToken, $fromPhoneNumberId, $components, $messages = 'messages');
            // dd($response);
        }
        catch(Exception $e){
            \Log::info($e->getMessage());
        }

        
    }

    public function changSupportStatus(Request $request){

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $file = File::findOrFail($request->file_id);
        $file->support_status = $request->support_status;
        $file->save();

        return Redirect::back()->with(['success' => 'File Support status changed.']);
    }

    public function changeStatus(Request $request){

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }
        
        $file = File::findOrFail($request->file_id);

        $file->status = $request->status;
        
        $customer = User::findOrFail($file->user_id);

        if($request->status == 'rejected'){

            $credit = new Credit();
            $credit->credits = $file->credits;
            $credit->user_id = $customer->id;
            $credit->file_id = $file->id;
            $credit->front_end_id = $customer->front_end_id;
            $credit->stripe_id = NULL;

            $credit->gifted = 1;
            $credit->price_payed = 0;

            if($request->reason_to_reject){
                $credit->message_to_credit = $request->reason_to_reject;
                $file->reason_to_reject = $request->reason_to_reject;
            }
            else{
                $credit->message_to_credit = 'File rejected and refunded!';
                $file->reason_to_reject = 'File rejected and refunded!';
            }

            $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);
            $credit->save();

        }
        
        $file->save();

        $admin = get_admin();
    
        // $template = EmailTemplate::where('name', 'Status Change')->first();
        $template = EmailTemplate::where('slug', 'sta-cha')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        

        $tunningType = $this->emailStagesAndOption($file);
        
        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        

        $tunningType = $this->emailStagesAndOption($file);
        
        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Status Change')->first();
        $messageTemplate = MessageTemplate::where('slug', 'sta-cha')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message1 = str_replace("#status", $file->status ,$message1);

        $message2 = str_replace("#customer", $file->name ,$message);
        $message2 = str_replace("#status", $file->status ,$message2);
        
        if($file->front_end_id == 1){
            $subject = "ECU Tech: File Status Changed!";
        }
        else{
            $subject = "TuningX: File Status Changed!";
        }

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if($this->manager['status_change_cus_email'.$file->front_end_id]){

            try{
                
                \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
            }
        }

        if($this->manager['status_change_admin_email'.$file->front_end_id]){

            try{
                \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
            }
        }

        if($this->manager['status_change_admin_sms'.$file->front_end_id]){
            $this->sendMessage($admin->phone, $message1, $file->front_end_id);
        }

        if($this->manager['status_change_admin_whatsapp'.$file->front_end_id]){
        
            $this->sendWhatsappforEng($admin->name,$admin->phone, 'status_change', $file);
        }

        if($this->manager['status_change_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id);
        }

        if($this->manager['status_change_cus_whatsapp'.$file->front_end_id]){
        
            $this->sendWhatsapp($customer->name,$customer->phone, 'admin_assign', $file);
        }

        return Redirect::back()->with(['success' => 'File status changed.']);
    }

    public function fileEngineersNotes(Request $request)
    {   

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $noteItself = $request->egnineers_internal_notes;
        
        $file = File::findOrFail($request->file_id);

        $reply = new EngineerFileNote();
        $reply->egnineers_internal_notes = $request->egnineers_internal_notes;

        if($request->file('engineers_attachement')){

            $attachment = $request->file('engineers_attachement');
            $fileName = $attachment->getClientOriginalName();
            $model = str_replace('/', '', $file->model );

            if($file->front_end_id == 1){
                
                if($file->subdealer_group_id){
                    $attachment->move(public_path('/../../subportal/public/'.$file->file_path),$fileName);
                }
                else{

                    $attachment->move(public_path('/../../portal/public/'.$file->file_path),$fileName);
                }
            }
            else{
                $attachment->move(public_path('/../../tuningX/public/'.$file->file_path),$fileName);

            }

            $reply->engineers_attachement = $fileName;
        }

        $reply->engineer = true;
        $reply->file_id = $request->file_id;
        $reply->request_file_id = $request->request_file_id;
        $reply->save();
        
        $file->support_status = "closed";
        $file->save();
        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();
    
        // $template = EmailTemplate::where('name', 'Message To Client')->first();
        $template = EmailTemplate::where('slug', 'mess-to-client')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
        $tunningType = $this->emailStagesAndOption($file);

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#note", $request->egnineers_internal_notes,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        

        $tunningType = $this->emailStagesAndOption($file);
        
        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#note", $request->egnineers_internal_notes,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Message To Client')->first();
        $messageTemplate = MessageTemplate::where('slug', 'mess-to-client')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);

        // $message1 = "Hi, Status changed for a file by Customer: " .$customer->name;
        // $message2 = "Hi, Status changed for a file by Customer: " .$file->name;

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if($file->front_end_id == 1){
            $subject = "ECU Tech: Engineer replied to your support message!";
        }
        else{
            $subject = "Tuningx: Engineer replied to your support message!";
        }


        if($this->manager['msg_eng_cus_email'.$file->front_end_id]){

            try{
                \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
            }
        }
        if($this->manager['msg_eng_admin_email'.$file->front_end_id]){

            try{
                \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
            }
        }
        
        if($this->manager['msg_eng_admin_sms'.$file->front_end_id]){
            
            $this->sendMessage($admin->phone, $message1, $file->front_end_id);
        }

        if($this->manager['msg_eng_admin_whatsapp'.$file->front_end_id]){
            
            $this->sendWhatsappforEng($admin->name, $admin->phone, 'support_message_from_engineer', $file, $noteItself);
        }

        if($this->manager['msg_eng_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id);
        }

        if($this->manager['msg_eng_cus_whatsapp'.$file->front_end_id]){
            
            $this->sendWhatsapp($customer->name, $customer->phone, 'support_message_from_engineer', $file, $noteItself);
        }

        $old = File::findOrFail($request->file_id);
        $old->checked_by = 'engineer';
        $old->save();

        return redirect()->back()
        ->with('success', 'Engineer note successfully Added!')
        ->with('tab','chat');

    }

    public function makeLogEntry($fileID, $type, $message){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $log = new Log();
        $log->file_id = $fileID;
        $log->type = $type;
        $log->message = $message;
        $log->save();

    }

    public function callbackKess3Complete(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        \Log::info( $request->all() );
    }
    
    public function uploadFileFromEngineer(Request $request)
    {
        $attachment = $request->file('file');
        $encode = (boolean) $request->encode;
       
        $file = File::findOrFail($request->file_id);

        $optionsMessage = '';
        if($file->options){
            foreach($file->options()->get() as $option) {
                $optionName = Service::findOrFail($option->service_id)->name;
                $optionsMessage .= "".$optionName."_";
            }
        }
        
        if($file->stage != 'Stage 0'){
            $fileName = $file->brand.'_'.$file->model.'_'.$file->ecu.'_'.$file->stage.'_'.$optionsMessage.'_v'.$file->files->count()+1;
        }
        else{
            $fileName = $file->brand.'_'.$file->model.'_'.$file->ecu.'_'.$optionsMessage.'_v'.$file->files->count()+1;
        }

        $newFileName = str_replace('/', '', $fileName);
        $newFileName = str_replace('\\', '', $newFileName);
        $newFileName = str_replace('#', '', $newFileName);
        $newFileName = str_replace(' ', '_', $newFileName);

        $engineerFile = new RequestFile();
        $engineerFile->request_file = $newFileName;
        $engineerFile->file_type = 'engineer_file';
        $engineerFile->tool_type = 'not_relevant';
        $engineerFile->master_tools = 'not_relevant';
        $engineerFile->file_id = $request->file_id;
        $engineerFile->engineer = true;

        // if($file->front_end_id == 2){
            $engineerFile->show_comments = 0;
        // }

        $engineerFile->save();

        if($file->subdealer_group_id){
            $attachment->move(public_path('/../../subportal/public'.$file->file_path),$newFileName);

        }
        
        else{
            if($file->front_end_id == 1)
                $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
            else
                $attachment->move(public_path('/../../tuningX/public'.$file->file_path),$newFileName);
        }
        
        if($encode){

            if($file->subdealer_group_id){

                $path = public_path('/../../subportal/public'.$file->file_path).$newFileName;
            }
            else{
                if($file->front_end_id == 1)
                    $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
                else
                    $path = public_path('/../../tuningX/public'.$file->file_path).$newFileName;
            }
            $encodingType = $request->encoding_type;

            if($file->alientech_file){ // if slot id is assigned
                $slotID = $file->alientech_file->slot_id;
                $this->alientechObj->saveGUIDandSlotIDToDownloadLaterForEncoding( $file, $path, $slotID, $encodingType, $engineerFile );
            }
        }
        
        $allEearlierReminders = EmailReminder::where('user_id', $file->user_id)
        ->where('file_id', $file->id)->get();

        foreach($allEearlierReminders as $reminderToBeDeleted){
            $reminderToBeDeleted->delete();
        }

        $schedual = Schedualer::take(1)->first();

        $reminder = new EmailReminder();
        $reminder->user_id = $file->user_id;
        $reminder->file_id = $file->id;
        $reminder->request_file_id = $engineerFile->id;
        $reminder->set_time = Carbon::now();
        $reminder->cycle = $schedual->cycle;

        $reminder->save();

        $haltEmailAndStatus = false;

        if( $engineerFile->is_kess3_slave == 1 and $engineerFile->uploaded_successfully == 0 ){
            $haltEmailAndStatus = true;
        }

        if($haltEmailAndStatus == 0){

            if($file->status == 'submitted'){

                $file->status = 'completed';
                $file->save();
            }
            
            if(!$file->response_time){

                $file->reupload_time = Carbon::now();
                $file->save();

                $file->response_time = $this->getResponseTime($file);
                $file->save();

            }

            if($file->original_file_id){
                $old = File::findOrFail($file->original_file_id);
                $old->checked_by = 'engineer';
                $file->support_status = "closed";
                $old->save();
            }

            // if($file->no_longer_auto == 0){
                $file->support_status = "closed";
                $file->checked_by = 'engineer';
                $file->save();
            // }

                $file->revisions = $file->files->count()+1;
                $file->save();
        }

        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();
    
        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
        $tunningType = $this->emailStagesAndOption($file);
        
        $html1 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html1);
        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
        $tunningType = $this->emailStagesAndOption($file);

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html2);
        $html2 = str_replace("#status", $file->status,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
        $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);
        
        if($file->front_end_id == 1){
            $subject = "ECU Tech: Engineer uploaded a file in reply.";
        }
        else{
            $subject = "TuningX: Engineer uploaded a file in reply.";
        }

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if( $haltEmailAndStatus == 0 ){

            if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

                try{
                    \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                }

            }
            if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){

                try{
                    \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));

                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                }
            }
            
            if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
                $this->sendMessage($admin->phone, $message1, $file->front_end_id);
            }

            if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
                $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
            }

            if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
                $this->sendMessage($customer->phone, $message2, $file->front_end_id);
            }

            if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
                $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
            }

        }
        
        return response('file uploaded', 200);
    }

    public function feedbackReports(){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $engineers = get_engineers();
        return view('files.feedback_reports', ['engineers' => $engineers]);
    }

    public function feedbackReportsLive(){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'feedback-report')){

            return view('files.feedback_reports_live');

        }
        else{
            return abort(404);
        }
    }

    public function reports(){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'engineers-report')){

        $engineers = get_engineers();
        return view('files.reports', ['engineers' => $engineers]);
        }
        else{
            abort(404);
        }
    }

    public function reportsEngineerLive(){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'engineers-report')){

            return view('files.report-engineers-live');
        }

        else{
            abort(404);
        }

    }

    public function getFileName($path, $file, $type){
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if($type == 'decoded'){
            $middle = '_decoded_api';
        }
        else if($type == 'encoded'){
            $middle = '_encoded_api';
        }

        if($extension != ''){
            $fileName = $file->file_attached.$middle.'.'.$extension;
        }
        else{
            $fileName = $file->file_attached.$middle;
        }

        if($type == 'decoded'){
            $file->file_attached = $fileName;
            $file->save();
        }

        $savingPath = public_path('/../../portal/public'.$file->file_path.$fileName);

        return array(
            'path' => $savingPath,
            'name' => $fileName,
        );
    }

    public function getFeedbackReport(Request $request){

        $files = $this->getReportFilesWithFeedback($request->engineer, $request->feedback);

        $html = '';
        $hasFiles = false;
        $count = 1;
        foreach($files as $file){

            $hasFiles = true;
            
            if($file->assigned){
                $assigned = $file->assigned->name;
            }
            else{
                $assigned = 'By Admin';
            }

            $options = '';
            if($file->options){
                foreach($file->options() as $option){
                    $options .= '<img class="p-l-10" alt="'.$option.'" width="33" height="33" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option;
                }
            }

            $html .= '<tr class="redirect-click" data-redirect="'.route('file', $file->id).'" role="row">';
            $html .= '<td>'. $count .'</td>';
            $html .= '<td>'.$file->brand.'</td>';
            $html .= '<td>'.$file->model.'</td>';
            $html .= '<td>'.$file->ecu.'</td>';   
            $html .= '<td><img class="p-r-5" alt="'.$file->stages.'" width="33" height="33" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'">'.$file->stages
            
            .$options.'</td>';
            if($file->type == 'happy' || $file->type == 'good'){
                $html .= '<td><span class="label label-success">'.ucfirst($file->type).'</span></td>'; 
            }
            else if ($file->type == 'ok'){
                $html .= '<td><span class="label label-info">'.ucfirst($file->type).'</span></td>'; 
            }
            else{
                if($file->type){
                    $html .= '<td><span class="label label-danger">'.ucfirst($file->type).'</span></td>'; 
                }
                else{
                    $html .= '<td><span class="label label-danger">'.'No Feedback'.'</span></td>'; 
                }
            }
            $html .= '<td>'.$assigned.'</td>';
            $html .= '</tr>';
            $count++;
        }

        return response()->json(['html' =>$html, 'has_files' => $hasFiles ], 200);
    }

    public function getEngineersFiles(Request $request){

        $files = $this->getReportFiles($request->engineer, $request->start, $request->end);

        $html = '';
        $hasFiles = false;
        $count = 1;
        foreach($files as $file){

            $hasFiles = true;
            
            if($file->assigned){
                $assigned = $file->assigned->name;
            }
            else{
                $assigned = 'By Admin';
            }

            $options = '';
            if($file->options){
                foreach($file->options() as $option){
                    $options .= '<img class="p-l-10" alt="'.$option.'" width="33" height="33" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option;
                }
            }

            $html .= '<tr class="redirect-click" data-redirect="'.route('file', $file->id).'" role="row">';
            $html .= '<td>'. $count .'</td>';
            $html .= '<td>'.$file->brand .$file->engine .' '. $file->vehicle()->TORQUE_standard .' '.'</td>';
            $html .= '<td>'.$assigned.'</td>';    
            $html .= '<td><img class="p-r-5" alt="'.$file->stages.'" width="33" height="33" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'">'.$file->stages
            
            .$options.'</td>';
            $html .= '<td>'.\Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans().'</td>';
            $html .= '<td>'.\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i: A').'</td>';
            $html .= '</tr>';
            $count++;
        }

        return response()->json(['html' =>$html, 'has_files' => $hasFiles ], 200);
    }

    public function getEngineersReport(Request $request) {

        $engineer = $request->engineer;
        $start = $request->start;
        $end = $request->end;
        $files = $this->getReportFiles($engineer, $start, $end);
        $pdf = PDF::loadView('files.pdf', [ 'files' => $files, 'end' => $end, 'start' => $start, 'engineer' => $engineer ]);
        if($engineer == 'all_engineers'){
            return $pdf->download('all_engineers_report.pdf');
        }
        return $pdf->download(User::findOrFail($engineer)->name."_report.pdf");
    }

    public function getReportFilesWithFeedback($engineer, $feedback){

        $filesObject = File::Orderby('files.created_at', 'desc')->where('is_credited', 1)->select('*')->addSelect('files.id as id');
        $filesObject = $filesObject->join('request_files', 'files.id', '=' , 'request_files.file_id');

        if($engineer != 'all_engineers'){
            $filesObject = $filesObject->where('assigned_to', $engineer);
        }

        if($feedback == 'all_types'){
            $filesObject = $filesObject->leftjoin('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id');
        }
        else if($feedback == 'not_provided'){
            $filesObject = $filesObject->join('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id', 'left outer');
            $filesObject = $filesObject->whereNull('file_feedback.type');
        }

        else{
            $filesObject = $filesObject->join('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id', 'left outer');
            $filesObject = $filesObject->where('file_feedback.type', $feedback);
        }

        return $filesObject->get();
    }

    public function getReportFiles($engineer, $start, $end){

        $filesObject = File::whereNotNull('response_time')->where('is_credited', 1);

        if($engineer != 'all_engineers'){
            $filesObject = $filesObject->where('assigned_to', $engineer);
        }

        if($start){
            $date = str_replace('/', '-', $start);
            $startDate = date('Y-m-d', strtotime($date));
            $filesObject = $filesObject->whereDate('created_at', '>=' , $startDate);
        }

        if($end){
            $date = str_replace('/', '-', $end);
            $endDate = date('Y-m-d', strtotime($date));
            $filesObject = $filesObject->whereDate('created_at', '<=' , $endDate);
        }
        
        return $filesObject->get();
    }

    public function getComments($file){

        if($file->automatic){
            return null;
        }
        // $commentObj = Comment::where('engine', $file->engine);
        $commentObj = Comment::where('make', $file->brand);

        // if($file->brand){
        //     $commentObj->where('make', $file->brand);
        // }

        // if($file->Model){
        //     $commentObj->where('model', $file->model);
        // }

        if($file->ecu){
            $commentObj->where('ecu', $file->ecu);
        }

        // if($file->generation){
        //     $commentObj->where('generation', $file->generation);
        // }

        $commentObj->whereNull('subdealer_group_id');
        
        return $commentObj->get();
    }

    public function getResponseTimeAuto($file){
        
        $fileAssignmentDateTime = Carbon::parse($file->assignment_time);
        $carbonUploadDateTime = Carbon::parse($file->reupload_time);

        $responseTime = $carbonUploadDateTime->diffInSeconds( $fileAssignmentDateTime );

        return $responseTime;
    }

    public function getResponseTime($file){
        
        $fileAssignmentDateTime = Carbon::parse($file->assignment_time);
        $carbonUploadDateTime = Carbon::parse($file->reupload_time);

        $feed = NewsFeed::findOrFail(1);
        
        $dailyActivationTimeCarbon = Carbon::parse($feed->daily_activation_time);
        $dailyDeactivationTimeCarbon = Carbon::parse($feed->daily_deactivation_time);

        $timeDiff = $dailyDeactivationTimeCarbon->diffInSeconds($dailyActivationTimeCarbon);

        $fileAssignmentDay = $fileAssignmentDateTime->format('Y-m-d');
        $fileUploadDay = $carbonUploadDateTime->format('Y-m-d');

        $assignmentBackToDate =  Carbon::parse( $fileAssignmentDay );
        $uploadBackToDate =  Carbon::parse( $fileUploadDay );

        $daysDiff =  $uploadBackToDate->diffInDays($assignmentBackToDate);
        
        $fileAssignmentDayWorkHourStart = Carbon::parse( $fileAssignmentDay.' '.$feed->daily_activation_time );
        $fileAssignmentDayWorkHourEnd = Carbon::parse( $fileAssignmentDay.' '.$feed->daily_deactivation_time );
        
        $fileUploadDayWorkHourStart = Carbon::parse( $fileUploadDay.' '.$feed->daily_activation_time );
        $fileUploadDayWorkHourEnd = Carbon::parse( $fileUploadDay.' '.$feed->daily_deactivation_time );

        $fileAssignmentDateAndTime = Carbon::parse($file->assignment_time);

        $fileAssignmentDateTime = Carbon::parse($file->assignment_time);
        $carbonUploadDateTime = Carbon::parse($file->reupload_time);

        $totalTimeWihoutSubtraction = ($timeDiff * $daysDiff);
        $totalTimeWihoutSubtraction += $timeDiff;

        $responseTime = 0;

        $differnceOfSecondsForFileAssingmentDay = 0;

        if( $fileAssignmentDateTime->between($fileAssignmentDayWorkHourStart, $fileAssignmentDayWorkHourEnd, true) ) {
            
            $differnceOfSecondsForFileAssingmentDay = $fileAssignmentDateTime->diffInSeconds( $fileAssignmentDayWorkHourStart );
            
        }
        else{
            
            if($fileAssignmentDateTime->greaterThan($fileAssignmentDayWorkHourEnd)){

                $totalTimeWihoutSubtraction -= $timeDiff;
            }
        }

        $responseTime = $totalTimeWihoutSubtraction - $differnceOfSecondsForFileAssingmentDay;
        
        $differnceOfSecondsForFileUploadDay = 0;
        
        if( $carbonUploadDateTime->between($fileUploadDayWorkHourStart, $fileUploadDayWorkHourEnd, true) ) {
            
            $differnceOfSecondsForFileUploadDay = $carbonUploadDateTime->diffInSeconds( $fileUploadDayWorkHourEnd );
            
        }
        else{
            if($fileUploadDayWorkHourStart->greaterThan($carbonUploadDateTime)){
                $responseTime -= $timeDiff;
            }
        }

        $responseTime = $responseTime - $differnceOfSecondsForFileUploadDay;

        return $responseTime;
    }

     /**
     * Show the file.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files')){

        if(Auth::user()->is_admin()){

            $file = File::where('id',$id)->where(function($q){

                $q->where('type', 'master');
                

            })->where('is_credited', 1)
            ->whereNull('original_file_id')
            ->orWhere(function($q){
                
                $q->where('type', 'subdealer');
                $q->whereNotNull('assigned_from');
                
            })->where('id',$id)
            ->where('is_credited', 1)
            ->whereNull('original_file_id')
            ->first();

            
        }
        else{

            if(get_engineers_permission(Auth::user()->id, 'show-all-files')){

                $file = File::where('id',$id)->where(function($q){

                    $q->where('type', 'master');
                    
    
                })->where('is_credited', 1)
                ->whereNull('original_file_id')
                ->orWhere(function($q){
                    
                    $q->where('type', 'subdealer');
                    $q->whereNotNull('assigned_from');
                    
                })->where('id',$id)
                ->whereNull('original_file_id')
                ->where('is_credited', 1)->first();

            }
            else{

                $file = File::where('id',$id)->where(function($q){

                    $q->where('type', 'master');
                    
    
                })
                ->where('id',$id)
                ->where('is_credited', 1)
                ->whereNull('original_file_id')
                ->where('assigned_to', Auth::user()->id)->first();

            }

        }

        if(!$file){
            abort(404);
        }
        
        if($file->checked_by == 'customer'){
            $file->checked_by = 'seen';
            $file->save();
        }
        
        foreach($file->new_requests as $new){
            if($new->checked_by == 'customer'){
                $new->checked_by = 'seen';
                $new->save();
            }
        }
        
        $vehicle = Vehicle::where('Make', $file->brand)
        ->where('Model', $file->model)
        ->where('Generation', $file->version)
        ->where('Engine', $file->engine)
        ->first();
        
        $engineers = get_engineers();
        
        // $withoutTypeArray = $file->files->toArray();
        // $unsortedTimelineObjects = [];

        // foreach($withoutTypeArray as $r) {
        //     $fileReq = RequestFile::findOrFail($r['id']);
        //     if($fileReq->file_feedback){
        //         $r['type'] = $fileReq->file_feedback->type;
        //     }
        //     $unsortedTimelineObjects []= $r;
        // } 
        
        // $createdTimes = [];

        // foreach($file->files->toArray() as $t) {
        //     $createdTimes []= $t['created_at'];
        // } 
    
        // foreach($file->engineer_file_notes->toArray() as $a) {
        //     $unsortedTimelineObjects []= $a;
        //     $createdTimes []= $a['created_at'];
        // }   

        // foreach($file->file_internel_events->toArray() as $b) {
        //     $unsortedTimelineObjects []= $b;
        //     $createdTimes []= $b['created_at'];
        // } 

        // foreach($file->file_urls->toArray() as $b) {
        //     $unsortedTimelineObjects []= $b;
        //     $createdTimes []= $b['created_at'];
        // } 

        // array_multisort($createdTimes, SORT_ASC, $unsortedTimelineObjects);

        if($file->ecu){
            $comments = $this->getComments($file);
        }
        else{
            $comments = null;
        }

        $showComments = false;

        $selectedOptions = [];

        foreach($file->options_services as $selected){
            $selectedOptions []= $selected->service_id;
        }

        if($comments){
            foreach($comments as $comment){
                if( in_array( $comment->service_id, $selectedOptions) ){
                    $showComments = true;
                }
            }
        }

        $options = Service::where('type', 'option')
        ->whereNull('subdealer_group_id')
        ->where('active', 1)
        ->orWhere('tuningx_active', 1)->whereNull('subdealer_group_id')->where('type', 'option')->get();

        $stages = Service::where('type', 'tunning')
        ->whereNull('subdealer_group_id')
        ->where('active', 1)
        ->orWhere('tuningx_active', 1)->whereNull('subdealer_group_id')->where('type', 'tunning')->get();
        
        $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();

        if(env('APP_ENV') == 'live'){
            return view('files.show', ['selectedOptions' => $selectedOptions, 'showComments' => $showComments,  'stages' => $stages , 'options' => $options, 'kess3Label' => $kess3Label, 'vehicle' => $vehicle,'file' => $file, 'engineers' => $engineers, 'comments' => $comments ]);
        }
        else{
            return view('files.show_backup', ['selectedOptions' => $selectedOptions, 'showComments' => $showComments, 'stages' => $stages , 'options' => $options, 'kess3Label' => $kess3Label, 'vehicle' => $vehicle,'file' => $file, 'engineers' => $engineers, 'comments' => $comments ]);
        }

        }
        else{
            abort(404);
        }
    
    }

    public function saveMoreFiles($id, $alientechFileID){

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        $token = Key::where('key', 'alientech_access_token')->first()->value;

        $file = File::findOrFail($id);
        // $alientechGUID = AlientechFile::where('purpose', 'download_encoded')->where('key', 'guid')->where('file_id', $id)->first()->value;
        $alientechObj = AlientechFile::findOrFail($alientechFileID);
        $alientechGUID = $alientechObj->value;
        
        $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$alientechGUID;

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $token,
        ];
  
        $response = Http::withHeaders($headers)->get($getsyncOpURL);
        $responseBody = json_decode($response->getBody(), true);

        if(!isset($responseBody['result']['name'])){
            $this->makeLogEntry($file->id, 'error', 'line 1998; file is not uploaded successfully.');
        }
        else{

        $var = $responseBody['result']['name'];

        $fileName = substr($var, strrpos($var, '/') + 1);
        $fileName = str_replace('#', '', $fileName);
        $fileName = $fileName.'_'.$file->id;
        
        $slotGuid = $responseBody['slotGUID'];
        
        $result = $responseBody['result'];

        if( isset($result['encodedFileURL']) ){
            
            $url = $result['encodedFileURL'];

            $headers = [
                'X-Alientech-ReCodAPI-LLC' => $token,
            ];
    
            $response = Http::withHeaders($headers)->get($url);
            $responseBody = json_decode($response->getBody(), true);

            // $base64_string = $responseBody['data'];
            $base64Data = $responseBody['data'];
            $contents   = base64_decode($base64Data);

            // specify the path and filename for the downloaded file
            $filepath = $responseBody['name'];

            $pathAndNameArrayEncoded = $this->getFileName($filepath, $file, 'encoded');
            
            // save the decoded string to a file
            $flag = file_put_contents($pathAndNameArrayEncoded['path'], $contents);

            $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

            $headers = [
                'X-Alientech-ReCodAPI-LLC' => $token,
            ];

            $response = Http::withHeaders($headers)->post($url, []);

            $extension = pathinfo($responseBody['name'], PATHINFO_EXTENSION);
            
            $obj = new AlientechFile();
            $obj->key = $extension;
            $obj->value = $pathAndNameArrayEncoded['name'];
            $obj->purpose = "encoded";
            $obj->file_id = $file->id;
            $obj->save();
        
            //// this is repetitive code.

            $engineerFile = new RequestFile();
            $engineerFile->request_file = $pathAndNameArrayEncoded['name'];
            $engineerFile->file_type = 'engineer_file';
            $engineerFile->tool_type = 'not_relevant';
            $engineerFile->master_tools = 'not_relevant';
            $engineerFile->file_id = $file->id;
            $engineerFile->engineer = true;
            $engineerFile->save();

            $allEearlierReminders = EmailReminder::where('user_id', $file->user_id)
            ->where('file_id', $file->id)->get();

            foreach($allEearlierReminders as $reminderToBeDeleted){
                $reminderToBeDeleted->delete();
            }

            $schedual = Schedualer::take(1)->first();

            $reminder = new EmailReminder();
            $reminder->user_id = $file->user_id;
            $reminder->file_id = $file->id;
            $reminder->request_file_id = $engineerFile->id;
            $reminder->set_time = Carbon::now();
            $reminder->cycle = $schedual->cycle;

            $reminder->save();

            if($file->status == 'submitted'){
                $file->status = 'completed';
                $file->save();
            }
            
            if(!$file->response_time){

                $file->reupload_time = Carbon::now();
                $file->save();

                $file->response_time = $this->getResponseTime($file);
                $file->save();

            }

            if($file->original_file_id){
                $old = File::findOrFail($file->original_file_id);
                $old->checked_by = 'engineer';
                $file->support_status = "closed";
                $old->save();
            }

                $file->support_status = "closed";
                $file->checked_by = 'engineer';
                $file->save();

            $customer = User::findOrFail($file->user_id);
            $admin = get_admin();
        
            // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
            $template = EmailTemplate::where('slug', 'file-up-from-eng')
            ->where('front_end_id', $file->front_end_id)
            ->first();

            $html1 = $template->html;

            $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
            $html1 = str_replace("#customer_name", $customer->name ,$html1);
            $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
            
            $tunningType = $this->emailStagesAndOption($file);
            
            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url", route('file', $file->id),$html1);

            $html2 = $template->html;

            $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
            $html2 = str_replace("#customer_name", $file->name ,$html2);
            $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
            
            $tunningType = $this->emailStagesAndOption($file);

            $html2 = str_replace("#tuning_type", $tunningType,$html2);
            $html2 = str_replace("#status", $file->status,$html2);

            if($file->front_end_id == 1){
                $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
            }
            else{
                $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
            }

            $optionsMessage = "";
            if($file->options){
                foreach($file->options() as $option) {
                    $optionsMessage .= ",".$option." ";
                }
            }

            // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
            $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')
            ->where('front_end_id', $file->front_end_id)->first();

            $message = $messageTemplate->text;

            $message1 = str_replace("#customer", $customer->name ,$message);
            $message2 = str_replace("#customer", $file->name ,$message);
            
            if($file->front_end_id == 1){
                $subject = "ECU Tech: Engineer uploaded a file in reply.";
            }
            else{
                $subject = "TuningX: Engineer uploaded a file in reply.";
            }

            if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

                try{
                    \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                }
            }
            if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){
                
                try{
                    \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));

                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                }
            }
            
            if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
                $this->sendMessage($admin->phone, $message1, $file->front_end_id);
            }

            if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
                $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
            }

            if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
                $this->sendMessage($customer->phone, $message2, $file->front_end_id);
            }

            if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
                $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
            }

            }
        } 
    }

    public function emailStagesAndOption($file){

        if( \App\Models\Service::FindOrFail( $file->stage_services->service_id ) ){
            $tunningType = '<img alt=".'.\App\Models\Service::FindOrFail( $file->stage_services->service_id )->name.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::FindOrFail( $file->stage_services->service_id )->icon .'">';
            $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::FindOrFail( $file->stage_services->service_id)->name.'</span>';
        }
        
        if($file->options_services){

            foreach($file->options_services as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.\App\Models\Service::FindOrFail( $option->service_id )->name .'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::FindOrFail( $option->service_id )->icon.'">';
                $tunningType .=  \App\Models\Service::FindOrFail( $option->service_id )->name;
                $tunningType .= '</div>';
            }
        }

        return $tunningType;
    }
}
