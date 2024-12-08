<?php

namespace App\Http\Controllers;

use App\Models\BoschNumber;
use App\Models\DTCLookup;
use Illuminate\Http\Request;

class DTCLookupController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){

        $dtclookupRecords = DTCLookup::paginate(10);
        return view('dtc_lookup.index', ['dtclookupRecords' => $dtclookupRecords]);
    }

    public function bosch(){

        $boschNumbers = BoschNumber::paginate(10);
        return view('dtc_lookup.bosch', ['boschNumbers' => $boschNumbers]);
    }
    
}
