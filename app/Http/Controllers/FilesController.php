<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EmailReminder;
use App\Models\EmailTemplate;
use App\Models\EngineerFileNote;
use App\Models\File;
use App\Models\MessageTemplate;
use App\Models\RequestFile;
use App\Models\Schedualer;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use PDF;
use SebastianBergmann\Template\Template;
use Twilio\Rest\Client;

class FilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['recordFeedback']]);
    }
    
    public function saveFeedbackEmailSchedual(Request $request) {

        $schedual = Schedualer::take(1)->first();

        if( !$schedual ) {
            $new = new Schedualer();
            $new->days = $request->days; 
            $new->time_of_day = $request->time_of_day; 
            $new->save(); 
        }
        else{
           
            $schedual->days = $request->days; 
            $schedual->time_of_day = $request->time_of_day; 
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
            $reminder = new EmailReminder();
            $reminder->file_id = $file->id;
            $reminder->user_id = $file->user_id;
            $reminder->request_file_id = $file->req_id;
            $reminder->set_time = Carbon::now();
            $reminder->save();
        }

        return redirect()->route('feedback-emails')->with(['success' => 'Schedual udpated, successfully.']);
    }

    public function feedbackEmails() {
        //email template
        $feebdackTemplate = EmailTemplate::findOrFail(9);
        $schedual = Schedualer::take(1)->first();
        return view('files.feedback_page', [ 'feebdackTemplate' => $feebdackTemplate, 'schedual' => $schedual ]);
    }

    public function saveFeedbackEmailTemplate(Request $request) {

        $feebdackTemplate = EmailTemplate::findOrFail(9);
        $feebdackTemplate->html = $request->new_template;
        $feebdackTemplate->save();

        return redirect()->route('feedback-emails')->with(['success' => 'Template udpated, successfully.']);

    }

    public function editMessage( Request $request ) {

        $message = EngineerFileNote::findOrFail($request->id);
        $message->egnineers_internal_notes = $request->message;
        $message->save();
        
        return redirect()->back()
        ->with('success', 'Engineer note successfully Edited!')
        ->with('tab','chat');
    }

    public function generateFeedbackEmail( $fileID, $requestFileID, $userID ) {

        $file = File::findOrFail($fileID); // this is a file on local
        // $file = File::findOrFail(203); // this is a file on live
        $feebdackTemplate = EmailTemplate::findOrFail(9); // email template must always be 9
        $html = $feebdackTemplate->html;
        $fileName = $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard;

        $html = str_replace('#file_name', $fileName, $html);
        $html = str_replace('#angry_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/angry', $html);
        $html = str_replace('#sad_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/sad', $html);
        $html = str_replace('#ok_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/ok', $html);
        $html = str_replace('#good_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/good', $html);
        $html = str_replace('#happy_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/happy', $html);
        $html = str_replace('#happy_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/happy', $html);
        $html = str_replace('#file_url', env('PORTAL_URL').'file/'.$fileID, $html);

        $subject = "ECU Tech: Feedback Request";
        \Mail::to('xrkalix@gmail.com')->send(new \App\Mail\AllMails(['engineer' => [], 'html' => $html, 'subject' => $subject]));

    }

    public function testFeedbackEmail() {
        // $file = File::findOrFail(42); // this is a file on local
        $file = File::findOrFail(231); // this is a file on live
        $requestFileID = 118;
        $userID = 50;

        $this->generateFeedbackEmail($file->id, $requestFileID, $userID);

        // $feebdackTemplate = EmailTemplate::findOrFail(9);
        // $html = $feebdackTemplate->html;

        // // dd($html);

        // $subject = "ECU Tech: Feedback Request";
        // \Mail::to('xrkalix@gmail.com')->send(new \App\Mail\AllMails(['engineer' => [], 'html' => $html, 'subject' => $subject]));

    }

    // public function recordFeedback($fileID, $userID, $feedback){
        
    // }

    public function download($file_name) {

        $file_path = public_path('/../../portal/public/uploads/'.$file_name);
        return response()->download($file_path);
    }

    /**
     * Show the files table.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        File::where('is_credited', 0)->delete(); // remove unneccesary NOT credited files

        if(Auth::user()->is_admin || Auth::user()->is_head){
            $files = File::orderBy('support_status', 'desc')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('is_credited', 1)->get();
        }
        else if(Auth::user()->is_engineer){
            $files = File::orderBy('support_status', 'desc')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('assigned_to', Auth::user()->id)->where('is_credited', 1)->get();
        }
        return view('files.files', ['files' => $files]);
    }

    public function deleteMessage(Request $request)
    {
        $note = EngineerFileNote::findOrFail($request->note_id);
        $note->delete();
        return response('Note deleted', 200);
    }

    public function deleteUploadedFile(Request $request)
    {
        $file = RequestFile::findOrFail($request->request_file_id);
        $file->delete();
        return response('File deleted', 200);
    }

    // public function testMessage(){
    //     $this->sendMessage('+923218612198', 'test message again');
    // }
    
    public function sendMessage($receiver, $message)
    {
        try {
            $accountSid = env("TWILIO_SID");
            $authToken = env("TWILIO_AUTH_TOKEN");
            $twilioNumber = env("TWILIO_NUMBER"); 
            $client = new Client($accountSid, $authToken);

            $message = $client->messages
                  ->create($receiver, // to
                           ["body" => $message, "from" => "ecutech"]
            );

            \Log::info('message sent to:'.$receiver);

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function assignEngineer(Request $request){
    
       $file = File::findOrFail($request->file_id);
       $file->assigned_to = $request->assigned_to;
       $file->assignment_time = Carbon::now();
       $file->save();

       $engineer = User::findOrFail($request->assigned_to);
       $customer = User::findOrFail($file->user_id);
    
    //    $template = EmailTemplate::where('name', 'Engineer Assignment Email')->first();
       $template = EmailTemplate::findOrFail(1);

       $html = $template->html;

       $html = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html);
       $html = str_replace("#customer_name", $customer->name ,$html);
       $html = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html);
       
       $tunningType = '<img alt=".'.$file->stages.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon .'">';
       $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.$file->stages.'</span>';
       
        if($file->options){

            foreach($file->options() as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.$option.'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">';
                $tunningType .=  $option;  
                $tunningType .= '</div>';
            }
        }

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

        $messageTemplate = MessageTemplate::findOrFail(1);

        $message = $messageTemplate->text;

        $message = str_replace("#customer", $customer->name ,$message);

        $subject = "ECU Tech: Task Assigned!";

        \Mail::to($engineer->email)->send(new \App\Mail\AllMails(['engineer' => $engineer, 'html' => $html, 'subject' => $subject]));
        $this->sendMessage($engineer->phone, $message);
        
        return Redirect::back()->with(['success' => 'Engineer Assigned to File.']);

    }

    public function changSupportStatus(Request $request){

        $file = File::findOrFail($request->file_id);
        $file->support_status = $request->support_status;
        $file->save();

        return Redirect::back()->with(['success' => 'File Support status changed.']);
    }

    public function changeStatus(Request $request){

        $file = File::findOrFail($request->file_id);
        $file->status = $request->status;
        $file->save();

        $customer = User::findOrFail($file->user_id);
        $admin = User::where('is_admin', 1)->first();
    
        // $template = EmailTemplate::where('name', 'Status Change')->first();
        $template = EmailTemplate::findOrFail(8);

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
        $tunningType = '<img alt=".'.$file->stages.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon .'">';
        $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.$file->stages.'</span>';
        
        if($file->options){
            foreach($file->options() as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.$option.'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">';
                $tunningType .=  $option;  
                $tunningType .= '</div>';
            }
        }
        
        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
        $tunningType = '<img alt=".'.$file->stages.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon .'">';
        $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.$file->stages.'</span>';
        
        if($file->options){
            foreach($file->options() as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.$option.'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">';
                $tunningType .=  $option;  
                $tunningType .= '</div>';
            }
        }

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Status Change')->first();
        $messageTemplate = MessageTemplate::findOrFail(6);

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message1 = str_replace("#status", $file->status ,$message1);

        $message2 = str_replace("#customer", $file->name ,$message);
        $message2 = str_replace("#status", $file->status ,$message2);
        
        $subject = "ECU Tech: File Status Changed!";

        \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject]));
        \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject]));
        
        $this->sendMessage($admin->phone, $message1);
        $this->sendMessage($customer->phone, $message2);

        return Redirect::back()->with(['success' => 'File status changed.']);
    }

    public function fileEngineersNotes(Request $request)
    {
        $reply = new EngineerFileNote();
        $reply->egnineers_internal_notes = $request->egnineers_internal_notes;
        $reply->engineer = true;
        $reply->file_id = $request->file_id;
        $reply->save();

        $file = File::findOrFail($request->file_id);
        $file->support_status = "closed";
        $file->save();
        $customer = User::findOrFail($file->user_id);
        $admin = User::where('is_admin', 1)->first();
    
        // $template = EmailTemplate::where('name', 'Message To Client')->first();
        $template = EmailTemplate::findOrFail(7);

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
        $tunningType = '<img alt=".'.$file->stages.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon .'">';
        $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.$file->stages.'</span>';
        
        if($file->options){
            foreach($file->options() as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.$option.'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">';
                $tunningType .=  $option;  
                $tunningType .= '</div>';
            }
        }

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#note", $request->egnineers_internal_notes,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
        $tunningType = '<img alt=".'.$file->stages.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon .'">';
        $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.$file->stages.'</span>';
        
        if($file->options){
            foreach($file->options() as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.$option.'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">';
                $tunningType .=  $option;  
                $tunningType .= '</div>';
            }
        }

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#note", $request->egnineers_internal_notes,$html2);
        $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Message To Client')->first();
        $messageTemplate = MessageTemplate::findOrFail(4);

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);

        // $message1 = "Hi, Status changed for a file by Customer: " .$customer->name;
        // $message2 = "Hi, Status changed for a file by Customer: " .$file->name;
       
        $subject = "ECU Tech: Engineer replied to your support message!";

        \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject]));
        \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject]));
        
        $this->sendMessage($admin->phone, $message1);
        $this->sendMessage($customer->phone, $message2);

        $old = File::findOrFail($request->file_id);
        $old->checked_by = 'engineer';
        $old->save();

        return redirect()->back()
        ->with('success', 'Engineer note successfully Added!')
        ->with('tab','chat');
    }

    public function uploadFileFromEngineer(Request $request)
    {
        $attachment = $request->file('file');

        $file = File::findOrFail($request->file_id);

        $fileName = $attachment->getClientOriginalName();

        //renaming mechanism
        // $fileNameArr = explode( ".", $fileName );
        // $extenstion = end ( $fileNameArr ) ;    

        // $newFileName = '';

        // if(strpos($fileName, ' en ') !== false) {

        //     $start = strpos($fileName, ' en ');
        //     $end = $start + 7;

        //     $endPart = substr($fileName, $start, 7);

        //     if(isset($file->ecu)){
        //         $newFileName = $file->id.' '.$file->brand.' '.$file->model.' '.$file->engine.' '.$file->ecu.$endPart.'.'.$extenstion;
                
        //     }
        //     else {
        //         $newFileName = $file->brand.' '.$file->model.' '.$file->engine.$endPart.'.'.$extenstion;
        //     }

        // }
        
        // else {
        //     $newFileName = str_replace('#', '_', $fileName);
        // }

        $newFileName = str_replace('#', '_', $fileName);

        $attachment->move(public_path("/../../portal/public/uploads/"),$newFileName);

        $engineerFile = new RequestFile();
        $engineerFile->request_file = $newFileName;
        $engineerFile->file_type = 'engineer_file';
        $engineerFile->tool_type = 'not_relevant';
        $engineerFile->master_tools = 'not_relevant';
        $engineerFile->file_id = $request->file_id;
        $engineerFile->engineer = true;
        $engineerFile->save();

        $reminder = new EmailReminder();
        $reminder->user_id = $file->user_id;
        $reminder->file_id = $file->id;
        $reminder->request_file_id = $engineerFile->id;
        $reminder->set_time = Carbon::now();
        $reminder->save();

        if($file->status == 'submitted'){
            $file->status = 'completed';
            $file->save();
        }
        
        if(!$file->response_time){

            $file->reupload_time = Carbon::now();
            $assignmentTimeInSeconds  = strtotime($file->assignment_time);
            $reloadTimeInSeconds  = strtotime($file->reupload_time);
            $file->response_time = $reloadTimeInSeconds - $assignmentTimeInSeconds;
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
        $admin = User::where('is_admin', 1)->first();
    
        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::findOrFail(6);

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
        $tunningType = '<img alt=".'.$file->stages.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon .'">';
        $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.$file->stages.'</span>';
        
        if($file->options){
            foreach($file->options() as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.$option.'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">';
                $tunningType .=  $option;  
                $tunningType .= '</div>';
            }
        }

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
        $tunningType = '<img alt=".'.$file->stages.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon .'">';
        $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.$file->stages.'</span>';
        
        if($file->options){
            foreach($file->options() as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.$option.'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">';
                $tunningType .=  $option;  
                $tunningType .= '</div>';
            }
        }

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);

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

        \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject]));
        \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject]));
        
        $this->sendMessage($admin->phone, $message1);
        $this->sendMessage($customer->phone, $message2);
    

        return response('file uploaded', 200);
    }

    public function feedbackReports(){
        $engineers = User::where('is_engineer', 1)->get();
        return view('files.feedback_reports', ['engineers' => $engineers]);
    }

    public function reports(){
        $engineers = User::where('is_engineer', 1)->get();
        return view('files.reports', ['engineers' => $engineers]);
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

        $commentObj = Comment::where('engine', $file->engine);

        if($file->brand){
            $commentObj->where('make', $file->brand);
        }

        if($file->Model){
            $commentObj->where('model', $file->model);
        }


        if($file->ecu){
            $commentObj->where('ecu', $file->ecu);
        }

        if($file->generation){
            $commentObj->where('generation', $file->generation);
        }

        return $commentObj->get();
    }

     /**
     * Show the file.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        if(Auth::user()->is_admin){
            $file = File::findOrFail($id);
        }
        else if(Auth::user()->is_engineer){
            $file = File::where('id',$id)->where('assigned_to', Auth::user()->id)->first();
        }

        if(!$file){
            abort(404);
        }
        
        if($file->checked_by == 'customer'){
            $file->checked_by = 'seen';
            $file->save();
        }

        $vehicle = Vehicle::where('Make', $file->brand)
        ->where('Model', $file->model)
        ->where('Generation', $file->version)
        ->where('Engine', $file->engine)
        ->first();
        
        $engineers = User::where('is_engineer', 1)->get();
        $withoutTypeArray = $file->files->toArray();
        $unsortedTimelineObjects = [];

        foreach($withoutTypeArray as $r) {
            $fileReq = RequestFile::findOrFail($r['id']);
            if($fileReq->file_feedback){
                $r['type'] = $fileReq->file_feedback->type;
            }
            $unsortedTimelineObjects []= $r;
        } 
        
        $createdTimes = [];

        foreach($file->files->toArray() as $t) {
            $createdTimes []= $t['created_at'];
        } 
    
        foreach($file->engineer_file_notes->toArray() as $a) {
            $unsortedTimelineObjects []= $a;
            $createdTimes []= $a['created_at'];
        }   

        foreach($file->file_internel_events->toArray() as $b) {
            $unsortedTimelineObjects []= $b;
            $createdTimes []= $b['created_at'];
        } 

        foreach($file->file_urls->toArray() as $b) {
            $unsortedTimelineObjects []= $b;
            $createdTimes []= $b['created_at'];
        } 

        array_multisort($createdTimes, SORT_ASC, $unsortedTimelineObjects);

        if($file->ecu){
            $comments = $this->getComments($file);
        }
        else{
            $comments = null;
        }
        
        return view('files.show', [ 'vehicle' => $vehicle,'file' => $file, 'messages' => $unsortedTimelineObjects, 'engineers' => $engineers, 'comments' => $comments ]);
    }
}
