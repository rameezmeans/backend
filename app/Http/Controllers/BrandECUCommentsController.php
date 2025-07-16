<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandECUCommentRequest;
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

        $ecus = Vehicle::orderBy('Engine_ECU', 'asc')
            ->select('Engine_ECU')
            ->whereNotNull('Engine_ECU')
            ->where('Make', '=', $brandMake)
            ->where('Engine_ECU', '!=', '')
            ->distinct()
            ->get();

            $ecusArray = [];

        foreach($ecus as $e){
            $temp = preg_split('/[\/\\\\]/', $e->Engine_ECU); // split on / or \
            $ecusArray = array_merge($ecusArray, $temp);
        }

        $ecusArray = array_values(array_unique($ecusArray));

        return response()->json($ecusArray);
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

    public function add(StoreBrandECUCommentRequest $request)
    {
        $brandComment = new BrandECUComments();
        $brandComment->brand = $request->brand;
        $brandComment->ecu = $request->ecu;
        $brandComment->type = $request->type;
        $brandComment->comment = $request->comment;
        $brandComment->save();

        return redirect()->route('brand-ecu-comments')->with('success', 'Comment added successfully!');
    }
}