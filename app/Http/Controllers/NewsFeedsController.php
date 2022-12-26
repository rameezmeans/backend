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

        // dd("Cron is working fine at: ".date('d-m-y h:i:s'));
        // dd("date time: ".strtotime(now()));
        // dd("activation date time: ".$feed->activate_at);
        // dd("activation date time: ".strtotime($feed->activate_at)." date time: ".strtotime(now()));

        return view('feeds.add_edit', ['feed' => $feed, 'date' => $date]);
    }

    public function update(Request $request) {

        $feed = NewsFeed::findOrFail($request->id);
        $feed->title = $request->title;
        $feed->feed = $request->feed;
        $feed->type = $request->type;

        $range = $request->dateTimeRange;
        $timeArray = explode(" - ",$range);
        // dd($timeArray);

        // $activateAt = str_replace( '/', '-', $timeArray[0] );
        $feed->activate_at = date_create_from_format("d/m/Y h:i A",$timeArray[0]);
        // $deactivateAt = str_replace( '/', '-', $timeArray[1] );
        $feed->deactivate_at = date_create_from_format("d/m/Y h:i A",$timeArray[1]);

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

        $feed = new NewsFeed();
        $feed->title = $request->title;

        $range = $request->dateTimeRange;
        $timeArray = explode(" - ",$range);

        // $activateAt = str_replace( '/', '-', $timeArray[0] );
        $feed->activate_at = date_create_from_format("d/m/Y h:i A",$timeArray[0]);
        // $deactivateAt = str_replace( '/', '-', $timeArray[1] );
        $feed->deactivate_at = date_create_from_format("d/m/Y h:i A",$timeArray[1]);
        
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
