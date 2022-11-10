<?php

namespace App\Http\Controllers;

use App\Models\EngineerFileNote;
use App\Models\File;
use App\Models\RequestFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FilesController extends Controller
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

    public function download($file_name) {
        
        $file_path = public_path('/../../portal/public/uploads/'.$file_name);
        return response()->download($file_path);
    }

    /**
     * Show the files table.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $files = File::orderBy('created_at', 'desc')->get();
        return view('files.files', ['files' => $files]);
    }

    public function fileEngineersNotes(Request $request)
    {
        $file = new EngineerFileNote();
        $file->egnineers_internal_notes = $request->egnineers_internal_notes;
        $file->engineer = true;
        $file->file_id = $request->file_id;
        $file->save();
        return redirect()->back()->with('success', 'Engineer note successfully Added!');
    }

    public function uploadFileFromEngineer(Request $request)
    {
        $attachment = $request->file('file');
        $fileName = $attachment->getClientOriginalName();
        $attachment->move(public_path("/../../portal/public/uploads/"),$fileName);

        $engineerFile = new RequestFile();
        $engineerFile->request_file = $fileName;
        $engineerFile->file_type = 'engineer_file';
        $engineerFile->tool_type = 'not_relevant';
        $engineerFile->master_tools = 'not_relevant';
        $engineerFile->file_id = $request->file_id;
        $engineerFile->engineer = true;
        $engineerFile->save();

        return response('file uploaded', 200);
    }

     /**
     * Show the file.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        $file = File::findOrFail($id);
        // dd($file);
        $withoutTypeArray = $file->files->toArray();
        $unsortedTimelineObjects = [];

        foreach($withoutTypeArray as $r) {
            $fileReq = RequestFile::findOrFail($r['id']);
            // if($fileReq->file_feedback){
            //     $r['type'] = $fileReq->file_feedback->type;
            // }
            $unsortedTimelineObjects []= $r;
        } 

        $createdTimes = [];

        foreach($file->files->toArray() as $t) {
            $createdTimes []= $t['created_at'];
        } 
    
        foreach($file->engineer_file_notes->toArray() as $a) {
            $unsortedTimelineObjects []= $a;
            $createdTimes []= $a['created_at'];
        }   

        foreach($file->file_internel_events->toArray() as $b) {
            $unsortedTimelineObjects []= $b;
            $createdTimes []= $b['created_at'];
        } 

        foreach($file->file_urls->toArray() as $b) {
            $unsortedTimelineObjects []= $b;
            $createdTimes []= $b['created_at'];
        } 

        array_multisort($createdTimes, SORT_ASC, $unsortedTimelineObjects);
        // dd($unsortedTimelineObjects);
        return view('files.show', ['file' => $file, 'messages' => $unsortedTimelineObjects]);
    }
}
