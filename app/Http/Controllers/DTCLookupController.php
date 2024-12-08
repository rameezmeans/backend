<?php

namespace App\Http\Controllers;

use App\Imports\BoschImport;
use App\Imports\DTCImport;
use App\Models\BoschNumber;
use App\Models\DTCLookup;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DTCLookupController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){

        $dtclookupRecords = DTCLookup::paginate(10);
        return view('dtc_lookup.index', ['dtclookupRecords' => $dtclookupRecords]);
    }

    public function importBoschPost(Request $request){

        Excel::import(new BoschImport,request()->file);
        return redirect()->route('bosch-lookup')->with('success',  'Bosch Records added, successfully.');
    }

    public function importDTCPost(Request $request){

        Excel::import(new DTCImport,request()->file);
        return redirect()->route('dtc-lookup')->with('success',  'DTC Records added, successfully.');
    }

    public function importDTC(){

        return view('dtc_lookup.import_dtc');
    }

    public function importBosch(){

        return view('dtc_lookup.import_bosch');
    }

    public function deleteBosch(Request $request){
        $number = BoschNumber::findOrFail($request->id);
        $number->delete();
    }

    public function deleteDTC(Request $request){
        $number = DTCLookup::findOrFail($request->id);
        $number->delete();
    }

    public function searchBosch(Request $request){

        $boschNumbers = BoschNumber::paginate(10);
        $record = BoschNumber::where('manufacturer_number', $request->manufacturer_number)->first();
        
        if($record == NULL){
            $record = 'Record Not Found!';
        }

        return view('dtc_lookup.bosch', ['record' => $record, 'boschNumbers' => $boschNumbers]);
    }

    public function searchDTC(Request $request){

        $dtclookupRecords = DTCLookup::paginate(10);
        $record = DTCLookup::where('code', $request->code)->first();
        
        if($record == NULL){
            $record = 'Record Not Found!';
        }

        return view('dtc_lookup.index', ['record' => $record, 'dtclookupRecords' => $dtclookupRecords]);
    }

    public function addDTC(Request $request){

        $add = new DTCLookup();
        $add->code = $request->code;
        $add->desc = $request->desc;
        $add->save();

        return redirect()->route('dtc-lookup')->with(['success' => 'DTC Record added, successfully.']);
    }

    public function addBosch(Request $request){

        $add = new BoschNumber;
        $add->manufacturer_number = $request->manufacturer_number;
        $add->ecu = $request->ecu;
        $add->save();

        return redirect()->route('bosch-lookup')->with(['success' => 'ECU Record added, successfully.']);
    }

    public function updateDTC(Request $request){

        $add = DTCLookup::findOrFail($request->id);
        $add->code = $request->code;
        $add->desc = $request->desc;
        $add->save();

        return redirect()->route('dtc-lookup')->with(['success' => 'DTC Record updated, successfully.']);
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

    public function editDTC($id){

        $dtcRecord = DTCLookup::findOrFail($id);
        return view('dtc_lookup.create_edit_dtc', ['dtcRecord' => $dtcRecord]);
    }

    public function createDTC(){
        return view('dtc_lookup.create_edit_dtc');
    }

    public function createBosch(){
        return view('dtc_lookup.create_edit_bosch');
    }

    public function bosch(){

        $boschNumbers = BoschNumber::orderBy('created_at', 'desc')->paginate(10);
        return view('dtc_lookup.bosch', ['boschNumbers' => $boschNumbers]);
    }
    
}
