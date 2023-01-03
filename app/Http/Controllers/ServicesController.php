<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ServicesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        return view('services.services', ['services' => $services]);
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
        return view('services.services_add_edit', ['service' => $service]);
    }

     /**
     * add the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:services|max:255|min:3',
            'type' => 'required',
            'credits' => 'required',
            'icon' => 'required',
            'description' => 'required',
        ]);

        $created = new Service();
        $created->name = $validated['name'];
        $created->type = $validated['type'];
        $created->credits = $validated['credits'];
        $created->description = $validated['description'];

        $file = $request->file('icon');
        $fileName = $file->getClientOriginalName();
        $file->move(public_path('icons'),$fileName);
        $created->icon = $fileName;

        $created->save();

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
        $service->credits = $request->credits;
        $service->type = $request->type;
        $service->description = $request->description;

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
        // dd($sorting);
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
        // dd($options);
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
     * delete the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $service->delete();
        $request->session()->put('success', 'Service deleted, successfully.');
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
