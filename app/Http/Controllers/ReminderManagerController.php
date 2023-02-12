<?php

namespace App\Http\Controllers;

use App\Models\ReminderManager;
use Illuminate\Http\Request;

class ReminderManagerController extends Controller
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
        $manager = $this->getManager();
        return view('reminder_manager.index', [ 'manager' => $manager ]);
    }

    public function setStatus(Request $request){
        
        $field = ReminderManager::where('type', $request->field)->first();
        $field->active = $request->checked === 'true'? true: false;
        $field->save();

    }

    public function getManager(){

        $reminderManagers = ReminderManager::all();
        $manager = [];
        foreach($reminderManagers as $row){
            $temp[$row->type] = $row->active;
            $manager = array_merge($manager, $temp);
        }

        return $manager;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReminderManager  $reminderManager
     * @return \Illuminate\Http\Response
     */
    public function show(ReminderManager $reminderManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReminderManager  $reminderManager
     * @return \Illuminate\Http\Response
     */
    public function edit(ReminderManager $reminderManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReminderManager  $reminderManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReminderManager $reminderManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReminderManager  $reminderManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReminderManager $reminderManager)
    {
        //
    }
}
