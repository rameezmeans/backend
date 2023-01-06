<?php

namespace App\Http\Controllers;

use App\Models\NewsFeed;
use Illuminate\Http\Request;

class NewsFeedsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

       
        $newsFeeds = NewsFeed::all();
        return view('feeds.index', ['newsFeeds' => $newsFeeds]);
    }

    public function add() {

        $date = date('d/m/Y h:i A');
        return view('feeds.add_edit', ['date' => $date]);
    }

    public function edit($id) {

        

        $date = date('d/m/Y h:i A');
        $feed = NewsFeed::findOrFail($id);

        if($feed->activate_at == null &&  $feed->deactivate_at == null){

            if( date('l') == $feed->activation_weekday ){
                
                if (date('H:i') > date('H:i', strtotime($feed->daily_activation_time))) {
                    
                    if($feed->active == 0){
                        
                        \Log::info("activating feed at:".date('l').$feed->title);
                        $feed->active = 1;
                        $feed->save();
                    }
                }
            }
            if( date('l') == $feed->deactivation_weekday ){

                if (date('H:i') > date('H:i', strtotime($feed->daily_deactivation_time))) {

                    if($feed->active == 1){

                        \Log::info("deactivating feed:".date('l').$feed->title);
                        $feed->active = 0;
                        $feed->save();
                    }
                }
            }
        }

        // dd("Cron is working fine at: ".date('l'));
        // dd("date time: ".strtotime(now()));
        // dd("activation date time: ".$feed->activate_at);
        // dd("activation date time: ".strtotime($feed->activate_at)." date time: ".strtotime(now()));

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

            // dd($request->all());

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
        // dd($request->all());
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

        return redirect()->route('feeds')->with(['success' => 'Feed added, successfully.']);

    }

    public function delete(Request $request)
    {
        $feed = NewsFeed::findOrFail($request->id);
        $feed->delete();
        $request->session()->put('success', 'Feed deleted, successfully.');

    }
}
