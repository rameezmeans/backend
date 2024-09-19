<?php

namespace App\Http\Controllers;

use App\Models\DTCLookup;
use Illuminate\Http\Request;

class DTCLookupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $dtclookupRecords = DTCLookup::all();
        return view('dtc_lookup.index', ['dtclookupRecords' => $dtclookupRecords]);
    }
    
}