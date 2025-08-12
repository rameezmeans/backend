<?php

namespace App\Http\Controllers;

use App\Models\Combination;
use App\Models\CombinationServices;
use App\Models\Service;
use Illuminate\Http\Request;
use Twilio\Serialize;

class CombinationsController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $combinations = Combination::all();
        return view('combinations.index', ['combinations' =>$combinations]);
    }

    public function edit($id) {
        $services = Service::where('active', 1)->whereNull('subdealer_group_id')->get();
        $combination = Combination::findOrFail($id);
        $selectedServices = [];

        foreach($combination->services as $s){
            $selectedServices []= $s->service_id;
        }

        return view('combinations.create-edit-combination', ['selectedServices' => $selectedServices, 'services' => $services, 'combination' => $combination]);
    }

    public function delete(Request $request) {
        
        $combination = Combination::findOrFail($request->id);
        $combination->delete();
    }

    public function create() {
        $services = Service::where('active', 1)->whereNull('subdealer_group_id')->get();
        return view('combinations.create-edit-combination', ['services' => $services]);
    }

    public function update(Request $request) {
        $combination = Combination::findOrFail($request->id);
        $combination->name = $request->name;
        $combination->discounted_credits = $request->discounted_credits;
        $combination->save();

        return redirect()->back()->with('success', 'Combination updated, successfully.');

    }

    public function store(Request $request){

        $combination = new Combination;
        $combination->name = $request->name;

        $credits = 0;
        $services = $request->services;

        foreach($request->services as $service){
            $serviceObj = Service::findOrFail($service);
            $credits += $serviceObj->credits;
        }

        $combination->actual_credits = $credits;
        $combination->discounted_credits = $credits;
        $combination->save();

        foreach($services as $service){
            $obj = new CombinationServices;
            $obj->combination_id = $combination->id;
            $obj->service_id = $service;
            $obj->save();
        }
        return redirect()->route('edit-combination', $combination->id)->with('success', 'Combination added, successfully.');
    }
}
