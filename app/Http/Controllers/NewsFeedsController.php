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

        return view('feeds.add_edit');
    }

    public function edit($id) {
        $feed = NewsFeed::findOrFail($id);
        return view('feeds.add_edit', ['feed' => $feed]);
    }

    public function update(Request $request) {

        $feed = NewsFeed::findOrFail($request->id);
        $feed->title = $request->title;
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

        $feed = new NewsFeed();
        $feed->title = $request->title;
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
