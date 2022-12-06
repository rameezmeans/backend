<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EngineerFileNote;
use App\Models\File;
use App\Models\RequestFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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
        if(Auth::user()->is_admin){
            $files = File::orderBy('created_at', 'desc')->where('is_credited', 1)->get();
        }
        else if(Auth::user()->is_engineer){
            $files = File::orderBy('created_at', 'desc')->where('assigned_to', Auth::user()->id)->where('is_credited', 1)->get();
        }
        return view('files.files', ['files' => $files]);
    }

    public function deleteMessage(Request $request)
    {
        $note = EngineerFileNote::findOrFail($request->note_id);
        $note->delete();
        return response('Note deleted', 200);
    }

    public function deleteUploadedFile(Request $request)
    {
        $file = RequestFile::findOrFail($request->request_file_id);
        $file->delete();
        return response('File deleted', 200);
    }

    public function assignEngineer(Request $request){
       $file = File::findOrFail($request->file_id);
       $file->assigned_to = $request->assigned_to;
       $file->assignment_time = Carbon::now();
       $file->save();
       return Redirect::back()->with(['success' => 'Engineer Assigned to File.']);

    }

    public function changeStatus(Request $request){

        $file = File::findOrFail($request->file_id);
        $file->status = $request->status;
        $file->save();
        return Redirect::back()->with(['success' => 'File status changed.']);
    }

    public function fileEngineersNotes(Request $request)
    {
        $file = new EngineerFileNote();
        $file->egnineers_internal_notes = $request->egnineers_internal_notes;
        $file->engineer = true;
        $file->file_id = $request->file_id;
        $file->save();
        return redirect()->back()
        ->with('success', 'Engineer note successfully Added!')
        ->with('tab','chat');
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

        $file = File::findOrFail($request->file_id);
        

        if(!$file->response_time){

            $file->reupload_time = Carbon::now();
            $assignmentTimeInSeconds  = strtotime($file->assignment_time);
            $reloadTimeInSeconds  = strtotime($file->reupload_time);
            $file->response_time = $reloadTimeInSeconds - $assignmentTimeInSeconds;
        }


        $file->save();

        return response('file uploaded', 200);
    }

    public function getComments($file){

        $commentObj = Comment::where('engine', $file->engine);

        if($file->brand){
            $commentObj->where('make', $file->brand);
        }

        if($file->Model){
            $commentObj->where('model', $file->model);
        }

        // if($file->ecu){
        //     $commentObj->where('ecu', $file->ecu);
        // }

        if($file->generation){
            $commentObj->where('generation', $file->generation);
        }

        return $commentObj->get();
    }

     /**
     * Show the file.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        if(Auth::user()->is_admin){
            $file = File::findOrFail($id);
        }
        else if(Auth::user()->is_engineer){
            $file = File::where('id',$id)->where('assigned_to', Auth::user()->id)->first();
        }

        if(!$file){
            abort(404);
        }

        $engineers = User::where('is_engineer', 1)->get();
        $withoutTypeArray = $file->files->toArray();
        $unsortedTimelineObjects = [];

        foreach($withoutTypeArray as $r) {
            $fileReq = RequestFile::findOrFail($r['id']);
            if($fileReq->file_feedback){
                $r['type'] = $fileReq->file_feedback->type;
            }
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

        $comments = $this->getComments($file);
        
        return view('files.show', [ 'file' => $file, 'messages' => $unsortedTimelineObjects, 'engineers' => $engineers, 'comments' => $comments ]);
    }
}
