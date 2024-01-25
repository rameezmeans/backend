<?php

namespace App\Console\Commands;

use App\Models\EmailReminder;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\NewsFeed;
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
        
        $allOtherFeed = NewsFeed::where('id', '!=', $ThatFeed->id)->get();

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
        \Mail::to($user->email)->send(new \App\Mail\AllMails(['engineer' => [], 'html' => $html, 'subject' => $subject, 'front_end_id' => $user->front_end_id]));

    }
    
    public function handle()
    {
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

        $flag = chmod( public_path("/../../portal/public/uploads") , 0777 );
        $theFlag = $this->recursiveChmod(public_path("/../../portal/public/uploads"));
        $flag1 = chmod( public_path("/../../portal/resources/lang/gr.json") , 0777 );

        // $flag = chmod( public_path("/../../backend/public/uploads") , 0777 );
        $theBackendFlag = $this->recursiveChmod(public_path("/../../backend/storage/logs"));
        $theTuningXFlag = $this->recursiveChmod(public_path("/../../tuningX/storage/logs"));
        $thePortalFlag = $this->recursiveChmod(public_path("/../../portal/storage/logs"));
        // $flag1 = chmod( public_path("/../../backend/resources/lang/gr.json") , 0777 );

        \Log::info("permissions are updated at ".date('d-m-y h:i:s'). " theBackendFlag:". $theBackendFlag);
        \Log::info("permissions are updated at ".date('d-m-y h:i:s'). " theTuningxFlag:". $theTuningXFlag);
        \Log::info("permissions are updated at ".date('d-m-y h:i:s'). " thePortalFlag:". $thePortalFlag);
        
        // \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));

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

        return Command::SUCCESS;
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
}
