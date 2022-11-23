<?php

namespace App\Http\Controllers;

use App\Models\BoschNumber;
use Illuminate\Http\Request;

class BoschECUNumbersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    /**
     * Show the services table.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $numbers = BoschNumber::orderBy('created_at', 'desc')->get();
        return view('numbers.numbers', ['numbers' => $numbers]);
    }

    public function create()
    {
        return view('numbers.numbers_create_edit');
    }

    public function edit($id)
    {
        $number = BoschNumber::findOrFail($id);
        return view('numbers.numbers_create_edit', ['number' => $number]);
    }

    public function delete(Request $request)
    {
        $number = BoschNumber::findOrFail($request->id);
        $number->delete();
        $request->session()->put('success', 'ECU Number deleted, successfully.');

    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'manufacturer_number' => 'required',
            'ecu' => 'required',
            'ecu_brand' => 'required',
            
        ]);

        $created = new BoschNumber();
        $created->manufacturer_number = $validated['manufacturer_number'];
        $created->ecu = $validated['ecu'];
        $created->ecu_brand = $validated['ecu_brand'];
        $created->status = 1;
        $created->save();

        return redirect()->route('numbers')->with(['success' => 'ECU Number added, successfully.']);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'manufacturer_number' => 'required',
            'ecu' => 'required',
            'ecu_brand' => 'required',
            
        ]);

        $created = BoschNumber::findOrFail($request->id);
        $created->manufacturer_number = $validated['manufacturer_number'];
        $created->ecu = $validated['ecu'];
        $created->ecu_brand = $validated['ecu_brand'];
        $created->save();

        return redirect()->route('numbers')->with(['success' => 'ECU Number updated, successfully.']);
    }

}
