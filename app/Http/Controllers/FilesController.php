<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EmailTemplate;
use App\Models\EngineerFileNote;
use App\Models\File;
use App\Models\RequestFile;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use PDF;
use SebastianBergmann\Template\Template;

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

    public function editMessage(Request $request) {

        $message = EngineerFileNote::findOrFail($request->id);
        $message->egnineers_internal_notes = $request->message;
        $message->save();
        
        return redirect()->back()
        ->with('success', 'Engineer note successfully Edited!')
        ->with('tab','chat');
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

        File::where('is_credited', 0)->delete(); // remove unneccesary NOT credited files

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
       $engineer = User::findOrFail($request->assigned_to);
    
       $template = EmailTemplate::where('name', 'Engineer Assignment Email')->first();
       
       $html = $template->html;

       $html = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html);
       $html = str_replace("#brand", $file->brand ,$html);
       $html = str_replace("#model", $file->model ,$html);
       $html = str_replace("#version", $file->version,$html);
       $html = str_replace("#engine", $file->engine,$html);
       $html = str_replace("#ecu", $file->ecu,$html);

        \Mail::send([], [], function (Message $message) use ($html, $engineer) {
                    $message->to($engineer->email)
                ->subject('ECUTech: You are assigned to a Task')
                ->from('info@ecutech.gr')
                ->setBody($html, 'text/html');
        });
    //    \Mail::to($engineer->email)->send(new \App\Mail\AssignEngineerToTaskMail(['engineer' => $engineer]));
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

        $old = File::findOrFail($request->file_id);
        $old->checked_by = 'engineer';
        $old->save();

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

        if($file->status == 'submitted'){
            $file->status = 'completed';
            $file->save();
        }
        
        if(!$file->response_time){

            $file->reupload_time = Carbon::now();
            $assignmentTimeInSeconds  = strtotime($file->assignment_time);
            $reloadTimeInSeconds  = strtotime($file->reupload_time);
            $file->response_time = $reloadTimeInSeconds - $assignmentTimeInSeconds;
        }

        if($file->original_file_id){
            $old = File::findOrFail($file->original_file_id);
            $old->checked_by = 'engineer';
            $old->save();
        }
        
            $file->checked_by = 'engineer';
            $file->save();
    

        return response('file uploaded', 200);
    }

    public function reports(){

        $engineers = User::where('is_engineer', 1)->get();
        return view('files.reports', ['engineers' => $engineers]);

    }

    public function getEngineersFiles(Request $request){

        $files = $this->getReportFiles($request->engineer, $request->start, $request->end);

        $html = '';
        $hasFiles = false;
        $count = 1;
        foreach($files as $file){

            $hasFiles = true;
            
            if($file->assigned){
                $assigned = $file->assigned->name;
            }
            else{
                $assigned = 'By Admin';
            }

            $options = '';
            if($file->options){
                foreach($file->options() as $option){
                    $options .= '<img class="p-l-10" alt="'.$option.'" width="33" height="33" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option;
                }
            }

            $html .= '<tr class="redirect-click" data-redirect="'.route('file', $file->id).'" role="row">';
            $html .= '<td>'. $count .'</td>';
            $html .= '<td>'.$file->brand .$file->engine .' '. $file->vehicle()->TORQUE_standard .' '.'</td>';
            $html .= '<td>'.$assigned.'</td>';    
            $html .= '<td><img class="p-r-5" alt="'.$file->stages.'" width="33" height="33" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'">'.$file->stages
            
            .$options.'</td>';
            $html .= '<td>'.\Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans().'</td>';
            $html .= '<td>'.\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i: A').'</td>';
            $html .= '</tr>';
            $count++;
        }

        return response()->json(['html' =>$html, 'has_files' => $hasFiles ], 200);
    }

    public function getEngineersReport(Request $request) {

        $engineer = $request->engineer;
        $start = $request->start;
        $end = $request->end;
        $files = $this->getReportFiles($engineer, $start, $end);
        $pdf = PDF::loadView('files.pdf', [ 'files' => $files, 'end' => $end, 'start' => $start, 'engineer' => $engineer ]);
        if($engineer == 'all_engineers'){
            return $pdf->download('all_engineers_report.pdf');
        }
        return $pdf->download(User::findOrFail($engineer)->name."_report.pdf");
    }

    public function getReportFiles($engineer, $start, $end){

        $filesObject = File::whereNotNull('response_time')->where('is_credited', 1);

        if($engineer != 'all_engineers'){
            $filesObject = $filesObject->where('assigned_to', $engineer);
        }

        if($start){
            $date = str_replace('/', '-', $start);
            $startDate = date('Y-m-d', strtotime($date));
            $filesObject = $filesObject->whereDate('created_at', '>=' , $startDate);
        }

        if($end){
            $date = str_replace('/', '-', $end);
            $endDate = date('Y-m-d', strtotime($date));
            $filesObject = $filesObject->whereDate('created_at', '<=' , $endDate);
        }
        
        return $filesObject->get();
    }

    public function getComments($file){

        $commentObj = Comment::where('engine', $file->engine);

        if($file->brand){
            $commentObj->where('make', $file->brand);
        }

        if($file->Model){
            $commentObj->where('model', $file->model);
        }


        if($file->ecu){
            $commentObj->where('ecu', $file->ecu);
        }

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

        // if($file->status == 'submitted'){
        //     $file->status = 'completed';
        //     $file->save();
        // }

        if($file->checked_by == 'customer'){
            $file->checked_by = 'seen';
            $file->save();
        }

        $vehicle = Vehicle::where('Make', $file->brand)
        ->where('Model', $file->model)
        ->where('Generation', $file->version)
        ->where('Engine', $file->engine)
        ->first();

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
        
        return view('files.show', [ 'vehicle' => $vehicle,'file' => $file, 'messages' => $unsortedTimelineObjects, 'engineers' => $engineers, 'comments' => $comments ]);
    }
}
