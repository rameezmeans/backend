<?php

namespace App\Http\Controllers;

use App\Models\SampleMessage;
use Illuminate\Http\Request;

class SampleMessagesController extends Controller
{
    /**
     * Ensure user is authenticated
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('adminOnly'); // Optional
    }

    /**
     * Display all sample messages
     */
    public function index()
    {
        $sampleMessages = SampleMessage::orderBy('created_at', 'desc')->get();
        return view('sample_messages.listings', compact('sampleMessages'));
    }

    /**
     * Show form to create a new sample message
     */
    public function create()
    {
        return view('sample_messages.create');
    }

    /**
     * Store a new sample message
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        SampleMessage::create([
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->route('sample-messages.index')->with('success', 'Message created successfully!');
    }

    /**
     * Show form to edit an existing sample message
     */
    public function edit($id)
    {
        $sampleMessage = SampleMessage::findOrFail($id);
        return view('sample_messages.create', [
            'sampleMessage' => $sampleMessage
        ]);
    }

    /**
     * Update an existing message
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $message = SampleMessage::findOrFail($id);
        $message->title = $request->title;
        $message->message = $request->message;
        $message->save();

        return redirect()->route('sample-messages.index')->with('success', 'Message updated successfully!');
    }

    /**
     * Delete a message
     */
    public function destroy($id)
    {
        $message = SampleMessage::findOrFail($id);
        $message->delete();

        return response()->json(['success' => true, 'message' => 'Message deleted successfully.']);
    }

    public function fetch()
    {
        $messages = SampleMessage::select('title', 'message')->get();
        return response()->json($messages);
    }
}