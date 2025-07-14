<?php

namespace App\Http\Controllers;

use App\Models\ReasonsToReject;

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
        return view('reasons_to_cancel.index', ['reasonToReject' => $reasonsToReject]);
    }

    
}