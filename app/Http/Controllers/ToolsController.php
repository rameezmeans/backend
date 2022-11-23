<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    /**
     * Show the services table.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tools = Tool::orderBy('created_at', 'desc')->get();
        return view('tools.tools', ['tools' => $tools]);
    }

    public function create()
    {
        return view('tools.tools_create_edit');
    }

    public function edit($id)
    {
        $tool = Tool::findOrFail($id);
        return view('tools.tools_create_edit', ['tool' => $tool]);
    }

    public function delete(Request $request)
    {
        $tool = Tool::findOrFail($request->id);
        $tool->delete();
        $request->session()->put('success', 'Tool deleted, successfully.');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:tools|max:255|min:3',
            'label' => 'required',
            'icon' => 'required',
            
        ]);

        $created = new Tool();
        $created->name = $validated['name'];
        $created->label = $validated['label'];

        $file = $request->file('icon');
        $fileName = $file->getClientOriginalName();
        $file->move(public_path('icons'),$fileName);
        $created->icon = $fileName;

        $created->save();

        return redirect()->route('tools')->with(['success' => 'Tools added, successfully.']);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|min:3',
            'label' => 'required'
            
        ]);

        $created = Tool::findOrFail($request->id);
        $created->name = $validated['name'];
        $created->label = $validated['label'];

        if($request->file('icon')){
            $file = $request->file('icon');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('icons'),$fileName);
            $created->icon = $fileName;
        }

        $created->save();

        return redirect()->route('tools')->with(['success' => 'Tools updated, successfully.']);
    }
}
