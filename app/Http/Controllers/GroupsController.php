<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function index(){
        $groups = Group::all();
        return view('groups.groups', ['groups' => $groups]);
    }

    public function create(){
        return view('groups.groups_create_edit');
    }

    public function edit($id){
        $group = Group::findOrFail($id);
        return view('groups.groups_create_edit', [ 'group' => $group ]);
    }

    public function add(Request $request){

        $validated = $request->validate([
            'name' => 'required|unique:groups|max:255|min:3',
            'tax' => 'required|numeric',
            'discount' =>  'required|numeric',
            'raise' => 'required|numeric',
            'bonus_credits' => 'required|numeric',
        ]);

        $group = new Group();
        $group->name = $validated['name'];
        $group->tax = $validated['tax'];
        $group->discount = $validated['discount'];
        $group->raise = $validated['raise'];
        $group->bonus_credits = $validated['bonus_credits'];
        $group->save();

        return redirect()->route('groups')->with(['success' => 'Group added, successfully.']);

    }

    public function update(Request $request){

        $group = Group::findOrFail($request->id);
        $group->name = $request->name;
        $group->tax = $request->tax;
        $group->discount = $request->discount;
        $group->raise = $request->raise;
        $group->bonus_credits = $request->bonus_credits;
        $group->save();

        return redirect()->route('groups')->with(['success' => 'Group added, successfully.']);

    }

    public function delete(Request $request)
    {
        $group = Group::findOrFail($request->id);
        $group->delete();
        $request->session()->put('success', 'Group deleted, successfully.');

    }
}
