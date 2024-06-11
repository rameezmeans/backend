<?php

namespace App\Http\Controllers;

use App\Models\FileReplySoftwareService;
use App\Models\ProcessingSoftware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessingSoftwaresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $processingSoftwares = ProcessingSoftware::all();
        return view('processing_softwares.index', [ 'processingSoftwares' => $processingSoftwares ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {   
        
        return view('processing_softwares.add_edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        
        return view('processing_softwares.add_edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|unique:processing_softwares|max:255|min:3',
        ]);

        $ps = new ProcessingSoftware();
        $ps->name = $request->name;
        $ps->save();

        return redirect()->back()->with(["success" => "Software Added."]);
        
    }

    public function softwareReport(){
        $softwaresAndBrandsRecords = FileReplySoftwareService::all();
        return view('processing_softwares.report', ['softwaresAndBrandsRecords' => $softwaresAndBrandsRecords]);
    }   

    public function update(Request $request)
    {   
        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|max:255|min:3',
        ]);

        $ps = ProcessingSoftware::findOrFail($request->id);
        $ps->name = $request->name;
        $ps->save();

        return redirect()->back()->with(["success" => "Software udated."]);
        
    }

    public function delete(Request $request)
    {   
        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $ps = ProcessingSoftware::findOrFail($request->id);
        $ps->delete();
        
    }
}
