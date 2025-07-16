<?php

namespace App\Http\Controllers;

use App\Models\BrandECUComments;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class BrandECUCommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('adminOnly');
    }

    public function index() {
        $brandEcuComments = BrandECUComments::all();
        return view('brand_ecu_comments.listings', ['brandEcuComments' => $brandEcuComments]);
    }

    public function getECUForComments($brandMake){
        
        $ecus = Vehicle::orderBy('ecu', 'asc')
            ->select('ecu')
            ->whereNotNull('ecu')
            ->where('ecu', '=', $brandMake)
            ->where('ecu', '!=', '')
            ->distinct()
            ->get();

        return response()->json($ecus);
    }
    
    public function create()
    {
        $brands = Vehicle::orderBy('make', 'asc')
            ->select('make')
            ->whereNotNull('make')
            ->where('make', '!=', '')
            ->distinct()
            ->get();

        return view('brand_ecu_comments.create', compact('brands'));
    }
}