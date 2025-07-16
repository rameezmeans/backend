<?php

namespace App\Http\Controllers;

use App\Models\BrandECUComments;

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
}