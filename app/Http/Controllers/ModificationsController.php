<?php

namespace App\Http\Controllers;

use App\Models\Modification;
use Illuminate\Http\Request;

class ModificationsController extends Controller
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

    public function index() {
        $modifications = Modification::all();
        return view( 'modifications.index', [ 
            'modifications' => $modifications, 
        ]);
    }

    public function update(Request $request) {

        $modification = Modification::findOrfail($request->id);
        $modification->name = $request->name;
        $modification->label = str_replace(" ","_",strtolower($request->name));
        $modification->save();

        return redirect()->route('modifications')->with(['success' => 'Modification updated!']);
    }

    public function add(Request $request) {

            $modification = new Modification();
            $modification->name = $request->name;
            $modification->label = str_replace(" ","_",strtolower($request->name));
            $modification->save();

            return redirect()->route('modifications')->with(['success' => 'Modification created!']);
        }

    public function create() {
        return view('modifications.create');

    }

    public function edit($id) {
        $modification = Modification::findOrfail($id);
        return view('modifications.create', ['modification' => $modification]);
    }

    public function delete(Request $request) {
        $modification = Modification::findOrfail($request->id);
        $modification->delete();
    }
}
