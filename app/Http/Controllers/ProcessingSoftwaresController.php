<?php

namespace App\Http\Controllers;

use App\Models\File;
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

        $brands = File::select('brand')->distinct()->orderBy('brand', 'asc')->get();

        // $softwaresAndBrandsRecords = FileReplySoftwareService::join('files', 'file_reply_software_service.file_id', '=', 'files.id')
        // ->whereNotNull('files.ecu')
        // ->select('files.brand','files.ecu', 'file_reply_software_service.software_id', 'file_reply_software_service.service_id')
        // ->distinct()->get();

        // $softwaresAndBrandsRecords = File::join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        // ->whereNotNull('files.ecu')
        // ->select('files.brand','files.ecu', 'files.id as file_id', 'file_reply_software_service.software_id as software_id', 'file_reply_software_service.service_id as service_id')
        // ->distinct('file_id', 'software_id', 'service_id')
        // ->orderBy('file_id', 'desc')
        // ->get();

        // $services = FileReplySoftwareService::select('service_id')->distinct()->orderBy('service_id', 'asc')->get();
        
        // $softwares = FileReplySoftwareService::select('software_id')->distinct()->orderBy('software_id', 'asc')->get();
        
        return view('processing_softwares.report', ['brands' => $brands]);
    }  
    
    public function ajaxSoftwareReport(Request $request){

        // dd($request->all());

        $softwaresAndBrandsRecords = File::join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        ->whereNotNull('files.ecu')
        ->where('files.brand', $request->brand)
        ->where('files.ecu', $request->ecu)
        ->select('file_reply_software_service.service_id as service_id','file_reply_software_service.software_id as software_id', '*')
        ->distinct('service_id')
        // ->orderBy('file_id', 'desc')
        ->get();

        $rows = "";
        // $replies = 0;

        dd($softwaresAndBrandsRecords);

        foreach($softwaresAndBrandsRecords as $record){
            $rows .= 
            
            "<td>".\App\Models\Service::findOrFail($record->service_id)->name."</td>".
            "<td>".\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name."</td>"
            // "<td>".all_files_with_this_ecu_brand_and_service_and_software($record->file_id, $record->service_id, $record->software_id)."</td>"
            ."</tr>";

            // $replies += all_files_with_this_ecu_brand_and_service_and_software($record->file_id, $record->service_id, $record->software_id);
        }

        return response()->json(['html' =>$rows, 'tasks' => count($softwaresAndBrandsRecords), 'replies' => $replies ], 200);
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
