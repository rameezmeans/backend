<?php

namespace App\Http\Controllers;

use App\Models\ReminderManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $ecuTechManager = $this->getManager(1);
        $tuningxManager = $this->getManager(2);
        return view('reminder_manager.index', [ 'ecuTechManager' => $ecuTechManager, 'tuningxManager' => $tuningxManager ]);
    }

    public function setStatus(Request $request){
        
        $field = ReminderManager::where('type', $request->field)
        ->where('front_end_id', $request->front_end_id)
        ->first();
        $field->active = $request->checked === 'true'? true: false;
        $field->save();

    }

    public function getAllManager(){
        
        $reminderManagers = ReminderManager::whereNull('subdealer_group_id')
        ->get();
        $manager = [];
        foreach($reminderManagers as $row){
            $temp[$row->type.$row->front_end_id] = $row->active;;
            $manager = array_merge($manager, $temp);
        }

        return $manager;
    }

    public function getManager($frontendID){
        
        $reminderManagers = ReminderManager::whereNull('subdealer_group_id')
        ->where('front_end_id', $frontendID)
        ->get();
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
