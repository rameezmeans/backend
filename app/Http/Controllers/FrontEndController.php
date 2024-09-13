<?php

namespace App\Http\Controllers;

use App\Models\FrontEnd;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('adminOnly');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $frontends = FrontEnd::all();
        return view('frontends.index',['frontends' => $frontends]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontends.create-edit');
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
            'url' => 'required|max:255|min:3',
        ]);

        $frontend = new FrontEnd();
        $frontend->name = $request->name;
        $frontend->url = $request->url;
        $frontend->description = $request->description;
        $frontend->save();

        return redirect()->route('frontends')->with(['success' => 'Frontends added, successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FrontEnd  $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function show(FrontEnd $frontEnd)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FrontEnd  $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $frontend = FrontEnd::findOrFail($id);
        return view('frontends.create-edit', ['frontend' => $frontend] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FrontEnd  $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $frontend = FrontEnd::findOrFail($request->id);
        $frontend->name = $request->name;
        $frontend->url = $request->url;
        $frontend->description = $request->description;
        $frontend->save();

        return redirect()->route('frontends')->with(['success' => 'Frontends updated, successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FrontEnd  $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $frontend = FrontEnd::findOrFail($request->id);
        $frontend->delete();
    }
}
