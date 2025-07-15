<?php

namespace App\Http\Controllers;

use App\Models\ReasonsToReject;
use Illuminate\Http\Request;

class ReasonsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function reasonsToReject(){
        $reasonsToReject = ReasonsToReject::all();
        return view('reasons_to_cancel.index', ['reasonsToReject' => $reasonsToReject]);
    }

    public function create(){
        return view('reasons_to_cancel.create');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        $reason = ReasonsToReject::find($id);

        if (!$reason) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reason not found.'
            ], 404);
        }

        $reason->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Reason deleted successfully.'
        ]);
    }

    public function update(Request $request, $id)
    {
        // Step 1: Validate the incoming request
        $validated = $request->validate([
            'reason_to_cancel' => 'required|string|max:255',
        ]);

        // Step 2: Find the existing record
        $reason = \App\Models\ReasonsToReject::findOrFail($id);

        // Step 3: Update the model
        $reason->reason_to_cancel = $validated['reason_to_cancel'];
        $reason->save();

        // Step 4: Redirect back to index with success message
        return redirect()->route('reasons-to-reject')
            ->with('success', 'Reason updated successfully.');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'reason_to_cancel' => 'required|string|max:255',
        ]);
        
        $reason = ReasonsToReject::create([
            'reason_to_cancel' => $validated['reason_to_cancel'],
        ]);

        return redirect()->route('reasons-to-reject')->with('success', 'Reason added successfully.');
    }

    public function edit($id)
    {
        $reason = ReasonsToReject::findOrFail($id);

        return view('reasons_to_cancel.create', compact('reason'));
    }
    
}