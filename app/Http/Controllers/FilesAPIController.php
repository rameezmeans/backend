<?php

namespace App\Http\Controllers;

use App\Models\AlientechFile;
use App\Models\Credit;
use App\Models\DownloadLuaFile;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\FileReplySoftwareService;
use App\Models\FileService;
use App\Models\Key;
use App\Models\MessageTemplate;
use App\Models\Price;
use App\Models\ReminderManager;
use App\Models\RequestFile;
use App\Models\Service;
use App\Models\TemporaryFile;
use App\Models\Tool;
use App\Models\TunnedFile;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Exception;
use Twilio\Rest\Client;

class FilesAPIController extends Controller
{

    public function submitFile( Request $request ) {

        $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();

        $manager = (new ReminderManagerController())->getManager();

        $tool = Tool::findOrFail($request->tool_id);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        
        $fileName = str_replace('#', '_', $fileName);
        $fileName = str_replace('.', '_', $fileName);
        $fileName = str_replace(' ', '_', $fileName);

        $fileName = preg_replace('/[^a-z0-9_ ]/i', '', $fileName); 
        
        $file = new File();
        $file->tool_id = $tool->id;
        $file->tool_type = $tool->type;
        $file->file_attached = $fileName;
        $file->name = $request->name;
        $file->email = $request->email;
        $file->phone = $request->phone;
        $file->model_year = $request->model_year;
        $file->file_type = $request->file_type;
        $file->license_plate = $request->license_plate;
        $file->vin_number = $request->vin_number;
        $file->brand = $request->brand;
        $file->model = $request->model;
        $file->engine = $request->engine;
        $file->version = $request->version;
        $file->ecu = $request->ecu;
        $file->gear_box = $request->gear_box;
        $file->additional_comments = $request->additional_comments;
        $file->checking_status == 'unchecked';

        $file->dtc_off_comments = $request->dtc_off_comments;
        $file->vmax_off_comments = $request->vmax_off_comments;

        $stage = Service::FindOrFail($request->stage);

        $price = Price::where('label', 'credit_price')->whereNull('subdealer_group_id')->first()->value;

        $servieCredits = 0;

        $customer = get_subdealer_user($request->subdealer_group_id);

        $head =  get_head();

        $creditsInAccount = $customer->credits->sum('credits');

        if($creditsInAccount >= $request->credits){

            $credit = new Credit();

            $credit->credits = -1*$servieCredits;
            $credit->price_payed = $servieCredits*$price;
            $credit->invoice_id = 'INV-'.$customer->stripe_payment_account()->prefix.mt_rand(1000,9999);
            $credit->user_id = $customer->id;
            $credit->save();

            if($customer->test == 1){
                $credit->test = 1;
            }

            $file->credit_id = $credit->id;
            $file->checked_by = "customer";
            $file->user_id = $customer->id;
            $file->username =  $customer->name;
            $file->assigned_to =  $head->id; // assigned to Nick

            if(File::where('credit_id', $credit->id)->first() === NULL){
                
                $file->credit_id = $credit->id;
                
                $file->assignment_time = Carbon::now();
                
                $modelToAdd = str_replace( '/', '', $file->model );
                $directoryToMake = public_path('uploads'.'/'.$file->brand.'/'.$modelToAdd.'/'.$file->id.'/');

                if (!file_exists($directoryToMake)) {
                    $oldmask = umask(000);
                    mkdir( $directoryToMake , 0777, true);
                    umask($oldmask);        
                }
                
                $file->file_path = '/uploads/'.$file->brand.'/'.$modelToAdd.'/'.$file->id.'/';
                $file->credits = 0;
                $file->save();

                $fileService = new FileService();
                $fileService->type = 'stage';
                $fileService->credits = $stage->credits;
                $servieCredits += $stage->credits;
                $fileService->service_id = $stage->id;
                $fileService->file_id = $file->id;
                $fileService->save();

                    if( $request->options && sizeof($request->options) > 0 ){
                        foreach($request->options as $option){

                            $optionService = Service::FindOrFail($option);
                            $fileOption = new FileService();
                            $fileOption->type = 'option';
                            $fileOption->credits = $optionService->credits;
                            $servieCredits += $optionService->credits;
                            $fileOption->service_id = $optionService->id;
                            $fileOption->file_id = $file->id;
                            $fileOption->save();
                        } 
                    }

                    $totalCredits = 0;

                    if($request->subdealer_group_id){
            
                    $stage = Service::findOrFail($file->stage_services->service_id);

                    if($stage->subdealerGroup){
                        $totalCredits += $stage->subdealerGroup->subdealer_credits;
                    }
                    else{
                        $totalCredits += $stage->credits;
                    }

                    if( $file->options_services && sizeof($file->options_services) > 0 ){
                        foreach($file->options_services as $option){
                            $optionService = Service::FindOrFail($option->service_id);
                            if($optionService->subdealerGroup){
                                $totalCredits += $optionService->subdealerGroup->master_credits;
                            }
                            else{
                                $totalCredits += $optionService->credits;
                            }
                        }
                    }
                }

                $file->credits = $servieCredits;
                $file->subdealer_credits = $totalCredits;
                $file->save();

                $credit->file_id = $file->id;
                $credit->save();
                
            }
            
            $file->stage = Service::findOrFail($file->stage_services->service_id)->name;
            $file->is_credited = 1; // finally is_credited now ... 
            $file->save();

            $count = File::where('checked_by', 'customer')->where('is_credited', 1)->count();
            // send to user using pusher
            Chatify::push("private-chatify.".env('LIVE_CHAT_ID'), 'file-uploaded', [
                'count' => $count
            ]);

            $admin = get_admin();
            
            $template = EmailTemplate::findOrFail(1);

            $html1 = $template->html;

            $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
            $html1 = str_replace("#customer_name", $customer->name ,$html1);
            $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
            
            $tunningType = $this->emailStagesAndOption($file);
            
            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url",env('BACKEND_URL').'file/'.$file->id,$html1);

            $messageTemplate = MessageTemplate::findOrFail(1);
            
            $message = $messageTemplate->text;
            $message = str_replace("#customer", $customer->name,$message);
            
            $subject = "ECU Tech: Task Assigned!";

            if($manager['eng_assign_eng_email']){
                \Mail::to($head->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
            }
            if($manager['eng_assign_eng_sms']){
                $this->sendMessage($head->phone, $message);
            }
            
            $template = EmailTemplate::findOrFail(2);

            $html = $template->html;

            $uploader = User::findOrFail($file->user_id);

            $html = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html);
            $html = str_replace("#customer_name", $uploader->name ,$html);
            $html = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html);
            
            $tunningType = $this->emailStagesAndOption($file);
            
            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url",env('BACKEND_URL').'file/'.$file->id,$html1);

            $messageTemplate = MessageTemplate::findOrFail(2);

            $message = $messageTemplate->text;
            
            $message = str_replace("#customer", $uploader->name,$message);
            
            $subject = "ECU Tech: File Uploaded!";

            if($manager['file_upload_admin_email']){
                \Mail::to($admin->email)->send(new \App\Mail\AllMails(['html' => $html, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
            }

            if($manager['file_upload_admin_sms']){
                $this->sendMessage($admin->phone, $message);
            }

            return response()->json('file submitted.');

        }

    }

    public function brands( Request $request ){
        $brandsObjects = Vehicle::OrderBy('make', 'asc')->select('make')->distinct()->get();
        $brands = [];
        foreach($brandsObjects as $b){
            if($b->make != '')
            $brands []= $b->make;
        }

        return response()->json($brands);
    }

    public function models(Request $request){
        $modelsObjects = Vehicle::OrderBy('model', 'asc')->select('model')->whereNotNull('model')->distinct()->where('make', '=', $request->brand)->get();

        $models = [];
        foreach($modelsObjects as $m){
            if($m->model != '')
            $models []= $m->model;
        }

        return response()->json($models);
    }

    public function versions(Request $request){

        $versionsObjects = Vehicle::OrderBy('generation', 'asc')->whereNotNull('generation')->select('generation')->distinct()
        ->where('Make', '=', $request->brand)
        ->where('Model', '=', $request->model)
        ->get();

        $versions = [];
        foreach($versionsObjects as $v){
            if($v->generation != '')
            $versions []= $v->generation;   
        }  

        return response()->json($versions);
    }

    public function engines(Request $request){

        $enginesObjects = Vehicle::OrderBy('engine', 'asc')
        ->whereNotNull('engine')->select('engine')->distinct()
        ->where('Make', '=', $request->brand)
        ->where('Model', '=', $request->model)
        ->where('Generation', '=', $request->version)
        ->get();

        $engines = [];
        foreach($enginesObjects as $e){
            if($e->engine != '')
            $engines []= $e->engine;   
        }   

        return response()->json($engines);
    }

    public function ecus(Request $request){

        $ecus = Vehicle::OrderBy('Engine_ECU', 'asc')->whereNotNull('Engine_ECU')->select('Engine_ECU')->distinct()
        ->where('Make', '=', $request->brand)
        ->where('Model', '=', $request->model)
        ->where('Generation', '=', $request->version)
        ->where('Engine', '=', $request->engine)
        ->get();

        $ecusArray = [];

        foreach($ecus as $e){
            $temp = explode(' / ', $e->Engine_ECU);
            $ecusArray = array_merge($ecusArray,$temp);
        }

        return response()->json($ecusArray);
    }

    public function subdealersFiles(Request $request){
        $files = File::where('subdealer_group_id', $request->subdealer_group_id)->get();
        return response()->json($files);
    }

    public function subdealersCredits(Request $request){
        $subdlear = get_subdealer_user($request->subdealer_group_id);
        $credits = Credit::where('user_id', $subdlear->id)->get();
        return response()->json($credits);
    }

    public function addSubdealersCredits(Request $request){

        $subdealer = get_subdealer_user($request->subdealer_group_id);

        $credit = new Credit();
        $credit->credits = $request->credits;
        $credit->user_id = $subdealer->id;
        $credit->stripe_id = $request->stripe_id;
        $credit->price_payed = $request->price_payed;
        $credit->invoice_id = 'Remote-'.mt_rand(1000,9999);
        $credit->save();

        return response()->json('credits added.');
    }

    public function subtractSubdealersCredits(Request $request){

        $subdealer = get_subdealer_user($request->subdealer_group_id);

        $credit = new Credit();
        $credit->credits = -1*($request->credits);
        $credit->user_id = $subdealer->id;
        $credit->stripe_id = $request->stripe_id;
        $credit->price_payed = 0;
        $credit->invoice_id = 'Remote-'.mt_rand(1000,9999);
        $credit->save();

        return response()->json('credits subtracted.');
    }

    public function subdealersTotalCredits(Request $request){
        $subdlear = get_subdealer_user($request->subdealer_group_id);
        $count = Credit::where('user_id', $subdlear->id)->sum('credits');
        return response()->json($count);
    }
    
    public function tools(){

        $tools = Tool::all();
        return response()->json($tools);
    }

    public function files($frontendID){

        $files = File::where('checking_status', 'unchecked')
        // ->where('type', 'master') // because slave files are supposed to decoded
        ->whereNull('subdealer_group_id')
        // ->where('front_end_id', $frontendID)
        ->get();

        $arrFiles = [];
        $temp = [];

        foreach($files as $file){

            $stage = NULL;

            if($file->custom_stage != NULL){
                $stage = \App\Models\Service::FindOrFail( $file->custom_stage )->label;
            }
            else{
                if($file->stage_services){
                    $stage = \App\Models\Service::FindOrFail( $file->stage_services->service_id )->label;
                }
            }

            $options = NULL;

            if($file->custom_options === NULL){

                if($file->options_services){
                    foreach($file->options_services as $o){
                        $options .= \App\Models\Service::FindOrFail( $o->service_id )->label.',';
                    }
                    $options = rtrim($options, ",");
                }
                else{
                    $options = null;
                }
            }
            else{

                if(!empty($file->custom_options)){
                    $customOptions = explode(',', $file->custom_options);
                    foreach($customOptions as $op){
                        if($op != 0){
                            $options .= \App\Models\Service::FindOrFail( $op )->label.',';
                        }
                    }
                    $options = rtrim($options, ",");
                }
                else{
                    
                    $options = "";
                }
            }
            
            if($stage != NULL){
                
                $temp['file_id'] = $file->id;
                $temp['inner_search'] = $file->inner_search;
                $temp['frontend'] = $file->front_end_id;

                $temp['stage'] = $stage;
                $temp['temporary_file_id'] = 0;
                $temp['options'] = $options;

                if($file->decoded_files->count() > 0){
                    if($file->front_end_id == 1){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->final_decoded_file();
                    }
                    else if($file->front_end_id == 3){
                    
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->final_decoded_file();
                    }
                    else if($file->front_end_id == 2){
                        // $temp['location'] = 'https://tuningx.test'.$file->file_path.$this->getFileToShowToLUA($file);
                        $temp['location'] = 'https://portal.tuning-x.com'.$file->file_path.$file->final_decoded_file();
                    }
                }
                else{

                    if($file->front_end_id == 1){
                    
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->file_attached;
                    }
                    else if($file->front_end_id == 3){
                    
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->file_attached;
                    }
                    else if($file->front_end_id == 2){
                        $temp['location'] = 'https://portal.tuning-x.com'.$file->file_path.$file->file_attached;
                    }

                }

                $temp['checked'] = $file->checking_status;

            }
            
            $arrFiles []= $temp;
        }

        return response()->json($arrFiles);
    }

    public function filesversions(){
    
        $files = File::where('checking_status_versions', '0')
        ->get();
    
        $arrFiles = [];
    
        foreach($files as $file){
    
            if($file->stage_services){
                $stage = \App\Models\Service::FindOrFail( $file->stage_services->service_id )->label;
            }
            else{
                $stage = $file->stages;
            }
    
            $options = NULL;
    
            if($file->custom_options == NULL){
    
                if($file->options_services){
                    foreach($file->options_services as $o){
                        $options .= \App\Models\Service::FindOrFail( $o->service_id )->label.',';
                    }
                    $options = rtrim($options, ",");
                }
                else{
                    $options = $file->options;
                }
            }
            else{
                if($file->custom_options !== ''){
                    $customOptions = explode(',', $file->custom_options);
                    foreach($customOptions as $op){
                        if($op != 0){
                            $options .= \App\Models\Service::FindOrFail( $op )->label.',';
                        }
                    }
                    $options = rtrim($options, ",");
                }
            }
                
                $temp = [];
                $temp['file_id'] = $file->id;
                $temp['temporary_file_id'] = 0;
                $temp['stage'] = $stage;
                $temp['options'] = $options;
    
                if($file->decoded_files->count() > 0){
                    if($file->front_end_id == 1){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->final_decoded_file();
                    }

                    else if($file->front_end_id == 2){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->final_decoded_file();
                    }
                    
                    else if($file->front_end_id == 3){
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->final_decoded_file();
                    }                    
                    
                }
                else{
                    if($file->front_end_id == 1 ){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->file_attached;
                    }
                    else if($file->front_end_id == 2){
                        $temp['location'] = 'https://portal.tuning-x.com'.$file->file_path.$file->file_attached;
                    }
                    else if($file->front_end_id == 3){
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->file_attached;
                    }
                }
    
                $temp['checked'] = $file->checking_status;
                $temp['checked-versions'] = $file->checking_status_versions;
            
            $arrFiles []= $temp;
        }

        $temporaryFiles = TemporaryFile::join('users', 'users.id', '=', 'temporary_files.user_id')
        ->where('users.test_features','=', 1)
        ->where('temporary_files.checking_status_versions','=', 0)
        ->select('*', 'temporary_files.id as id')->get();

        foreach($temporaryFiles as $file){
            $temp = [];
            $temp['file_id'] = 0;
            $temp['temporary_file_id'] = $file->id;
            $temp['location'] = 'https://portal.ecutech.gr/uploads/'.$file->file_attached;
            $temp['checked'] = 'unchecked';
            $temp['stage'] = null;
            $temp['options'] = null;
            $temp['checked-versions'] = 0;

            $arrFiles []= $temp;
        }
    
        return response()->json($arrFiles);
    }

    public function getFileToShowToLUA($file){

        $name = "";

        foreach($file->decoded_files as $d){
            if($d->extension != '')
                $name = $d->name.'.'.$d->extension;
            else
                $name = $d->name;
        }

        return $name;
    }

    public function setCheckingStatus(Request $request){

        $file = File::findOrFail($request->file_id);

        $chatID = env('CHAT_USER_ID');

        if($file->checking_status == 'unchecked'){

            $file->checking_status = $request->checking_status;
            $flag = $file->save();

            if( $request->tuned_file && $request->tuned_file != '' && isset($request->tuned_file) ){

                $optionsMessage = '';

                if($file->options){
                    foreach($file->options()->get() as $option) {
                        $optionName = Service::findOrFail($option->service_id)->name;
                        $optionsMessage .= "".$optionName."_";
                    }
                }

                // $fileToSave = $request->tuned_file;

                $fileToSave = $file->brand.'_'.$file->model.'_'.$file->ecu.'_'.$file->stage.'_'.$optionsMessage.'_v'.$file->files->count()+1;

                $fileToSave = str_replace('/', '', $fileToSave);
                $fileToSave = str_replace('\\', '', $fileToSave);
                $fileToSave = str_replace('#', '', $fileToSave);
                $fileToSave = str_replace(' ', '_', $fileToSave);

                if($file->inner_search == 1){
                    $engineerFile = new DownloadLuaFile();
                    $engineerFile->request_file = $fileToSave;
                    $engineerFile->file_type = 'engineer_file';
                    $engineerFile->tool_type = 'not_relevant';
                    $engineerFile->master_tools = 'not_relevant';
                    $engineerFile->lua_command = $request->lua_command;
                    $engineerFile->file_id = $file->id;
                    $engineerFile->engineer = true;
                    $engineerFile->save();
                }
                else{
                    
                    $engineerFile = new RequestFile();
                    $engineerFile->request_file = $fileToSave;
                    $engineerFile->file_type = 'engineer_file';
                    $engineerFile->tool_type = 'not_relevant';
                    $engineerFile->master_tools = 'not_relevant';
                    $engineerFile->lua_command = $request->lua_command;
                    $engineerFile->file_id = $file->id;
                    $engineerFile->engineer = true;
                    $engineerFile->save();
                

                    if($file->stage_services->service_id != 1){
                        $newRecord = new FileReplySoftwareService();
                        $newRecord->file_id = $file->id;
                        $newRecord->service_id = $file->stage_services->service_id;
                        $newRecord->software_id = 9;
                        $newRecord->reply_id = $engineerFile->id;
                        $newRecord->save();
                    }
                    
                    if(!$file->options_services()->get()->isEmpty()){

                        foreach($file->options_services()->get() as $option){

                            $newRecord = new FileReplySoftwareService();
                            $newRecord->file_id = $file->id;
                            $newRecord->service_id = $option->service_id;
                            $newRecord->software_id = 9;
                            $newRecord->reply_id = $engineerFile->id;
                            $newRecord->save();
                
                        }

                    }
                }

                    $middleName = $file->id;
                    $middleName .= date("dmy");
                    
                    foreach($file->softwares as $s){
                        if($s->service_id != 1){
                            if($s->reply_id == $engineerFile->id){
                                $middleName .= $s->service_id.$s->software_id;
                            }
                        }
                    }

                //     $fileName = $file->brand.'_'.$file->model.'_'.$middleName.'_v'.$file->files->count()+1;

                //     $fileToSave = str_replace('/', '', $fileToSave);
                // $fileToSave = str_replace('\\', '', $fileToSave);
                // $fileToSave = str_replace('#', '', $fileToSave);
                // $fileToSave = str_replace(' ', '_', $fileToSave);

                    $engineerFile->request_file = $fileToSave;
                    $engineerFile->save();

                    $tunnedFile = new TunnedFile();
                    $tunnedFile->file = $request->tuned_file;
                    $tunnedFile->file_id = $file->id;
                    $tunnedFile->save();


                    if($file->front_end_id == 1){

                        copy( public_path('/../../portal/public/uploads/filesready'.'/'.$request->tuned_file), 
                        public_path('/../../portal/public'.$file->file_path.$fileToSave) );

                        unlink( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file );

                        $path = public_path('/../../portal/public'.$file->file_path.$fileToSave);
                
                    }

                    else if($file->front_end_id == 3){

                        // copy( public_path('/../../e-tuningfiles/public/uploads/filesready'.'/'.$request->tuned_file), 
                        // public_path('/../../e-tuningfiles/public'.$file->file_path.$fileName) );

                        // unlink( public_path('/../../e-tuningfiles/public/uploads/filesready').'/'.$file->tunned_files->file );

                        copy( public_path('/../../portal/public/uploads/filesready'.'/'.$request->tuned_file), 
                        public_path('/../../e-tuningfiles/public'.$file->file_path.$fileToSave) );

                        unlink( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file );

                        $path = public_path('/../../e-tuningfiles/public'.$file->file_path.$fileToSave);
                
                    }

                    else if($file->front_end_id == 2){

                        // copy( public_path('/../../tuningX/public/uploads/filesready'.'/'.$request->tuned_file), 
                        // public_path('/../../tuningX/public'.$file->file_path.$fileName) );

                        // unlink( public_path('/../../tuningX/public/uploads/filesready').'/'.$file->tunned_files->file );

                        copy( public_path('/../../portal/public/uploads/filesready'.'/'.$request->tuned_file), 
                        public_path('/../../tuningX/public'.$file->file_path.$fileToSave) );

                        unlink( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file );

                        $path = public_path('/../../tuningX/public'.$file->file_path.$fileToSave);

                    }

                    if($file->alientech_file){ // if slot id is assigned
                        $slotID = $file->alientech_file->slot_id;
                        $encodingType = $this->getEncodingType($file);
                        (new AlientechController)->saveGUIDandSlotIDToDownloadLaterForEncoding( $file, $path, $slotID, $encodingType, $engineerFile );
                    }

                    if($file->status == 'submitted'){
                        if($file->no_longer_auto == 0){
                            if($file->inner_search == 0){
                                $file->status = 'completed';
                                $file->support_status = "closed";
                                $file->checked_by = 'engineer';
                                $file->save();
                            }
                        }
                    }

                    if(!$file->response_time){
                        if($file->no_longer_auto == 0){
                            $file->reupload_time = Carbon::now();
                            $file->response_time = (new FilesController)->getResponseTimeAuto($file);
                            $file->save();
                        }
            
                    }

                    $file->automatic = 1;
                    $file->save();
                    
                    if($flag){

                        if($file->front_end_id == 1){

                            Chatify::push("private-chatify-download-portal-".$chatID, 'download-button', [
                                'status' => 'download',
                                'file_id' => $file->id
                            ]);
                
                        }

                        else if($file->front_end_id == 3){

                            Chatify::push("private-chatify-download-efiles-".$chatID, 'download-button', [
                                'status' => 'download',
                                'file_id' => $file->id
                            ]);
                
                        }

                        else if($file->front_end_id == 2){
                            
                            Chatify::push("private-chatify-download-tuningx-".$chatID, 'download-button', [
                                'status' => 'download',
                                'file_id' => $file->id
                            ]);
        
                        }

                        $this->sendMail($file);

                        return response()->json('file found.');
                    }
                }

                else{
            
                    if($file->front_end_id == 1){

                        Chatify::push("private-chatify-download-portal-".$chatID, 'download-button', [
                            'status' => 'fail',
                            'file_id' => $file->id
                        ]);
        
                    }

                    else if($file->front_end_id == 3){

                        Chatify::push("private-chatify-download-efiles-".$chatID, 'download-button', [
                            'status' => 'fail',
                            'file_id' => $file->id
                        ]);
            
                    }

                    else{
                        
                        Chatify::push("private-chatify-download-tuningx-".$chatID, 'download-button', [
                            'status' => 'fail',
                            'file_id' => $file->id
                        ]);
                    }
                    
                    return response()->json('search failed.');
                }

            }

            else{
        
            if($file->front_end_id == 1){

                Chatify::push("private-chatify-download-portal-".$chatID, 'download-button', [
                    'status' => 'fail',
                    'file_id' => $file->id
                ]);

            }

            else if($file->front_end_id == 3){

                Chatify::push("private-chatify-download-efiles-".$chatID, 'download-button', [
                    'status' => 'fail',
                    'file_id' => $file->id
                ]);
    
            }

            else{
                
                Chatify::push("private-chatify-download-tuningx-".$chatID, 'download-button', [
                    'status' => 'fail',
                    'file_id' => $file->id
                ]);
            }
            
            return response()->json('search failed.');
        }
        
    }

    public function sendMail($file){

        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();
    
        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::findOrFail(6);

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
        else if($file->front_end_id == 3){
            $html2 = str_replace("#file_url",  'http://portal.e-tuningfiles.com/'."file/".$file->id,$html2);
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
        $messageTemplate = MessageTemplate::findOrFail(7);

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);
        
        $subject = "ECU Tech: Engineer uploaded a file in reply.";

        $manager = (new ReminderManagerController())->getAllManager();

        if($manager['eng_file_upload_cus_email'.$file->front_end_id]){
            \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
        }
        if($manager['eng_file_upload_admin_email'.$file->front_end_id]){
            \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
        }
        
        if($manager['eng_file_upload_admin_sms'.$file->front_end_id]){
            $this->sendMessage($admin->phone, $message2, $file->front_end_id);
        }

        if($manager['eng_file_upload_admin_sms'.$file->front_end_id]){
        
            $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
        }

        // if($manager['eng_file_upload_cus_sms']){
        //     $this->sendMessage($customer->phone, $message2);
        // }

        if($manager['eng_file_upload_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id);
        }

        if($manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
            $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
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
        else if($file->front_end_id == 3){
            $frontEnd = "E-files";
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
            
        }
        catch(Exception $e){
            \Log::info($e->getMessage());
        }

        
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
            else if($frontendID == 3)
            {
                $message = $client->messages
                    ->create($receiver, // to
                            ["body" => $message, "from" => "E-TuningFiles"]
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
    
    public function getEncodingType($file){

        $e = '';

        $extensionArr = [];
        foreach($file->decoded_files as $d){
            $extensionArr []= $d->extension; 
        }

        foreach($extensionArr as $ex){
            if($ex == 'dec'){
                $e = 'dec';
            }
            else if($ex == 'mpc'){
                $e = 'micro';
            }
            
            else if($ex == 'fls'){
                $e = 'fls';
            }

        }

        return $e;
    }

    public function setStatusAndEmail(Request $request){

        $file = File::findOrFail($request->file_id);

        $file->status = 'completed';
        $file->support_status = "closed";
        $file->checked_by = 'engineer';
        $file->save();

        if(!$file->response_time){

            $file->reupload_time = Carbon::now();
            $file->save();

            $file->response_time = (new FilesController)->getResponseTimeAuto($file);
            $file->save();

        }

        // $this->sendMail($file);

        return response()->json('status changed and email sent to the client.');

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
