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
use App\Models\AlientechFile;
use App\Models\FileService;
use App\Models\Key;
use App\Models\Log;
use App\Models\Service;
use App\Models\Tool;
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

use PDO;
use stdClass;

class FilesController extends Controller
{
    private $manager;
    private $alientechObj;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->alientechObj = new AlientechController();
        $reminderManager = new ReminderManagerController();
        $this->manager = $reminderManager->getManager();
        $this->middleware('auth',['except' => ['recordFeedback']]);
    }

    public function getAccessToken(){

        $apiURL = 'https://encodingapi.alientech.to/api/access-tokens/request';
        $postInput = [
            'clientApplicationGUID' => 'f8b0f518-8de7-4528-b8db-3995e1b787e9',
            'secretKey' => "#5!/ThmmM*?;D\\jvjQ6%9/",
        ];
  
        $headers = [
            'Content-Type' => 'application/json'
        ];
  
        $response = Http::withHeaders($headers)->post($apiURL, $postInput);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
     
        if(isset($responseBody['accessToken'])){
            $key = new Key();
            $key->key = 'alientech_access_token';
            $key->value = $responseBody['accessToken'];
            $key->save();
            return $responseBody['accessToken'];
        }

        return null;
    }

    public function callbackKess3(Response $response){
        \Log::info($response);
    }

    public function decodeFile(){

        $token = Key::where('key', 'alientech_access_token')->first()->value;

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots";

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $token,
        ];
  
        $response = Http::withHeaders($headers)->get($url);
        $responseBody = json_decode($response->getBody(), true);

        // dd($responseBody);

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

    public function fileCopyAndPath(){

        // $files = File::all();

        // foreach($files as $file){
        
        //     $toPath = public_path('/../../portal/public/uploads/'.$file->file_attached);

        //     $inPath = public_path('/../../portal/public/uploads/'.$file->brand.'/'.$file->model.'/'.$file->id.'/');
            
        //     if (!file_exists($inPath )) {
        //         mkdir($inPath , 0777, true);
        //     }

        //     $flag = copy( $toPath, $inPath.$file->file_attached);

        //     $file->file_path = '/uploads/'.$file->brand.'/'.$file->model.'/'.$file->id.'/';
        //     $file->save();

        //     $engineerFiles = RequestFile::where('file_id', $file->id)->get();

        //     foreach($engineerFiles as $f){
        //         $flag = copy( $toPath, $inPath.$f->request_file);
        //     }

            
        // }
    }

    public function liveFiles(){

        // $files = File::all();

        // foreach($files as $file){

        //     if($file->stages != NULL){

        //         FileService::where('file_id', $file->id)->delete();
        //         FileService::where('temporary_file_id', $file->id)->delete();

        //         $stage = Service::where('name', $file->stages)->first();

        //         if($stage){
        //             $fileService = new FileService();
        //             $fileService->type = 'stage';
        //             $fileService->credits = $stage->credits;
        //             $fileService->service_id = $stage->id;
        //             $fileService->file_id = $file->id;
        //             $fileService->save();
        //         }
        //         else{
        //             \Log::info('stage not found: '. $file->stages);
        //         }
        //     }
        //     if( $file->options ){
        //         foreach(explode(',', $file->options) as $option){
    
        //             $optionService = Service::where('name', $option)->first();

        //             if($optionService){

        //                 $fileOption = new FileService();
        //                 $fileOption->type = 'option';
        //                 $fileOption->credits = $optionService->credits;
                        
        //                 $fileOption->service_id = $optionService->id;
        //                 $fileOption->file_id = $file->id;
        //                 $fileOption->save();
        //             }
        //             else{
        //                 \Log::info('option not found: '. $option);
        //             }
        //         } 
        //  }

            
        // }

        return view('files.live_files');    
    }
    
    public function updateFileVehicle(Request $request) {
        
        $this->validate($request, [
            // 'brand' => 'required',
            // 'model' => 'required',
            'engine' => 'required',
            'version' => 'required'
        ]);

        $file = File::findOrFail($request->id);
        // $file->brand = $request->brand;
        // $file->model = $request->model;
        $file->version = $request->version;
        $file->engine = $request->engine;
        $file->ecu = $request->ecu;
        $file->save();

        return redirect()->back()
        ->with('success', 'File successfully Edited!');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getModels(Request $request)
    {
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

    public function saveFeedbackEmailSchedual(Request $request) {

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
        //email template
        $feebdackTemplate = EmailTemplate::findOrFail(9);
        $schedual = Schedualer::take(1)->first();
        return view('files.feedback_page', [ 'feebdackTemplate' => $feebdackTemplate, 'schedual' => $schedual ]);
    }

    public function saveFeedbackEmailTemplate(Request $request) {

        $feebdackTemplate = EmailTemplate::findOrFail(9);
        $feebdackTemplate->html = $request->new_template;
        $feebdackTemplate->save();

        return redirect()->route('feedback-emails')->with(['success' => 'Template udpated, successfully.']);

    }

    public function editMessage( Request $request ) {

        $message = EngineerFileNote::findOrFail($request->id);
        $message->egnineers_internal_notes = $request->message;
        $message->save();
        
        return redirect()->back()
        ->with('success', 'Engineer note successfully Edited!')
        ->with('tab','chat');
    }

    // public function generateFeedbackEmail( $fileID, $requestFileID, $userID ) {

    //     $file = File::findOrFail($fileID); // this is a file on local
    //     // $file = File::findOrFail(203); // this is a file on live
    //     $feebdackTemplate = EmailTemplate::findOrFail(9); // email template must always be 9
    //     $html = $feebdackTemplate->html;
    //     $fileName = $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard;

    //     $html = str_replace('#file_name', $fileName, $html);
    //     $html = str_replace('#angry_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/angry', $html);
    //     $html = str_replace('#sad_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/sad', $html);
    //     $html = str_replace('#ok_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/ok', $html);
    //     $html = str_replace('#good_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/good', $html);
    //     $html = str_replace('#happy_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/happy', $html);
    //     $html = str_replace('#happy_link', env('PORTAL_URL').'record_feedback/'.$fileID.'/'.$userID.'/'.$requestFileID.''.'/happy', $html);
    //     $html = str_replace('#file_url', env('PORTAL_URL').'file/'.$fileID, $html);

    //     $subject = "ECU Tech: Feedback Request";
    //     \Mail::to('xrkalix@gmail.com')->send(new \App\Mail\AllMails(['engineer' => [], 'html' => $html, 'subject' => $subject]));

    // }

    // public function testFeedbackEmail() {
    //     // $file = File::findOrFail(42); // this is a file on local
    //     $file = File::findOrFail(231); // this is a file on live
    //     $requestFileID = 118;
    //     $userID = 50;

    //     $this->generateFeedbackEmail($file->id, $requestFileID, $userID);

    //     // $feebdackTemplate = EmailTemplate::findOrFail(9);
    //     // $html = $feebdackTemplate->html;

    //     // // dd($html);

    //     // $subject = "ECU Tech: Feedback Request";
    //     // \Mail::to('xrkalix@gmail.com')->send(new \App\Mail\AllMails(['engineer' => [], 'html' => $html, 'subject' => $subject]));

    // }



    // public function recordFeedback($fileID, $userID, $feedback){
        
    // }

    // public function downloadDecoded($id,$file_name) {

    //     $token = Key::where('key', 'alientech_access_token')->first()->value;

    //     $file = File::findOrFail($id);

    //     $alientechGUID = AlientechFile::where('key', 'guid')->where('file_id', $id)->first()->value;
        
    //     $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$alientechGUID;

    //     $headers = [
    //         'X-Alientech-ReCodAPI-LLC' => $token,
    //     ];
  
    //     $response = Http::withHeaders($headers)->get($getsyncOpURL);
    //     $responseBody = json_decode($response->getBody(), true);

    //     // dd($responseBody);

    //     $slotGuid = $responseBody['slotGUID'];
        
    //     $result = $responseBody['result'];

    //     if($result['kess3Mode'] == 'OBD'){

    //         if( isset($result['obdDecodedFileURL']) ){
            
    //             $url = $result['obdDecodedFileURL'];

    //             $headers = [
    //                 'X-Alientech-ReCodAPI-LLC' => $token,
    //             ];
        
    //             $response = Http::withHeaders($headers)->get($url);
    //             $responseBody = json_decode($response->getBody(), true);

    //             $base64Data = $responseBody['data'];
    //             $contents   = base64_decode($base64Data);

    //             // dd($responseBody);
    //             // specify the path and filename for the downloaded file
    //             $filepath = $responseBody['name'];

    //             $pathAndNameArray = $this->getFileName($filepath, $file, 'decoded');

    //             // save the decoded string to a file
    //             $flag = file_put_contents($pathAndNameArray['path'], $contents);

    //             $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

    //             $headers = [
    //             // 'Content-Type' => 'multipart/form-data',
    //             'X-Alientech-ReCodAPI-LLC' => $token,
    //             ];

    //             $response = Http::withHeaders($headers)->post($url, []);

    //             // // set the content type and headers for downloading the file
    //             // header('Content-Type: application/octet-stream');
    //             // header('Content-Disposition: attachment; filename="'.$responseBody['name'].'"');
                
    //             // // download the file to the user's browser
    //             // readfile($filepath);

    //             // $extension = pathinfo($responseBody['name'], PATHINFO_EXTENSION);
                
    //             // $path = public_path('/../../portal/public'.$file->file_path);
    //             // $file_path = $path.$file->file_attached.'.'.$extension;
    //             // return response()->download($file_path);
                
    //             // $path = public_path('/../../portal/public'.$file->file_path);
    //             // $file_path = $path.$file_name;
    //             // return response()->download($file_path);

    //         }
    //     }
    //     else if($result['kess3Mode'] == 'BootBench'){
    //         foreach($result['bootBenchComponents'] as $row){

    //             $url = $row['decodedFileURL'];

    //             $headers = [
    //                 'X-Alientech-ReCodAPI-LLC' => $token,
    //             ];
        
    //             $response = Http::withHeaders($headers)->get($url);
    //             $responseBody = json_decode($response->getBody(), true);

    //             $base64Data = $responseBody['data'];
    //             $contents   = base64_decode($base64Data);

    //             // specify the path and filename for the downloaded file
    //             $filepath = $responseBody['name'];

    //             $pathAndNameArray = $this->getFileName($filepath, $file,'decoded');

    //             // save the decoded string to a file
    //             $flag = file_put_contents($pathAndNameArray['path'], $contents);

    //             $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

    //             $headers = [
    //                 'X-Alientech-ReCodAPI-LLC' => $token,
    //             ];

    //             $response = Http::withHeaders($headers)->post($url, []);

    //         }
    //     }
    // }

    public function download($id,$file_name) {
        $file = File::findOrFail($id);
        $path = public_path('/../../portal/public'.$file->file_path);
        $file_path = $path.$file_name;
        return response()->download($file_path);
    }

    // /**
    //  * Show the files table.
    //  *
    //  * @return \Illuminate\Contracts\Support\Renderable
    //  */
    // public function index()
    // {

    //     // File::where('is_credited', 0)->delete();

    //     // if(Auth::user()->is_admin || Auth::user()->is_head){
    //         // $files = File::orderBy('support_status', 'desc')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('is_credited', 1)->get();
    //         // $files = File::select('*')
    //         // ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
    //         // ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
    //         // ->orderBy('ss', 'asc')
    //         // ->orderBy('s', 'asc')
    //         // ->orderBy('created_at', 'desc')
    //         // ->where('is_credited', 1)
    //         // ->get();
            
    //     // }
    //     // else if(Auth::user()->is_engineer){
    //     //     // $files = File::orderBy('support_status', 'desc')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('assigned_to', Auth::user()->id)->where('is_credited', 1)->get();
    //     //     $files = File::select('*')
    //     //     ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
    //     //     ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
    //     //     ->orderBy('ss', 'asc')
    //     //     ->orderBy('s', 'asc')
    //     //     ->orderBy('created_at', 'desc')
    //     //     ->where('is_credited', 1)
    //     //     // ->where('assigned_to', Auth::user()->id)
    //     //     ->get();
    //     // }

    //     // foreach($files as $file){
    //     //     if($file->reupload_time){
    //     //         $file->response_time = $this->getResponseTime($file);
    //     //         $file->save();
    //     //     }
    //     // }

    //     // $reminders = EmailReminder::all();

    //     // $dateCheck = date('Y-m-d');

    //     // // dd($dateCheck);
    //     // // dd($dateCheck);
    //     // // $dateCheck = '2023-02-01';

    //     // $current = Carbon::parse(Carbon::createFromTimestamp(strtotime($dateCheck))->format('Y-m-d'));
        
    //     // // dd($current);
        
    //     // // dd($current);
    //     // // dd(Carbon::now());

    //     // $schedualer = Schedualer::take(1)->first();

    //     // $days = $schedualer->days;
    //     // $time = $schedualer->time_of_day;

    //     // foreach($reminders as $reminder){

    //     //     // dd($reminder->set_time);

    //     //     $reminderSetDate = Carbon::parse(Carbon::createFromTimestamp(strtotime($reminder->set_time))->format('Y-m-d'));
            
    //     //     // dd($reminderSetDate);

    //     //     $emailTime = $reminderSetDate->addDays($days);

    //     //     // dd($emailTime);

    //     //     $result = $emailTime->eq($current);

    //     //     // dd($result);

    //     //     if($result){
    //     //             // dd(Carbon::parse($time));
    //     //             // $timeGreater = now()->greaterThan(Carbon::parse($time));
    //     //             $timeGreater = true;
    //     //             // $timeGreater = now()->greaterThan(Carbon::parse());

    //     //        if($timeGreater){
    //     //             //    $this->generateFeedbackEmail($reminder->file_id, $reminder->request_file_id, $reminder->user_id);
    //     //             $reminder->cycle = $reminder->cycle - 1;

    //     //             if($reminder->cycle == 0){
    //     //                 $reminder->delete();
    //     //             }
    //     //             else{
    //     //                 $reminder->set_time = Carbon::now();
    //     //                 $reminder->save();
    //     //             }
    //     //        }
    //     //     }
    //     // }
        
    //     return view('files.files', ['files' => $files]);
    // }

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

    // public function testMessage(){
    //     $this->sendMessage('+923218612198', 'test message again');
    //     dd('sms test');
    // }

    // public function testEmail(){
    //     $html = "<p>testing</p>";
    //     \Mail::to('xrkalix@gmail.com')->send(new \App\Mail\AllMails(['engineer' => NULL, 'html' => $html, 'subject' => 'testing']));
    //     dd('email test');
    // }

    public function sendMessage($receiver, $message)
    {
        try {
            $accountSid = env("TWILIO_SID");
            $authToken = env("TWILIO_AUTH_TOKEN");
            $twilioNumber = env("TWILIO_NUMBER"); 
            $client = new Client($accountSid, $authToken);

            $message = $client->messages
                  ->create($receiver, // to
                           ["body" => $message, "from" => "ecutech"]
            );

            \Log::info('message sent to:'.$receiver);

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function assignEngineer(Request $request){
    
       $file = File::findOrFail($request->file_id);
       $file->assigned_to = $request->assigned_to;
       $file->assignment_time = Carbon::now();
       $file->save();

       $engineer = User::findOrFail($request->assigned_to);
       $customer = User::findOrFail($file->user_id);
    
    //    $template = EmailTemplate::where('name', 'Engineer Assignment Email')->first();
       $template = EmailTemplate::findOrFail(1);

       $html = $template->html;

       $html = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html);
       $html = str_replace("#customer_name", $customer->name ,$html);
       $html = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html);
       
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

        $messageTemplate = MessageTemplate::findOrFail(1);

        $message = $messageTemplate->text;

        $message = str_replace("#customer", $customer->name ,$message);

        $subject = "ECU Tech: Task Assigned!";

        if($this->manager['eng_assign_eng_email']){

            \Mail::to($engineer->email)->send(new \App\Mail\AllMails(['engineer' => $engineer, 'html' => $html, 'subject' => $subject]));
        }

        if($this->manager['eng_assign_eng_sms']){
        
            $this->sendMessage($engineer->phone, $message);
        }
        
        return Redirect::back()->with(['success' => 'Engineer Assigned to File.']);

    }

    public function changSupportStatus(Request $request){

        $file = File::findOrFail($request->file_id);
        $file->support_status = $request->support_status;
        $file->save();

        return Redirect::back()->with(['success' => 'File Support status changed.']);
    }

    public function changeStatus(Request $request){
        
        $file = File::findOrFail($request->file_id);
        $file->status = $request->status;
        $file->save();

        $customer = User::findOrFail($file->user_id);
        $admin = User::where('is_admin', 1)->first();
    
        // $template = EmailTemplate::where('name', 'Status Change')->first();
        $template = EmailTemplate::findOrFail(8);

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
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
        
        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
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

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Status Change')->first();
        $messageTemplate = MessageTemplate::findOrFail(6);

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message1 = str_replace("#status", $file->status ,$message1);

        $message2 = str_replace("#customer", $file->name ,$message);
        $message2 = str_replace("#status", $file->status ,$message2);
        
        $subject = "ECU Tech: File Status Changed!";

        if($this->manager['status_change_cus_email']){
            \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject]));
        }

        if($this->manager['status_change_admin_email']){
            \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject]));
        }

        if($this->manager['status_change_admin_sms']){
            $this->sendMessage($admin->phone, $message1);
        }
        if($this->manager['status_change_cus_sms']){
            $this->sendMessage($customer->phone, $message2);
        }

        return Redirect::back()->with(['success' => 'File status changed.']);
    }

    public function fileEngineersNotes(Request $request)
    {   
        $file = File::findOrFail($request->file_id);

        $reply = new EngineerFileNote();
        $reply->egnineers_internal_notes = $request->egnineers_internal_notes;

        if($request->file('engineers_attachement')){

            $attachment = $request->file('engineers_attachement');
            $fileName = $attachment->getClientOriginalName();
            $attachment->move(public_path('/../../portal/public/uploads/'.$file->brand.'/'.$file->model.'/'.$file->id.'/'),$fileName);
            $reply->engineers_attachement = $fileName;
        }

        $reply->engineer = true;
        $reply->file_id = $request->file_id;
        $reply->save();
        
        $file->support_status = "closed";
        $file->save();
        $customer = User::findOrFail($file->user_id);
        $admin = User::where('is_admin', 1)->first();
    
        // $template = EmailTemplate::where('name', 'Message To Client')->first();
        $template = EmailTemplate::findOrFail(7);

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
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

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#note", $request->egnineers_internal_notes,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
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

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#note", $request->egnineers_internal_notes,$html2);
        $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'Message To Client')->first();
        $messageTemplate = MessageTemplate::findOrFail(4);

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);

        // $message1 = "Hi, Status changed for a file by Customer: " .$customer->name;
        // $message2 = "Hi, Status changed for a file by Customer: " .$file->name;
       
        $subject = "ECU Tech: Engineer replied to your support message!";
        if($this->manager['msg_eng_cus_email']){
            \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject]));
        }
        if($this->manager['msg_eng_admin_email']){
            \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject]));
        }
        
        if($this->manager['msg_eng_admin_sms']){
            $this->sendMessage($admin->phone, $message1);
        }
        if($this->manager['msg_eng_cus_sms']){
            $this->sendMessage($customer->phone, $message2);
        }

        $old = File::findOrFail($request->file_id);
        $old->checked_by = 'engineer';
        $old->save();

        return redirect()->back()
        ->with('success', 'Engineer note successfully Added!')
        ->with('tab','chat');

    }

    // public function uploadFileFromEngineerAndEncode(Request $request){

    //     $attachment = $request->file('file');

    //     $file = File::findOrFail($request->file_id);

    //     $fileName = $attachment->getClientOriginalName();
        
    //     $newFileName = str_replace('#', '_', $fileName);
    //     $newFileName = str_replace(' ', '_', $fileName);

    //     $attachment->move(public_path('/../../portal/public/uploads/'.$file->brand.'/'.$file->model.'/'.$file->id.'/'),$newFileName);

    //     $path = public_path('/../../portal/public/uploads/'.$file->brand.'/'.$file->model.'/'.$file->id.'/').$newFileName;
    //     $slotID = AlientechFile::where('key', 'slotGUID')->where('file_id', $file->id)->first()->value;
    //     $token = Key::where('key', 'alientech_access_token')->first()->value;

    //     $encodingType = $request->encoding_type;
    //     $response = $this->uploadFileToEncode($token, $path, $slotID, $encodingType);

    //     if( isset($response->guid) ){

    //         if($encodingType == 'dec'){
    //             $url = "https://encodingapi.alientech.to/api/kess3/encode-obd-file";
    //         }
    //         else if($encodingType == 'micro'){
    //             $url = "https://encodingapi.alientech.to/api/kess3/encode-boot-bench-file";
    //         }

    //         $headers = [
    //         'X-Alientech-ReCodAPI-LLC' => $token,
    //         ];
            
    //         if($encodingType == 'dec'){
    //             $postInput = [
    //                 'userCustomerCode' => 'user1',
    //                 'kess3FileSlotGUID' => $slotID,
    //                 'modifiedFileGUID' => $response->guid,
    //                 // 'WillCorrectCVN' => true,
    //             ];
    //         }
    //         else if($encodingType == 'micro'){
    //             $postInput = [
    //                 'userCustomerCode' => 'user1',
    //                 'kess3FileSlotGUID' => $slotID,
    //                 'modifiedFileGUID' => $response->guid,
    //                 // 'WillCorrectCVN' => true,
    //             ];
    //         }

    //         $syncResponse = Http::withHeaders($headers)->post($url, $postInput);                
    //         $syncResponseBody = json_decode($syncResponse->getBody(), true);

    //         if(!AlientechFile::where('file_id', $file->id)->where('purpose', 'download_encoded')->first()){

    //             $obj = new AlientechFile();
    //             $obj->key = 'guid';
    //             $obj->value = $syncResponseBody['guid'];
    //             $obj->purpose = "download_encoded";
    //             $obj->file_id = $file->id;
    //             $obj->save();
    //         }

    //         return response('file will be ready after few seconds. please refresh the page.', 200);

    //     }
    //     else{

    //         $this->makeLogEntry($file->id, 'error', 'File Upload error. Line: 1236.');
    //         return response('Uploaded file is not Compatible', 500);
    //     }
    // }

    public function makeLogEntry($fileID, $type, $message){

        $log = new Log();
        $log->file_id = $fileID;
        $log->type = $type;
        $log->message = $message;
        $log->save();

    }

    // public function uploadFileToEncode($token, $path, $slotID, $encodingType){

    //     $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotID."/reopen";

    //     $headers = [
    //     // 'Content-Type' => 'multipart/form-data',
    //     'X-Alientech-ReCodAPI-LLC' => $token,
    //     ];

    //     $response = Http::withHeaders($headers)->post($url, []);
        
    //     if($encodingType == 'dec'){
    //         $target_url = 'https://encodingapi.alientech.to/api/kess3/upload-modified-file/user01/'.$slotID.'/OBDModified';
    //     }
    //     else if($encodingType == 'micro'){
    //         $target_url = 'https://encodingapi.alientech.to/api/kess3/upload-modified-file/user01/'.$slotID.'/BootBenchModifiedMicro';
    //     }

    //     // $target_url = 'https://encodingapi.alientech.to/api/kess3/upload-modified-file/user01/'.$slotID.'/BootBenchModifiedEEPROM';
    
    //     if (function_exists('curl_file_create')) { // php 5.5+
    //         $cFile = curl_file_create($path);
    //         // $cFile = curl_file_create(public_path('Test_File1'));
    //       } else { // 
    //         // $cFile = '@' . realpath(public_path('Test_File1'));
    //         $cFile = '@' . realpath($path);
    //       }
    
    //       $post = array('file'=> $cFile);
    //       $ch = curl_init();
    //       curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-type: multipart/form-data',
    //         'X-Alientech-ReCodAPI-LLC:'.$token
    //     ]);
    //       curl_setopt($ch, CURLOPT_URL,$target_url);
    //       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    //       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    //       curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    //       $result=curl_exec ($ch);
    //       curl_close ($ch);
    //       return json_decode($result);
    // }

    public function callbackKess3Complete(Request $request){
        \Log::info( $request->all() );
    }
    
    public function uploadFileFromEngineer(Request $request)
    {
        $attachment = $request->file('file');
        $encode = (boolean) $request->encode;
       
        $file = File::findOrFail($request->file_id);

        $fileName = $attachment->getClientOriginalName();

        $newFileName = str_replace('#', '', $fileName);
        $newFileName = str_replace(' ', '_', $newFileName);
        
        $attachment->move(public_path('/../../portal/public'.$file->file_path),$newFileName);
        
        if($encode){

            $path = public_path('/../../portal/public'.$file->file_path).$newFileName;
            $encodingType = $request->encoding_type;

            if($file->alientech_file){ // if slot id is assigned
                $slotID = $file->alientech_file->slot_id;
                $this->alientechObj->saveGUIDandSlotIDToDownloadLaterForEncoding( $file, $path, $slotID, $encodingType );
            }
        }
        
        $engineerFile = new RequestFile();
        $engineerFile->request_file = $newFileName;
        $engineerFile->file_type = 'engineer_file';
        $engineerFile->tool_type = 'not_relevant';
        $engineerFile->master_tools = 'not_relevant';
        $engineerFile->file_id = $request->file_id;
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
            $file->status = 'completed';
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
            $file->support_status = "closed";
            $old->save();
        }

            $file->support_status = "closed";
            $file->checked_by = 'engineer';
            $file->save();

        $customer = User::findOrFail($file->user_id);
        $admin = User::where('is_admin', 1)->first();
    
        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::findOrFail(6);

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
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

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
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

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
        $messageTemplate = MessageTemplate::findOrFail(7);

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);
        
        $subject = "ECU Tech: Engineer uploaded a file in reply.";

        if($this->manager['eng_file_upload_cus_email']){
            \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject]));
        }
        if($this->manager['eng_file_upload_admin_email']){
            \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject]));
        }
        
        if($this->manager['eng_file_upload_admin_sms']){
            $this->sendMessage($admin->phone, $message1);
        }
        if($this->manager['eng_file_upload_cus_sms']){
            $this->sendMessage($customer->phone, $message2);
        }
        
        return response('file uploaded', 200);
    }

    public function feedbackReports(){
        $engineers = User::where('is_engineer', 1)->get();
        return view('files.feedback_reports', ['engineers' => $engineers]);
    }

    public function feedbackReportsLive(){
        
        return view('files.feedback_reports_live');
    }

    public function reports(){
        $engineers = User::where('is_engineer', 1)->get();
        return view('files.reports', ['engineers' => $engineers]);
    }

    public function reportsEngineerLive(){
        return view('files.report-engineers-live');
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

        $savingPath = public_path('/../../portal/public'.$file->file_path.$fileName);

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

    public function getResponseTimeAuto($file){
        
        $fileAssignmentDateTime = Carbon::parse($file->assignment_time);
        $carbonUploadDateTime = Carbon::parse($file->reupload_time);

        $responseTime = $carbonUploadDateTime->diffInSeconds( $fileAssignmentDateTime );

        return $responseTime;
    }

    public function getResponseTime($file){
        
        $fileAssignmentDateTime = Carbon::parse($file->assignment_time);
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

        $fileAssignmentDateAndTime = Carbon::parse($file->assignment_time);

        $fileAssignmentDateTime = Carbon::parse($file->assignment_time);
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

     /**
     * Show the file.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        if(Auth::user()->is_admin){
            $file = File::where('id', $id)->where('is_credited', 1)->first();
        }
        else if(Auth::user()->is_engineer){
            $file = File::where('id',$id)->where('is_credited', 1)->first();
            // $file = File::where('id',$id)->where('assigned_to', Auth::user()->id)->where('is_credited', 1)->first();
        }

        if(!$file){
            abort(404);
        }
        
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

        if($file->ecu){
            $comments = $this->getComments($file);
        }
        else{
            $comments = null;
        }

        $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();

        return view('files.show', [ 'kess3Label' => $kess3Label, 'vehicle' => $vehicle,'file' => $file, 'messages' => $unsortedTimelineObjects, 'engineers' => $engineers, 'comments' => $comments ]);
    }

    public function saveMoreFiles($id, $alientechFileID){

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
            $this->makeLogEntry($file->id, 'error', 'line 1998; file is not uploaded successfully.');
        }
        else{

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
            $file->status = 'completed';
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
            $file->support_status = "closed";
            $old->save();
        }

            $file->support_status = "closed";
            $file->checked_by = 'engineer';
            $file->save();

        $customer = User::findOrFail($file->user_id);
        $admin = User::where('is_admin', 1)->first();
    
        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::findOrFail(6);

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html1);
        
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

        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard ,$html2);
        
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

        $html2 = str_replace("#tuning_type", $tunningType,$html2);
        $html2 = str_replace("#status", $file->status,$html2);
        $html2 = str_replace("#file_url",  env('PORTAL_URL')."file/".$file->id,$html2);

        $optionsMessage = "";
        if($file->options){
            foreach($file->options() as $option) {
                $optionsMessage .= ",".$option." ";
            }
        }

        // $messageTemplate = MessageTemplate::where('name', 'File Uploaded from Engineer')->first();
        $messageTemplate = MessageTemplate::findOrFail(7);

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);
        
        $subject = "ECU Tech: Engineer uploaded a file in reply.";

        if($this->manager['eng_file_upload_cus_email']){
            \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject]));
        }
        if($this->manager['eng_file_upload_admin_email']){
            \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject]));
        }
        
        if($this->manager['eng_file_upload_admin_sms']){
            $this->sendMessage($admin->phone, $message1);
        }
        if($this->manager['eng_file_upload_cus_sms']){
            $this->sendMessage($customer->phone, $message2);
        }

        }
    }

        
    }
}
