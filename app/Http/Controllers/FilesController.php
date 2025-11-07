<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EmailReminder;
use App\Models\EmailTemplate;
use App\Models\EngineerFileNote;
use App\Models\File;
use App\Models\MessageTemplate;
use App\Models\NewsFeed;
use App\Models\ReminderManager;
use App\Models\RequestFile;
use App\Models\Schedualer;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\ReminderManagerController;
use App\Models\ACMFile;
use App\Models\AlientechFile;
use App\Models\AutotunerEncrypted;
use App\Models\Credit;
use App\Models\EngineerAssignmentLog;
use App\Models\EngineerOptionsOffer;
use App\Models\FileFeedback;
use App\Models\FileInternalEvent;
use App\Models\FileMessage;
use App\Models\FileReasonsToReject;
use App\Models\FileReplySoftwareService;
use App\Models\FileService;
use App\Models\FilesStatusLog;
use App\Models\FileUrl;
use App\Models\FrontEnd;
use App\Models\Key;
use App\Models\Log;
use App\Models\MagicEncryptedFile;
use App\Models\NewRequestComment;
use App\Models\OptionComment;
use App\Models\ProcessedFile;
use App\Models\ProcessingSoftware;
use App\Models\ReasonsToReject;
use App\Models\RoleUser;
use App\Models\Service;
use App\Models\Tool;
use App\Models\UploadLater;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PDF;
use SebastianBergmann\Template\Template;
use Svg\Tag\Rect;
use Twilio\Rest\Client;
use Yajra\DataTables\DataTables;

use Stichoza\GoogleTranslate\GoogleTranslate;

use PDO;
use PDOException;
use stdClass;
use Symfony\Component\Mailer\Exception\TransportException;
use Twilio\Serialize;

class FilesController extends Controller
{
    private $manager;
    private $alientechObj;
    private $magicObj;
    private $autotunerObj;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->alientechObj = new AlientechController();
        $this->magicObj = new MagicController();
        $this->autotunerObj = new AutotunerController();
        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();
        $this->middleware('auth',['except' => ['recordFeedback']]);
    }

    public function testing(){
        $supportFiles = File::withOldEngineerNotes(5)->get();

        $supportMessageRecord = Key::where('key','support_messages_engineer')->first()->value;

        // dd($supportMessageRecord);

        foreach($supportFiles as $f){

        if($supportMessageRecord != -1){

                $engineer = User::findOrFail($supportMessageRecord);

                if($engineer->online == 1){
                    $f->assigned_to = $engineer->id;
                }
                else{

                    $onlineEngineer = User::where(function ($query) {
                    $query->whereIn('role_id', [2, 3])
                        ->orWhere('id', 1);
                    })
                    ->where('online', 1)
                    ->first();


                    if ($onlineEngineer) {
                        $f->assigned_to = $onlineEngineer->id;
                        $f->save();
                    }

                    // $f->assigned_to = User::where('id', 1)->where('role_id', 1)->first()->id;
                }


            }
            else if($supportMessageRecord == -1){

                if (($user = User::find($f->latestRequestFile?->user_id)) && $user->online) {
                    $f->assigned_to = $user->id;
                    $f->save();
                }
            }
        }
    }

    public function makeAlientechLogEntry( $fileID, $type, $message, $call, $response, $tempFileID = 0 ){

        $log = new Log();
        $log->type = $type;
        $log->request_type = 'alientech';
        $log->message = $message;
        $log->file_id = $fileID;
        $log->temporary_file_id = $tempFileID;

        if(is_array($call) || is_object($call)){
            $log->call = json_encode($call);
        }
        else if(is_string($call)){
            $log->call = $call;
        }
        if(is_array($response) || is_object($response)){
            $log->response = json_encode($response);
        }
        else if(is_string($response)){
            $log->response = $response;
        }

        $log->save();

    }

	public function assignedToMe(Request $request){
        $file = File::findOrFail($request->file_id);
        $file->assigned_to = Auth::user()->id;
        $file->assignment_time = Carbon::now();
        $file->save();

        $assign = new EngineerAssignmentLog();
        $assign->assigned_from = "No One";
        $assign->assigned_to = Auth::user()->name;
        $assign->assigned_by = Auth::user()->name;
        $assign->file_id = $file->id;
        $assign->save();

        return Redirect::back()->withErrors(['success' => 'Comments added']);
    }

	public function setFileOnHold(Request $request){

        $file = File::findOrFail($request->file_id);
        $file->status = 'on_hold';

        $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
        $onHoldTime = (strtotime($file->submission_timer)+($fsdt*60)) - strtotime(now());
        if($onHoldTime > 0){
            $file->on_hold_time = $onHoldTime;
            $file->save();
        }

        $this->changeStatusLog($file, 'on_hold', 'status', 'File is set on hold by engineer or admin.');

        $file->updated_at = Carbon::now();
        $file->save();

        return Redirect::back()->withErrors(['success' => 'Files is on Hold']);
    }

	public function translateMessage(Request $request){
        $record = EngineerFileNote::findOrFail($request->id);

        $text = $record->egnineers_internal_notes;
        $tr = new GoogleTranslate();
        $tr->setTarget('en');
        $translation = $tr->translate($text);

        return response($translation, 200);
    }

    public function getSearchResults(Request $request){
        $keyword = $request->keyword;

        if($keyword != ''){
            $results = EngineerFileNote::where('egnineers_internal_notes', 'like', '%'.$keyword.'%')->paginate(10);
        }
        else{
            $results = NULL;
        }

        return view('files.search_messages', ['results' => $results, 'request' => $request]);
    }

    public function messageSearch(){
        return view('files.search_messages');
    }

    public function setNewRequestComment(Request $request){

        $existingRecord = NewRequestComment::where('new_request_id', $request->new_request_id)->first();

        if($request->comment != NULL){

            if($existingRecord == NULL){
                $newRecord = new NewRequestComment();
                $newRecord->comment = $request->comment;
                $newRecord->new_request_id = $request->new_request_id;
                $newRecord->save();
            }
            else{
                $existingRecord->comment = $request->comment;
                $existingRecord->new_request_id = $request->new_request_id;
                $existingRecord->save();
            }
        }
        else{
            $existingRecord->comment = $request->comment;
            $existingRecord->new_request_id = $request->new_request_id;
            $existingRecord->save();
        }

        return Redirect::back()->withErrors(['success' => 'Comments added']);

    }

    public function deleteACMFile(Request $request){
        $file = ACMFile::findOrFail($request->acm_file_id);
        $file->delete();
    }

    public function ajaxAllUsersRejectedFiles($userID, Request $request){
         $data = File::select('*', 'files.id as row_id')
        ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "processing" THEN 2 WHEN status = "ready_to_send" THEN 3 ELSE 4 END AS s'))
        ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
        ->orderBy('ss', 'asc')
        ->orderBy('s', 'asc')
        ->orderBy('created_at', 'desc')
        ->where('is_credited', 1)
        ->where('user_id', $userID)
        ->whereNull('original_file_id')
        ->where('status', 'rejected')
        ->where(function ($query) {
        $query->where('files.type', '=', 'master')
                ->orWhereNotNull('assigned_from')->where('files.type', '=', 'subdealer');
        });

        return Datatables::of($data)

            ->addIndexColumn()
            ->addColumn('new_tab', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-primary text-white">'.'Click'.'</span></a>';
                }
                else if($frontEndID == 2){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-warning">'.'Click'.'</span></a>';
                }
                else if($frontEndID == 3){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-info text-white">'.'Click'.'</span></a>';
                }

                else if($frontEndID == 4){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-success text-white">'.'Click'.'</span></a>';
                }

                return $btn;
            })
            ->addColumn('frontend', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 2){
                    $btn = '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 3){
                    $btn = '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                else if($frontEndID == 4){
                    $btn = '<span class="label bg-success text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                return $btn;

            })
            ->addColumn('support_status', function($row){

                $supportStatus = $row->support_status;

                if($supportStatus == 'open'){
                    return '<label class="label bg-danger text-white">'.$supportStatus.'</label>';
                }
                else{
                    return '<lable class="label bg-success text-black">'.$supportStatus.'</lable>';
                }

            })
            ->addColumn('status', function($row){

                $status = $row->status;

                if($status == 'completed'){
                    return '<lable class="label label-success text-white">'.$status.'</lable>';
                }
                else if($status == 'rejected'){
                    return '<lable class="label label-danger text-white">'.'canceled'.'</lable>';
                }
                else{
                    return '<lable class="label bg-blue-200 text-black">'.$status.'</lable>';
                }

            })
            ->addColumn('stage', function($row){

                $file = File::findOrFail($row->id);

                if($file->stage_services){
                return '<img alt="'.$file->stage.'" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                }

            })

            ->addColumn('options', function($row){

                $options = '';
                $file = File::findOrFail($row->id);

                foreach($file->options_services as $option){
                    $service = \App\Models\Service::where('id',$option->service_id)->first();
                    if($service != null){


                            if($service){
                                $options .= '<img class="parent-adjusted" alt="'.$service->name.'" width="30" height="30" data-src-retina="'.url('icons').'/'.$service->icon .'" data-src="'.url('icons').'/'.$service->icon .'" src="'.url('icons').'/'.$service->icon.'">';
                            }
                            else{
                                $options.= "<span>Service Deleted.</span>";
                            }
                        }
                    }

                return $options;

            })

            ->editColumn('created_at', function ($credit) {
                return [
                    'display' => e($credit->created_at->format('d-m-Y')),
                    'timestamp' => $credit->created_at->timestamp
                ];
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
            })

            ->addColumn('created_time', function ($credit) {
                    return $credit->created_at->format('h:i A');
            })
            ->addColumn('engineer', function ($row) {
                if(User::where('id',$row->assigned_to)->first()){
                    return User::findOrFail($row->assigned_to)->name;
                }
                else{
                    if($row->automatic == 1){
                        return "Automatic";
                    }
                    else{
                        return "NONE";
                    }
                }
            })
            ->addColumn('response_time', function ($row) {
                $rt = $row->response_time;
                if($rt == null ){
                    return '<label class="label label-success">Not Responsed<label>';
                }
                else{

                    return '<label class="label label-success">'.\Carbon\CarbonInterval::seconds($rt)->cascade()->forHumans().'<label>';
                }
            })
            ->rawColumns(['new_tab','timers','frontend','support_status','status','stage','options','engineer','response_time'])
            ->setRowClass(function ($row) {
                $classes = "";

                if($row->red == 1){
                    $classes .= 'bg-red-200';
                }

                if($row->checked_by == 'customer'){
                    $classes .= 'bg-grey text-white';
                }

                $classes .= ' redirect-click ';

                return $classes;
            })
            ->setRowAttr([
                'data-redirect' => function($row) {
                    return route('file', $row->id);
                },

            ])
            ->make(true);
    }

    public function ajaxAllUserFiles($userID, Request $request){

        $data = File::select('*', 'files.id as row_id')
        ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "processing" THEN 2 WHEN status = "ready_to_send" THEN 3 ELSE 4 END AS s'))
        ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
        ->orderBy('ss', 'asc')
        ->orderBy('s', 'asc')
        ->orderBy('created_at', 'desc')
        ->where('is_credited', 1)
        ->where('user_id', $userID)
        ->whereNull('original_file_id')
        ->where(function ($query) {
        $query->where('files.type', '=', 'master')
                ->orWhereNotNull('assigned_from')->where('files.type', '=', 'subdealer');
        });

        return Datatables::of($data)

            ->addIndexColumn()
            ->addColumn('new_tab', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-primary text-white">'.'Click'.'</span></a>';
                }
                else if($frontEndID == 2){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-warning">'.'Click'.'</span></a>';
                }
                else if($frontEndID == 3){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-info text-white">'.'Click'.'</span></a>';
                }

                else if($frontEndID == 4){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-success text-white">'.'Click'.'</span></a>';
                }

                return $btn;
            })
            ->addColumn('frontend', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 2){
                    $btn = '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 3){
                    $btn = '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                else if($frontEndID == 4){
                    $btn = '<span class="label bg-success text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                return $btn;

            })
            ->addColumn('support_status', function($row){

                $supportStatus = $row->support_status;

                if($supportStatus == 'open'){
                    return '<label class="label bg-danger text-white">'.$supportStatus.'</label>';
                }
                else{
                    return '<lable class="label bg-success text-black">'.$supportStatus.'</lable>';
                }

            })
            ->addColumn('status', function($row){

                $status = $row->status;

                if($status == 'completed'){
                    return '<lable class="label label-success text-white">'.$status.'</lable>';
                }
                else if($status == 'rejected'){
                    return '<lable class="label label-danger text-white">'.'canceled'.'</lable>';
                }
                else{
                    return '<lable class="label bg-blue-200 text-black">'.$status.'</lable>';
                }

            })
            ->addColumn('stage', function($row){

                $file = File::findOrFail($row->id);

                if($file->stage_services){
                return '<img alt="'.$file->stage.'" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                }

            })

            ->addColumn('options', function($row){

                $options = '';
                $file = File::findOrFail($row->id);

                foreach($file->options_services as $option){
                    $service = \App\Models\Service::where('id',$option->service_id)->first();
                    if($service != null){


                            if($service){
                                $options .= '<img class="parent-adjusted" alt="'.$service->name.'" width="30" height="30" data-src-retina="'.url('icons').'/'.$service->icon .'" data-src="'.url('icons').'/'.$service->icon .'" src="'.url('icons').'/'.$service->icon.'">';
                            }
                            else{
                                $options.= "<span>Service Deleted.</span>";
                            }
                        }
                    }

                return $options;

            })

            ->editColumn('created_at', function ($credit) {
                return [
                    'display' => e($credit->created_at->format('d-m-Y')),
                    'timestamp' => $credit->created_at->timestamp
                ];
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
            })

            ->addColumn('created_time', function ($credit) {
                    return $credit->created_at->format('h:i A');
            })
            ->addColumn('engineer', function ($row) {
                if(User::where('id',$row->assigned_to)->first()){
                    return User::findOrFail($row->assigned_to)->name;
                }
                else{
                    if($row->automatic == 1){
                        return "Automatic";
                    }
                    else{
                        return "NONE";
                    }
                }
            })
            ->addColumn('response_time', function ($row) {
                $rt = $row->response_time;
                if($rt == null ){
                    return '<label class="label label-success">Not Responsed<label>';
                }
                else{

                    return '<label class="label label-success">'.\Carbon\CarbonInterval::seconds($rt)->cascade()->forHumans().'<label>';
                }
            })
            ->rawColumns(['new_tab','timers','frontend','support_status','status','stage','options','engineer','response_time'])
            ->setRowClass(function ($row) {
                $classes = "";

                if($row->red == 1){
                    $classes .= 'bg-red-200';
                }

                if($row->checked_by == 'customer'){
                    $classes .= 'bg-grey text-white';
                }

                $classes .= ' redirect-click ';

                return $classes;
            })
            ->setRowAttr([
                'data-redirect' => function($row) {
                    return route('file', $row->id);
                },

            ])
            ->make(true);


    }

    public function showFiles($userID){

        return view('files.show_all_users_files', ['userID' => $userID]);
    }

    public function downloadTermsTable(Request $request){

        $data = File::select('*')->where('is_credited', 1)->orderBy('created_at', 'desc');

        if ($request->filled('from_date') && $request->filled('to_date')) {

            $data = $data->where('created_at', '>=', $request->from_date)
                           ->where('created_at', '<=', $request->to_date);

            // $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date]);

        }

        return DataTables::of($data)
            ->addIndexColumn()

            ->editColumn('created_at', function ($credit) {
                return [
                    'display' => e($credit->created_at->format('d-m-Y')),
                    'timestamp' => $credit->created_at->timestamp
                ];
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
            })
            ->addColumn('created_time', function ($credit) {
                return $credit->created_at->format('h:i A');
            })
            ->addColumn('download', function($row){
                return '<a class="btn btn-warning text-black" href="'.route('download', [$row->id, 'terms_'.$row->id.'.pdf', 0]).'">Download</a>';
            })
            ->rawColumns(['task_id','created_time','download'])
            ->make(true);
    }

    public function downloadTerms(){

        return view('files.download_terms');
    }

    public function addLaterMessage(Request $request){
        $new = new FileMessage();
        $new->file_id = $request->file_id;
        $new->message = $request->message;
        $new->save();

        $file = File::findOrFail($request->file_id);
        $this->changeStatusLog($file, 'ready_to_send', 'status', 'Engineer uploaded the file but for showing it later to customer.');
        $file->status = 'ready_to_send';
        $file->save();

        return redirect()->route('files')->with(['success' => 'Message added to send later!']);
    }

    public function sendCustomerFile(Request $request){

        $file = File::findOrFail($request->file_id);
        $requestFile = RequestFile::findOrFail($request->request_file_id);

        // $message = FileMessage::where('request_file_id', $request->request_file_id)->first();

        $requestFile->show_later = 0;
        $requestFile->created_at = Carbon::now();
        $requestFile->updated_at = Carbon::now();
        $requestFile->save();

        $file->reupload_time = Carbon::now();
        $file->save();

        $file->status = 'completed';

        $file->response_time = $this->getResponseTime($file);
        $file->save();

        // $reply = new EngineerFileNote();

        // $message = str_replace(PHP_EOL,"<br>",$message->message);

        // $reply->egnineers_internal_notes = $message;

        // $reply->engineer = true;
        // $reply->file_id = $file->id;
        // $reply->user_id = Auth::user()->id;
        // $reply->request_file_id = $requestFile->id;

        // $reply->save();

        // FileMessage::where('request_file_id', $request->request_file_id)->delete();
        UploadLater::where('request_file_id', $request->request_file_id)->delete();

        // if(false){

            $customer = User::findOrFail($file->user_id);
            $admin = get_admin();

            // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
            $template = EmailTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

            $html1 = $template->html;

            $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
            $html1 = str_replace("#customer_name", $customer->name ,$html1);
            $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html1);

            $tunningType = $this->emailStagesAndOption($file);

            $html1 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html1);
            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url", route('file', $file->id),$html1);

            $html2 = $template->html;

            $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
            $html2 = str_replace("#customer_name", $file->name ,$html2);
            $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html2);

            $tunningType = $this->emailStagesAndOption($file);

            $html2 = str_replace("#tuning_type", $tunningType,$html2);
            $html2 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html2);
            $html2 = str_replace("#status", $file->status,$html2);

            if($file->front_end_id == 1){
                $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
            }
            else if($file->front_end_id == 3){
                $html2 = str_replace("#file_url",  'http://portal.e-tuningfiles.com/'."file/".$file->id,$html2);
            }
            else{
                $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
            }

            $optionsMessage = "";
            if($file->options){
                foreach($file->options() as $option) {
                    $optionsMessage .= ",".$option." ";
                }
            }

            // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
            $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

            $message = $messageTemplate->text;

            $message1 = str_replace("#customer", $customer->name ,$message);
            $message2 = str_replace("#customer", $file->name ,$message);

            if($file->front_end_id == 1){
                $subject = "ECU Tech: Engineer uploaded a file in reply.";
            }
            else if($file->front_end_id == 3){
                $subject = "E-files: Engineer uploaded a file in reply.";
            }
            else if($file->front_end_id == 2){
                $subject = "TuningX: Engineer uploaded a file in reply.";
            }

            $reminderManager = new ReminderManagerController();
            $this->manager = $reminderManager->getAllManager();



                if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

                    try{
                        \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                        $this->makeLogEntry('success', 'email sent to:'.$customer->email, 'email', $file->id);

                    }
                    catch(TransportException $e){
                        \Log::info($e->getMessage());
                    }

                }
                if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){

                    try{
                        \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                        $this->makeLogEntry('success', 'email sent to:'.$admin->email, 'email', $file->id);

                    }
                    catch(TransportException $e){
                        \Log::info($e->getMessage());
                        $this->makeLogEntry('error', 'email not sent to:'.$admin->email.$e->getMessage(), 'email', $file->id);
                    }
                }

                if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
                    $this->sendMessage($admin->phone, $message1, $file->front_end_id, $file->id);
                }

                if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
                    $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
                }

                if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
                    $this->sendMessage($customer->phone, $message2, $file->front_end_id, $file->id);
                }

                if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
                    $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
                }
            // }

            // return redirect()->back()->with(['success' => 'File sent to customer!']);
            return redirect()->route('files')->with(['success' => 'File sent to customer!']);

    }

    public function sendMessageToCustomer(Request $request){

        $noteItself = $request->message;

        $file = File::findOrFail($request->file_id);

        $reply = new EngineerFileNote();

        $message = str_replace(PHP_EOL,"<br>",$request->message);

        $reply->egnineers_internal_notes = $message;
        $reply->engineer = true;
        $reply->file_id = $request->file_id;
        $reply->user_id = Auth::user()->id;

        $latest = RequestFile::where('file_id', $request->file_id)->where('show_later', 0)->latest()->first();

        if($latest != NULL){
            $reply->request_file_id = $latest->id;
        }
        else{
            $reply->request_file_id = NULL;
        }

        $reply->save();

        FileMessage::where('file_id', $request->file_id)->delete();

        if($file->original_file_id != NULL){
            $ofile = File::findOrFail($file->original_file_id);
            $this->changeStatusLog($ofile, 'closed', 'support_status', 'Chat reply was sent from engineer on request file.');
            $this->changeStatusLog($ofile, 'completed', 'status', 'Chat reply was sent from engineer on request file.');
            $ofile->support_status = "closed";
            $ofile->status = "completed";
            $ofile->save();
        }
        $this->changeStatusLog($file, 'closed', 'support_status', 'Chat reply was sent from engineer.');

        $file->support_status = "closed";

        if($file->rejected){
            $file->rejected = 1;
            $file->status = "rejected";
            $this->changeStatusLog($file, 'rejected', 'status', 'Chat reply was sent from engineer on request file.');
        }
        else{
            $file->status = "completed";
            $this->changeStatusLog($file, 'completed', 'status', 'Chat reply was sent from engineer on request file.');
        }

        $file->save();
        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();

        // $template = EmailTemplate::where('name', 'Message To Client')->first();
        $template = EmailTemplate::where('slug', 'mess-to-client')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html1);

        $tunningType = $this->emailStagesAndOption($file);

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#note", $request->egnineers_internal_notes,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html2);


        $tunningType = $this->emailStagesAndOption($file);

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#note", $request->egnineers_internal_notes,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else if($file->front_end_id == 3){
            $html2 = str_replace("#file_url",  'http://portal.e-tuningfiles.com/'."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Message To Client')->first();
        $messageTemplate = MessageTemplate::where('slug', 'mess-to-client')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);

        // $message1 = "Hi, Status changed for a file by Customer: " .$customer->name;
        // $message2 = "Hi, Status changed for a file by Customer: " .$file->name;

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if($file->front_end_id == 1){
            $subject = "ECU Tech: Engineer replied to your support message!";
        }
        else if($file->front_end_id == 3){
            $subject = "E-files: Engineer replied to your support message!";
        }
        else{
            $subject = "Tuningx: Engineer replied to your support message!";
        }


        if($this->manager['msg_eng_cus_email'.$file->front_end_id]){

            try{
                \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$customer->email, 'email', $file->id);
            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$customer->email.$e->getMessage(), 'email', $file->id);
            }
        }
        if($this->manager['msg_eng_admin_email'.$file->front_end_id]){

            try{
                \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$admin->email, 'email', $file->id);

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$admin->email.$e->getMessage(), 'email', $file->id);
            }
        }

        if($this->manager['msg_eng_admin_sms'.$file->front_end_id]){

            $this->sendMessage($admin->phone, $message1, $file->front_end_id, $file->id);
        }

        if($this->manager['msg_eng_admin_whatsapp'.$file->front_end_id]){

            $this->sendWhatsappforEng($admin->name, $admin->phone, 'support_message_from_engineer', $file, $noteItself);
        }

        if($this->manager['msg_eng_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id, $file->id);
        }

        if($this->manager['msg_eng_cus_whatsapp'.$file->front_end_id]){

            $this->sendWhatsapp($customer->name, $customer->phone, 'support_message_from_engineer', $file, $noteItself);
        }

        $old = File::findOrFail($request->file_id);
        $old->checked_by = 'engineer';
        $old->save();

        return redirect()->route('files')
        ->with('success', 'Engineer message successfully Added!')
        ->with('tab','chat');

    }

    public function getCustomerMessage(Request $request){
        $message = FileMessage::where('file_id', $request->file_id)->first()->message;
        return  response()->json( ['message' => $message]);
    }

    public function editCustomerMessage(Request $request){
        $message = FileMessage::where('request_file_id', $request->request_file_id)->first();
        $message->message = str_replace(PHP_EOL,"<br>",$request->message);
        $message->save();

        return redirect()->back()->with(['success' => 'Message edited!']);
    }

    public function showRejectedFiles($userID){
        return view('files.show_rejected_users_files', ['userID' => $userID]);
    }

    public function uploadACMReply(Request $request){

        $attachment = $request->file('acm_file');
        $fileName = $attachment->getClientOriginalName();

        $file = File::findOrFail($request->file_id);

        $middleName = $file->id;
        $middleName .= date("dmy");

        foreach($file->softwares as $s){
            if($s->service_id != 1){
                // if($s->reply_id == $engineerFile->id){
                    $middleName .= $s->service_id.$s->software_id;
                // }
            }
        }

        $newFileName = $file->brand.'_'.$file->model.'_'.$middleName.'_acm_v'.$file->files->count();

        $newFileName = str_replace('/', '', $newFileName);
        $newFileName = str_replace('\\', '', $newFileName);
        $newFileName = str_replace('#', '', $newFileName);
        $newFileName = str_replace(' ', '_', $newFileName);

        $acmFile = new ACMFile();
        $acmFile->acm_file = $newFileName;
        $acmFile->request_file_id = $request->request_file_id;
        $acmFile->save();

        if($file->front_end_id == 1){

            if(env('APP_ENV') == 'local'){
                $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
            }
            else{
                if($file->on_dev == 1){
                    $attachment->move(public_path('/../../stagingportalecutech/public'.$file->file_path),$newFileName);
                }
                else{
                    $attachment->move(env('MNT_ECUTECH').$file->file_path,$newFileName);
                    //p $attachment->move('/mnt/portal.ecutech.gr'.$file->file_path,$newFileName);
                    // $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
                }
            }

        }

        else if($file->front_end_id == 3){

            if(env('APP_ENV') == 'local'){
                $attachment->move(public_path('/../../e-tuningfiles/public'.$file->file_path),$newFileName);
            }
            else{

                if($file->on_dev == 1){
                    $attachment->move(public_path('/../../stagingportaletuningfiles/public'.$file->file_path),$newFileName);
                }
                else{
                    $attachment->move(env('MNT_ETUNINGFILES').$file->file_path,$newFileName);
                    //p $attachment->move('/mnt/portal.e-tuningfiles.com'.$file->file_path,$newFileName);
                }
            }

        }
        else if($file->front_end_id == 4){

            if($file->on_dev == 1){
                $attachment->move(public_path('/../../stagingportaletuningfiles/public'.$file->file_path),$newFileName);
            }
            else{


                $attachment->move(public_path('/../../ctf/public'.$file->file_path),$newFileName);
            }

        }

        else{

            if(env('APP_ENV') == 'local'){
                $attachment->move(public_path('/../../tuningX/public'.$file->file_path),$newFileName);
            }
            else{

                if($file->on_dev == 1){
                    $attachment->move(public_path('/../../TuningXV2/public'.$file->file_path),$newFileName);
                }
                else{
                    $attachment->move(env('MNT_TUNINGX').$file->file_path,$newFileName);
                    //p $attachment->move('/mnt/portal.tuning-x.com'.$file->file_path,$newFileName);

                }
            }
        }

        return redirect()->back()->with(['success' => 'Engineers ACM file is uploaded successfully!']);

    }

    public function removeNullSoftwares(Request $request){
        $allNulls = FileReplySoftwareService::where('file_id', $request->file_id)
        ->whereNull('reply_id')->delete();

        return  response()->json( ['msg' => 'records deleted.', 'file_id' => $request->file_id] );
    }

    public function addUploadLaterRecord(Request $request){

        $newMessage = new UploadLater();
        $newMessage->file_id = $request->file_id;
        $newMessage->save();

        return  response()->json( ['msg' => 'upload later record added.']);

    }

    public function removeNullMessages(Request $request){
        $allNulls = FileMessage::where('file_id', $request->file_id)
        ->whereNull('request_file_id')->delete();

        return  response()->json( ['msg' => 'records deleted.', 'file_id' => $request->file_id] );
    }

    public function removeNullUploadLaterRecords(Request $request){
        $allNulls = UploadLater::where('file_id', $request->file_id)
        ->whereNull('request_file_id')->delete();

        return  response()->json( ['msg' => 'records deleted.', 'file_id' => $request->file_id] );
    }

    public function addSoftwares(Request $request){

        $data = json_decode($request->form_data);

        $fileID = NULL;
        $finalArray = [];
        $exclude = [];

        foreach($data as $d) {

            if($d->name == 'exclude_service[]'){
                $exclude []= $d->value;
            }

            if($d->name == 'file_id'){
                $fileID = $d->value;
            }

            if($d->name == 'service_id'){

                $nowService = $d->value;

                foreach($data as $in){

                    if($in->name == 'processing-software-'.$nowService){

                        $finalArray[$nowService] = $in->value;
                    }

                }
            }

        }

        foreach($finalArray as $key => $value){

            if (!in_array($key, $exclude)){

                $latest = FileReplySoftwareService::where('file_id', $fileID)
                ->where('service_id', $key)
                ->latest('created_at')->first();

                if($latest){
                    $latest->revised = 1;
                    $latest->save();
                }

                $newRecord = new FileReplySoftwareService();
                $newRecord->file_id = $fileID;
                $newRecord->service_id = $key;
                $newRecord->software_id = $value;
                $newRecord->save();

            }

        }

        return  response()->json( ['msg' => 'records added.', 'file_id' => $fileID] );

    }

    public function enableDownload(Request $request){

        $file = File::findOrFail($request->id);

        // $aFile = AlientechFile::where('file_id', $file->id);
        // $aFile->delete();

        $file->disable_customers_download = 0;
        $file->status = 'completed';
        $this->changeStatusLog($file, 'completed', 'status', 'File is enabled to download.');
        $file->red = 0;
        $file->submission_timer = NULL;
        $file->timer = NULL;

        $file->support_status = "closed";
        $this->changeStatusLog($file, 'closed', 'support_status', 'File is enabled to download.');
        $file->checked_by = 'engineer';

        $file->reupload_time = Carbon::now();
        $file->updated_at = Carbon::now();

        $file->response_time = $this->getResponseTime($file);
        $file->save();

        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();

        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html1);

        $tunningType = $this->emailStagesAndOption($file);

        $html1 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html1);
        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html2);

        $tunningType = $this->emailStagesAndOption($file);

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html2);
        $html2 = str_replace("#status", $file->status,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else if($file->front_end_id == 3){
            $html2 = str_replace("#file_url",  "http://portal.e-tuningfiles.com/"."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
        $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);

        if($file->front_end_id == 1){
            $subject = "ECU Tech: Engineer uploaded a file in reply.";
        }
        else if($file->front_end_id == 3){
            $subject = "E-files: Engineer uploaded a file in reply.";
        }
        else{
            $subject = "TuningX: Engineer uploaded a file in reply.";
        }

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();



        if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

            try{
                \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$customer->email, 'email', $file->id);
            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$customer->email.$e->getMessage(), 'email', $file->id);
            }

        }
        if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){

            try{
                \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$admin->email, 'email', $file->id);

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$admin->email.$e->getMessage(), 'email', $file->id);
            }
        }

        if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
            $this->sendMessage($admin->phone, $message1, $file->front_end_id, $file->id);
        }

        if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
            $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
        }

        if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id, $file->id);
        }

        if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
            $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
        }



        return redirect()->back()->with(['success' => 'Now Engineers can download files which are in revision section!']);
    }

    public function flipShowComments(Request $request){

        $file = RequestFile::findOrFail($request->id);
        $file->show_comments = ($request->showCommentsOnFile == 'false') ? 0 : 1;
        $file->save();
        return response('comments flipped', 200);

    }

    public function declineShowFile(Request $request){

        $reqfile = RequestFile::findOrFail($request->id);
        $reqfile->show_file_denied = 1;
        $reqfile->save();
        return response('file declined', 200);
    }

    public function fillProcessingSoftware(Request $request){

        $processingSoftwares = ProcessingSoftware::all();

        $psArray = [];
        foreach($processingSoftwares as $ps){
            $psArray [$ps->id]= $ps->name;
        }

        $allNulls = FileReplySoftwareService::where('file_id', $request->file_id)
        ->whereNull('reply_id')->delete();

        $fileSoftwares = FileReplySoftwareService::where('file_id', $request->file_id)
        ->where('reply_id', $request->new_request_id)
        ->get();

        $count = 1;
        $strStage = '';
        $opArr = [];

        foreach($fileSoftwares as $fs){

            if($count == 1){

                $service = Service::findOrFail($fs->service_id);

                if($service->type == 'tunning'){

                    foreach($psArray as $k=>$ps){

                        if($fs->software_id == $k){
                            $strStage .= '<option selected="selected" value="'.$k.'">'.$ps.'</option>';
                        }
                        else{
                            $strStage .= '<option value="'.$k.'">'.$ps.'</option>';
                        }
                    }
                }
                else{

                    $temp = '';

                foreach($psArray as $k=>$ps){

                    if($fs->software_id == $k){
                        $temp .= '<option selected="selected" value="'.$k.'">'.$ps.'</option>';

                    }
                    else{
                        $temp .= '<option value="'.$k.'">'.$ps.'</option>';
                    }

                    $opArr [$fs->service_id]= $temp;
                }


                }

            }
            else{

                $temp = '';

                foreach($psArray as $k=>$ps){

                    if($fs->software_id == $k){
                        $temp .= '<option selected="selected" value="'.$k.'">'.$ps.'</option>';

                    }
                    else{
                        $temp .= '<option value="'.$k.'">'.$ps.'</option>';
                    }

                    $opArr [$fs->service_id]= $temp;
                }

            }

            $count++;

        }

        return response(['strStage' => $strStage, 'opArr' => $opArr], 200);

    }

    public function updateProcessingSoftware(Request $request){

        $delete = FileReplySoftwareService::where('file_id', $request->file_id)
        ->where('reply_id', $request->reply_id)->delete();

        if($request->service_id != NULL){

            $new = new FileReplySoftwareService();
            $new->file_id = $request->file_id;
            $new->reply_id = $request->reply_id;
            $new->service_id = $request->service_id;
            $new->software_id = $request->stage_software;
            $new->save();
        }

        if($request->option_id != NULL){

            for( $i=0; $i < count($request->option_id); $i++ ){

                $new = new FileReplySoftwareService();
                $new->file_id = $request->file_id;
                $new->reply_id = $request->reply_id;
                $new->service_id = $request->option_id[$i];
                $new->software_id = $request->option_softwares[$i];
                $new->save();

            }
        }

        return redirect()->back()->with(['success' => 'Options and Servies are updated!']);

    }

    public function declineComments(Request $request){

        $reqfile = RequestFile::findOrFail($request->id);
        $reqfile->comments_denied = 1;
        $reqfile->save();
        return response('comments declined', 200);

    }

    public function flipShowFile(Request $request){

        $reqfile = RequestFile::findOrFail($request->id);
        $reqfile->is_kess3_slave = ($request->showFile == 'false') ? 1 : 0;
        $reqfile->show_file_denied = 0;
        $reqfile->uploaded_successfully = 1; // just for one update
        $reqfile->save();

        if($reqfile->is_kess3_slave == 0){

            $file = File::findOrFail($reqfile->file_id);

            $file->status = 'completed';
            $this->changeStatusLog($file, 'completed', 'status', 'File is shown to customer to download.');
            $file->red = 0;
            $file->submission_timer = NULL;
            $file->updated_at = Carbon::now();
            $file->reupload_time = Carbon::now();
            $file->response_time = $this->getResponseTime($file);

            $file->save();

            $customer = User::findOrFail($file->user_id);
            $admin = get_admin();

            // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
            $template = EmailTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

            $html1 = $template->html;

            $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
            $html1 = str_replace("#customer_name", $customer->name ,$html1);
            $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html1);

            $tunningType = $this->emailStagesAndOption($file);

            $html1 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html1);
            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url", route('file', $file->id),$html1);

            $html2 = $template->html;

            $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
            $html2 = str_replace("#customer_name", $file->name ,$html2);
            $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html2);

            $tunningType = $this->emailStagesAndOption($file);

            $html2 = str_replace("#tuning_type", $tunningType,$html2);
            $html2 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html2);
            $html2 = str_replace("#status", $file->status,$html2);

            if($file->front_end_id == 1){
                $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
            }
            else if($file->front_end_id == 3){
                $html2 = str_replace("#file_url",  "http://portal.e-tuningfiles.com/"."file/".$file->id,$html2);
            }
            else{
                $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
            }

            $optionsMessage = "";
            if($file->options){
                foreach($file->options() as $option) {
                    $optionsMessage .= ",".$option." ";
                }
            }

            // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
            $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

            $message = $messageTemplate->text;

            $message1 = str_replace("#customer", $customer->name ,$message);
            $message2 = str_replace("#customer", $file->name ,$message);

            if($file->front_end_id == 1){
                $subject = "ECU Tech: Engineer uploaded a file in reply.";
            }
            else if($file->front_end_id == 3){
                $subject = "E-files: Engineer uploaded a file in reply.";
            }
            else{
                $subject = "TuningX: Engineer uploaded a file in reply.";
            }

            $reminderManager = new ReminderManagerController();
            $this->manager = $reminderManager->getAllManager();



            if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

                try{
                    \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                    $this->makeLogEntry('success', 'email sent to:'.$customer->email, 'email', $file->id);
                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                    $this->makeLogEntry('error', 'email not sent to:'.$customer->email.$e->getMessage(), 'email', $file->id);
                }

            }
            if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){

                try{
                    \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                    $this->makeLogEntry('success', 'email sent to:'.$admin->email, 'email', $file->id);

                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                    $this->makeLogEntry('error', 'email not sent to:'.$admin->email.$e->getMessage(), 'email', $file->id);
                }
            }

            if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
                $this->sendMessage($admin->phone, $message1, $file->front_end_id, $file->id);
            }

            if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
                $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
            }

            if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
                $this->sendMessage($customer->phone, $message2, $file->front_end_id, $file->id);
            }

            if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
                $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
            }

        }

        return response('file flipped', 200);

    }

    public function deleteFiles(Request $request){

        $ids = $request->ids;
        $files = File::whereIn('id', $ids)->get();

        foreach($files as $file){

            FileService::where('file_id', $file->id)->delete();
            ProcessedFile::where('file_id', $file->id)->delete();
            RequestFile::where('file_id', $file->id)->delete();
            FileInternalEvent::where('file_id', $file->id)->delete();
            FileFeedback::where('file_id', $file->id)->delete();
            AlientechFile::where('file_id', $file->id)->delete();
            EngineerFileNote::where('file_id', $file->id)->delete();
            FilesStatusLog::where('file_id', $file->id)->delete();
            FileUrl::where('file_id', $file->id)->delete();
            Log::where('file_id', $file->id)->delete();
            FileReplySoftwareService::where('file_id', $file->id)->delete();

            $file->delete();
        }


    }

    public function forceOptionsOffer(Request $request){

        $fileID = $request->file_id;
        $forceProposedOptions = $request->force_proposed_options;

        $file = File::findOrFail($fileID);

        foreach($file->options as $service){
            $service->delete();
        }

        $proposedCredits = 0;

        if($file->front_end_id == 1){
            $proposedCredits += Service::findOrFail($file->stage_services->service_id)->credits;
        }
        else if($file->front_end_id == 2){
            if($file->tool_type == 'master'){

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->tuningx_credits;

            }
            else{

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->tuningx_slave_credits;
            }
        }
        else if($file->front_end_id == 3){

            if($file->tool_type == 'master'){

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->efiles_credits;

            }
            else{

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->efiles_slave_credits;
            }

        }

        if($forceProposedOptions){

            foreach($forceProposedOptions as $offer){

                    $option = new FileService();
                    $option->service_id = $offer;
                    $option->type = 'option';

                    if($file->front_end_id == 1){

                        $proposedCredits += Service::findOrFail($offer)->credits;
                        $option->credits = Service::findOrFail($offer)->credits;

                    }
                    else if($file->front_end_id == 2){

                        $service = Service::findOrFail($offer);

                        if($file->tool_type == 'master'){

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->master_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->master_credits;

                        }
                        else{

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                        }

                    }
                    else if($file->front_end_id == 3){

                        $service = Service::findOrFail($offer);

                        // code is same because we have different service IDs for different frontends when it comes to options

                        if($file->tool_type == 'master'){

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->master_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->master_credits;

                        }
                        else{

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                        }
                    }

                    $option->file_id = $fileID;
                    $option->save();
            }
        }

        $differece = $file->credits - $proposedCredits;

        $user = User::findOrfail($file->user_id);

        if( $differece > 0 ){

            $credit = new Credit();
            $credit->credits = $differece;
            $credit->user_id = $user->id;
            $credit->front_end_id = $user->front_end_id;
            $credit->country = code_to_country( $user->country );
            $credit->file_id = $file->id;
            $credit->stripe_id = NULL;

            if($user->test == 1){
                $credit->test = 1;
            }

            $credit->gifted = 1;
            $credit->price_payed = 0;

            $credit->message_to_credit = 'File options accepted and credits returned!';

            $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);
            $credit->save();
        }

        $file->credits = $proposedCredits;

        $file->save();

        return redirect()->back()->with(['success' => 'options adjusted and credits returned!']);
    }

    public function multiDelete(Request $request){

        $files = File::orderBy('created_at', 'desc')->get();

        return view('files.multi_delete', [ 'files' => $files ]);
    }

    public function addOptionsOffer(Request $request){

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $forceProposedOptions = $request->proposed_options;

        $fileID = $request->file_id;

        $file = File::findOrFail($fileID);

        foreach($file->options as $service){
            $service->delete();
        }

        $proposedCredits = 0;

        if($file->front_end_id == 1){
            $proposedCredits += Service::findOrFail($file->stage_services->service_id)->credits;
        }
        else if($file->front_end_id == 2){
            if($file->tool_type == 'master'){

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->tuningx_credits;

            }
            else{

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->tuningx_slave_credits;
            }
        }
        else if($file->front_end_id == 3){

            if($file->tool_type == 'master'){

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->efiles_credits;

            }
            else{

                $proposedCredits += Service::findOrFail($file->stage_services->service_id)->efiles_slave_credits;
            }

        }

        if($forceProposedOptions){

            foreach($forceProposedOptions as $offer){

                    $option = new FileService();
                    $option->service_id = $offer;
                    $option->type = 'option';

                    if($file->front_end_id == 1){

                        $proposedCredits += Service::findOrFail($offer)->credits;
                        $option->credits = Service::findOrFail($offer)->credits;

                    }
                    else if($file->front_end_id == 2){

                        $service = Service::findOrFail($offer);

                        if($file->tool_type == 'master'){

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->master_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->master_credits;

                        }
                        else{

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                        }

                    }
                    else if($file->front_end_id == 3){

                        $service = Service::findOrFail($offer);

                        // code is same because we have different service IDs for different frontends when it comes to options

                        if($file->tool_type == 'master'){

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->master_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->master_credits;

                        }
                        else{

                            $proposedCredits += $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                            $option->credits = $service->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                        }
                    }

                    $option->file_id = $fileID;
                    $option->save();
            }
        }

        $differece = $file->credits - $proposedCredits;

            $user = User::findOrfail($file->user_id);

            if( $differece > 0 ){

                $credit = new Credit();
                $credit->credits = $differece;
                $credit->user_id = $user->id;
                $credit->front_end_id = $user->front_end_id;
                $credit->country = code_to_country( $user->country );
                $credit->file_id = $file->id;
                $credit->stripe_id = NULL;

                if($user->test == 1){
                    $credit->test = 1;
                }

                $credit->gifted = 1;
                $credit->price_payed = 0;

                $credit->message_to_credit = 'File options updated and credits returned!';

                $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);
                $credit->save();


            $file->credits = $proposedCredits;

            $file->save();

            return redirect()->back()->with(['success' => 'Options updated!']);

        }
        else{

            $file = File::findOrFail($request->file_id);

            $proposed = new EngineerOptionsOffer();
            $proposed->file_id = $request->file_id;
            $proposed->type = 'stage';
            $proposed->service_id = $request->proposed_stage;
            $proposed->save();

            $proposedOptions = $request->proposed_options;

            if($proposedOptions){
                foreach($proposedOptions as $o){
                    $proposed = new EngineerOptionsOffer();
                    $proposed->file_id = $request->file_id;
                    $proposed->type = 'option';
                    $proposed->service_id = $o;
                    $proposed->save();
                }
            }

            $file->status = 'on_hold';
            $this->changeStatusLog($file, 'on_hold', 'status', 'File is set on hold after offering options to customer.');
            $file->updated_at = Carbon::now();
            $file->save();

            return redirect()->back()->with(['success' => 'New stages and options proposed!']);
        }
    }

    public function flipDecodedMode(Request $request){

        $file = File::findOrFail($request->file_id);

        if($file->decoded_mode == 1){

            $file->file_attached = $file->file_attached_backup;
            $file->file_attached_backup = null;
            $file->decoded_mode = 0;
            $file->save();

        }

        else if ($file->decoded_mode == 0){

            $file->file_attached_backup = $file->file_attached;
            $file->file_attached = $file->final_decoded_file();
            $file->decoded_mode = 1;
            $file->save();

        }

        return redirect()->back()
        ->with('success', 'File Decoded mode is updated!');
    }
    public function support($id){

        $requestFile = RequestFile::findOrFail($id);
        $file = File::findOrFail($requestFile->file_id);

        foreach($file->engineer_file_notes as $f){
            $f->sent_by = NULL;
            $f->save();
        }

        if(($file->front_end_id == 1 && $file->subdealer_group_id == NULL)){
            abort(404);
        }

        return view('files.support', ['requestFile' => $requestFile, 'file' => $file]);
    }

    public function changeCheckingStatus(Request $request){

        $file = File::findOrFail($request->file_id);

        if($file->checking_status == 'unchecked'){
            $file->checking_status = 'fail';
            $file->save();
            return  response()->json( ['msg' => 'File was not found.', 'fail' => 1, 'file_id' => $file->id] );
        }
        if($file->checking_status == 'completed'){
            return response()->json( ['msg' => 'File found.', 'fail' => 2, 'file_id' => $file->id] );
        }
        return response()->json( ['msg' => 'File Status does not change.', 'fail' => 0, 'file_id' => $file->id] );
    }

    public function search(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $file = File::findOrFail($request->file_id);

        if($request->download_directly == 'download' && $request->custom_stage ){

            $file->custom_stage = $request->custom_stage;
            $file->save();
        }
        else{
            $file->custom_stage = NULL;
            $file->save();
        }

        if($request->download_directly == 'download' && $request->custom_options ){

            $file->custom_options = implode(',', $request->custom_options);
            $file->save();
        }
        else{
            $file->custom_options = '';
            $file->save();
        }

        if($request->file('decrypted_file')){

            $attachment = $request->file('decrypted_file');
            $fileName = $attachment->getClientOriginalName();

            // $file->file_attached = $fileName;

            $file->save();

            if($file->front_end_id == 1){

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../portal/public/'.$file->file_path),$fileName);
                }
                else{
                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../stagingportalecutech/public/'.$file->file_path),$fileName);
                    }
                    else{
                        $attachment->move(env('MNT_ECUTECH').$file->file_path,$fileName);
                        //p $attachment->move('/mnt/portal.ecutech.gr'.$file->file_path,$fileName);
                        // $attachment->move(public_path('/../../portal/public/'.$file->file_path),$fileName);
                    }
                }

            }
            else if($file->front_end_id == 3){

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../e-tuningfiles/public/'.$file->file_path),$fileName);
                }
                else{
                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../stagingportaletuningfiles/public/'.$file->file_path),$fileName);
                    }
                    else{
                        $attachment->move(env('MNT_ETUNINGFILES').$file->file_path,$fileName);
                       //p $attachment->move('/mnt/portal.e-tuningfiles.com'.$file->file_path,$fileName);
                        // $attachment->move(public_path('/../../portal.e-tuningfiles.com/public/'.$file->file_path),$fileName);
                    }
                }

            }
            else if($file->front_end_id == 4){

                // $attachment->move(public_path('/../../portal/public/'.$file->file_path),$fileName);

                if($file->on_dev == 1){
                    $attachment->move(public_path('/../../stagingportaletuningfiles/public/'.$file->file_path),$fileName);
                }
                else{
                    $attachment->move(public_path('/../../ctf/public/'.$file->file_path),$fileName);
                }

            }
            else{

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../tuningX/public/'.$file->file_path),$fileName);
                }
                else{

                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../TuningXV2/public/'.$file->file_path),$fileName);
                    }
                    else{
                        $attachment->move(env('MNT_TUNINGX').$file->file_path,$fileName);
                        //p $attachment->move('/mnt/portal.tuning-x.com'.$file->file_path,$fileName);
                        // $attachment->move(public_path('/../../tuningX/public/'.$file->file_path),$fileName);
                    }
                }
            }

        }

        $processFile = new ProcessedFile();
        $processFile->file_id = $file->id;
        $processFile->type = 'decoded';
        $processFile->name = explode(".", $fileName)[0];

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        if($ext != ""){
            $processFile->extension = $ext;
        }

        $processFile->save();

        $file->checking_status = 'unchecked';
        $file->checking_status_versions = 0;
        $file->inner_search = 1;
        $file->save();

        return view('files.search', ['file' => $file]);

    }

    public function delete(Request $request){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-file')) {

            $file = File::findOrFail($request->id);

            FileService::where('file_id', $file->id)->delete();
            ProcessedFile::where('file_id', $file->id)->delete();
            RequestFile::where('file_id', $file->id)->delete();
            FileInternalEvent::where('file_id', $file->id)->delete();
            FileFeedback::where('file_id', $file->id)->delete();
            AlientechFile::where('file_id', $file->id)->delete();
            EngineerFileNote::where('file_id', $file->id)->delete();
            FilesStatusLog::where('file_id', $file->id)->delete();
            FileUrl::where('file_id', $file->id)->delete();
            Log::where('file_id', $file->id)->delete();
            FileReplySoftwareService::where('file_id', $file->id)->delete();

            $file->delete();

            return response()->json( 'deleted' );
        }
        else{
            return abort(404);
        }

    }

    // public function getAccessToken(){

    //     $apiURL = 'https://encodingapi.alientech.to/api/access-tokens/request';
    //     $postInput = [
    //         'clientApplicationGUID' => 'f8b0f518-8de7-4528-b8db-3995e1b787e9',
    //         'secretKey' => "#5!/ThmmM*?;D\\jvjQ6%9/",
    //     ];

    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];

    //     $response = Http::withHeaders($headers)->post($apiURL, $postInput);

    //     $statusCode = $response->status();
    //     $responseBody = json_decode($response->getBody(), true);

    //     if(isset($responseBody['accessToken'])){
    //         $key = new Key();
    //         $key->key = 'alientech_access_token';
    //         $key->value = $responseBody['accessToken'];
    //         $key->save();
    //         return $responseBody['accessToken'];
    //     }

    //     return null;
    // }

    public function callbackKess3(Response $response){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        \Log::info($response);
    }

    public function decodeFile(){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $token = Key::where('key', 'alientech_access_token')->first()->value;

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots";

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $token,
        ];

        $response = Http::withHeaders($headers)->get($url);
        $responseBody = json_decode($response->getBody(), true);

        foreach($responseBody as $row){

            if($row['isClosed'] == false){

                $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$row['guid']."/close";

                $headers = [
                'X-Alientech-ReCodAPI-LLC' => $token,
                ];

                $response = Http::withHeaders($headers)->post($url, []);
            }

        }

        dd('all_closed');
        exit;
    }

    public function deactivateAllExceptThisFeed($ThatFeed) {

        $allOtherFeed = NewsFeed::where('id', '!=', $ThatFeed->id)->get();

        foreach($allOtherFeed as $feed){
            if($feed->active == 1){
                // \Log::info("deactivating feed inner:".$feed->title);
                $feed->active = 0;
                $feed->save();
            }
        }
    }

    public function feedadjustment(){

        $feeds = NewsFeed::all();

        $dateCheck = date('l');
        $timeCheck = date('H:i');

        $deactiveAll = false;

        $thatFeed = '';

        foreach($feeds as $feed) {

            // \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));
            // \Log::info("Feed Activation Day: ".strtotime(date('d-m-y h:i:s')));
            // \Log::info("activation date time: ".$feed->activate_at);
            // \Log::info("activation date time: ".strtotime($feed->activate_at));

            if($feed->activation_weekday == null &&  $feed->deactivation_weekday == null){

                if( strtotime(now()) >= strtotime($feed->activate_at) && strtotime(now()) <= strtotime($feed->deactivate_at)){
                    // if($feed->active == 0){
                            // \Log::info("activating feed:".$feed->title);
                            $feed->active = 1;
                            $feed->save();

                            $deactiveAll = true;
                            $thatFeed = $feed;
                        // }
                    }
                    else {
                        if($feed->active == 1){
                            // \Log::info("deactivating feed:".$feed->title);
                            $feed->active = 0;
                            $feed->save();
                        }
                    }
                }

            if(!$deactiveAll) {

                if($feed->activate_at == null &&  $feed->deactivate_at == null) {

                    if($this->getDayNumber($dateCheck) >= $this->getDayNumber($feed->activation_weekday) && $this->getDayNumber($dateCheck) <= $this->getDayNumber($feed->deactivation_weekday) ) {

                        if ( strtotime($timeCheck) > strtotime($feed->daily_activation_time) && strtotime($timeCheck) < strtotime($feed->daily_deactivation_time) ) {

                            if($feed->active == 0){

                                // \Log::info("activating feed at:".date('l').$feed->title);
                                $feed->active = 1;
                                $feed->save();
                            }
                        }

                        else {

                            if($feed->active == 1){

                                // \Log::info("deactivating feed at:".date('l').$feed->title);
                                $feed->active = 0;
                                $feed->save();
                            }
                        }
                    }

                    else{

                            if($feed->active == 1){

                                // \Log::info("deactivating feed:".date('l').$feed->title);
                                $feed->active = 0;
                                $feed->save();
                            }

                        }
                    }
                }
                if($deactiveAll){
                    $this->deactivateAllExceptThisFeed($thatFeed);
                }
        }
    }

    function recursiveChmod($path, $filePerm=0777, $dirPerm=0777) {
        // Check if the path exists
        if (!file_exists($path)) {
            return(false);
        }

        // See whether this is a file
        if (is_file($path)) {
            // Chmod the file with our given filepermissions
            chmod($path, $filePerm);

        // If this is a directory...
        } elseif (is_dir($path)) {
            // Then get an array of the contents
            $foldersAndFiles = scandir($path);

            // Remove "." and ".." from the list
            $entries = array_slice($foldersAndFiles, 2);

            // Parse every result...
            foreach ($entries as $entry) {
                // And call this function again recursively, with the same permissions
                $this->recursiveChmod($path."/".$entry, $filePerm, $dirPerm);
            }

            // When we are done with the contents of the directory, we chmod the directory itself
            chmod($path, $dirPerm);
        }

        // Everything seemed to work out well, return true
        return(true);
    }

    public function getDayNumber($day) {

        $days = [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday'
        ];

        foreach($days as $key => $row){
            if($row == $day)
            return $key;
        }

    }

    public function myAjaxFiles(Request $request){
        $data = File::select('*', 'files.id as row_id')
        ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "processing" THEN 2 WHEN status = "ready_to_send" THEN 3 ELSE 4 END AS s'))
        ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
        ->orderBy('ss', 'asc')
        ->orderBy('s', 'asc')
        ->orderBy('created_at', 'desc')
        ->where('is_credited', 1)
        ->where('assigned_to', Auth::user()->id)
        // ->whereNull('original_file_id')
        ->where(function ($query) {
        $query->where('files.type', '=', 'master')
                ->orWhereNotNull('assigned_from')->where('files.type', '=', 'subdealer');
        });

        if ($request->filled('from_date') && $request->filled('to_date')) {

            $data = $data->whereDate('created_at', '>=', $request->from_date)
            ->whereDate('created_at', '<=', $request->to_date);

        }

        if ($request->filled('late')) {
            if($request->late == 'late'){
                $data = $data->where('delayed', '=', 1);
            }
            else if($request->late == 'not_late'){
                $data = $data->where('delayed', '=', 0);
            }
        }

        if ($request->filled('automatic')) {
            if($request->automatic == 'automatic'){
                $data = $data->where('automatic', '=', 1);
            }
            else if($request->automatic == 'not_automatic'){
                $data = $data->where('automatic', '=', 0);
            }
        }

        if ($request->filled('frontend')) {
            if($request->frontend != 'all'){
                $data = $data->where('front_end_id', '=', $request->frontend);
            }
        }

        if ($request->filled('support_status')) {
            if($request->support_status != 'all'){
                $data = $data->where('support_status', '=', $request->support_status);
            }
        }

        if ($request->filled('status')) {
            if($request->status != 'all'){
                $data = $data->whereIn('status', $request->status);
            }
        }

        if ($request->filled('stage')) {
            if($request->stage != 'all'){
                $data = $data->whereIn('stage', $request->stage);
            }
        }

        // if ($request->filled('options')) {

        //     $data = $data->whereIn('file_services.service_id', [$request->options]);

        // }

        if ($request->filled('engineer')) {
            if($request->engineer != 'all'){
                // if($request->engineer == 'automatic'){
                //     $data = $data->whereIn('assigned_to', $request->engineer)->orWhere('automatic','=', 1);
                // }
                // else{
                    $data = $data->whereIn('assigned_to', $request->engineer);
                // }
            }
        }

        return Datatables::of($data)

            ->addIndexColumn()
            ->addColumn('new_tab', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-primary text-white">'.'Click'.'</span></a>';
                }
                else if($frontEndID == 2){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-warning">'.'Click'.'</span></a>';
                }
                else if($frontEndID == 3){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-info text-white">'.'Click'.'</span></a>';
                }

                else if($frontEndID == 4){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-success text-white">'.'Click'.'</span></a>';
                }

                return $btn;
            })
            ->addColumn('timers', function($row){

                $file = File::findOrFail($row->row_id);

                if($file->delayed == 1){
                    return '<span class="label label-danger text-white m-r-5">Late</span>';
                }

                $returnStr = "";

                if($file->timer != NULL){

                    $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
                    $fodt = Key::where('key', 'file_open_delay_time')->first()->value;

                    if($file->support_status == 'open'){

                        $openTimeLeft = (strtotime($file->timer)+($fodt*60)) - strtotime(now());

                    }

                    if($file->support_status == 'open'){
                        if($openTimeLeft > 0){
                            $returnStr .='<lable class="label label-danger text-white m-r-5 open" id="o_'.$file->id.'" data-seconds="'.$openTimeLeft.'"></lable>';
                        }
                    }


                }

                if($file->submission_timer != NULL){

                    $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
                    $fodt = Key::where('key', 'file_open_delay_time')->first()->value;


                    if($file->status == 'submitted'){
                        $submissionTimeLeft = (strtotime($file->submission_timer)+($fsdt*60)) - strtotime(now());
                    }
                    // else if($file->status == 'on_hold'){
                    //     if($file->on_hold_time == NULL){
                    //         $onHoldTime = (strtotime($file->submission_timer)+($fsdt*60)) - strtotime(now());
                    //         if($onHoldTime > 0){
                    //             $file->on_hold_time = $onHoldTime;
                    //             $file->save();
                    //         }
                    //     }
                    // }

                    if($file->status == 'submitted' ||  $file->status == 'on_hold'){

                        if($file->status == 'submitted'){
                            if($submissionTimeLeft > 0){
                                $returnStr .='<span class="label label-info text-white m-r-5 submission" id="s_'.$file->id.'" data-seconds="'.$submissionTimeLeft.'"></span>';
                            }
                        }
                        else if($file->status == 'on_hold'){
                            if($file->on_hold_time != NULL){
                                $returnStr .='<span class="label label-info text-white m-r-5 submission-stoped" id="s_'.$file->id.'" data-seconds="'.$file->on_hold_time.'"></span>';
                            }
                        }
                    }

                }

                return $returnStr;

            })
            ->addColumn('frontend', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 2){
                    $btn = '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 3){
                    $btn = '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                return $btn;

            })
            ->addColumn('support_status', function($row){

                $supportStatus = $row->support_status;

                if($supportStatus == 'open'){
                    return '<label class="label bg-danger text-white">'.$supportStatus.'</label>';
                }
                else{
                    return '<lable class="label bg-success text-black">'.$supportStatus.'</lable>';
                }

            })
            ->addColumn('status', function($row){

                $status = $row->status;

                if($status == 'completed'){
                    return '<lable class="label label-success text-white">'.$status.'</lable>';
                }
                else if($status == 'rejected'){
                    return '<lable class="label label-danger text-white">'.'canceled'.'</lable>';
                }
                else{
                    return '<lable class="label bg-blue-200 text-black">'.$status.'</lable>';
                }

            })
            ->addColumn('stage', function($row){

                $file = File::findOrFail($row->id);

                if($file->stage_services){
                return '<img alt="'.$file->stage.'" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                }

            })

            ->addColumn('options', function($row){

                $options = '';
                $file = File::findOrFail($row->id);

                foreach($file->options_services as $option){
                    $service = \App\Models\Service::where('id',$option->service_id)->first();
                    if($service != null){


                            if($service){
                                $options .= '<img class="parent-adjusted" alt="'.$service->name.'" width="30" height="30" data-src-retina="'.url('icons').'/'.$service->icon .'" data-src="'.url('icons').'/'.$service->icon .'" src="'.url('icons').'/'.$service->icon.'">';
                            }
                            else{
                                $options.= "<span>Service Deleted.</span>";
                            }
                        }
                    }

                return $options;

            })

            ->editColumn('created_at', function ($credit) {
                return [
                    'display' => e($credit->created_at->format('d-m-Y')),
                    'timestamp' => $credit->created_at->timestamp
                ];
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
            })

            ->addColumn('created_time', function ($credit) {
                    return $credit->created_at->format('h:i A');
            })
            ->addColumn('engineer', function ($row) {
                if(User::where('id',$row->assigned_to)->first()){
                    return User::findOrFail($row->assigned_to)->name;
                }
                else{
                    if($row->automatic == 1){
                        return "Automatic";
                    }
                    else{
                        return "NONE";
                    }
                }
            })
            ->addColumn('response_time', function ($row) {
                $rt = $row->response_time;
                if($rt == null ){
                    return '<label class="label label-success">Not Responsed<label>';
                }
                else{

                    return '<label class="label label-success">'.\Carbon\CarbonInterval::seconds($rt)->cascade()->forHumans().'<label>';
                }
            })
            ->rawColumns(['new_tab','timers','frontend','support_status','status','stage','options','engineer','response_time'])
            ->setRowClass(function ($row) {
                $classes = "";

                if($row->red == 1){
                    $classes .= 'bg-red-200';
                }

                if($row->checked_by == 'customer'){
                    $classes .= 'bg-grey text-white';
                }

                $classes .= ' redirect-click ';

                return $classes;
            })
            ->setRowAttr([
                'data-redirect' => function($row) {
                    return route('file', $row->id);
                },

            ])
            ->make(true);
    }

    public function ajaxFiles(Request $request){

        $data = File::select('*', 'files.id as row_id')
        ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "processing" THEN 2 WHEN status = "ready_to_send" THEN 3 ELSE 4 END AS s'))
        ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
        ->orderBy('ss', 'asc')
        ->orderBy('s', 'asc')
        ->orderBy('created_at', 'desc')
        ->where('is_credited', 1)
        // ->whereNull('original_file_id')
        ->where(function ($query) {
        $query->where('files.type', '=', 'master')
                ->orWhereNotNull('assigned_from')->where('files.type', '=', 'subdealer');
        });

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $data = $data->whereDate('created_at', '>=', $request->from_date)
                        ->whereDate('created_at', '<=', $request->to_date);
        }
        // If no from_date/to_date, do nothing → all records shown

        if ($request->filled('late')) {
            if($request->late == 'late'){
                $data = $data->where('delayed', '=', 1);
            }
            else if($request->late == 'not_late'){
                $data = $data->where('delayed', '=', 0);
            }
        }

        if ($request->filled('automatic')) {
            if($request->automatic == 'automatic'){
                $data = $data->where('automatic', '=', 1);
            }
            else if($request->automatic == 'not_automatic'){
                $data = $data->where('automatic', '=', 0);
            }
        }

        if ($request->filled('frontend')) {
            if($request->frontend != 'all'){
                $data = $data->where('front_end_id', '=', $request->frontend);
            }
        }

        if ($request->filled('support_status')) {
            if($request->support_status != 'all'){
                $data = $data->where('support_status', '=', $request->support_status);
            }
        }

        if ($request->filled('status')) {
            if($request->status != 'all'){
                $data = $data->whereIn('status', $request->status);
            }
        }

        if ($request->filled('stage')) {
            if($request->stage != 'all'){
                $data = $data->whereIn('stage', $request->stage);
            }
        }

        // if ($request->filled('options')) {
        //     $data = $data->whereIn('file_services.service_id', [$request->options]);

        // }

        if ($request->filled('engineer')) {
            if($request->engineer != 'all'){
                // if($request->engineer == 'automatic'){
                //     $data = $data->whereIn('assigned_to', $request->engineer)->orWhere('automatic','=', 1);
                // }
                // else{
                    $data = $data->whereIn('assigned_to', $request->engineer);
                // }
            }
        }

        return Datatables::of($data)

            ->addIndexColumn()
            ->addColumn('timers', function($row){

                $file = File::findOrFail($row->row_id);

                if($file->delayed == 1){
                    return '<span class="label label-danger text-white m-r-5">Late</span>';
                }

                $returnStr = "";

                if($file->timer != NULL){

                    $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
                    $fodt = Key::where('key', 'file_open_delay_time')->first()->value;

                    if($file->support_status == 'open'){

                        $openTimeLeft = (strtotime($file->timer)+($fodt*60)) - strtotime(now());

                    }

                    if($file->support_status == 'open'){
                        if($openTimeLeft > 0){
                            $returnStr .='<lable class="label label-danger text-white m-r-5 open" id="o_'.$file->id.'" data-seconds="'.$openTimeLeft.'"></lable>';
                        }
                    }


                }

                if($file->submission_timer != NULL){

                    $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
                    $fodt = Key::where('key', 'file_open_delay_time')->first()->value;


                    if($file->status == 'submitted'){
                        $submissionTimeLeft = (strtotime($file->submission_timer)+($fsdt*60)) - strtotime(now());
                    }
                    // else if($file->status == 'on_hold'){
                    //     if($file->on_hold_time == NULL){
                    //         $onHoldTime = (strtotime($file->submission_timer)+($fsdt*60)) - strtotime(now());
                    //         if($onHoldTime > 0){
                    //             $file->on_hold_time = $onHoldTime;
                    //             $file->save();
                    //         }
                    //     }
                    // }

                    if($file->status == 'submitted' ||  $file->status == 'on_hold'){

                        if($file->status == 'submitted'){
                            if($submissionTimeLeft > 0){
                                $returnStr .='<span class="label label-info text-white m-r-5 submission" id="s_'.$file->id.'" data-seconds="'.$submissionTimeLeft.'"></span>';
                            }
                        }
                        else if($file->status == 'on_hold'){
                            if($file->on_hold_time != NULL){
                                $returnStr .='<span class="label label-info text-white m-r-5 submission-stoped" id="s_'.$file->id.'" data-seconds="'.$file->on_hold_time.'"></span>';
                            }
                        }
                    }

                }

                return $returnStr;

            })
            ->addColumn('new_tab', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-primary text-white">'.'Click'.'</span></a>';
                }
                else if($frontEndID == 2){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-warning">'.'Click'.'</span></a>';
                }
                else if($frontEndID == 3){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-info text-white">'.'Click'.'</span></a>';
                }

                else if($frontEndID == 4){
                    $btn = '<a target="_blank" href="'.route('file', $row->id).'"><span class="label bg-success text-white">'.'Click'.'</span></a>';
                }

                return $btn;
            })
            ->addColumn('frontend', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 2){
                    $btn = '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 3){
                    $btn = '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                else if($frontEndID == 4){
                    $btn = '<span class="label bg-success text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                return $btn;

            })
            ->addColumn('support_status', function($row){

                $supportStatus = $row->support_status;

                if($supportStatus == 'open'){
                    return '<label class="label bg-danger text-white">'.$supportStatus.'</label>';
                }
                else{
                    return '<lable class="label bg-success text-black">'.$supportStatus.'</lable>';
                }

            })
            ->addColumn('status', function($row){

                $status = $row->status;

                if($status == 'completed'){
                    return '<lable class="label label-success text-white">'.$status.'</lable>';
                }
                else if($status == 'rejected'){
                    return '<lable class="label label-danger text-white">'.'canceled'.'</lable>';
                }
                else{
                    return '<lable class="label bg-blue-200 text-black">'.$status.'</lable>';
                }

            })
            ->addColumn('stage', function($row){

                $file = File::findOrFail($row->id);

                if($file->stage_services){
                return '<img alt="'.$file->stage.'" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                }

            })

            ->addColumn('options', function($row){

                $options = '';
                $file = File::findOrFail($row->id);

                foreach($file->options_services as $option){
                    $service = \App\Models\Service::where('id',$option->service_id)->first();
                    if($service != null){


                            if($service){
                                $options .= '<img class="parent-adjusted" alt="'.$service->name.'" width="30" height="30" data-src-retina="'.url('icons').'/'.$service->icon .'" data-src="'.url('icons').'/'.$service->icon .'" src="'.url('icons').'/'.$service->icon.'">';
                            }
                            else{
                                $options.= "<span>Service Deleted.</span>";
                            }
                        }
                    }

                return $options;

            })

            ->editColumn('created_at', function ($credit) {
                return [
                    'display' => e($credit->created_at->format('d-m-Y')),
                    'timestamp' => $credit->created_at->timestamp
                ];
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
            })

            ->addColumn('created_time', function ($credit) {
                    return $credit->created_at->format('h:i A');
            })
            ->addColumn('engineer', function ($row) {
                if(User::where('id',$row->assigned_to)->first()){
                    return User::findOrFail($row->assigned_to)->name;
                }
                else{
                    if($row->automatic == 1){
                        return "Automatic";
                    }
                    else{
                        return "NONE";
                    }
                }
            })
            ->addColumn('response_time', function ($row) {
                $rt = $row->response_time;
                if($rt == null ){
                    return '<label class="label label-success">Not Responsed<label>';
                }
                else{

                    return '<label class="label label-success">'.\Carbon\CarbonInterval::seconds($rt)->cascade()->forHumans().'<label>';
                }
            })
            ->rawColumns(['new_tab','timers','frontend','support_status','status','stage','options','engineer','response_time'])
            ->setRowClass(function ($row) {
                $classes = "";

                if($row->red == 1){
                    $classes .= 'bg-red-200';
                }

                if($row->checked_by == 'customer'){
                    $classes .= 'bg-grey text-white';
                }

                $classes .= ' redirect-click ';

                return $classes;
            })
            ->setRowAttr([
                'data-redirect' => function($row) {
                    return route('file', $row->id);
                },

            ])
            ->make(true);
    }

    public function engineersReportsTable(Request $request){

        $data = File::select('*')->where('is_credited', 1);
        // ->whereNull('original_file_id');

        if ($request->filled('from_date') && $request->filled('to_date')) {

            $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date]);

        }

        if ($request->filled('frontend')) {
            if($request->frontend != 'all'){
                $data = $data->where('front_end_id', '=', $request->frontend);
            }
        }

        if ($request->filled('stage')) {
            if($request->stage != 'all'){
                $data = $data->where('stage', '=', $request->stage);
            }
        }

        if ($request->filled('engineer')) {
            if($request->engineer != 'all'){
                $data = $data->where('assigned_to', '=', $request->engineer);
            }
        }

        return Datatables::of($data)

        ->addIndexColumn()

        ->addColumn('frontend', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = FrontEnd::findOrFail($frontEndID)->name;
                }
                else if($frontEndID == 2){
                    $btn = FrontEnd::findOrFail($frontEndID)->name;
                }
                else if($frontEndID == 3){
                    $btn = FrontEnd::findOrFail($frontEndID)->name;
                }

                return $btn;

            })

            ->editColumn('created_at', function ($credit) {
                return [
                    'display' => e($credit->created_at->format('d-m-Y')),
                    'timestamp' => $credit->created_at->timestamp
                ];
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
            })

            ->addColumn('vehicle', function($row){

                $file = File::findOrFail($row->id);
                return $file->brand.' '.$file->engine.' '.$file->vehicle()->TORQUE_standard;

            })

            ->addColumn('options', function($row){

                $file = File::findOrFail($row->id);

                $all = "";

                foreach($file->options_services as $option){
                    if(\App\Models\Service::where('id', $option->service_id)->first() != null){
                        $all .= \App\Models\Service::where('id', $option->service_id)->first()->name.',';
                    }
                }

                return $all;

            })

            ->addColumn('engineer', function($row){

                if($row->assigned_to){

                    $user = User::findOrFail($row->assigned_to);
                    return $user->name;
                }
                else{
                    return "Not assigned";
                }

            })

            ->addColumn('response_time', function($row){

                $rt = $row->response_time;

                if($rt == null ){
                    return 'Not Responded';
                }
                else{
                    return \Carbon\CarbonInterval::seconds($rt)->cascade()->forHumans();
                }



            })

            ->rawColumns(['frontend','vehicle','options', 'engineer','response_time'])

            ->make(true);

    }

    public function assignToAnother(Request $request){

        $file = File::findOrFail($request->file_id);
        $file->assigned_from = $file->assigned_to;
        $file->assigned_to = $request->engineer;
        $file->assignment_time = Carbon::now();
        $file->save();

        $assign = new EngineerAssignmentLog();
        $assign->assigned_from = $file->assigned_to;
        $assign->assigned_to = $request->engineer;
        $assign->assigned_by = Auth::user()->name;
        $assign->file_id = $file->id;
        $assign->save();

        return redirect()->back()->with(['success' => 'assgin to another engineer']);
    }

    public function tasksRulesSet(Request $request){

        $stageEngineer = Key::where('key','stage_engineer')->first();
        $stageEngineer->value = $request->stage_engineer;
        $stageEngineer->save();

        $optionsEngineer = Key::where('key','options_engineer')->first();
        $optionsEngineer->value = $request->options_engineer;
        $optionsEngineer->save();

        $stagesOptionsEngineer = Key::where('key','stages_options_engineer')->first();
        $stagesOptionsEngineer->value = $request->stages_options_engineer;
        $stagesOptionsEngineer->save();

        $supportMessagesEngineer = Key::where('key','support_messages_engineer')->first();
        $supportMessagesEngineer->value = $request->support_messages_engineer;
        $supportMessagesEngineer->save();

        return redirect()->back()->with(['success' => 'rules set']);
    }

    public function engineersAssignment(){

        $stageEngineer = Key::where('key','stage_engineer')->first()->value;
        $optionsEngineer = Key::where('key','options_engineer')->first()->value;
        $stagesOptionsEngineer = Key::where('key','stages_options_engineer')->first()->value;
        $supportMessagesEngineer = Key::where('key','support_messages_engineer')->first()->value;

        $allEngineers = User::whereIn('role_id', [2,3])->where('test', 0)->whereNull('subdealer_group_id')->orWhere('id', 3)->get();
        return view('files.engineers_assignment', ['supportMessagesEngineer' => $supportMessagesEngineer,'allEngineers' => $allEngineers, 'stageEngineer' => $stageEngineer, 'optionsEngineer' => $optionsEngineer, 'stagesOptionsEngineer' => $stagesOptionsEngineer]);
    }

    public function flipEngineerStatus(Request $request){

        $engineer = User::findOrFail($request->id);

        if($engineer->online){
            $engineer->online = 0;
        }
        else{
            $engineer->online = 1;
        }

        $engineer->save();

        return response()->json( [ 'status flipped' ] );
    }

    public function myLiveFiles(){

        $this->feedadjustment();

        $stages = Service::where('type', 'tunning')->get();
        $options = Service::where('type', 'option')->get();
        $engineers = User::whereIn('role_id', [2,3])->whereNull('subdealer_group_id')->orWhere('id', 3)->get();
        $allEngineers = User::whereIn('role_id', [2,3])->where('test', 0)->whereNull('subdealer_group_id')->orWhere('id', 3)->get();
        // dd($allEngineers);
        $loggedInUser = Auth::user();

        // if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files')){

            return view('files.my_live_files', ['loggedInUser' => $loggedInUser, 'allEngineers' => $allEngineers, 'stages' => $stages, 'options' => $options, 'engineers' => $engineers]);
    }

    public function liveFiles(){

        $this->feedadjustment();

        $stages = Service::where('type', 'tunning')->get();
        $options = Service::where('type', 'option')->get();
        $engineers = User::whereIn('role_id', [2,3])->orWhere('id', 3)->get();
        $allEngineers = User::whereIn('role_id', [2,3])->where('test', 0)->whereNull('subdealer_group_id')->orWhere('id', 3)->get();
        $loggedInUser = Auth::user();

        // if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files')){

        return view('files.live_files', ['loggedInUser' => $loggedInUser, 'allEngineers' => $allEngineers, 'stages' => $stages, 'options' => $options, 'engineers' => $engineers]);
        // }
        // else{
        //     return abort(404);
        // }
    }

    public function updateFileVehicle(Request $request) {

    if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-file')){

        $this->validate($request, [
            'engine' => 'required',
            'version' => 'required'
        ]);

        $file = File::findOrFail($request->id);

        $file->version = $request->version;
        $file->engine = $request->engine;
        $file->ecu = $request->ecu;
        $file->save();

        return redirect()->back()
        ->with('success', 'File successfully Edited!');

    }

    else{
        return abort(404);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getModels(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $brand = $request->brand;

        $models = Vehicle::OrderBy('model', 'asc')->select('model')->whereNotNull('model')->distinct()->where('make', '=', $brand)->get();

        return response()->json( [ 'models' => $models ] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getVersions(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $model = $request->model;
        $brand = $request->brand;

        $versions = Vehicle::OrderBy('generation', 'asc')->whereNotNull('generation')->select('generation')->distinct()
        ->where('Make', '=', $brand)
        ->where('Model', '=', $model)
        ->get();

        return response()->json( [ 'versions' => $versions ] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEngines(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $model = $request->model;
        $brand = $request->brand;
        $version = $request->version;

        $engines = Vehicle::OrderBy('engine', 'asc')->whereNotNull('engine')->select('engine')->distinct()
        ->where('Make', '=', $brand)
        ->where('Model', '=', $model)
        ->where('Generation', '=', $version)
        ->get();

        return response()->json( [ 'engines' => $engines ] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getECUs(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $model = $request->model;
        $brand = $request->brand;
        $version = $request->version;
        $engine = $request->engine;

        $ecus = Vehicle::OrderBy('Engine_ECU', 'asc')->whereNotNull('Engine_ECU')->select('Engine_ECU')->distinct()
        ->where('Make', '=', $brand)
        ->where('Model', '=', $model)
        ->where('Generation', '=', $version)
        ->where('Engine', '=', $engine)
        ->get();

        $ecusArray = [];

        foreach($ecus as $e){
            $temp = explode(' / ', $e->Engine_ECU);
            $ecusArray = array_merge($ecusArray,$temp);
        }

        $ecusArray = array_values(array_unique($ecusArray));

        return response()->json( [ 'ecus' => $ecusArray ]);
    }

    public function editFile($id) {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-file')){

            $file = File::findOrFail($id);

            $brandsObjects = Vehicle::OrderBy('make', 'asc')->select('make')->distinct()->get();

            $brands = [];
            foreach($brandsObjects as $b){
                if($b->make != '')
                $brands []= $b->make;
            }

            $modelsObjects = Vehicle::OrderBy('model', 'asc')->select('model')->whereNotNull('model')->distinct()->where('make', '=', $file->brand)->get();

            $models = [];
            foreach($modelsObjects as $m){
                if($m->model != '')
                $models []= $m->model;
            }

            $versionsObjects = Vehicle::OrderBy('generation', 'asc')->whereNotNull('generation')->select('generation')->distinct()
            ->where('Make', '=', $file->brand)
            ->where('Model', '=', $file->model)
            ->get();

            $versions = [];
            foreach($versionsObjects as $v){
                if($v->generation != '')
                $versions []= $v->generation;
            }

            $enginesObjects = Vehicle::OrderBy('engine', 'asc')->whereNotNull('engine')->select('engine')->distinct()
            ->where('Make', '=', $file->brand)
            ->where('Model', '=', $file->model)
            ->where('Generation', '=', $file->version)
            ->get();

            $engines = [];
            foreach($enginesObjects as $e){
                if($e->engine != '')
                $engines []= $e->engine;
            }

            $ecus = Vehicle::OrderBy('Engine_ECU', 'asc')->whereNotNull('Engine_ECU')->select('Engine_ECU')->distinct()
            ->where('Make', '=', $file->brand)
            ->where('Model', '=', $file->model)
            ->where('Generation', '=', $file->version)
            ->where('Engine', '=', $file->engine)
            ->get();

            $ecusArray = [];

            foreach($ecus as $e){
                $temp = explode(' / ', $e->Engine_ECU);
                $ecusArray = array_merge($ecusArray,$temp);
            }

            $ecusArray = array_values(array_unique($ecusArray));

                return view('files.edit', [ 'file' => $file,
                'brands' => $brands,
                'models' => $models,
                'versions' => $versions,
                'engines' => $engines,
                'ecus' => $ecusArray
            ]);
        }
        else{

            return abort(404);
        }
    }

    public function saveFeedbackEmailSchedual(Request $request) {

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $this->validate($request, [
            'days' => 'required|min:1',
            'time_of_day' => 'required',
            'cycle' => 'required|min:1'
        ]);

        $schedual = Schedualer::take(1)->first();

        if( !$schedual ) {
            $new = new Schedualer();
            $new->days = $request->days;
            $new->time_of_day = $request->time_of_day;
            $new->cycle = $request->cycle;
            $new->save();
        }
        else{

            $schedual->days = $request->days;
            $schedual->time_of_day = $request->time_of_day;
            $schedual->cycle = $request->cycle;
            $schedual->save();
        }

        $filesObject = File::Orderby('files.created_at', 'desc')
        ->where('is_credited', 1)->select('*')
        ->addSelect('files.id as id')
        ->addSelect('request_files.id as req_id');
        $filesObject = $filesObject->join('request_files', 'files.id', '=' , 'request_files.file_id');
        $filesObject = $filesObject->join('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id', 'left outer')->whereNull('type');
        $reminderFiles = $filesObject->get();

        foreach($reminderFiles as $file){

            $alreadyadded = EmailReminder::where('file_id', $file->id)
            ->where('user_id', $file->user_id)
            ->where('request_file_id', $file->req_id)
            ->first();

            if(!$alreadyadded){
                $reminder = new EmailReminder();
                $reminder->file_id = $file->id;
                $reminder->user_id = $file->user_id;
                $reminder->request_file_id = $file->req_id;
                $reminder->set_time = Carbon::now();
                $reminder->cycle =  $request->cycle;
                $reminder->save();
            }
            else{
                $alreadyadded->set_time = Carbon::now();
                $alreadyadded->cycle =  $request->cycle;
                $alreadyadded->save();
            }
        }

        return redirect()->route('feedback-emails')->with(['success' => 'Schedual udpated, successfully.']);
    }

    public function feedbackEmails() {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        //email template
        $feebdackTemplate = EmailTemplate::findOrFail(9);
        $schedual = Schedualer::take(1)->first();
        return view('files.feedback_page', [ 'feebdackTemplate' => $feebdackTemplate, 'schedual' => $schedual ]);
    }

    public function saveFeedbackEmailTemplate(Request $request) {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $feebdackTemplate = EmailTemplate::findOrFail(9);
        $feebdackTemplate->html = $request->new_template;
        $feebdackTemplate->save();

        return redirect()->route('feedback-emails')->with(['success' => 'Template udpated, successfully.']);

    }

    public function editMessage( Request $request ) {

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $message = EngineerFileNote::findOrFail($request->id);
        $message->egnineers_internal_notes = $request->message;
        $message->save();

        return redirect()->back()
        ->with('success', 'Engineer note successfully Edited!')
        ->with('tab','chat');
    }

    public function downloadAutotuner( $id,$requestFileID ) {

        $file = File::findOrFail($id);

        $autoTunerEncryptedFile = AutotunerEncrypted::where('file_id', $id)
        ->where('request_file_id', $requestFileID)
        ->first();

        if($file->front_end_id == 1){

            if(env('APP_ENV') == 'local'){
                $file_path = public_path('/../../portal/public/'.$file->file_path).$autoTunerEncryptedFile->name;
            }
            else{

                // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;

                if($file->on_dev == 1){

                    $file_path = public_path('/../../stagingportalecutech/public/'.$file->file_path).$autoTunerEncryptedFile->name;

                }
                else{
                    $file_path = env('MNT_ECUTECH').$file->file_path.$autoTunerEncryptedFile->name;
                    //p $file_path = '/mnt/portal.ecutech.gr'.$file->file_path.$autoTunerEncryptedFile->name;
                    // $file_path = public_path('/../../portal/public/'.$file->file_path).$autoTunerEncryptedFile->name;
                }
            }

        }
        else if($file->front_end_id == 3){

            if(env('APP_ENV') == 'local'){
                $file_path = public_path('/../../e-tuningfiles/public/'.$file->file_path).$autoTunerEncryptedFile->name;
            }
            else{

                // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;

                if($file->on_dev == 1){

                    $file_path = public_path('/../../stagingportaletuningfiles/public/'.$file->file_path).$autoTunerEncryptedFile->name;

                }
                else{
                    $file_path = env('MNT_ETUNINGFILES').$file->file_path.$autoTunerEncryptedFile->name;
                    //p $file_path = '/mnt/portal.e-tuningfiles.com'.$file->file_path.$autoTunerEncryptedFile->name;
                    // $file_path = public_path('/../../portal.e-tuningfiles.com/public/'.$file->file_path).$autoTunerEncryptedFile->name;
                }
            }

        }
        else if($file->front_end_id == 4){

            // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;

            if($file->on_dev == 1){

                $file_path = public_path('/../../stagingportaletuningfiles/public/'.$file->file_path).$autoTunerEncryptedFile->name;

            }
            else{

                $file_path = public_path('/../../ctf/public/'.$file->file_path).$autoTunerEncryptedFile->name;
            }

        }
        else{

            if(env('APP_ENV') == 'local'){
                $file_path = public_path('/../../tuningX/public/'.$file->file_path).$autoTunerEncryptedFile->name;
            }
            else{

                if($file->on_dev == 1){

                    $file_path = public_path('/../../TuningXV2/public/'.$file->file_path).$autoTunerEncryptedFile->name;

                }
                else{
                    $file_path = env('MNT_TUNINGX').$file->file_path.$autoTunerEncryptedFile->name;
                    //p $file_path = '/mnt/portal.tuning-x.com'.$file->file_path.$autoTunerEncryptedFile->name;
                    // $file_path = public_path('/../../tuningX/public/'.$file->file_path).$autoTunerEncryptedFile->name;
                }
            }
        }

        return response()->download($file_path);

    }

    public function downloadMagic( $id,$requestFileID ) {

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $file = File::findOrFail($id);

        $magicEncryptedFile = MagicEncryptedFile::where('file_id', $id)
        ->where('request_file_id', $requestFileID)
        ->where('downloadable', 1)
        ->first();


        if($file->front_end_id == 1){

            if(env('APP_ENV') == 'local'){
                $file_path = public_path('/../../portal/public/'.$file->file_path).$magicEncryptedFile->name;
            }
            else{

                // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;

                if($file->on_dev == 1){

                    $file_path = public_path('/../../stagingportalecutech/public/'.$file->file_path).$magicEncryptedFile->name;

                }
                else{
                    $file_path = env('MNT_ECUTECH').$file->file_path.$magicEncryptedFile->name;
                    //p $file_path = '/mnt/portal.ecutech.gr'.$file->file_path.$magicEncryptedFile->name;
                    // $file_path = public_path('/../../portal/public/'.$file->file_path).$magicEncryptedFile->name;
                }
            }

        }
        else if($file->front_end_id == 3){

            if(env('APP_ENV') == 'local'){
                $file_path = public_path('/../../e-tuningfiles/public/'.$file->file_path).$magicEncryptedFile->name;
            }
            else{

                // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;

                if($file->on_dev == 1){

                    $file_path = public_path('/../../stagingportaletuningfiles/public/'.$file->file_path).$magicEncryptedFile->name;

                }
                else{
                    $file_path = env('MNT_ETUNINGFILES').$file->file_path.$magicEncryptedFile->name;
                    //p $file_path = '/mnt/portal.e-tuningfiles.com'.$file->file_path.$magicEncryptedFile->name;
                    // $file_path = public_path('/../../portal.e-tuningfiles.com/public/'.$file->file_path).$magicEncryptedFile->name;
                }
            }

        }
        else if($file->front_end_id == 4){

            // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;

            if($file->on_dev == 1){

                $file_path = public_path('/../../stagingportaletuningfiles/public/'.$file->file_path).$magicEncryptedFile->name;

            }
            else{

                $file_path = public_path('/../../ctf/public/'.$file->file_path).$magicEncryptedFile->name;
            }

        }
        else{

            if(env('APP_ENV') == 'local'){
                $file_path = public_path('/../../tuningX/public/'.$file->file_path).$magicEncryptedFile->name;
            }
            else{

                if($file->on_dev == 1){

                    $file_path = public_path('/../../TuningXV2/public/'.$file->file_path).$magicEncryptedFile->name;

                }
                else{
                    $file_path = env('MNT_TUNINGX').$file->file_path.$magicEncryptedFile->name;
                    //p $file_path = '/mnt/portal.tuning-x.com/uploads'.$file->file_path.$magicEncryptedFile->name;
                    // $file_path = public_path('/../../tuningX/public/'.$file->file_path).$magicEncryptedFile->name;
                }
            }
        }

        return response()->download($file_path);
    }

    public function downloadEncrypted( $id,$fileName ) {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $file = File::findOrFail($id);

        $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();

        if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id){

            $notProcessedAlientechFile = AlientechFile::where('file_id', $file->id)
            ->where('purpose', 'decoded')
            ->where('type', 'download')
            ->where('processed', 0)
            ->first();

            if($notProcessedAlientechFile){

                $fileNameEncoded = $this->alientechObj->downloadEncodedFile($id, $notProcessedAlientechFile, $fileName);
                $notProcessedAlientechFile->processed = 1;
                $notProcessedAlientechFile->save();

                if($file->front_end_id == 1){

                    if(env('APP_ENV') == 'local'){
                        $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;
                    }
                    else{

                        if($file->on_dev == 1){

                            $file_path = public_path('/../../stagingportalecutech/public/'.$file->file_path).$fileNameEncoded;

                        }
                        else{
                            $file_path = env('MNT_ECUTECH').$file->file_path.$fileNameEncoded;
                            //p $file_path = '/mnt/portal.ecutech.gr'.$file->file_path.$fileNameEncoded;
                            // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;
                        }
                    }

                }
                else if($file->front_end_id == 3){

                    if(env('APP_ENV') == 'local'){
                        $file_path = public_path('/../../e-tuningfiles/public/'.$file->file_path).$fileNameEncoded;
                    }
                    else{

                        if($file->on_dev == 1){

                            $file_path = public_path('/../../stagingportaletuningfiles/public/'.$file->file_path).$fileNameEncoded;

                        }
                        else{
                            $file_path = env('MNT_ETUNINGFILES').$file->file_path.$fileNameEncoded;
                            //p $file_path = '/mnt/portal.e-tuningfiles.com'.$file->file_path.$fileNameEncoded;
                            // $file_path = public_path('/../../portal.e-tuningfiles.com/public/'.$file->file_path).$fileNameEncoded;
                        }
                    }

                }
                else if($file->front_end_id == 4){

                    // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileNameEncoded;

                    if($file->on_dev == 1){

                        $file_path = public_path('/../../stagingportaletuningfiles/public/'.$file->file_path).$fileNameEncoded;

                    }
                    else{

                        $file_path = public_path('/../../ctf/public/'.$file->file_path).$fileNameEncoded;
                    }

                }
                else{

                    if(env('APP_ENV') == 'local'){
                        $file_path = public_path('/../../tuningX/public/'.$file->file_path).$fileNameEncoded;
                    }
                    else{

                        if($file->on_dev == 1){

                            $file_path = public_path('/../../TuningXV2/public/'.$file->file_path).$fileNameEncoded;

                        }
                        else{
                            $file_path = env('MNT_TUNINGX').$file->file_path.$fileNameEncoded;
                            //p $file_path = '/mnt/portal.tuning-x.com/uploads'.$file->file_path.$fileNameEncoded;
                            // $file_path = public_path('/../../tuningX/public/'.$file->file_path).$fileNameEncoded;
                        }
                    }
                }


                return response()->download($file_path);
            }
            else{
                $encodedFileNameToBe = $fileName.'_encoded_api';
                $processedFile = ProcessedFile::where('name', $encodedFileNameToBe)->first();

                if($processedFile){

                if($processedFile->extension != ''){
                    $finalFileName = $processedFile->name;
                    // $finalFileName = $processedFile->name.'.'.$processedFile->extension;


                }
                else{
                    $finalFileName = $processedFile->name;
                }
            }else{
                $finalFileName = $fileName;
            }

            if($file->front_end_id == 1){

                if(env('APP_ENV') == 'local'){
                    $file_path = public_path('/../../portal/public/'.$file->file_path).$finalFileName;
                }
                else{

                    if($file->on_dev == 1){

                        $file_path = public_path('/../../stagingportalecutech/public/'.$file->file_path).$finalFileName;

                    }
                    else{
                        $file_path = env('MNT_ECUTECH').$file->file_path.$finalFileName;
                        //p $file_path = '/mnt/portal.ecutech.gr'.$file->file_path.$finalFileName;
                        // $file_path = public_path('/../../portal/public/'.$file->file_path).$finalFileName;
                    }
                }

            }
            else if($file->front_end_id == 3){

                if(env('APP_ENV') == 'local'){
                    $file_path = public_path('/../../e-tuningfiles/public/'.$file->file_path).$finalFileName;
                }
                else{

                    if($file->on_dev == 1){

                        $file_path = public_path('/../../stagingportaletuningfiles/public/'.$file->file_path).$finalFileName;

                    }
                    else{

                        if($file->api == 0){
                            $file_path = env('MNT_ETUNINGFILES').$file->file_path.$finalFileName;
                            //p $file_path = '/mnt/portal.e-tuningfiles.com'.$file->file_path.$finalFileName;
                            // $file_path = public_path('/../../portal.e-tuningfiles.com/public/'.$file->file_path).$finalFileName;
                        }
                        else if($file->api == 1){
                            $file_path = public_path('/public/'.$file->file_path).$finalFileName;
                        }
                    }
                }

            }
            else if($file->front_end_id == 4){

                // dd($file->api);

                // $file_path = public_path('/../../portal/public/'.$file->file_path).$finalFileName;

                if($file->on_dev == 1){

                    $file_path = public_path('/../../stagingportaletuningfiles/public/'.$file->file_path).$finalFileName;

                }
                else{

                    if($file->api == 0){

                        $file_path = public_path('/../../ctf/public/'.$file->file_path).$finalFileName;
                    }
                    else if($file->api == 1){

                        $file_path = public_path('/public/'.$file->file_path).$finalFileName;


                    }

                    // dd($file_path);
                }

            }
            else{

                if(env('APP_ENV') == 'local'){
                    $file_path = public_path('/../../tuningX/public/'.$file->file_path).$finalFileName;
                }
                else{

                    if($file->on_dev == 1){

                        $file_path = public_path('/../../TuningXV2/public/'.$file->file_path).$finalFileName;

                    }
                    else{
                        $file_path = env('MNT_TUNINGX').$file->file_path.$finalFileName;
                        //p $file_path = '/mnt/portal.tuning-x.com/uploads'.$file->file_path.$finalFileName;
                        // $file_path = public_path('/../../tuningX/public/'.$file->file_path).$finalFileName;
                    }
                }
            }
                return response()->download($file_path);

            }

        }
        // else{

        //     if(env('APP_ENV') == 'local'){
        //         $file_path = public_path('/../../portal/public/'.$file->file_path).$fileName;
        //     }
        //     else{
        //         $file_path = '/mnt/portal.ecutech.gr'.$file->file_path.$fileName;
        //         // $file_path = public_path('/../../portal/public/'.$file->file_path).$fileName;
        //     }
        //     return response()->download($file_path);
        // }
    }

    public function download($id,$file_name, $deleteFile = false) {

        $file = File::findOrFail($id);

        if($file->front_end_id == 1){

            if(env('APP_ENV') == 'local'){
                $path = public_path('/../../portal/public'.$file->file_path);
            }
            else{
                if($file->subdealer_group_id){
                    $path = public_path('/../../subportal/public'.$file->file_path);
                }
                else{

                    if($file->on_dev == 1){
                        $path = public_path('/../../stagingportalecutech/public'.$file->file_path);

                    }
                    else{
                        $path = env('MNT_ECUTECH').$file->file_path;
                        //p $path = '/mnt/portal.ecutech.gr'.$file->file_path;
                        // $path = public_path('/../../portal/public'.$file->file_path);
                    }
                }
            }
        }
        else if($file->front_end_id == 3){
            if(env('APP_ENV') == 'local'){
                $path = public_path('/../../e-tuningfiles/public'.$file->file_path);
            }
            else{
                if($file->subdealer_group_id){
                    $path = public_path('/../../subportal/public'.$file->file_path);
                }
                else{

                    if($file->on_dev == 1){
                        $path = public_path('/../../stagingportaletuningfiles/public'.$file->file_path);

                    }
                    else{
                        $path = env('MNT_ETUNINGFILES').$file->file_path;
                        //p $path = '/mnt/portal.e-tuningfiles.com'.$file->file_path;
                        // $path = public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path);
                    }
                }
            }
        }
        else if($file->front_end_id == 4){
            if($file->subdealer_group_id){
                $path = public_path('/../../subportal/public'.$file->file_path);
            }
            else{

                if($file->on_dev == 1){
                    $path = public_path('/../../stagingportaletuningfiles/public'.$file->file_path);

                }
                else{
                    $path = public_path('/../../ctf/public'.$file->file_path);
                }
            }
        }
        else{

            if(env('APP_ENV') == 'local'){
                $path = public_path('/../../tuningX/public'.$file->file_path);
            }
            else{

                if($file->on_dev == 1){
                    $path = public_path('/../../TuningXV2/public'.$file->file_path);

                }
                else{
                    $path = env('MNT_TUNINGX').$file->file_path;
                    //p $path = '/mnt/portal.tuning-x.com'.$file->file_path;
                    // $path = public_path('/../../tuningX/public'.$file->file_path);
                }
            }
        }

        $file_path = $path.$file_name;

        if($deleteFile){
            return response()->download($file_path)->deleteFileAfterSend(true);
        }
        else{
            return response()->download($file_path);
        }

    }

    public function deleteMessage(Request $request)
    {

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $note = EngineerFileNote::findOrFail($request->note_id);
        $note->delete();
        return response('Note deleted', 200);
    }

    public function deleteUploadedFile(Request $request)
    {

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $file = RequestFile::findOrFail($request->request_file_id);
        $file->delete();

        $softwareRecords = FileReplySoftwareService::where('reply_id', $request->request_file_id)->delete();
		// $messagesRecords = EngineerFileNote::where('request_file_id', $request->request_file_id)->delete();
		// $messagesRecords = EngineerFileNote::where('request_file_id', $request->request_file_id)->delete();
		$messagesRecords = FileMessage::where('request_file_id', $request->request_file_id)->delete();
		$messagesRecords = UploadLater::where('request_file_id', $request->request_file_id)->delete();

        return response('File deleted', 200);
    }

    public function sendTestMessage()
    {

        // dd('message');
        try {

            // dd('message');

            $accountSid = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_sid')->first()->value;

            $authToken = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_token')->first()->value;

            $twilioNumber = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_number')->first()->value;


            $client = new Client($accountSid, $authToken);


            $message = $client->messages
                ->create('+923218612198', // to
                        ["body" => 'Test Message', "from" => "E-TuningFiles"]
            );


            \Log::info('message sent to:'.'+923218612198');

        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::info($e->getMessage());
        }
    }

    public function sendMessage($receiver, $message, $frontendID, $fileID)
    {
        try {

            $accountSid = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_sid')->first()->value;

            $authToken = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_token')->first()->value;

            $twilioNumber = Key::whereNull('subdealer_group_id')
            ->where('key', 'twilio_number')->first()->value;


            $client = new Client($accountSid, $authToken);

            if($frontendID == 2)
            {
                $message = $client->messages
                    ->create($receiver, // to
                            ["body" => $message, "from" => "TuningX"]
                );
            }

            else if($frontendID == 3)
            {
                $message = $client->messages
                    ->create($receiver, // to
                            ["body" => $message, "from" => "E-TuningFiles"]
                );
            }

            else{

                $message = $client->messages
                    ->create($receiver, // to
                            ["body" => $message, "from" => "ECUTech"]
                );

            }

            \Log::info('message sent to:'.$receiver);
            $this->makeLogEntry('success', 'message sent to:'.$receiver, 'sms', $fileID);

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            $this->makeLogEntry('error', $e->getMessage(), 'sms', $fileID);
        }
    }

    public function makeLogEntry( $type, $message, $requestType, $fileID = 0 ){

        $log = new Log();
        // $log->temporary_file_id = $temporaryFileID;
        $log->file_id = $fileID;
        $log->type = $type;
        $log->request_type = $requestType;
        $log->message = $message;
        $log->save();

    }

    public function assignEngineer(Request $request){

        // dd($request->all());

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $file = File::findOrFail($request->file_id);

        // dd($file);

        $assign = new EngineerAssignmentLog();
        if($file->assigned_to){
            $assign->assigned_from = User::findOrFail($file->assigned_to)->name;
        }
        else{
            $assign->assigned_from = "None";
        }

        $assign->assigned_to = User::findOrFail($request->assigned_to)->name;
        $assign->assigned_by = Auth::user()->name;
        $assign->file_id = $file->id;
        $assign->save();

        $file->assigned_to = $request->assigned_to;
        $file->assignment_time = Carbon::now();
        $file->checked_by = 'customer';

        $file->save();



        $engineer = User::findOrFail($request->assigned_to);

        // dd($engineer);

        $customer = User::findOrFail($file->user_id);

        //    $template = EmailTemplate::where('name', 'Engineer Assignment Email')->first();
        $template = EmailTemplate::where('slug', 'eng-assign')->where('front_end_id', $file->front_end_id)->first();

        $html = $template->html;

        $html = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html);
        $html = str_replace("#customer_name", $customer->name ,$html);
        $html = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html);


        $tunningType = $this->emailStagesAndOption($file);

        $html = str_replace("#tuning_type", $tunningType,$html);
        $html = str_replace("#status", $file->status,$html);
        $html = str_replace("#file_url", route('file', $file->id),$html);

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Engineer Assignment')->first();

        $messageTemplate = MessageTemplate::where('slug', 'eng-assign')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message = str_replace("#customer", $customer->name ,$message);

        if($file->front_end_id == 1){
            $subject = "ECU Tech: Task Assigned!";
        }
        else if($file->front_end_id == 3){
            $subject = "E-files: Task Assigned!";
        }
        else if($file->front_end_id == 2){
            $subject = "TuningX: Task Assigned!";
        }

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if($this->manager['eng_assign_eng_email'.$file->front_end_id]){

            try{

                \Mail::to($engineer->email)->send(new \App\Mail\AllMails(['engineer' => $engineer, 'html' => $html, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$engineer->email, 'email', $file->id);

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$engineer->email.$e->getMessage(), 'email', $file->id);

            }
        }

        if($this->manager['eng_assign_eng_sms'.$file->front_end_id]){

            $this->sendMessage($engineer->phone, $message, $file->front_end_id, $file->id);
        }

        if($this->manager['eng_assign_eng_whatsapp'.$file->front_end_id]){

            $this->sendWhatsappforEng($engineer->name,$engineer->phone, 'admin_assign', $file);
        }

        return Redirect::to('files')->with(['success' => 'Engineer Assigned to File.']);

    }

    public function sendWhatsappforEng($name, $number, $template, $file, $supportMessage = null){

        $accessToken = config('whatsApp.access_token');
        $fromPhoneNumberId = config('whatsApp.from_phone_number_id');

        $optionsMessage = $file->stage;

        if($file->options){
            foreach($file->options()->get() as $option) {
                $optionName = Service::findOrFail($option->service_id)->name;
                $optionsMessage .= ", ".$optionName."";
            }
        }

        $customer = User::findOrFail($file->user_id)->name;

        if($file->front_end_id == 1){
            $frontEnd = "ECUTech";
        }
        else if($file->front_end_id == 3){
            $frontEnd = "E-files";
        }
        else{
            $frontEnd = "Tuning-X";
        }

        if($supportMessage){
            $components  =
            [
                [
                    "type" => "header",
                    "parameters" => array(
                        array("type"=> "text","text"=> $frontEnd),
                    )
                ],
                [
                    "type" => "body",
                    "parameters" => array(
                        array("type"=> "text","text"=> "dear ".$name),
                        array("type"=> "text","text"=> "Mr. ".$customer),
                        array("type"=> "text","text"=> $file->brand." ".$file->engine),
                        array("type"=> "text","text"=> $optionsMessage),
                        array("type"=> "text","text"=> $supportMessage),
                    )
                ]
            ];
        }
        else{
            $components  =
            [
                [
                    "type" => "header",
                    "parameters" => array(
                        array("type"=> "text","text"=> $frontEnd),
                    )
                ],
                [
                    "type" => "body",
                    "parameters" => array(
                        array("type"=> "text","text"=> "dear ".$name),
                        array("type"=> "text","text"=> "Mr. ".$customer),
                        array("type"=> "text","text"=> $file->brand." ".$file->engine),
                        array("type"=> "text","text"=> $optionsMessage),
                    )
                ]
            ];
        }

        $whatappObj = new WhatsappController();

        try {
            $response = $whatappObj->sendTemplateMessage($number,$template, 'en', $accessToken, $fromPhoneNumberId, $components, $messages = 'messages');

        }
        catch(Exception $e){
            \Log::info($e->getMessage());
        }

    }

    public function sendWhatsapp($name, $number, $template, $file, $supportMessage = null){

        $accessToken = config('whatsApp.access_token');
        $fromPhoneNumberId = config('whatsApp.from_phone_number_id');

        $optionsMessage = $file->stage;

        if($file->options){
            foreach($file->options()->get() as $option) {
                $optionName = Service::findOrFail($option->service_id)->name;
                $optionsMessage .= ", ".$optionName."";
            }
        }

        $customer = 'Task Customer';

        if($file->name){
            $customer = $file->name;
        }

        if($file->front_end_id == 1){
            $frontEnd = "ECUTech";
        }
        else if($file->front_end_id == 3){
            $frontEnd = "E-files";
        }
        else{
            $frontEnd = "Tuning-X";
        }

        if($supportMessage){
            $components  =
            [
                [
                    "type" => "header",
                    "parameters" => array(
                        array("type"=> "text","text"=> $frontEnd),
                    )
                ],
                [
                    "type" => "body",
                    "parameters" => array(
                        array("type"=> "text","text"=> "dear ".$name),
                        array("type"=> "text","text"=> "Mr. ".$customer),
                        array("type"=> "text","text"=> $file->brand." ".$file->engine),
                        array("type"=> "text","text"=> $optionsMessage),
                        array("type"=> "text","text"=> $supportMessage),
                    )
                ]
            ];
        }
        else{
            $components  =
            [
                [
                    "type" => "header",
                    "parameters" => array(
                        array("type"=> "text","text"=> $frontEnd),
                    )
                ],
                [
                    "type" => "body",
                    "parameters" => array(
                        array("type"=> "text","text"=> "dear ".$name),
                        array("type"=> "text","text"=> "Mr. ".$customer),
                        array("type"=> "text","text"=> $file->brand." ".$file->engine),
                        array("type"=> "text","text"=> $optionsMessage),
                    )
                ]
            ];
        }

        $whatappObj = new WhatsappController();

        try {
            $response = $whatappObj->sendTemplateMessage($number,$template, 'en', $accessToken, $fromPhoneNumberId, $components, $messages = 'messages');

        }
        catch(Exception $e){
            \Log::info($e->getMessage());
        }


    }

    public function changSupportStatus(Request $request){

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $file = File::findOrFail($request->file_id);
        $this->changeStatusLog($file, $request->support_status, 'support_status', 'File support status changed by engineer from panel.');
        $file->support_status = $request->support_status;
        if($file->support_status == 'closed'){
            if($file->red == 1){
                $file->red = 0;
                $file->timer = NULL;
            }
        }

        if($file->support_status == 'open'){
            $file->assigned_to = NULL;
        }

        $file->save();

        return Redirect::back()->with(['success' => 'File Support status changed.']);
    }

    public function changeStatus(Request $request){

        // if(!Auth::user()->is_admin()){
        //     return abort(404);
        // }

        $file = File::findOrFail($request->file_id);

        $this->changeStatusLog($file, $request->status, 'status', 'File status changed by engineer from Admin Task panel.');

        $file->status = $request->status;

        $file->updated_at = Carbon::now();

        $customer = User::findOrFail($file->user_id);

        if($file->status == 'completed'){
            if($file->red == 1){
                $file->red = 0;
                $file->submission_timer = NULL;
            }
        }

        if($file->status == 'on_hold'){

            $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
            $onHoldTime = (strtotime($file->submission_timer)+($fsdt*60)) - strtotime(now());
            if($onHoldTime > 0){
                $file->on_hold_time = $onHoldTime;
                $file->save();
            }
        }

        if($request->status == 'rejected'){

            if($file->rejected == 0){

                $credit = new Credit();
                $credit->credits = $file->credits;
                $credit->user_id = $customer->id;
                $credit->file_id = $file->id;
                $credit->country = code_to_country( $customer->country );
                $credit->front_end_id = $customer->front_end_id;
                $credit->stripe_id = NULL;

                if($customer->test == 1){
                    $credit->test = 1;
                }

                $credit->gifted = 1;
                $credit->price_payed = 0;

                // dd($request->all());

                if(isset($request->reasons)){
                    $reasons = implode(', ',$request->reasons);
                    $new = new FileReasonsToReject();
                    $new->file_id = $file->id;
                    $new->reasons_to_cancel = $reasons;
                    $new->save();
                }

                if($request->reason_to_reject){
                    $credit->message_to_credit = $request->reason_to_reject;
                    $file->reason_to_reject = $request->reason_to_reject;
                }
                else{
                    $credit->message_to_credit = 'File Canceled!';
                    $file->reason_to_reject = 'File Canceled!';
                }

                $file->rejected = 1;

                $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);
                $credit->save();
            }

        }

        $file->save();

        $admin = get_admin();

        // $template = EmailTemplate::where('name', 'Status Change')->first();
        $template = EmailTemplate::where('slug', 'sta-cha')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html1);


        $tunningType = $this->emailStagesAndOption($file);

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html2);


        $tunningType = $this->emailStagesAndOption($file);

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else if($file->front_end_id == 3){
            $html2 = str_replace("#file_url",  'http://portal.e-tuningfiles.com/'."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Status Change')->first();
        $messageTemplate = MessageTemplate::where('slug', 'sta-cha')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message1 = str_replace("#status", $file->status ,$message1);

        $message2 = str_replace("#customer", $file->name ,$message);
        $message2 = str_replace("#status", $file->status ,$message2);

        if($file->front_end_id == 1){
            $subject = "ECU Tech: File Status Changed!";
        }
        else if($file->front_end_id == 3){
            $subject = "E-files: File Status Changed!";
        }
        else{
            $subject = "TuningX: File Status Changed!";
        }

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if($this->manager['status_change_cus_email'.$file->front_end_id]){

            try{

                \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$customer->email, 'email', $file->id);

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$customer->email.$e->getMessage(), 'email', $file->id);
            }
        }

        if($this->manager['status_change_admin_email'.$file->front_end_id]){

            try{
                \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$admin->email, 'email', $file->id);

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$admin->email.$e->getMessage(), 'email', $file->id);
            }
        }

        if($this->manager['status_change_admin_sms'.$file->front_end_id]){
            $this->sendMessage($admin->phone, $message1, $file->front_end_id, $file->id);
        }

        if($this->manager['status_change_admin_whatsapp'.$file->front_end_id]){

            $this->sendWhatsappforEng($admin->name,$admin->phone, 'status_change', $file);
        }

        if($this->manager['status_change_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id, $file->id);
        }

        if($this->manager['status_change_cus_whatsapp'.$file->front_end_id]){

            $this->sendWhatsapp($customer->name,$customer->phone, 'admin_assign', $file);
        }

        return Redirect::back()->with(['success' => 'File status changed.']);
    }

    public function fileEngineersNotes(Request $request)
    {
        $noteItself = $request->egnineers_internal_notes;

        $file = File::findOrFail($request->file_id);

        $reply = new EngineerFileNote();

        $message = str_replace(PHP_EOL,"<br>",$request->egnineers_internal_notes);

        $reply->egnineers_internal_notes = $message;

        if($request->file('engineers_attachement')){

            $attachment = $request->file('engineers_attachement');
            $fileName = $attachment->getClientOriginalName();
            $model = str_replace('/', '', $file->model );

            $fileName = str_replace('/', '', $fileName);
            $fileName = str_replace('\\', '', $fileName);
            $fileName = str_replace('#', '', $fileName);
            $fileName = str_replace(' ', '_', $fileName);

            if($file->front_end_id == 1){

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../portal/public/'.$file->file_path),$fileName);
                }
                else{

                    if($file->subdealer_group_id){
                        $attachment->move(public_path('/../../subportal/public/'.$file->file_path),$fileName);
                    }
                    else{

                        if($file->on_dev == 1){
                            $attachment->move(public_path('/../../stagingportalecutech/public/'.$file->file_path),$fileName);
                        }
                        else{
                            $attachment->move(env('MNT_ECUTECH').$file->file_path,$fileName);
                            //p $attachment->move('/mnt/portal.ecutech.gr'.$file->file_path,$fileName);
                            // $attachment->move(public_path('/../../portal/public/'.$file->file_path),$fileName);
                        }
                    }
                }
            }
            else if($file->front_end_id == 3){

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../e-tuningfiles/public/'.$file->file_path),$fileName);
                }
                else{

                    if($file->subdealer_group_id){
                        $attachment->move(public_path('/../../subportal/public/'.$file->file_path),$fileName);
                    }
                    else{

                        if($file->on_dev == 1){
                            $attachment->move(public_path('/../../stagingportaletuningfiles/public/'.$file->file_path),$fileName);
                        }
                        else{
                            $attachment->move(env('MNT_ETUNINGFILES').$file->file_path,$fileName);
                            // $path = '/mnt/portal.e-tuningfiles.com'.$file->file_path;
                            //p $attachment->move('/mnt/portal.e-tuningfiles.com'.$file->file_path,$fileName);
                            // $attachment->move(public_path('/../../portal.e-tuningfiles.com/public/'.$file->file_path),$fileName);
                        }
                    }
                }
            }
            else if($file->front_end_id == 4){

                if($file->subdealer_group_id){
                    $attachment->move(public_path('/../../subportal/public/'.$file->file_path),$fileName);
                }
                else{

                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../stagingportaletuningfiles/public/'.$file->file_path),$fileName);
                    }
                    else{
                        $attachment->move(public_path('/../../ctf/public/'.$file->file_path),$fileName);
                    }
                }
            }
            else{

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../tuningX/public/'.$file->file_path),$fileName);
                }
                else{

                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../TuningXV2/public/'.$file->file_path),$fileName);
                    }

                    else{
                        $attachment->move(env('MNT_TUNINGX').$file->file_path,$fileName);
                        //p $attachment->move('/mnt/portal.tuning-x.com/uploads'.$file->file_path,$fileName);
                        // $attachment->move(public_path('/../../tuningX/public/'.$file->file_path),$fileName);
                    }
                }

            }

            $reply->engineers_attachement = $fileName;
        }

        $reply->engineer = true;
        $reply->file_id = $request->file_id;
        $reply->user_id = Auth::user()->id;

        $latest = RequestFile::where('file_id', $request->file_id)->where('show_later', 0)->latest()->first();

        if($latest != NULL){
            $reply->request_file_id = $latest->id;
        }
        else{
            $reply->request_file_id = NULL;
        }

        $reply->save();

        if($file->original_file_id != NULL){
            $ofile = File::findOrFail($file->original_file_id);
            $this->changeStatusLog($ofile, 'closed', 'support_status', 'Chat reply was sent from engineer on request file.');
            $ofile->support_status = "closed";
            $ofile->save();
        }
        $this->changeStatusLog($file, 'closed', 'support_status', 'Chat reply was sent from engineer.');
        $file->support_status = "closed";
        $file->save();
        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();

        // $template = EmailTemplate::where('name', 'Message To Client')->first();
        $template = EmailTemplate::where('slug', 'mess-to-client')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine,$html1);

        $tunningType = $this->emailStagesAndOption($file);

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#note", $request->egnineers_internal_notes,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine,$html2);


        $tunningType = $this->emailStagesAndOption($file);

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#note", $request->egnineers_internal_notes,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else if($file->front_end_id == 3){
            $html2 = str_replace("#file_url",  'http://portal.e-tuningfiles.com/'."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Message To Client')->first();
        $messageTemplate = MessageTemplate::where('slug', 'mess-to-client')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);

        // $message1 = "Hi, Status changed for a file by Customer: " .$customer->name;
        // $message2 = "Hi, Status changed for a file by Customer: " .$file->name;

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if($file->front_end_id == 1){
            $subject = "ECU Tech: Engineer replied to your support message!";
        }
        else if($file->front_end_id == 3){
            $subject = "E-files: Engineer replied to your support message!";
        }
        else{
            $subject = "Tuningx: Engineer replied to your support message!";
        }


        if($this->manager['msg_eng_cus_email'.$file->front_end_id]){

            try{
                \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$customer->email, 'email', $file->id);
            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$customer->email.$e->getMessage(), 'email', $file->id);
            }
        }
        if($this->manager['msg_eng_admin_email'.$file->front_end_id]){

            try{
                \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                $this->makeLogEntry('success', 'email sent to:'.$admin->email, 'email', $file->id);

            }
            catch(TransportException $e){
                \Log::info($e->getMessage());
                $this->makeLogEntry('error', 'email not sent to:'.$admin->email.$e->getMessage(), 'email', $file->id);
            }
        }

        if($this->manager['msg_eng_admin_sms'.$file->front_end_id]){

            $this->sendMessage($admin->phone, $message1, $file->front_end_id, $file->id);
        }

        if($this->manager['msg_eng_admin_whatsapp'.$file->front_end_id]){

            $this->sendWhatsappforEng($admin->name, $admin->phone, 'support_message_from_engineer', $file, $noteItself);
        }

        if($this->manager['msg_eng_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id, $file->id);
        }

        if($this->manager['msg_eng_cus_whatsapp'.$file->front_end_id]){

            $this->sendWhatsapp($customer->name, $customer->phone, 'support_message_from_engineer', $file, $noteItself);
        }

        $old = File::findOrFail($request->file_id);
        $old->checked_by = 'engineer';
        $old->save();

        return redirect()->back()
        ->with('success', 'Engineer note successfully Added!')
        ->with('tab','chat');

    }

    // public function makeLogEntry($fileID, $type, $message){

    //     if(!Auth::user()->is_admin()){
    //         return abort(404);
    //     }

    //     $log = new Log();
    //     $log->file_id = $fileID;
    //     $log->type = $type;
    //     $log->message = $message;
    //     $log->save();

    // }

    public function callbackKess3Complete(Request $request){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        \Log::info( $request->all() );
    }

    public function uploadFileFromEngineer(Request $request) {

        $attachment = $request->file('file');
        $oldName = $attachment->getClientOriginalName();
        $encode = (boolean) $request->encode;
        $magic = (boolean) $request->magic;

        $file = File::findOrFail($request->file_id);

        $engineerFile = new RequestFile();
        $engineerFile->request_file = $oldName;
        $engineerFile->old_name = $oldName;
        $engineerFile->file_type = 'engineer_file';
        $engineerFile->tool_type = 'not_relevant';
        $engineerFile->master_tools = 'not_relevant';
        $engineerFile->file_id = $request->file_id;
        $engineerFile->user_id = Auth::user()->id;
        $engineerFile->engineer = true;

        $engineerFile->save();

        $allSoftwareRecrods = FileReplySoftwareService::where('file_id', $engineerFile->file_id)
        ->whereNull('reply_id')->get();

        foreach($allSoftwareRecrods as $record){
            $record->reply_id = $engineerFile->id;
            $record->save();
        }

        $allMessagesRecrods = FileMessage::where('file_id', $engineerFile->file_id)
        ->whereNull('request_file_id')->get();

        foreach($allMessagesRecrods as $message){
            $message->request_file_id = $engineerFile->id;
            $message->save();
        }

        $allUploadLaterRecrods = UploadLater::where('file_id', $engineerFile->file_id)
        ->whereNull('request_file_id')->get();

        foreach($allUploadLaterRecrods as $later){
            $later->request_file_id = $engineerFile->id;
            $later->save();
        }

        // if($file->front_end_id == 2){
            $engineerFile->show_comments = 0;
        // }

        $middleName = $file->id;
        $middleName .= date("dmy");

        foreach($file->softwares as $s){
            if($s->service_id != 1){
                if($s->reply_id == $engineerFile->id){
                    $middleName .= $s->service_id.$s->software_id;
                }
            }
        }

        $newFileName = $file->brand.'_'.$file->model.'_'.$middleName.'_v'.$file->files->count();

        $newFileName = str_replace('/', '', $newFileName);
        $newFileName = str_replace('\\', '', $newFileName);
        $newFileName = str_replace('#', '', $newFileName);
        $newFileName = str_replace(' ', '_', $newFileName);

        $engineerFile->request_file = $newFileName;
        $engineerFile->save();

        if($encode == 0){

        if($file->subdealer_group_id){
            $attachment->move(public_path('/../../subportal/public'.$file->file_path),$newFileName);

        }

        else{

            if($file->front_end_id == 1){

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
                }
                else{

                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../stagingportalecutech/public'.$file->file_path),$newFileName);
                    }
                    else{
                        $attachment->move(env('MNT_ECUTECH').$file->file_path,$newFileName);
                        //p $attachment->move('/mnt/portal.ecutech.gr'.$file->file_path,$newFileName);
                        // $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
                    }
                }
            }
            else if($file->front_end_id == 3){

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../e-tuningfiles/public'.$file->file_path),$newFileName);
                }
                else{

                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../stagingportaletuningfiles/public'.$file->file_path),$newFileName);
                    }
                    else{
                        $attachment->move(env('MNT_ETUNINGFILES').$file->file_path,$newFileName);
                        // $path = '/mnt/portal.e-tuningfiles.com'.$file->file_path;
                        //p $attachment->move('/mnt/portal.e-tuningfiles.com'.$file->file_path,$newFileName);
                        // $attachment->move(public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path),$newFileName);
                    }
                }

            }
            else if($file->front_end_id == 4){
                // $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);

                if($file->on_dev == 1){
                    $attachment->move(public_path('/../../stagingportaletuningfiles/public'.$file->file_path),$newFileName);
                }
                else{
                    $attachment->move(public_path('/../../ctf/public'.$file->file_path),$newFileName);
                }

            }
            else if($file->front_end_id == 2){

                if(env('APP_ENV') == 'local'){
                    $attachment->move(public_path('/../../tuningX/public'.$file->file_path),$newFileName);
                }
                else{

                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../TuningXV2/public'.$file->file_path),$newFileName);
                    }
                    else{
                        $attachment->move(env('MNT_TUNINGX').$file->file_path,$newFileName);
                        //p $attachment->move('/mnt/portal.tuning-x.com'.$file->file_path,$newFileName);
                        // $attachment->move(public_path('/../../tuningX/public'.$file->file_path),$newFileName);
                    }
                }
            }
        }

        }

        else if($encode == 1){

            if($file->subdealer_group_id){
                $attachment->move(public_path('/../../subportal/public'.$file->file_path),$newFileName);

            }

            else{

                if($file->front_end_id == 1){
                    if(env('APP_ENV') == 'local'){
                        $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
                    }
                    else{

                        if($file->on_dev == 1){
                            $attachment->move(public_path('/../../stagingportalecutech/public'.$file->file_path),$newFileName);
                        }
                        else{
                            $attachment->move(env('MNT_ECUTECH').$file->file_path,$newFileName);
                            //p $attachment->move('/mnt/portal.ecutech.gr'.$file->file_path,$newFileName);
                            // $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
                        }
                    }

                }
                else if($file->front_end_id == 3){

                    if(env('APP_ENV') == 'local'){
                        $attachment->move(public_path('/../../e-tuningfiles/public'.$file->file_path),$newFileName);
                    }
                    else{
                        if($file->on_dev == 1){
                            $attachment->move(public_path('/../../stagingportaletuningfiles/public'.$file->file_path),$newFileName);
                        }
                        else{
                            $attachment->move(env('MNT_ETUNINGFILES').$file->file_path,$newFileName);
                            //p $attachment->move('/mnt/portal.e-tuningfiles.com'.$file->file_path,$newFileName);
                            // $attachment->move(public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path),$newFileName);
                        }
                    }

                }
                else if($file->front_end_id == 4){
                    // $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);

                    if($file->on_dev == 1){
                        $attachment->move(public_path('/../../stagingportaletuningfiles/public'.$file->file_path),$newFileName);
                    }
                    else{
                        $attachment->move(public_path('/../../ctf/public'.$file->file_path),$newFileName);
                    }

                }
                else if($file->front_end_id == 2){

                    if(env('APP_ENV') == 'local'){
                        $attachment->move(public_path('/../../tuningX/public'.$file->file_path),$newFileName);
                    }
                    else{

                        if($file->on_dev == 1){
                            $attachment->move(public_path('/../../TuningXV2/public'.$file->file_path),$newFileName);
                        }
                        else{
                            $attachment->move(env('MNT_TUNINGX').$file->file_path,$newFileName);
                            //p $attachment->move('/mnt/portal.tuning-x.com'.$file->file_path,$newFileName);
                            // $attachment->move(public_path('/../../tuningX/public'.$file->file_path),$newFileName);
                        }
                    }
                }
            }

            if($file->subdealer_group_id){

                $path = public_path('/../../subportal/public'.$file->file_path).$newFileName;
            }
            else{
                if($file->front_end_id == 1){

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
                    }
                    else{

                        if($file->on_dev == 1){

                            $path = public_path('/../../stagingportalecutech/public'.$file->file_path).$newFileName;
                        }
                        else{
                            $path = env('MNT_ECUTECH').$file->file_path.$newFileName;
                            //p $path = '/mnt/portal.ecutech.gr'.$file->file_path.$newFileName;
                            // $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
                        }
                    }

                }
                else if($file->front_end_id == 3){

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../e-tuningfiles/public'.$file->file_path).$newFileName;
                    }
                    else{

                        if($file->on_dev == 1){

                            $path = public_path('/../../stagingportaletuningfiles/public'.$file->file_path).$newFileName;
                        }
                        else{
                             $path = env('MNT_ETUNINGFILES').$file->file_path.$newFileName;
                            //p $path = '/mnt/portal.e-tuningfiles.com'.$file->file_path.$newFileName;
                            // $path = public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path).$newFileName;
                        }
                    }

                }
                else if($file->front_end_id == 4){

                    // $path = public_path('/../../portal/public'.$file->file_path).$newFileName;

                    if($file->on_dev == 1){

                        $path = public_path('/../../stagingportaletuningfiles/public'.$file->file_path).$newFileName;
                    }
                    else{
                        $path = public_path('/../../ctf/public'.$file->file_path).$newFileName;
                    }

                }
                else if($file->front_end_id == 2){

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../tuningX/public'.$file->file_path).$newFileName;
                    }
                    else{

                        if($file->on_dev == 1){

                            $path = public_path('/../../TuningXV2/public'.$file->file_path).$newFileName;
                        }
                        else{
                            $path = env('MNT_TUNINGX').$file->file_path.$newFileName;
                            //p $path = '/mnt/portal.tuning-x.com/uploads'.$file->file_path.$newFileName;
                            // $path = public_path('/../../tuningX/public'.$file->file_path).$newFileName;
                        }
                    }
                }
            }
            $encodingType = $request->encoding_type;

            if($file->alientech_file){ // if slot id is assigned
                $slotID = $file->alientech_file->slot_id;
                $this->alientechObj->saveGUIDandSlotIDToDownloadLaterForEncoding( $file, $path, $slotID, $encodingType, $engineerFile );
            }
        }

        if($magic == 1){

            // if($file->subdealer_group_id){
            //     $attachment->move(public_path('/../../subportal/public'.$file->file_path),$newFileName);

            // }

            // else{

            //     if($file->front_end_id == 1){
            //         // $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);

            //         if($file->on_dev == 1){
            //             $attachment->move(public_path('/../../stagingportalecutech/public'.$file->file_path),$newFileName);
            //         }
            //         else{
            //             $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
            //         }

            //     }
            //     else if($file->front_end_id == 3){
            //         // $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);

            //         // if($file->on_dev == 1){
            //         //     $attachment->move(public_path('/../../stagingportalecutech/public'.$file->file_path),$newFileName);
            //         // }
            //         // else{
            //             $attachment->move(public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path),$newFileName);
            //         // }

            //     }
            //     else if($file->front_end_id == 2){

            //         if($file->on_dev == 1){
            //             $attachment->move(public_path('/../../TuningXV2/public'.$file->file_path),$newFileName);
            //         }
            //         else{
            //             $attachment->move(public_path('/../../tuningX/public'.$file->file_path),$newFileName);
            //         }
            //     }
            // }

            $magicEncryptionType = $request->magic_encryption_type;

            if($file->subdealer_group_id){

                $path = public_path('/../../subportal/public'.$file->file_path).$newFileName;
            }
            else{
                if($file->front_end_id == 1){

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
                    }
                    else{

                        if($file->on_dev == 1){

                            $path = public_path('/../../stagingportalecutech/public'.$file->file_path).$newFileName;
                        }
                        else{
                            $path = env('MNT_ECUTECH').$file->file_path.$newFileName;
                            //p $path = '/mnt/portal.ecutech.gr'.$file->file_path.$newFileName;
                            // $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
                        }
                    }

                }
                else if($file->front_end_id == 3){

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
                    }
                    else{
                        if($file->on_dev == 1){

                            $path = public_path('/../../stagingportaletuningfiles/public'.$file->file_path).$newFileName;
                        }
                        else{
                            $path = env('MNT_ETUNINGFILES').$file->file_path.$newFileName;
                            //p $path = public_path('/mnt/portal.e-tuningfiles.com'.$file->file_path).$newFileName;
                        }
                    }

                }
                else if($file->front_end_id == 4){

                    // $path = public_path('/../../portal/public'.$file->file_path).$newFileName;

                    if($file->on_dev == 1){

                        $path = public_path('/../../stagingportaletuningfiles/public'.$file->file_path).$newFileName;
                    }
                    else{
                        $path = public_path('/../../ctf/public'.$file->file_path).$newFileName;
                    }

                }
                else{

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../tuningX/public'.$file->file_path).$newFileName;
                    }
                    else{

                        if($file->on_dev == 1){

                            $path = public_path('/../../TuningXV2/public'.$file->file_path).$newFileName;
                        }
                        else{
                            $path = env('MNT_TUNINGX').$file->file_path.$newFileName;
                            //p $path = '/mnt/portal.tuning-x.com/uploads'.$file->file_path.$newFileName;
                            // $path = public_path('/../../tuningX/public'.$file->file_path).$newFileName;
                        }
                    }
                }
            }

            $flexLabel = Tool::where('label', 'Flex')->where('type', 'slave')->first();

            if($file->tool_type == 'slave' && $file->tool_id == $flexLabel->id){
                $this->magicObj->magicEncrypt( $path, $file, $newFileName, $engineerFile, $magicEncryptionType );
            }

        }

        $autotunerLabel = Tool::where('label', 'Autotuner')->where('type', 'slave')->first();

        if($file->tool_type == 'slave' && $file->tool_id == $autotunerLabel->id){

            if($file->subdealer_group_id){

                $path = public_path('/../../subportal/public'.$file->file_path).$newFileName;
            }
            else{
                if($file->front_end_id == 1){

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
                    }
                    else{

                        if($file->on_dev == 1){

                            $path = public_path('/../../stagingportalecutech/public'.$file->file_path).$newFileName;
                        }
                        else{
                            $path = env('MNT_ECUTECH').$file->file_path.$newFileName;
                            //p $path = '/mnt/portal.ecutech.gr'.$file->file_path.$newFileName;
                            // $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
                        }
                    }

                }
                else if($file->front_end_id == 3){

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path).$newFileName;
                    }
                    else{

                        if($file->on_dev == 1){

                            $path = public_path('/../../stagingportaletuningfiles/public'.$file->file_path).$newFileName;
                        }
                        else{
                            $path = env('MNT_ETUNINGFILES').$file->file_path.$newFileName;
                            //p $path = '/mnt/portal.e-tuningfiles.com'.$file->file_path.$newFileName;
                            // $path = public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path).$newFileName;
                        }
                    }

                }
                else if($file->front_end_id == 4){

                    // $path = public_path('/../../portal/public'.$file->file_path).$newFileName;

                    if($file->on_dev == 1){

                        $path = public_path('/../../stagingportaletuningfiles/public'.$file->file_path).$newFileName;
                    }
                    else{
                        $path = public_path('/../../ctf/public'.$file->file_path).$newFileName;
                    }

                }
                else{

                    if(env('APP_ENV') == 'local'){
                        $path = public_path('/../../tuningX/public'.$file->file_path).$newFileName;
                    }
                    else{

                        if($file->on_dev == 1){

                            $path = public_path('/../../TuningXV2/public'.$file->file_path).$newFileName;
                        }
                        else{
                            $path = env('MNT_TUNINGX').$file->file_path.$newFileName;
                            //p $path = '/mnt/portal.tuning-x.com'.$file->file_path.$newFileName;
                            // $path = public_path('/../../tuningX/public'.$file->file_path).$newFileName;
                        }
                    }
                }
            }


            $this->autotunerObj->encrypt( $path, $file, $newFileName, $engineerFile);
        }

        $allEearlierReminders = EmailReminder::where('user_id', $file->user_id)
        ->where('file_id', $file->id)->get();

        foreach($allEearlierReminders as $reminderToBeDeleted){
            $reminderToBeDeleted->delete();
        }

        $schedual = Schedualer::take(1)->first();

        $reminder = new EmailReminder();
        $reminder->user_id = $file->user_id;
        $reminder->file_id = $file->id;
        $reminder->request_file_id = $engineerFile->id;
        $reminder->set_time = Carbon::now();
        $reminder->cycle = $schedual->cycle;

        $reminder->save();

        $haltEmailAndStatus = false;

        if( $engineerFile->is_kess3_slave == 1 and $engineerFile->uploaded_successfully == 0 ){
            $haltEmailAndStatus = true;
        }

        if(!$haltEmailAndStatus){

            if($file->upload_later){
                $this->changeStatusLog($file, 'ready_to_send', 'status', 'Engineer uploaded the file but for showing it later to customer.');
                $file->status = 'ready_to_send';

                $engineerFile->show_later = 1;
                $engineerFile->save();
            }

            if($file->status == 'submitted'){

                if(!$file->upload_later){
                    $this->changeStatusLog($file, 'completed', 'status', 'Engineer uploaded the file.');
                    $file->status = 'completed';
                }

                $file->red = 0;
                $file->submission_timer = NULL;
                $file->updated_at = Carbon::now();
                $file->save();
            }

            if(!$file->response_time){

                $file->reupload_time = Carbon::now();
                $file->save();

                $file->response_time = $this->getResponseTime($file);
                $file->save();

            }

            if($file->original_file_id){
                $old = File::findOrFail($file->original_file_id);
                $old->checked_by = 'engineer';

                if($old->support_status == 'open'){
                    $this->changeStatusLog($old, 'closed', 'support_status', 'Engineer uploaded the file.');
                }

                $old->support_status = "closed";

                $old->red = 0;
                $old->timer = NULL;
                $old->save();
            }

            // if($file->no_longer_auto == 0){

                if($file->support_status == 'open'){
                    $this->changeStatusLog($file, 'closed', 'support_status', 'Engineer uploaded the file.');
                }
                $file->support_status = "closed";

                $file->red = 0;
                $file->timer = NULL;
                $file->checked_by = 'engineer';
                $file->save();
            // }

                $file->revisions = $file->files->count()+1;
                $file->save();
        }

        if(!$file->upload_later){

        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();

        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html1);

        $tunningType = $this->emailStagesAndOption($file);

        $html1 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html1);
        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html2);

        $tunningType = $this->emailStagesAndOption($file);

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#response_time", \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans(),$html2);
        $html2 = str_replace("#status", $file->status,$html2);

        if($file->front_end_id == 1){
            $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
        }
        else if($file->front_end_id == 3){
            $html2 = str_replace("#file_url",  'http://portal.e-tuningfiles.com/'."file/".$file->id,$html2);
        }
        else{
            $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
        }

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
        $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')->where('front_end_id', $file->front_end_id)->first();

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);

        if($file->front_end_id == 1){
            $subject = "ECU Tech: Engineer uploaded a file in reply.";
        }
        else if($file->front_end_id == 3){
            $subject = "E-files: Engineer uploaded a file in reply.";
        }
        else if($file->front_end_id == 2){
            $subject = "TuningX: Engineer uploaded a file in reply.";
        }

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        if( $haltEmailAndStatus == 0 ){

            if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

                try{
                    \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                    $this->makeLogEntry('success', 'email sent to:'.$customer->email, 'email', $file->id);

                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                }

            }
            if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){

                try{
                    \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                    $this->makeLogEntry('success', 'email sent to:'.$admin->email, 'email', $file->id);

                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                    $this->makeLogEntry('error', 'email not sent to:'.$admin->email.$e->getMessage(), 'email', $file->id);
                }
            }

            if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
                $this->sendMessage($admin->phone, $message1, $file->front_end_id, $file->id);
            }

            if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
                $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
            }

            if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
                $this->sendMessage($customer->phone, $message2, $file->front_end_id, $file->id);
            }

            if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
                $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
            }

        }
    }

        return response('file uploaded', 200);
    }

    public function feedbackReports(){

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $engineers = get_engineers();
        return view('files.feedback_reports', ['engineers' => $engineers]);
    }

    public function feedbackReportsLive(){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'feedback-report')){

            return view('files.feedback_reports_live');

        }
        else{
            return abort(404);
        }
    }

    public function reports(){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'engineers-report')){

        $engineers = get_engineers();
        return view('files.reports', ['engineers' => $engineers]);
        }
        else{
            abort(404);
        }
    }

    public function reportsEngineerLive(){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'engineers-report')){

            $stages = Service::where('type', 'tunning')->get();

            $engineers = User::whereIn('role_id', [2,3])->orWhere('id', 3)->get();

            return view('files.report-engineers-live', ['stages' => $stages, 'engineers' => $engineers]);
        }

        else{
            abort(404);
        }

    }

    public function getFileName($path, $file, $type){
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if($type == 'decoded'){
            $middle = '_decoded_api';
        }
        else if($type == 'encoded'){
            $middle = '_encoded_api';
        }

        if($extension != ''){
            $fileName = $file->file_attached.$middle.'.'.$extension;
        }
        else{
            $fileName = $file->file_attached.$middle;
        }

        if($type == 'decoded'){
            $file->file_attached = $fileName;
            $file->save();
        }

        if(env('APP_ENV') == 'local'){
            $savingPath = public_path('/../../portal/public'.$file->file_path.$fileName);
        }
        else{
            if($file->on_dev == 1){
                $savingPath = public_path('/../../stagingportalecutech/public'.$file->file_path.$fileName);
            }
            else{
                $savingPath = env('MNT_ECUTECH').$file->file_path.$fileName;
                //p $savingPath = '/mnt/portal.ecutech.gr'.$file->file_path.$fileName;
                // $savingPath = public_path('/../../portal/public'.$file->file_path.$fileName);
            }
        }

        return array(
            'path' => $savingPath,
            'name' => $fileName,
        );
    }

    public function getFeedbackReport(Request $request){

        $files = $this->getReportFilesWithFeedback($request->engineer, $request->feedback);

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
            $html .= '<td>'.$file->brand.'</td>';
            $html .= '<td>'.$file->model.'</td>';
            $html .= '<td>'.$file->ecu.'</td>';
            $html .= '<td><img class="p-r-5" alt="'.$file->stages.'" width="33" height="33" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'">'.$file->stages

            .$options.'</td>';
            if($file->type == 'happy' || $file->type == 'good'){
                $html .= '<td><span class="label label-success">'.ucfirst($file->type).'</span></td>';
            }
            else if ($file->type == 'ok'){
                $html .= '<td><span class="label label-info">'.ucfirst($file->type).'</span></td>';
            }
            else{
                if($file->type){
                    $html .= '<td><span class="label label-danger">'.ucfirst($file->type).'</span></td>';
                }
                else{
                    $html .= '<td><span class="label label-danger">'.'No Feedback'.'</span></td>';
                }
            }
            $html .= '<td>'.$assigned.'</td>';
            $html .= '</tr>';
            $count++;
        }

        return response()->json(['html' =>$html, 'has_files' => $hasFiles ], 200);
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
            $html .= '<td>'.$file->brand .$file->engine  .'</td>';
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

    public function getReportFilesWithFeedback($engineer, $feedback){

        $filesObject = File::Orderby('files.created_at', 'desc')->where('is_credited', 1)->select('*')->addSelect('files.id as id');
        $filesObject = $filesObject->join('request_files', 'files.id', '=' , 'request_files.file_id');

        if($engineer != 'all_engineers'){
            $filesObject = $filesObject->where('assigned_to', $engineer);
        }

        if($feedback == 'all_types'){
            $filesObject = $filesObject->leftjoin('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id');
        }
        else if($feedback == 'not_provided'){
            $filesObject = $filesObject->join('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id', 'left outer');
            $filesObject = $filesObject->whereNull('file_feedback.type');
        }

        else{
            $filesObject = $filesObject->join('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id', 'left outer');
            $filesObject = $filesObject->where('file_feedback.type', $feedback);
        }

        return $filesObject->get();
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

        if($file->automatic){
            return null;
        }
        // $commentObj = Comment::where('engine', $file->engine);
        $commentObj = Comment::where('make', $file->brand);

        // if($file->brand){
        //     $commentObj->where('make', $file->brand);
        // }

        // if($file->Model){
        //     $commentObj->where('model', $file->model);
        // }

        if($file->ecu){
            $commentObj->where('ecu', $file->ecu);
        }

        // if($file->generation){
        //     $commentObj->where('generation', $file->generation);
        // }

        $commentObj->whereNull('subdealer_group_id');

        return $commentObj->get();
    }

    public function getResponseTimeAuto($file){

        $fileAssignmentDateTime = Carbon::parse($file->assignment_time);
        $carbonUploadDateTime = Carbon::parse($file->reupload_time);

        $responseTime = $carbonUploadDateTime->diffInSeconds( $fileAssignmentDateTime );

        return $responseTime;
    }

    public function getResponseTime($file){

        $fileAssignmentDateTime = Carbon::parse($file->created_at);
        $carbonUploadDateTime = Carbon::parse($file->reupload_time);

        $feed = NewsFeed::findOrFail(1);

        $dailyActivationTimeCarbon = Carbon::parse($feed->daily_activation_time);
        $dailyDeactivationTimeCarbon = Carbon::parse($feed->daily_deactivation_time);

        $timeDiff = $dailyDeactivationTimeCarbon->diffInSeconds($dailyActivationTimeCarbon);

        $fileAssignmentDay = $fileAssignmentDateTime->format('Y-m-d');
        $fileUploadDay = $carbonUploadDateTime->format('Y-m-d');

        $assignmentBackToDate =  Carbon::parse( $fileAssignmentDay );
        $uploadBackToDate =  Carbon::parse( $fileUploadDay );

        $daysDiff =  $uploadBackToDate->diffInDays($assignmentBackToDate);

        $fileAssignmentDayWorkHourStart = Carbon::parse( $fileAssignmentDay.' '.$feed->daily_activation_time );
        $fileAssignmentDayWorkHourEnd = Carbon::parse( $fileAssignmentDay.' '.$feed->daily_deactivation_time );

        $fileUploadDayWorkHourStart = Carbon::parse( $fileUploadDay.' '.$feed->daily_activation_time );
        $fileUploadDayWorkHourEnd = Carbon::parse( $fileUploadDay.' '.$feed->daily_deactivation_time );

        $fileAssignmentDateAndTime = Carbon::parse($file->created_at);

        $fileAssignmentDateTime = Carbon::parse($file->created_at);
        $carbonUploadDateTime = Carbon::parse($file->reupload_time);

        $totalTimeWihoutSubtraction = ($timeDiff * $daysDiff);
        $totalTimeWihoutSubtraction += $timeDiff;

        $responseTime = 0;

        $differnceOfSecondsForFileAssingmentDay = 0;

        if( $fileAssignmentDateTime->between($fileAssignmentDayWorkHourStart, $fileAssignmentDayWorkHourEnd, true) ) {

            $differnceOfSecondsForFileAssingmentDay = $fileAssignmentDateTime->diffInSeconds( $fileAssignmentDayWorkHourStart );

        }
        else{

            if($fileAssignmentDateTime->greaterThan($fileAssignmentDayWorkHourEnd)){

                $totalTimeWihoutSubtraction -= $timeDiff;
            }
        }

        $responseTime = $totalTimeWihoutSubtraction - $differnceOfSecondsForFileAssingmentDay;

        $differnceOfSecondsForFileUploadDay = 0;

        if( $carbonUploadDateTime->between($fileUploadDayWorkHourStart, $fileUploadDayWorkHourEnd, true) ) {

            $differnceOfSecondsForFileUploadDay = $carbonUploadDateTime->diffInSeconds( $fileUploadDayWorkHourEnd );

        }
        else{
            if($fileUploadDayWorkHourStart->greaterThan($carbonUploadDateTime)){
                $responseTime -= $timeDiff;
            }
        }

        $responseTime = $responseTime - $differnceOfSecondsForFileUploadDay;

        return $responseTime;
    }

    function manageFiles($files, $frontendID){

        $activeFeed = NewsFeed::where('active', 1)->where('front_end_id', $frontendID)->first();

        $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
        $fsat = Key::where('key', 'file_submitted_alert_time')->first()->value;
        $foat = Key::where('key', 'file_open_alert_time')->first()->value;
        $fodt = Key::where('key', 'file_open_delay_time')->first()->value;

        if($activeFeed != NULL){

            if($activeFeed->type == 'good_news'){

                foreach($files as $file){

                    if($file->timer == NULL){

                        $file->timer = Carbon::now();
                        $file->save();
                    }

                    if($file->submission_timer == NULL){

                        $file->submission_timer = Carbon::now();
                        $file->save();
                    }

                    if($file->status == 'on_hold') {

                        $newtimestamp = strtotime($file->submission_timer.'+ 1 minute');
                        $file->submission_timer = date('Y-m-d H:i:s', $newtimestamp);
                        $file->save();

                        \Log::info("here new submission time: ".'file:'.$file->id.' --- '.$file->submission_timer);
                        \Log::info("here new submission time: ".'file:'.$file->id.' --- '.$file->submission_timer);
                        \Log::info("here new submission time: ".'file:'.$file->id.' --- '.$file->submission_timer);

                    }

                    if($file->timer != NULL){

                        if($file->red == 0){

                            if($file->support_status == 'open') {

                                if( (strtotime($file->timer)+($foat*60))  <= strtotime(now())){
                                    $file->red = 1;
                                    $file->save();
                                }
                            }

                            if($file->status == 'submitted') {

                                if( (strtotime($file->submission_timer)+($fsat*60))  <= strtotime(now())){
                                    $file->red = 1;
                                    $file->save();
                                }
                            }


                        }

                        if($file->delay == 0){

                            if($file->support_status == 'open') {

                                if( (strtotime($file->timer)+($fodt*60))  <= strtotime(now())){
                                    $file->delayed = 1;
                                    $file->save();
                                }
                            }

                            if($file->status == 'on_hold') {

                                if( (strtotime($file->submission_timer)+($fsat*60))  > strtotime(now())){
                                    $file->delayed = 1;
                                    $file->save();
                                }
                            }



                        }

                    }
                }
            }
        }

    }

     /**
     * Show the file.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {



        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files')){

        if(Auth::user()->is_admin()){

            $file = File::where('id',$id)->where(function($q){

                $q->where('type', 'master');


            })->where('is_credited', 1)
            // ->whereNull('original_file_id')
            ->orWhere(function($q){

                $q->where('type', 'subdealer');
                $q->whereNotNull('assigned_from');

            })->where('id',$id)
            ->where('is_credited', 1)
            // ->whereNull('original_file_id')
            ->first();


        }
        else{

            if(get_engineers_permission(Auth::user()->id, 'show-all-files')){

                $file = File::where('id',$id)->where(function($q){

                    $q->where('type', 'master');


                })->where('is_credited', 1)
                // ->whereNull('original_file_id')
                ->orWhere(function($q){

                    $q->where('type', 'subdealer');
                    $q->whereNotNull('assigned_from');

                })->where('id',$id)
                // ->whereNull('original_file_id')
                ->where('is_credited', 1)->first();

            }
            else{

                $file = File::where('id',$id)->where(function($q){

                    $q->where('type', 'master');


                })
                ->where('id',$id)
                ->where('is_credited', 1)
                // ->whereNull('original_file_id')
                ->where('assigned_to', Auth::user()->id)->first();

            }

        }

        if(!$file){
            abort(404);
        }

        // dd($file);

        // dd($file->options_services);

        if($file->checked_by == 'customer'){
            $file->checked_by = 'seen';
            $file->save();
        }

        foreach($file->new_requests as $new){
            if($new->checked_by == 'customer'){
                $new->checked_by = 'seen';
                $new->save();
            }
        }

        $vehicle = Vehicle::where('Make', $file->brand)
        ->where('Model', $file->model)
        ->where('Generation', $file->version)
        ->where('Engine', $file->engine)
        ->first();

        // dd($vehicle);

        $engineers = get_engineers();

        $activeFeed = NewsFeed::where('active', 1)->where('front_end_id', $file->front_end_id)->first();

        if($activeFeed){
            $activeFeedType = $activeFeed->type;
        }
        else{
            $activeFeedType = 'danger';
        }

        // $withoutTypeArray = $file->files->toArray();
        // $unsortedTimelineObjects = [];

        // foreach($withoutTypeArray as $r) {
        //     $fileReq = RequestFile::findOrFail($r['id']);
        //     if($fileReq->file_feedback){
        //         $r['type'] = $fileReq->file_feedback->type;
        //     }
        //     $unsortedTimelineObjects []= $r;
        // }

        // $createdTimes = [];

        // foreach($file->files->toArray() as $t) {
        //     $createdTimes []= $t['created_at'];
        // }

        // foreach($file->engineer_file_notes->toArray() as $a) {
        //     $unsortedTimelineObjects []= $a;
        //     $createdTimes []= $a['created_at'];
        // }

        // foreach($file->file_internel_events->toArray() as $b) {
        //     $unsortedTimelineObjects []= $b;
        //     $createdTimes []= $b['created_at'];
        // }

        // foreach($file->file_urls->toArray() as $b) {
        //     $unsortedTimelineObjects []= $b;
        //     $createdTimes []= $b['created_at'];
        // }

        // array_multisort($createdTimes, SORT_ASC, $unsortedTimelineObjects);

        if($file->ecu){
            $comments = $this->getComments($file);
        }
        else{
            $comments = null;
        }

        $showComments = false;

        $selectedStageOptionsLabels = [];

        $stageO = $file->stage_services;

        $stage = Service::findOrFail($stageO->service_id);

        $selectedStageOptionsLabels []= $stage->label;

        foreach($file->options_services as $op){
            $o = Service::findOrFail($op->service_id);
            $selectedStageOptionsLabels []= $o->label;
        }

        $optionsCommentsRecords = OptionComment::whereIn('service_label', $selectedStageOptionsLabels)
        ->where('brand', $file->brand)
        ->where('ecu', $file->ecu)
        ->get();

        $selectedOptions = [];
        foreach($file->options_services as $selected){
            $selectedOptions []= $selected->service_id;
        }

        if($comments != NULL){
            foreach($comments as $comment){
                // dd($comment->service_id);
                // dd($selectedOptions);
                if( in_array( $comment->service_id, $selectedOptions) ){
                    $showComments = true;
                    break;
                }
            }
        }

        // dd($showComments);

        $options = Service::where('type', 'option')
        ->whereNull('subdealer_group_id')
        ->where('active', 1)
        ->orWhere('tuningx_active', 1)->whereNull('subdealer_group_id')->where('type', 'option')->get();

        $stages = Service::where('type', 'tunning')
        ->whereNull('subdealer_group_id')
        ->where('active', 1)
        ->orWhere('tuningx_active', 1)->whereNull('subdealer_group_id')->where('type', 'tunning')->get();

        $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();
        $flexLabel = Tool::where('label', 'Flex')->where('type', 'slave')->first();
        $autotunerLabel = Tool::where('label', 'Autotuner')->where('type', 'slave')->first();

        $prossingSoftwares = ProcessingSoftware::orderBy('name', 'asc')->get();
        $reasons = ReasonsToReject::orderBy('reason_to_cancel', 'asc')->get();

        $allEngineers = User::whereIn('role_id', [2,3])->where('test', 0)->whereNull('subdealer_group_id')->orWhere('id', 3)->get();
        // dd($allEngineers);
        // dd($file->reasons);

        $servername = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $dbname = env('DB_DATABASE');
        $socket = env('DB_SOCKET');

        // dd($servername." ".$username." ".$password." ".$dbname." ".$socket);

        // try {
        //     // $conn = new PDO("mysql:host=$servername;dbname=$dbname;unix_socket=$socket", $username, $password);
        //     $conn = new PDO("mysql:host=$servername;dbname=$dbname;", $username, $password);
        //     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //     // dd($conn);

        //     // Query to get the latest version from the table 'lua_versions' where file_id = 1
        //     $query = "SELECT * FROM lua_versions WHERE File_Id = " . $file->id . " ORDER BY Id DESC LIMIT 1";

        //     // Execute the query
        //     $result = $conn->query($query);

        //     // Fetch the result as an associative array
        //     $latestVersion = $result->fetch(PDO::FETCH_ASSOC);

        //     dd($latestVersion);

        // }
        // catch (PDOException $e) {
        //     echo "Connection failed: " . $e->getMessage();
        // }

        // // Close the connection
        // $conn = null;

        // dd(env('APP_ENV'));

        if(env('APP_ENV') == 'live'){
            return view('files.show', ['reasons' => $reasons, 'allEngineers' => $allEngineers, 'activeFeedType' => $activeFeedType, 'optionsCommentsRecords' => $optionsCommentsRecords, 'prossingSoftwares' => $prossingSoftwares,'o_file' => $file,'selectedOptions' => $selectedOptions, 'showComments' => $showComments,  'stages' => $stages , 'options' => $options, 'kess3Label' => $kess3Label, 'autotunerLabel' => $autotunerLabel, 'flexLabel' => $flexLabel, 'vehicle' => $vehicle,'file' => $file, 'engineers' => $engineers, 'comments' => $comments ]);
        }
        else if(env('APP_ENV') == 'staging'){
            return view('files.show', ['reasons' => $reasons, 'allEngineers' => $allEngineers,'activeFeedType' => $activeFeedType, 'optionsCommentsRecords' => $optionsCommentsRecords, 'prossingSoftwares' => $prossingSoftwares,'o_file' => $file,'selectedOptions' => $selectedOptions, 'showComments' => $showComments,  'stages' => $stages , 'options' => $options, 'kess3Label' => $kess3Label, 'autotunerLabel' => $autotunerLabel, 'flexLabel' => $flexLabel, 'vehicle' => $vehicle,'file' => $file, 'engineers' => $engineers, 'comments' => $comments ]);
        }
        else{
            return view('files.show', ['reasons' => $reasons, 'allEngineers' => $allEngineers,'activeFeedType' => $activeFeedType, 'optionsCommentsRecords' => $optionsCommentsRecords, 'prossingSoftwares' => $prossingSoftwares, 'o_file' => $file,'selectedOptions' => $selectedOptions, 'showComments' => $showComments, 'stages' => $stages , 'options' => $options, 'kess3Label' => $kess3Label, 'autotunerLabel' => $autotunerLabel, 'flexLabel' => $flexLabel, 'vehicle' => $vehicle,'file' => $file, 'engineers' => $engineers, 'comments' => $comments ]);
        }

        }
        else{
            abort(404);
        }

    }

    public function saveMoreFiles($id, $alientechFileID){

        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getAllManager();

        $token = Key::where('key', 'alientech_access_token')->first()->value;

        $file = File::findOrFail($id);
        // $alientechGUID = AlientechFile::where('purpose', 'download_encoded')->where('key', 'guid')->where('file_id', $id)->first()->value;
        $alientechObj = AlientechFile::findOrFail($alientechFileID);
        $alientechGUID = $alientechObj->value;

        $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$alientechGUID;

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $token,
        ];

        $response = Http::withHeaders($headers)->get($getsyncOpURL);
        $responseBody = json_decode($response->getBody(), true);

        if(!isset($responseBody['result']['name'])){
            $this->makeAlientechLogEntry( $file->id, 'error', 'line 3653; file is not uploaded successfully.', $alientechObj, $response->getBody());
        }
        else{

            $this->makeAlientechLogEntry( $file->id, 'success', 'file uploaded successfully.', $alientechObj, $responseBody['result']['name']);
            $var = $responseBody['result']['name'];

        $fileName = substr($var, strrpos($var, '/') + 1);
        $fileName = str_replace('#', '', $fileName);
        $fileName = $fileName.'_'.$file->id;

        $slotGuid = $responseBody['slotGUID'];

        $result = $responseBody['result'];

        if( isset($result['encodedFileURL']) ){

            $url = $result['encodedFileURL'];

            $headers = [
                'X-Alientech-ReCodAPI-LLC' => $token,
            ];

            $response = Http::withHeaders($headers)->get($url);
            $responseBody = json_decode($response->getBody(), true);

            // $base64_string = $responseBody['data'];
            $base64Data = $responseBody['data'];
            $contents   = base64_decode($base64Data);

            // specify the path and filename for the downloaded file
            $filepath = $responseBody['name'];

            $pathAndNameArrayEncoded = $this->getFileName($filepath, $file, 'encoded');

            // save the decoded string to a file
            $flag = file_put_contents($pathAndNameArrayEncoded['path'], $contents);

            $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

            $headers = [
                'X-Alientech-ReCodAPI-LLC' => $token,
            ];

            $response = Http::withHeaders($headers)->post($url, []);

            $extension = pathinfo($responseBody['name'], PATHINFO_EXTENSION);

            $obj = new AlientechFile();
            $obj->key = $extension;
            $obj->value = $pathAndNameArrayEncoded['name'];
            $obj->purpose = "encoded";
            $obj->file_id = $file->id;
            $obj->save();

            //// this is repetitive code.

            $engineerFile = new RequestFile();
            $engineerFile->request_file = $pathAndNameArrayEncoded['name'];
            $engineerFile->file_type = 'engineer_file';
            $engineerFile->tool_type = 'not_relevant';
            $engineerFile->master_tools = 'not_relevant';
            $engineerFile->file_id = $file->id;
            $engineerFile->engineer = true;
            $engineerFile->save();

            $allEearlierReminders = EmailReminder::where('user_id', $file->user_id)
            ->where('file_id', $file->id)->get();

            foreach($allEearlierReminders as $reminderToBeDeleted){
                $reminderToBeDeleted->delete();
            }

            $schedual = Schedualer::take(1)->first();

            $reminder = new EmailReminder();
            $reminder->user_id = $file->user_id;
            $reminder->file_id = $file->id;
            $reminder->request_file_id = $engineerFile->id;
            $reminder->set_time = Carbon::now();
            $reminder->cycle = $schedual->cycle;

            $reminder->save();

            if($file->status == 'submitted'){
                $this->changeStatusLog($file, 'completed', 'status', 'File is enabled to download.');
                $file->status = 'completed';
                $file->red = 0;
                $file->submission_timer = NULL;
                $file->updated_at = Carbon::now();
                $file->save();
            }

            if(!$file->response_time){

                $file->reupload_time = Carbon::now();
                $file->save();

                $file->response_time = $this->getResponseTime($file);
                $file->save();

            }

            if($file->original_file_id){
                $old = File::findOrFail($file->original_file_id);
                $old->checked_by = 'engineer';
                $this->changeStatusLog($old, 'closed', 'support_status', 'Engineer uploaded the file.');
                $old->support_status = "closed";

                $old->red = 0;
                $old->timer = NULL;
                $old->save();
            }

            $this->changeStatusLog($file, 'closed', 'support_status', 'Engineer uploaded the file.');
                $file->support_status = "closed";

                $file->red = 0;
                $file->timer = NULL;
                $file->checked_by = 'engineer';
                $file->save();

            $customer = User::findOrFail($file->user_id);
            $admin = get_admin();

            // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
            $template = EmailTemplate::where('slug', 'file-up-from-eng')
            ->where('front_end_id', $file->front_end_id)
            ->first();

            $html1 = $template->html;

            $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
            $html1 = str_replace("#customer_name", $customer->name ,$html1);
            $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html1);

            $tunningType = $this->emailStagesAndOption($file);

            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url", route('file', $file->id),$html1);

            $html2 = $template->html;

            $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
            $html2 = str_replace("#customer_name", $file->name ,$html2);
            $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html2);

            $tunningType = $this->emailStagesAndOption($file);

            $html2 = str_replace("#tuning_type", $tunningType,$html2);
            $html2 = str_replace("#status", $file->status,$html2);

            if($file->front_end_id == 1){
                $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);
            }
            else if($file->front_end_id == 3){
                $html2 = str_replace("#file_url",  'http://portal.e-tuningfiles.com/'."file/".$file->id,$html2);
            }
            else{
                $html2 = str_replace("#file_url",  'http://portal.tuning-x.com/'."file/".$file->id,$html2);
            }

            $optionsMessage = "";
            if($file->options){
                foreach($file->options() as $option) {
                    $optionsMessage .= ",".$option." ";
                }
            }

            // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
            $messageTemplate = MessageTemplate::where('slug', 'file-up-from-eng')
            ->where('front_end_id', $file->front_end_id)->first();

            $message = $messageTemplate->text;

            $message1 = str_replace("#customer", $customer->name ,$message);
            $message2 = str_replace("#customer", $file->name ,$message);

            if($file->front_end_id == 1){
                $subject = "ECU Tech: Engineer uploaded a file in reply.";
            }
            else if($file->front_end_id == 3){
                $subject = "E-files: Engineer uploaded a file in reply.";
            }
            else{
                $subject = "TuningX: Engineer uploaded a file in reply.";
            }

            if($this->manager['eng_file_upload_cus_email'.$file->front_end_id]){

                try{
                    \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                    $this->makeLogEntry('success', 'email sent to:'.$customer->email, 'email', $file->id);
                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                }
            }
            if($this->manager['eng_file_upload_admin_email'.$file->front_end_id]){

                try{
                    \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
                    $this->makeLogEntry('success', 'email sent to:'.$admin->email, 'email', $file->id);

                }
                catch(TransportException $e){
                    \Log::info($e->getMessage());
                    $this->makeLogEntry('error', 'email not sent to:'.$admin->email.$e->getMessage(), 'email', $file->id);
                }
            }

            if($this->manager['eng_file_upload_admin_sms'.$file->front_end_id]){
                $this->sendMessage($admin->phone, $message1, $file->front_end_id, $file->id);
            }

            if($this->manager['eng_file_upload_admin_whatsapp'.$file->front_end_id]){
                $this->sendWhatsappforEng($admin->name,$admin->phone, 'eng_file_upload', $file);
            }

            if($this->manager['eng_file_upload_cus_sms'.$file->front_end_id]){
                $this->sendMessage($customer->phone, $message2, $file->front_end_id, $file->id);
            }

            if($this->manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
                $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
            }

            }
        }
    }

    public function changeStatusLog($file, $to, $type, $desc){

        $new = new FilesStatusLog();
        $new->type = $type;

        if($type == 'status'){
            $new->from = $file->status;
        }
        else if($type == 'support_status'){
            $new->from = $file->support_status;
        }

        $new->to = $to;
        $new->desc = $desc;
        $new->file_id = $file->id;
        $new->changed_by = Auth::user()->id;
        $new->save();
    }

    public function emailStagesAndOption($file){

        if( \App\Models\Service::FindOrFail( $file->stage_services->service_id ) ){
            $tunningType = '<img alt=".'.\App\Models\Service::FindOrFail( $file->stage_services->service_id )->name.'" width="33" height="33" src="'.url('icons').'/'.\App\Models\Service::FindOrFail( $file->stage_services->service_id )->icon .'">';
            $tunningType .= '<span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::FindOrFail( $file->stage_services->service_id)->name.'</span>';
        }

        if($file->options_services){

            foreach($file->options_services as $option) {
                $tunningType .= '<div class="p-l-20"><img alt="'.\App\Models\Service::FindOrFail( $option->service_id )->name .'" width="40" height="40" src="'.url('icons').'/'.\App\Models\Service::FindOrFail( $option->service_id )->icon.'">';
                $tunningType .=  \App\Models\Service::FindOrFail( $option->service_id )->name;
                $tunningType .= '</div>';
            }
        }

        return $tunningType;
    }
}
