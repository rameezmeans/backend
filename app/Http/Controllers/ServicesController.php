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
        $this->middleware('auth');
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
            'icon_url' => 'required',
            'description' => 'required',
        ]);
        $created = Service::create($validated);

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
        $service->icon_url = $request->icon_url;
        $service->description = $request->description;
        $service->save();

        return redirect()->route('services')->with(['success' => 'Service updated, successfully.']);
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
}
