<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $vehicles = Vehicle::all();
        return view('vehicles.vehicles', ['vehicles' => $vehicles]);
    }

    public function show($id){
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicles.show', ['vehicle' => $vehicle]);
    }

    public function update(Request $request){
        $vehicle = Vehicle::findOrFail($request->id);
        $vehicle->Name = $request->Name;
        $vehicle->Make = $request->Make;
        $vehicle->save();
        return redirect()->route('vehicles')->with(['success' => 'Vehicle updated, successfully.']);

    }
}
