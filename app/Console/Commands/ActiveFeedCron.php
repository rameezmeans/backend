<?php

namespace App\Console\Commands;

use App\Models\Credit;
use App\Models\EmailReminder;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\Key;
use App\Models\NewsFeed;
use App\Models\PaymentLog;
use App\Models\Schedualer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActiveFeedCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

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

    public function deactivateAllExceptThisFeed($ThatFeed) {
        
        $allOtherFeed = NewsFeed::where('id', '!=', $ThatFeed->id)
        ->where('front_end_id', $ThatFeed->front_end_id)
        ->get();

        foreach($allOtherFeed as $feed){
            if($feed->active == 1){
                // \Log::info("deactivating feed inner:".$feed->title);
                $feed->active = 0;
                $feed->save();
            }
        }
    }

    public function generateFeedbackEmail( $fileID, $requestFileID, $userID ) {

        $file = File::findOrFail($fileID); 
        $user = User::findOrFail($userID);
        
        if($user->front_end_id == 1){
            $feebdackTemplate = EmailTemplate::findOrFail(9); // email template must always be 9
            $url = 'https://portal.ecutech.gr/';
            $subject = "ECU Tech: Feedback Request";
        }
        else if($user->front_end_id == 2){
            $feebdackTemplate = EmailTemplate::findOrFail(49);
            $url = 'https://portal.tuning-x.com/';
            $subject = "TuningX: Feedback Request";
        }

        else if($user->front_end_id == 3){
            $feebdackTemplate = EmailTemplate::findOrFail(57);
            $url = 'https://portal.e-tuningfiles.com/';
            $subject = "E-TuningFiles: Feedback Request";
        }

        $html = $feebdackTemplate->html;
        $fileName = $file->brand." ".$file->engine;

        $html = str_replace('#file_name', $fileName, $html);
        $html = str_replace('#angry_link', $url.'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/angry', $html);
        $html = str_replace('#sad_link', $url.'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/sad', $html);
        $html = str_replace('#ok_link', $url.'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/ok', $html);
        $html = str_replace('#good_link', $url.'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/good', $html);
        $html = str_replace('#happy_link', $url.'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/happy', $html);
        $html = str_replace('#happy_link', $url.'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/happy', $html);
        $html = str_replace('#file_url', $url.'file/'.$fileID, $html);

        
        \Mail::to($user->email)->send(new \App\Mail\AllMails(['engineer' => [], 'html' => $html, 'subject' => $subject, 'front_end_id' => $user->front_end_id]));

    }
    
    public function handle()
    {

        \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));
        \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));

        $reminders = EmailReminder::all();

        $dateCheck = date('Y-m-d');
        $current = Carbon::parse(Carbon::createFromTimestamp(strtotime($dateCheck))->format('Y-m-d'));
        $schedualer = Schedualer::take(1)->first();

        $days = $schedualer->days;
        $time = $schedualer->time_of_day;

        foreach($reminders as $reminder){

            $reminderSetDate = Carbon::parse(Carbon::createFromTimestamp(strtotime($reminder->set_time))->format('Y-m-d'));
            $emailTime = $reminderSetDate->addDays($days);
            $result = $emailTime->eq($current);
                if($result){
                    $timeGreater = now()->greaterThan(Carbon::parse($time));
                    if($timeGreater){

                        $this->generateFeedbackEmail($reminder->file_id, $reminder->request_file_id, $reminder->user_id);
                        $reminder->cycle = $reminder->cycle - 1;

                        if($reminder->cycle == 0){
                            $reminder->delete();
                        }
                        else{
                            $reminder->set_time = Carbon::now();
                            $reminder->save();
                        }

                        \Log::info("Check cycles as well. They must be 2.Email Sent at: ".date('d-m-y h:i:s').' for file: '.$reminder->file_id);
                    }
                }
        }

        ///////////////////////

        // $flag = chmod( public_path("/../../portal/public/uploads") , 0777 );
        // $theFlag = $this->recursiveChmod(public_path("/../../portal/public/uploads"));
        // $flag1 = chmod( public_path("/../../portal/resources/lang/gr.json") , 0777 );

        // \Log::info("lang/gr: permission: ".$flag1);

        // $flag = chmod( public_path("/../../tuningX/public/uploads") , 0777 );
        // $theFlag = $this->recursiveChmod(public_path("/../../tuningX/public/uploads"));

        // $flag = chmod( public_path("/../../portal.e-tuningfiles.com/public/uploads") , 0777 );
        // $theFlag = $this->recursiveChmod(public_path("/../../portal.e-tuningfiles.com/public/uploads"));
        // // $flag1 = chmod( public_path("/../../tuningX/resources/lang/gr.json") , 0777 );

        // $flag = chmod( public_path("/../../TuningXV2/public/uploads") , 0777 );
        // $theFlag = $this->recursiveChmod(public_path("/../../TuningXV2/public/uploads"));

        // //here we are 
        // // $flag = chmod( public_path("/../../EcuTechV2/public/uploads") , 0777 );
        // // $theFlag = $this->recursiveChmod(public_path("/../../EcuTechV2/public/uploads"));

        // // $flag = chmod( public_path("/../../backend/public/uploads") , 0777 );
        // $flagBackned = chmod( public_path("/../../backend/storage/logs") , 0777 );
        // $theBackendFlag = $this->recursiveChmod(public_path("/../../backend/storage/logs"));



        // $flag = chmod( public_path("/../../devback/storage/logs") , 0777 );
        // $theBackendFlagLog = $this->recursiveChmod(public_path("/../../devback/storage/logs"));

        // $flag = chmod( public_path("/../../tuningX/storage/logs") , 0777 );
        // $theTuningXFlag = $this->recursiveChmod(public_path("/../../tuningX/storage/logs"));

        // $flag = chmod( public_path("/../../portal.e-tuningfiles.com/storage/logs") , 0777 );
        // $theTuningXFlag = $this->recursiveChmod(public_path("/../../portal.e-tuningfiles.com/storage/logs"));

        // $flag = chmod( public_path("/../../portal/storage/logs") , 0777 );
        // $thePortalFlag = $this->recursiveChmod(public_path("/../../portal/storage/logs"));
        // // $flag1 = chmod( public_path("/../../backend/resources/lang/gr.json") , 0777 );

        $creditsWithoutZohoID = Credit::whereNull('zohobooks_id')
        ->where('credits','>', 0)
        ->where('gifted', 0)
        ->whereDate('created_at', Carbon::today())
        ->where('created_at', '<', Carbon::now()->subMinutes(5)->toDateTimeString())
        ->get();

        $emailWent = false;
        $emailWentElorus = false;

        foreach($creditsWithoutZohoID as $c){

            if(!$c->log){
                $emailWent = true;
                $logInstance = new PaymentLog();
                $logInstance->payment_id = $c->id;
                $logInstance->user_id = $c->user_id;
                $logInstance->zohobooks_payment = false;
                $logInstance->zohobooks_id = NULL;
                $logInstance->email_sent = 1;
                $logInstance->reason_to_skip_zohobooks_payment_id = "zohobooks invoice did not went through.";
                $logInstance->save();
                send_error_email($c->id, 'Transaction happened without zoho id', $c->front_end_id);
            }
        }

        $creditsWithoutElorusID = Credit::whereNull('elorus_id')
        ->where('credits','>', 0)
        ->where('gifted', 0)
        ->whereDate('created_at', Carbon::today())
        ->where('created_at', '<', Carbon::now()->subMinutes(5)->toDateTimeString())
        ->get();
        
        foreach($creditsWithoutElorusID as $c){
            if($c->elorus_able()){
                
                if($c->log){
                    if($c->log->reason_to_skip_elorus_id == NULL){
                        $emailWentElorus = true;
                        $logInstance = $c->log;
                        $logInstance->payment_id = $c->id;
                        $logInstance->user_id = $c->user_id;
                        $logInstance->elorus_id = NULL;
                        $logInstance->email_sent = 1;
                        $logInstance->reason_to_skip_elorus_id = "elorus invoice did not went through.";
                        $logInstance->save();
                        send_error_email($c->id, 'Transaction happened without elorus id', $c->front_end_id);
                    }

                }
                else{
                    $emailWentElorus = true;
                    $logInstance = new PaymentLog();
                    $logInstance->payment_id = $c->id;
                    $logInstance->user_id = $c->user_id;
                    $logInstance->elorus_id = NULL;
                    $logInstance->email_sent = 1;
                    $logInstance->reason_to_skip_elorus_id = "elorus invoice did not went through.";
                    $logInstance->save();
                    send_error_email($c->id, 'Transaction happened without elorus id', $c->front_end_id);
                }
            }
        }

        if($emailWent){
            \Log::info("email went at ".date('d-m-y h:i:s'));
        }

        if($emailWentElorus){
            \Log::info("email went for elorus at ".date('d-m-y h:i:s'));
        }
       
        // \Log::info("permissions are updated at ".date('d-m-y h:i:s'). " flagBackned:". $flagBackned);
        // \Log::info("permissions are updated at ".date('d-m-y h:i:s'). " theBackendFlagLog:". $theBackendFlagLog);
        // \Log::info("permissions are updated at ".date('d-m-y h:i:s'). " theBackendFlag:". $theBackendFlag);
        // \Log::info("permissions are updated at ".date('d-m-y h:i:s'). " theTuningxFlag:". $theTuningXFlag);
        // \Log::info("permissions are updated at ".date('d-m-y h:i:s'). " thePortalFlag:". $thePortalFlag);
        
        \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));

        $feeds1 = NewsFeed::where('front_end_id', 1)->get();
        $this->feedManage($feeds1);

        $feeds2 = NewsFeed::where('front_end_id', 2)->get();
        $this->feedManage($feeds2);

        $feeds3 = NewsFeed::where('front_end_id', 3)->get();
        $this->feedManage($feeds3);

        $submittedFiles1 = File::where('status', 'submitted')->where('front_end_id', 1)->get();
        $this->manageFiles($submittedFiles1, 1);

        $submittedFiles2 = File::where('status', 'submitted')->where('front_end_id', 2)->get();
        $this->manageFiles($submittedFiles2, 2);

        $submittedFiles3 = File::where('status', 'submitted')->where('front_end_id', 3)->get();
        $this->manageFiles($submittedFiles3, 3);

        $openFiles1 = File::where('support_status', 'open')->where('front_end_id', 1)->get();
        $this->manageFiles($openFiles1, 1);

        $openFiles2 = File::where('support_status', 'open')->where('front_end_id', 2)->get();
        $this->manageFiles($openFiles2, 2);

        $openFiles3 = File::where('support_status', 'open')->where('front_end_id', 3)->get();
        $this->manageFiles($openFiles3, 3);

        return Command::SUCCESS;
    }

    function manageFiles($files, $frontendID){

        $activeFeed = NewsFeed::where('active', 1)->where('front_end_id', $frontendID)->first();

        $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
        $fsat = Key::where('key', 'file_submitted_alert_time')->first()->value;
        $foat = Key::where('key', 'file_open_alert_time')->first()->value;
        $fodt = Key::where('key', 'file_open_delay_time')->first()->value;

        if($activeFeed != NULL){

            if($activeFeed->type == 'good_news'){

                foreach($files as $file){

                    if($file->timer == NULL){

                        $file->timer = Carbon::now();
                        $file->save();
                    }

                    if($file->submission_timer == NULL){

                        $file->submission_timer = Carbon::now();
                        $file->save();
                    }

                    if($file->timer != NULL){

                        if($file->red == 0){

                            if($file->support_status == 'open') {

                                if( (strtotime($file->timer)+($foat*60))  <= strtotime(now())){
                                    $file->red = 1;
                                    $file->save();
                                }   
                            }
                            
                            if($file->status == 'submitted') {
                                
                                if( (strtotime($file->submission_timer)+($fsat*60))  <= strtotime(now())){
                                    $file->red = 1;
                                    $file->save();
                                } 
                            }
                        }

                        if($file->delay == 0){

                            if($file->support_status == 'open') {

                                if( (strtotime($file->timer)+($fodt*60))  <= strtotime(now())){
                                    $file->delayed = 1;
                                    $file->save();
                                }   
                            }

                            if($file->status == 'submitted') {

                                if( (strtotime($file->submission_timer)+($fsdt*60))  <= strtotime(now())){
                                    $file->delayed = 1;
                                    $file->save();
                                } 

                            }
                        }

                    }
                }
            }
        }

    }

    function feedManage($feeds){

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

    // function recursiveChmod($path, $filePerm=0777, $dirPerm=0777) {
    //     // Check if the path exists
    //     if (!file_exists($path)) {
    //         return(false);
    //     }
 
    //     // See whether this is a file
    //     if (is_file($path)) {
    //         // Chmod the file with our given filepermissions
    //         chmod($path, $filePerm);
 
    //     // If this is a directory...
    //     } elseif (is_dir($path)) {
    //         // Then get an array of the contents
    //         $foldersAndFiles = scandir($path);
 
    //         // Remove "." and ".." from the list
    //         $entries = array_slice($foldersAndFiles, 2);
 
    //         // Parse every result...
    //         foreach ($entries as $entry) {
    //             // And call this function again recursively, with the same permissions
    //             $this->recursiveChmod($path."/".$entry, $filePerm, $dirPerm);
    //         }
 
    //         // When we are done with the contents of the directory, we chmod the directory itself
    //         chmod($path, $dirPerm);
    //     }
 
    //     // Everything seemed to work out well, return true
    //     return(true);
    // }
}
