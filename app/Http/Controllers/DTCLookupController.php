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

    public function deleteBosch(Request $request){

    }

    public function searchBosch(Request $request){

        $boschNumbers = BoschNumber::paginate(10);
        $record = BoschNumber::where('manufacturer_number', $request->manufacturer_number)->first();
        
        if($record == NULL){
            $record = 'Record Not Found!';
        }

        return view('dtc_lookup.bosch', ['record' => $record, 'boschNumbers' => $boschNumbers]);
    }

    public function addBosch(Request $request){

        $add = new BoschNumber;
        $add->manufacturer_number = $request->manufacturer_number;
        $add->ecu = $request->ecu;
        $add->save();

        return redirect()->route('bosch-lookup')->with(['success' => 'ECU Record added, successfully.']);
    }

    public function updateBosch(Request $request){

        $add = BoschNumber::findOrFail($request->id);
        $add->manufacturer_number = $request->manufacturer_number;
        $add->ecu = $request->ecu;
        $add->save();

        return redirect()->route('bosch-lookup')->with(['success' => 'ECU Record updated, successfully.']);
    }

    public function editBosch($id){

        $boschRecord = BoschNumber::findOrFail($id);
        return view('dtc_lookup.create_edit_bosch', ['boschRecord' => $boschRecord]);
    }

    public function createBosch(){
        return view('dtc_lookup.create_edit_bosch');
    }

    public function bosch(){

        $boschNumbers = BoschNumber::paginate(10);
        return view('dtc_lookup.bosch', ['boschNumbers' => $boschNumbers]);
    }
    
}
