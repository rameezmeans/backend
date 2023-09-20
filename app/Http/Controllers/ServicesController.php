<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\TranslationController;
use App\Models\Translation;

class ServicesController extends Controller
{
    private $translationObj;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->translationObj = new TranslationController();
        $this->middleware('auth',['except' => ['getStages', 'getOptions']]);
        $this->middleware('adminOnly', ['except' => ['getStages', 'getOptions']]);
    }

    /**
     * Show the services table.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->get();

        $sharedServices = Service::orderBy('sorting', 'asc')
        ->join('services_subdealer_groups', 'services_subdealer_groups.service_id', '=', 'services.id' )
        
        ->select('*','services.id AS id',
          'services.credits AS credits',
          'services.created_at AS created_at'
          )
        
        
        ->get();

        return view('services.services', ['services' => $services, 'sharedServices' => $sharedServices]);
    }

    /**
     * create the services.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('services.services_add_edit');
    }

    /**
     * edit the services.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $modelInstance = Translation::where('model_id', $service->id)->where('model', 'Service')->first();
        $vehicleTypes = explode(',', $service->vehicle_type);
        return view('services.services_add_edit', [ 'modelInstance' => $modelInstance, 'service' => $service, 'vehicleTypes' => $vehicleTypes ]);
    }

     /**
     * add the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|min:3',
            'label' => 'required|max:255|min:3',
            'type' => 'required',
            'credits' => 'required',
            'tuningx_credits' => 'required',
            'icon' => 'required',
            'description' => 'required',
            'greek_description' => '',
            'vehicle_type' => 'required',
        ]);

        $created = new Service();
        $created->name = $validated['name'];
        $created->label = $validated['label'];
        $created->type = $validated['type'];
        $created->vehicle_type = implode( ',', $validated['vehicle_type'] );
        $created->credits = $validated['credits'];
        $created->tuningx_credits = $validated['tuningx_credits'];
        $created->description = $validated['description'];

        $file = $request->file('icon');
        $fileName = $file->getClientOriginalName();
        $file->move(public_path('icons'),$fileName);
        $created->icon = $fileName;

        $created->save();

        $texts['english'] =  $validated['description'];
        $texts['greek'] =  $validated['greek_description'];

        $this->translationObj->store($created->id, 'Service', $texts);

        return redirect()->route('services')->with(['success' => 'Service added, successfully.']);
    }

     /**
     * update the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $service->name = $request->name;
        $service->label = $request->label;
        $service->credits = $request->credits;
        $service->tuningx_credits = $request->tuningx_credits;
        $service->tuningx_slave_credits = $request->tuningx_slave_credits;
        $service->type = $request->type;
        $service->vehicle_type = implode( ',', $request->vehicle_type );
    
        $service->description = $request->description;
        $texts['english'] = $request->description;;
        $texts['greek'] = $request->greek_description;

        $this->translationObj->store($request->id, 'Service', $texts);
        
        if($request->file('icon')){
            $file = $request->file('icon');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('icons'),$fileName);
            $service->icon = $fileName;
        }

        $service->save();

        return redirect()->route('services')->with(['success' => 'Service updated, successfully.']);
    }

    public function saveSorting(Request $request){

        $sorting = json_decode($request->sorting);
        $sort = 1;
        foreach($sorting as $row){
            $service = Service::findOrFail($row->id);
            $service->sorting = $sort;
            $service->save();
            $sort++;
        }

        return response()->json(['msg' => 'sorting done']);
    }

    public function sortingServices(){

        $options = Service::orderBy('sorting', 'asc')->where('active', 1)->where('type', 'option')->get();
        $stages = Service::orderBy('sorting', 'asc')->where('active', 1)->where('type', 'tunning')->get();
        return view('services.sorting', ['options' => $options, 'stages' => $stages]);
    }

    /**
     * update the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changeStatus(Request $request)
    {
        $service = Service::findOrFail($request->service_id);
        if($request->status == 'true'){
            $service->active = true;
        }
        else{
            $service->active = false;
        }
        $service->save();
        return response()->json(['success' => 'status changed']);
    }

    /**
     * update the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changeTuningxStatus(Request $request)
    {
        $service = Service::findOrFail($request->service_id);
        if($request->status == 'true'){
            $service->tuningx_active = true;
        }
        else{
            $service->tuningx_active = false;
        }
        $service->save();
        return response()->json(['success' => 'status changed']);
    }

     /**
     * delete the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $service->delete();
        $request->session()->put('success', 'Service deleted, successfully.');
        
    }

    /**
     * delete the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getStages(Request $request)
    {
        $stages = Service::orderBy('sorting', 'asc')->where('type', 'tunning')->where('active', 1)->get();
        return response()->json(['stages' => $stages]);
    }

    /**
     * delete the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getOptions(Request $request)
    {
        $options = Service::orderBy('sorting', 'asc')->where('type', 'option')->where('active', 1)->get();
        return response()->json(['options' => $options]);
    }
}
