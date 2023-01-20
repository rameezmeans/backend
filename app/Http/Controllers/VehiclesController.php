<?php

namespace App\Http\Controllers;

use App\Imports\VehiclesImport;
use App\Models\Comment;
use App\Models\Service;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VehiclesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function index(){
        $vehicles = Vehicle::all();

        foreach($vehicles as $vehicle){
            $vehicle->Brand_image_URL = str_replace(' ', '_', strtolower($vehicle->Make));
            $vehicle->save();
        }

        return view('vehicles.vehicles', ['vehicles' => $vehicles]);
    }

    public function create(){
        return view('vehicles.vehicles_create_edit');
    }

    public function addOptionComments(Request $request){

        $comment = new Comment();
        $comment->engine = $request->engine;
        $comment->make = $request->make;
        $comment->ecu = $request->ecu;
        $comment->generation = $request->generation;
        $comment->model = $request->model;
        $comment->option = $request->option;
        $comment->comments = $request->comments;
        $comment->save();

        return redirect()->route('add-comments', $request->id)->with('success',  'Comment added, successfully.');

    }

    public function addComments($id){

        $vehicle = Vehicle::findOrFail($id);

        $trimmedECUs = [];
        $options = null;
        $comments = null;
        $includedOptions = null;
        $hasECU = 0;
        $includedOptions = [];

        $options = Service::where('type', 'option')->get();
        $comments = $this->getComments($vehicle);

        if($vehicle->Engine_ECU){

            $hasECU = 1;
            $ecus = explode(' / ', $vehicle->Engine_ECU);

            $trimmedECUs = [];
            foreach($ecus as $ecu){
                $trimmedECUs []= trim($ecu);
            }

            foreach($trimmedECUs as $row){
                $includedOptions [$row]=  $this->getOptions($row, $vehicle);
            }

        }

        else{
            abort('404');
        }
        
        return view('vehicles.add_comments', 
        [
            'vehicle' => $vehicle, 
            'ecus' => $trimmedECUs, 
            'options' => $options,
            'comments' => $comments,
            'includedOptions' => $includedOptions,
            'hasECU' => $hasECU,
        ]);
    }

    public function importVehicles(Request $request){
        Excel::import(new VehiclesImport,request()->file);
        return redirect()->route('vehicles')->with('success',  'Vehicle added, successfully.');
    }

    public function importVehiclesView(){

        return view('vehicles.import');

    }


    public function massDelete(Request $request){

        foreach($request->searchIDs as $id){
            Vehicle::FindOrFail($id)->delete();
        }

        return response('vehicles deleted', 200);
    }

    public function editOptionComment(Request $request){

        $comment = Comment::findOrFail($request->id);
        $comment->comments = $request->comments;
        $comment->save();

        return redirect()->route('add-comments', $request->vehicle_id)->with('success',  'Comment updated, successfully.');

    }

    public function getOptions($ecu, $vehicle){

        $commentObj = Comment::where('engine', $vehicle->Engine);

        if($vehicle->Make){
            $commentObj->where('make', $vehicle->Make);
        }

        if($vehicle->Model){
            $commentObj->where('model', $vehicle->Model);
        }

        if($vehicle->Engine_ECU){
            $commentObj->where('ecu', $ecu);
        }

        if($vehicle->Generation){
            $commentObj->where('generation', $vehicle->Generation);
        }

        $comments = $commentObj->get();

        $options = [];
        foreach($comments as $comment){
            $options []= $comment->option;
        }

        return $options;
    }

    public function show($id){

        $vehicle = Vehicle::findOrFail($id);
        
        return view('vehicles.vehicles_create_edit', 
        [
            'vehicle' => $vehicle
        ]);

    }

    public function getComments($vehicle){

        $commentObj = Comment::where('engine', $vehicle->Engine);

       

        if($vehicle->Make){
            $commentObj->where('make', $vehicle->Make);
        }

        if($vehicle->Model){
            $commentObj->where('model', $vehicle->Model);
        }

        // I need all and then I get specific through blade file.

        // if($vehicle->Engine_ECU){
        //     $commentObj->where('ecu', $vehicle->Engine_ECU);
        // }

        if($vehicle->Generation){
            $commentObj->where('generation', $vehicle->Generation);
        }

        return $commentObj->get();
    }

    public function deleteComment(Request $request)
    {
        $vehicle = Comment::findOrFail($request->id);
        
        $vehicle->delete();
        $request->session()->put('success', 'Comment deleted, successfully.');

    }

    public function delete(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->id);
        
        $vehicle->delete();
        $request->session()->put('success', 'Vehicle deleted, successfully.');

    }

    public function add(Request $request){
        $vehicle = new Vehicle();
        $vehicle->Name = $request->Name;
        $vehicle->Make = $request->Make;
        $vehicle->Engine_URL = $request->Engine_URL;
        $vehicle->Brand_image_URL = $request->Brand_image_URL;
        $vehicle->Chart_image_URL = $request->Chart_image_URL;
        $vehicle->Model = $request->Model;
        $vehicle->Generation = $request->Generation;
        $vehicle->Engine = $request->Engine;
        $vehicle->BHP_standard = $request->BHP_standard;
        $vehicle->BHP_tuned = $request->BHP_tuned;
        $vehicle->BHP_difference = $request->BHP_difference;
        $vehicle->TORQUE_standard = $request->TORQUE_standard;
        $vehicle->TORQUE_tuned = $request->TORQUE_tuned;
        $vehicle->TORQUE_difference = $request->TORQUE_difference;
        $vehicle->Type_of_fuel = $request->Type_of_fuel;
        $vehicle->Method = $request->Method;
        $vehicle->Tuningtype = date('Y-m-d', strtotime(str_replace('/', '-', $request->Tuningtype)));
        $vehicle->Cylinder_content = $request->Cylinder_content;
        $vehicle->Engine_ECU = $request->Engine_ECU;
        $vehicle->Gearbox_ECU = $request->Gearbox_ECU;
        $vehicle->Compression_ratio = $request->Compression_ratio;
        $vehicle->Bore_X_stroke = $request->Bore_X_stroke;
        $vehicle->Type_of_turbo = $request->Type_of_turbo;
        $vehicle->Engine_number = $request->Engine_number;
        $vehicle->Read_options = $request->Read_options;
        $vehicle->Additional_options = $request->Additional_options;
        $vehicle->save();
        return redirect()->route('vehicles')->with('success',  'Vehicle added, successfully.');

    }

    public function update(Request $request){
        $vehicle = Vehicle::findOrFail($request->id);
        $vehicle->Name = $request->Name;
        $vehicle->Make = $request->Make;
        $vehicle->Engine_URL = $request->Engine_URL;
        $vehicle->Brand_image_URL = $request->Brand_image_URL;
        $vehicle->Chart_image_URL = $request->Chart_image_URL;
        $vehicle->Model = $request->Model;
        $vehicle->Generation = $request->Generation;
        $vehicle->Engine = $request->Engine;
        $vehicle->BHP_standard = $request->BHP_standard;
        $vehicle->BHP_tuned = $request->BHP_tuned;
        $vehicle->BHP_difference = $request->BHP_difference;
        $vehicle->TORQUE_standard = $request->TORQUE_standard;
        $vehicle->TORQUE_tuned = $request->TORQUE_tuned;
        $vehicle->TORQUE_difference = $request->TORQUE_difference;
        $vehicle->Type_of_fuel = $request->Type_of_fuel;
        $vehicle->Method = $request->Method;
        $vehicle->Tuningtype = date('Y-m-d', strtotime(str_replace('/', '-', $request->Tuningtype)));
        $vehicle->Cylinder_content = $request->Cylinder_content;
        $vehicle->Engine_ECU = $request->Engine_ECU;
        $vehicle->Gearbox_ECU = $request->Gearbox_ECU;
        $vehicle->Compression_ratio = $request->Compression_ratio;
        $vehicle->Bore_X_stroke = $request->Bore_X_stroke;
        $vehicle->Type_of_turbo = $request->Type_of_turbo;
        $vehicle->Engine_number = $request->Engine_number;
        $vehicle->Read_options = $request->Read_options;
        $vehicle->Additional_options = $request->Additional_options;
        $vehicle->save();
        return redirect()->route('vehicles')->with('success',  'Vehicle updated, successfully.');

    }
}
