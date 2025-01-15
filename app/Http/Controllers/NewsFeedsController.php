<?php

namespace App\Http\Controllers;

use App\Models\FrontEnd;
use App\Models\IntegerMeta;
use App\Models\Key;
use App\Models\NewsFeed;
use Illuminate\Http\Request;

class NewsFeedsController extends Controller
{

    private $translationObj;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->translationObj = new TranslationController();
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function addResellersText(Request $request) {
        
        $frontend = FrontEnd::findOrFail($request->id);
        $frontend->resellers_text = $request->resellers_text;
        $frontend->type = $request->type;
        $frontend->save();

        return redirect()->route('feeds')->with(['success' => 'Resellers Text Updated, successfully.']);
    }

    public function addCautionText(Request $request) {

        $frontend = FrontEnd::findOrFail($request->id);
        $frontend->caution_text = $request->caution_text;
        $frontend->save();

        return redirect()->route('feeds')->with(['success' => 'Caution Text Updated, successfully.']);
    }

    public function index() {
        
        $newsFeeds = NewsFeed::whereNull('subdealer_group_id')->get();

        if(env('APP_ENV') == 'local'){
            $this->simulateActivation($newsFeeds);
        }

        $ecutechOnlineStatus = IntegerMeta::where('key', 'ecutech_online_status')->first()->value;
        $tuningXOnlineStatus = IntegerMeta::where('key', 'tuningx_online_status')->first()->value;
        $etfOnlineStatus     = IntegerMeta::where('key', 'etf_online_status')->first()->value;

        $newsFeedsECUTech = NewsFeed::whereNull('subdealer_group_id')->where('front_end_id', 1)->get();
        $newsFeedsTuningX = NewsFeed::whereNull('subdealer_group_id')->where('front_end_id', 2)->get();
        $newsFeedsEfiles = NewsFeed::whereNull('subdealer_group_id')->where('front_end_id', 3)->get();

        return view( 'feeds.index', [ 

            'ecutechOnlineStatus' => $ecutechOnlineStatus, 
            'tuningXOnlineStatus' => $tuningXOnlineStatus, 
            'etfOnlineStatus' => $etfOnlineStatus, 

            'newsFeedsECUTech' => $newsFeedsECUTech, 
            'newsFeedsTuningX' => $newsFeedsTuningX, 
            'newsFeedsEfiles' =>$newsFeedsEfiles 
            ] );
    }

    public function simulateActivation($feeds){

        $dateCheck = date('l');
        // $dateCheck = 'Monday';
        $timeCheck = date('H:i');
        // $timeCheck = '09:10';

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
                            \Log::info("activating feed:".$feed->title);
                            $feed->active = 1;
                            $feed->save();
    
                            $deactiveAll = true;
                            $thatFeed = $feed;
                        // }
                    }
                    else {
                        if($feed->active == 1){
                            \Log::info("deactivating feed:".$feed->title);
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
                                
                                \Log::info("activating feed at:".date('l').$feed->title);
                                $feed->active = 1;
                                $feed->save();
                            }
                        }

                        else {
                            
                            if($feed->active == 1){
                                
                                \Log::info("deactivating feed at:".date('l').$feed->title);
                                $feed->active = 0;
                                $feed->save();
                            }
                        }
                    }

                    else{

                            if($feed->active == 1){

                                \Log::info("deactivating feed:".date('l').$feed->title);
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

    public function deactivateAllExceptThisFeed($ThatFeed) {
        
        $allOtherFeed = NewsFeed::where('id', '!=', $ThatFeed->id)->get();

        foreach($allOtherFeed as $feed){
            if($feed->active == 1){
                \Log::info("deactivating feed inner:".$feed->title);
                $feed->active = 0;
                $feed->save();
            }
        }
    }
    public function add() {

        $date = date('d/m/Y h:i A');
        return view('feeds.add_edit', ['date' => $date]);
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

    public function test($feed){

        // $dateCheck = date('l');
        $dateCheck = 'Monday';
        // $timeCheck = date('H:i');
        $timeCheck = '17:05';

        // \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));
            // \Log::info("Feed Activation Day: ".strtotime(date('d-m-y h:i:s')));
            // \Log::info("activation date time: ".$feed->activate_at);
            // \Log::info("activation date time: ".strtotime($feed->activate_at));

        if($feed->activate_at == null &&  $feed->deactivate_at == null) {

            // dd('first');

            if($this->getDayNumber($dateCheck) >= $this->getDayNumber($feed->activation_weekday) && $this->getDayNumber($dateCheck) <= $this->getDayNumber($feed->deactivation_weekday) ) {
                // dd('second');
                dd(strtotime($timeCheck).' '.strtotime($feed->daily_activation_time) );
                dd(strtotime($timeCheck).' '.strtotime($feed->daily_deactivation_time) );

                if ( strtotime($timeCheck) > strtotime($feed->daily_activation_time) && strtotime($timeCheck) < strtotime($feed->daily_deactivation_time) ) {
                    

                    // dd(strtotime($timeCheck).' '.strtotime($feed->daily_activation_time) );
                    

                    dd('third');
                    if($feed->active == 0){
                        
                        \Log::info("activating feed at:".date('l').$feed->title);
                        $feed->active = 1;
                        $feed->save();
                    }
                }

                else {
                    dd('forth');
                    if($feed->active == 1){
                        
                        \Log::info("deactivating feed at:".date('l').$feed->title);
                        $feed->active = 0;
                        $feed->save();
                    }
                }
            }

            else{
                dd('fifth');
                if($feed->active == 1){

                    \Log::info("deactivating feed:".date('l').$feed->title);
                    $feed->active = 0;
                    $feed->save();
                }
                
            }
        }

        if($feed->activation_weekday == null &&  $feed->deactivation_weekday == null){

        if( strtotime(now()) >= strtotime($feed->activate_at)){
            if($feed->active == 0){
                    \Log::info("activating feed:".$feed->title);
                    $feed->active = 1;
                    $feed->save();
                }
            }
            if( strtotime(now()) >= strtotime($feed->deactivate_at)){
                if($feed->active == 1){
                    \Log::info("deactivating feed:".$feed->title);
                    $feed->active = 0;
                    $feed->save();
                }
            }
        }

        // dd($testing);
    }

    public function edit($id) {

        $date = date('d/m/Y h:i A');
        $feed = NewsFeed::findOrFail($id);
        // $this->test($feed);
        return view('feeds.add_edit', ['feed' => $feed, 'date' => $date]);
    }

    public function update(Request $request) {

        $feed = NewsFeed::findOrFail($request->id);
        $feed->title = $request->title;

        if($request->activation_weekday == null && $request->deactivation_weekday == null){

            $range = $request->dateTimeRange;
            $timeArray = explode(" - ",$range);
            $feed->activate_at = date_create_from_format("d/m/Y h:i A",$timeArray[0]);
            $feed->deactivate_at = date_create_from_format("d/m/Y h:i A",$timeArray[1]);
            $feed->activation_weekday = null;
            $feed->deactivation_weekday = null;

        }
        else{

            $feed->activation_weekday = $request->activation_weekday;
            $feed->deactivation_weekday = $request->deactivation_weekday;
            $feed->daily_activation_time = $request->daily_activation_time;
            $feed->daily_deactivation_time = $request->daily_deactivation_time;
            $feed->activate_at = null;
            $feed->deactivate_at = null;
        }

        $feed->feed = $request->feed;
        $feed->type = $request->type;
        
        $feed->save();

        $texts['english'] = $request->feed;;
        $texts['greek']   = $request->feed_in_greek;

        $this->translationObj->store($request->id, 'Feed', $texts); 

        return redirect()->route('feeds')->with(['success' => 'Feed Updated, successfully.']);

    }

    /**
     * update the feed status to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changeStatus(Request $request)
    {
        $feed = NewsFeed::findOrFail($request->feed_id);
        if($request->status == 'true'){
            $feed->active = true;
        }
        else{
            $feed->active = false;
        }
        $feed->save();
        return response()->json(['success' => 'Status changed']);
    }

    public function post(Request $request) {

        $validated = $request->validate([
            'title' => 'required|unique:news_feeds|max:255|min:3',
            'feed' => 'required'
        ]);

        $feed = new NewsFeed();
        $feed->title = $request->title;

        if($request->activation_weekday == null && $request->deactivation_weekday == null){

            $range = $request->dateTimeRange;
            $timeArray = explode(" - ",$range);
            $feed->activate_at = date_create_from_format("d/m/Y h:i A",$timeArray[0]);
            $feed->deactivate_at = date_create_from_format("d/m/Y h:i A",$timeArray[1]);
            $feed->activation_weekday = null;
            $feed->deactivation_weekday = null;

        }
        else{

            $feed->activation_weekday = $request->activation_weekday;
            $feed->deactivation_weekday = $request->deactivation_weekday;
            $feed->daily_activation_time = $request->daily_activation_time;
            $feed->daily_deactivation_time = $request->daily_deactivation_time;
            $feed->activate_at = null;
            $feed->deactivate_at = null;
        }
        
        $feed->feed = $request->feed;
        $feed->type = $request->type;
        
        $feed->save();

        $texts['english'] = $request->feed;;
        $texts['greek']   = $request->feed_in_greek;

        $this->translationObj->store($request->id, 'Feed', $texts); 

        return redirect()->route('feeds')->with(['success' => 'Feed added, successfully.']);

    }

    public function timers(){

        $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
        $fsat = Key::where('key', 'file_submitted_alert_time')->first()->value;
        $foat = Key::where('key', 'file_open_alert_time')->first()->value;
        $fodt = Key::where('key', 'file_open_delay_time')->first()->value;

        return view('feeds.timers', [
            'fsdt' => $fsdt, 
            'fsat' => $fsat,
            'foat' => $foat,
            'fodt' => $fodt
        ]);
    }

    public function updateTimers(Request $request){

        dd($request->all());
    }

    public function delete(Request $request)
    {
        $feed = NewsFeed::findOrFail($request->id);
        $feed->delete();
        $request->session()->put('success', 'Feed deleted, successfully.');

    }
}
