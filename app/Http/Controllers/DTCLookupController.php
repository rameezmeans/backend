<?php

namespace App\Http\Controllers;

use App\Models\DTCLookup;
use Illuminate\Http\Request;

class DTCLookupController extends Controller
{
    public function index(){

        $dtclookupRecords = DTCLookup::all();
        return view('dtc_lookup.index', ['dtclookupRecords' => $dtclookupRecords]);
    }

    // public function create(){
    //     return view('dtc_lookup.create');
    // }

}
