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
    public function fmsPackages()
    {
        $packages = Package::whereNull('subdealer_group_id')->where('type', 'service')->where('from_master_subdealer', 1)->get();
        $evcPackages = Package::whereNull('subdealer_group_id')->where('type', 'evc')->where('from_master_subdealer', 1)->get();

        return view('packages.fms_packages', ['packages' => $packages, 'evcPackages' => $evcPackages]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::whereNull('subdealer_group_id')->where('type', 'service')->where('from_master_subdealer', 0)->get();
        $evcPackages = Package::whereNull('subdealer_group_id')->where('type', 'evc')->where('from_master_subdealer', 0)->get();
       
        return view('packages.index', ['packages' => $packages, 'evcPackages' => $evcPackages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fmsCreate()
    {
        return view('packages.fms_create_edit');
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
        $package->desc = $request->desc;

        if(isset($request->from_master_subdealer)){
            $package->from_master_subdealer = $request->from_master_subdealer;
        }
        else{
            $package->from_master_subdealer = 0;
        }

        $package->save();

        if($package->from_master_subdealer){
            return redirect()->route('fms-packages')->with(['success' => 'Package created!']);
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function fmsEdit($id)
    {
        $package = Package::findOrFail($id);
        return view('packages.fms_create_edit', ['package' => $package]);
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
        $package->desc = $request->desc;
        $package->save();

        if(isset($request->from_master_subdealer)){
            $package->from_master_subdealer = $request->from_master_subdealer;
        }
        else{
            $package->from_master_subdealer = 0;
        }

        $package->save();

        if($package->from_master_subdealer){
            return redirect()->route('fms-packages')->with(['success' => 'Package udpated!']);
        }
        return redirect()->route('packages')->with(['success' => 'Package udpated!']);
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
