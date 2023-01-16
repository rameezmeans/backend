<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class MessageTemplatesController extends Controller
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

    public function index() {
        
        $templates = MessageTemplate::all();
        return view('message_templates.index', ['templates' => $templates]);

    }

    public function add() {
        
        return view( 'message_templates.edit_add_message_template' );
        
    }

    public function edit($id) {

        $template = MessageTemplate::findOrFail($id);
        return view( 'message_templates.edit_add_message_template', [ 'template' => $template ] );
    }

    public function post(Request $request) {

        $template = new MessageTemplate();
        $template->name = $request->name;
        $template->text = $request->text;
        $template->save();

        return redirect()->route('message-templates')->with(['success' => 'Template added, successfully.']);


    }

    public function update(Request $request) {

        $template = MessageTemplate::findOrFail($request->id);
        $template->name = $request->name;
        $template->text = $request->text;
        $template->save();

        return redirect()->route('message-templates')->with(['success' => 'Template updated, successfully.']);

    }

    public function delete(Request $request) {

        $template = MessageTemplate::findOrFail($request->id);
        $template->delete();
        $request->session()->put('success', 'Template deleted, successfully.');
    }
}
