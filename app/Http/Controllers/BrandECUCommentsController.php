<?php

namespace App\Http\Controllers;

use App\Models\BrandECUComments;
use App\Models\Vehicle;

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

    public function create()
    {
        $brands = Vehicle::OrderBy('make', 'asc')->select('make')->distinct()->get();
        return view('brand_ecu_comments.create', compact('brands'));
    }
}