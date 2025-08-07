<?php

namespace App\Http\Controllers;

use App\Models\WorkHours;
use Illuminate\Http\Request;

class WorkHoursController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function index(){

        $workHours = WorkHours::all();
        return view('work_hours.index', ['workHours' => $workHours]);
    }

    public function edit($id){

        $workHour = WorkHours::findOrFail($id);
        return view('work_hours.edit_work_hour', ['workHour' => $workHour]);
    }

    public function update(Request $request){

        $time = WorkHours::findOrFail($request->id);
        $time->start = $request->start;
        $time->end = $request->end;
        $time->save();

        return redirect()->route('work-hours')->with(['success' => 'Time Updated, successfully.']);
    }

}
