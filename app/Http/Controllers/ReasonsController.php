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

        return view('reasons-to-reject.create', compact('reason'));
    }
    
}