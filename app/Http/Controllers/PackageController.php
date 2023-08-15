<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::whereNull('subdealer_group_id')->where('type', 'service')->get();
        $evcPackages = Package::whereNull('subdealer_group_id')->where('type', 'evc')->get();
       
        return view('packages.index', ['packages' => $packages, 'evcPackages' => $evcPackages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('packages.packages_create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|min:3',
            'credits' => 'required|numeric',
            'actual_price' =>  'required|numeric',
            'discounted_price' => 'required|numeric',
        ]);

        $package = new Package();
        $package->name = $request->name;
        $package->credits = $request->credits;
        $package->actual_price = $request->actual_price;
        $package->discounted_price = $request->discounted_price;
        $package->type = $request->type;
        $package->save();

        return redirect()->route('packages')->with(['success' => 'Package created!']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('packages.packages_create_edit', ['package' => $package]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $package = Package::findOrFail($request->package_id);

        if($request->status == 'true'){
            $package->active = true;
        }
        else{
            $package->active = false;
        }
        $package->save();

        return response()->json(['success' => 'status changed']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $package = Package::findOrFail($request->id);
        $package->name = $request->name;
        $package->credits = $request->credits;
        $package->actual_price = $request->actual_price;
        $package->discounted_price = $request->discounted_price;
        $package->save();

        return redirect()->route('packages')->with(['success' => 'Package updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $package = Package::findOrFail($request->id);
        $package->delete();
    }
}
