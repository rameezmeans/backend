<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplatesController extends Controller
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

        $tuningxTemplates = EmailTemplate::whereNull('subdealer_group_id')
        ->where('front_end_id', 2)
        ->get();

        $efilesTemplates = EmailTemplate::whereNull('subdealer_group_id')
        ->where('front_end_id', 3)
        ->get();

        $ecutechTemplates = EmailTemplate::whereNull('subdealer_group_id')
        ->where('front_end_id', 1)
        ->get();

         $ctfhTemplates = EmailTemplate::whereNull('subdealer_group_id')
        ->where('front_end_id', 4)
        ->get();

        return view('email_templates.index',
        [
            'ecutechTemplates' => $ecutechTemplates,
            'tuningxTemplates' => $tuningxTemplates,
            'efilesTemplates' => $efilesTemplates,
            'ctfhTemplates' => $ctfhTemplates,
        ]);
    }

    public function add() {
        return view('email_templates.add_edit');
    }

    public function edit($id) {
        $template = EmailTemplate::findOrFail($id);
        return view('email_templates.add_edit', ['template' => $template]);
    }

    public function post(Request $request) {

        $template = new EmailTemplate();
        $template->name = $request->name;
        $template->html = $request->html;
        $template->save();

        return response()->json('Done', 200);

    }

    public function update(Request $request) {

        $template = EmailTemplate::findOrFail($request->id);
        // $template->name = $request->name;
        $template->html = $request->html;
        $template->save();

        return response()->json('Done', 200);
    }

    public function delete(Request $request) {

        $template = EmailTemplate::findOrFail($request->id);
        $template->delete();
        $request->session()->put('success', 'Template deleted, successfully.');
    }

    public function test() {
        
        // return view('files.feedback_recorded');
        return view('files.assign_email');
    }

}
