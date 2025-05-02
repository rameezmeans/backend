<?php

namespace App\Http\Controllers;

use App\Models\AlientechFile;
use App\Models\Combination;
use App\Models\Credit;
use App\Models\DownloadLuaFile;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\FileReplySoftwareService;
use App\Models\FileService;
use App\Models\Key;
use App\Models\Log;
use App\Models\MessageTemplate;
use App\Models\Price;
use App\Models\ReminderManager;
use App\Models\RequestFile;
use App\Models\Service;
use App\Models\TemporaryFile;
use App\Models\Tool;
use App\Models\TunnedFile;
use App\Models\User;
use App\Models\UserTool;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Chatify\Facades\ChatifyMessenger as Chatify;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

class FilesAPIController extends Controller
{

    public function pythonApplyModifications(Request $request){

        $fileID = $request->file_id;
        $mod = $request->mod;
        $timeout = $request->timeout;
        $enableMaxDiffArea = $request->enable_max_diff_area;
        $maxDiffArea = $request->max_diff_area;
        $enableMaxDiffBytes = $request->enable_max_diff_bytes;
        $maxDiffBytes = $request->max_diff_bytes;
        $minSimilarityDiffThreshold = $request->min_similarity_diff_threshold;
        $loop = $request->loop;

        try {
            $response = Http::timeout(10)->post('http://79.129.68.101:5000/api2', [
                'FILE_ID' => $fileID,
                'MOD' => $mod,
                'ENABLE_MAX_DIFF_AREA' => $enableMaxDiffArea,
                'MAX_DIFF_AREA' => $maxDiffArea,
                'ENABLE_MAX_DIFF_BYTES' => $enableMaxDiffBytes,
                'MAX_DIFF_BYTES' => $maxDiffBytes,
                'MIN_SIMILARITY_DIFF_THRESHOLD' => $minSimilarityDiffThreshold,
                'TIMEOUT' => $timeout,
                'LOOP' => $loop,
            ]);
        
            if ($response->successful()) {
                // Success! Handle response
                $data = $response->json();
                return response()->json($data);
            } elseif ($response->clientError()) {
                // 4xx errors
                FacadesLog::error('Client error', ['response' => $response->body()]);
                return response()->json(['status' => 400 ,'error' => '400: Client Error', 'response' => $response->body()], 400);
            } elseif ($response->serverError()) {
                // 5xx errors
                FacadesLog::error('Server error', ['response' => $response->body()]);
                return response()->json(['status' => 500 ,'error' => '500: Server Error', 'response' => $response->body()], 500);
            }
        } catch (\Exception $e) {
            FacadesLog::error('Request failed', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Request failed: ' . $e->getMessage()], 500);
        }

    }

    public function pythonFileSearch(Request $request){

        $tempFile = TemporaryFile::where('id', $request->temp_file_id)->first();

        if($tempFile == NULL){
            return response()->json(['error' => '400: Client Error', 'response' => "tempFile does not found."], 400);
        }

        $location = url('uploads').'/'.$tempFile->file_attached;

        dd($location);
        
        $threshold = $request->threshold;
        $timeout = $request->timeout;
        $fileSizeFilter = $request->file_size_filter;

        try {
            $response = Http::timeout(10)->post('http://79.129.68.101:5000/api1', [
                'INPUT_FILE_URL' => $location,
                'FILE_MATCHING' => $threshold,
                'TIMEOUT' => $timeout,
                'FILE_SIZE_FILTER' => $fileSizeFilter,
            ]);
        
            if ($response->successful()) {
                // Success! Handle response
                $data = $response->json();
                return response()->json($data);
            } elseif ($response->clientError()) {
                // 4xx errors
                FacadesLog::error('Client error', ['response' => $response->body()]);
                return response()->json(['status' => 400 ,'error' => '400: Client Error', 'response' => $response->body()], 400);
            } elseif ($response->serverError()) {
                // 5xx errors
                FacadesLog::error('Server error', ['response' => $response->body()]);
                return response()->json(['status' => 500 ,'error' => '500: Server Error', 'response' => $response->body()], 500);
            }
        } catch (\Exception $e) {
            FacadesLog::error('Request failed', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Request failed: ' . $e->getMessage()], 500);
        }

    }

    public function homeInformation(Request $request){

        $user = User::findOrFail($request->user_id);

        $thisWeeksFilesCount = File::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('user_id', $user->id)->count();
        $thisMonthsFilesCount = File::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at',Carbon::now()->month)->where('user_id', $user->id)->count();

        $thisYearsFilesCount = File::whereYear('created_at', Carbon::now()->year)->where('user_id', $user->id)->count();

        $invoices = Credit::orderBy('created_at', 'desc')->where('credits','!=', 0)->where('user_id', $user->id)->get();

        $records = [];
        foreach($invoices as $invoice){
            $temp = [];

            $temp['date'] = date('Y - m - d', strtotime( $invoice->created_at));
            $temp['credits'] = $invoice->credits;
            $temp['message'] = $invoice->message_to_credit;
            $temp['price'] = $invoice->price_payed;

            $records []= $temp;
        }

        $monthsOfYear = ['January','Fabrury','March','April','May',
        'June','July','August','September','October', 'November', 'December'];

        $items = File::select('id', 'created_at')->where('user_id', $user->id)
        ->get()
        ->groupBy(function($date) {

            if(Carbon::parse($date->created_at)->format('Y') == date('Y')){
                //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                return Carbon::parse($date->created_at)->format('m'); // grouping by months
            }
        });

        $count = [];
        $countYear = [];
        
        foreach ($items as $key => $value) {
            $count[(int)$key] = count($value);
        }
        
        for($i = 1; $i <= 12; $i++){
            if(!empty($count[$i])){
                $countYear[$i] = $count[$i];    
            }else{
                $countYear[$i] = 0;    
            }
        }

        $datesMonth = [];
        $datesMonthCount = [];

        for($i = 1; $i <=  date('t'); $i++){
            // add the date to the dates array
            $datesMonth[] =  str_pad($i, 2, '0', STR_PAD_LEFT).'-'. date('M');
            $datesMonthCount []= File::whereMonth('created_at',date('m'))->whereDay('created_at',$i)->where('user_id', $user->id)->count();
        }

        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekEnd = Carbon::now()->endOfWeek();

        $weekRange = $this->createDateRangeArray($thisWeekStart, $thisWeekEnd);

        $weekCount = [];
        foreach($weekRange as $r) {
            $date = DateTime::createFromFormat('d/m/Y', $r);
            $day = $date->format('d');
            $month = $date->format('m');
            $weekCount []= File::whereMonth('created_at',$month)->whereDay('created_at',$day)->where('user_id', $user->id)->count();
        }


        return response()->json([
            'thisWeeksFilesCount' => $thisWeeksFilesCount,
            'thisMonthsFilesCount' => $thisMonthsFilesCount,
            'thisYearsFilesCount' => $thisYearsFilesCount,
            'invoices' => $records,
            'monthsOfYear' => $monthsOfYear,
            'countYear' => $countYear,
            'datesMonth' => $datesMonth,
            'datesMonthCount' => $datesMonthCount,
            'weekRange' => $weekRange,
            'weekCount' => $weekCount,
        ], 200);

    }

    public function createDateRangeArray($strDateFrom,$strDateTo){
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
    
        // could test validity of dates here but I'm already doing
        // that in the main script
    
        $aryRange = [];
    
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
    
        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('d/m/y', $iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('d/m/y', $iDateFrom));
            }
        }
        return $aryRange;
    }

    public function saveFileOptions(Request $request){

        $file = TemporaryFile::findOrfail($request->temporary_file_id);
        $stage = Service::findOrFail($request->stage);
        $frontendID = $request->front_end_id;
        $options = $request->options;

        $servieCredits = 0;

        $serviceIDs = [];
        $serviceIDs []= $stage->id;

        if( $options && sizeof($options) > 0 ){
            foreach($options as $option){

                FileService::where('service_id', $option)->where('temporary_file_id', $file->id)->delete();
                $optionService = Service::FindOrFail($option);
                $fileOption = new FileService();
                $fileOption->type = 'option';

                // $fileOption->credits = $optionService->credits;

                $optionsRecord = $optionService->optios_stage($stage->id)->first();

                if($frontendID == 1){

                    $servieCredits += $optionService->credits;
                    $fileOption->credits = $optionService->credits;
                    
                    $serviceIDs []= $optionService->id;
                
                }
                else{

                    if($file->tool_type == 'master'){
                        $servieCredits += $optionsRecord->master_credits;
                        $fileOption->credits = $optionsRecord->master_credits;
                    }
                    else{
                        $servieCredits += $optionsRecord->slave_credits;
                        $fileOption->credits = $optionsRecord->slave_credits;
                    }

                }

                $fileOption->service_id = $optionService->id;
                $fileOption->temporary_file_id = $file->id;
                $fileOption->save();
            } 
        }

        $combination = $this->getCombination($serviceIDs);

        $discount = 0;

        if($combination){

            $discount = $combination->actual_credits - $combination->discounted_credits;
        }

        return $servieCredits-$discount;

    }

    public function getCombination($serviceIDs){

        $combinations = Combination::all();
        
        $recordServiceIDs = [];

        $found = false;
        $combinationFound = null;

        foreach($combinations as $combination){

            $recordServiceIDs = [];

            foreach($combination->services as $record){
                $recordServiceIDs []= $record->service_id;
            }

            $serviceCount = count($recordServiceIDs);
            $recordsCount = count($serviceIDs);

            $count = 0;
            
            if($recordsCount == $serviceCount){
            
                foreach($recordServiceIDs as $id){

                    if( !in_array($id, $serviceIDs) ){
                        $found = false;
                        break;
                    }
                    else{
                        $count++;
                    }

                    if($count == $recordsCount){
                        $found = true;
                        $combinationFound = $combination;
                        break;
                    }
                }
            }
        
        }

        return $combinationFound;
    }

    public function saveFile(Request $request){
        
        $user = User::findOrFail($request->user_id);
        $type = 'stripe';
        $fileID = $request->file_id;
        $creditsToFile = $request->credits;
        
        if($type == 'stripe'){
            $account = $user->stripe_payment_account();
        }
        else if($type == 'paypal'){
            $account = $user->paypal_payment_account();
        }
        else{
            $account = $user->viva_payment_account();
        }

        $head =  get_head();
        // $creditsInAccount = $this->getUserCreditsInAccount($user);
        $creditsInAccount = 17;
        
        if($creditsInAccount >= $creditsToFile){

            $tempFileObj = TemporaryFile::findOrFail($fileID);
            
            $tempFile = TemporaryFile::findOrFail($fileID)->toArray();

            // $credit = new Credit();

            // $credit->credits = -1*$creditsToFile;
            // $credit->price_payed = 0;
            // $credit->front_end_id = $user->front_end_id;
            // $credit->invoice_id = 'SPENT-'.$account->prefix.mt_rand(100,999);
            // $credit->user_id = $user->id;
            
            // $credit->created_at = Carbon::now()->addSecond(2);
            // $credit->updated_at = Carbon::now()->addSecond(2);
            
            // if($user->test == 1){
            //     $credit->test = 1;
            // }

            // $credit->save();

            $tempFile['credit_id'] = 0;
            $tempFile['checked_by'] = "customer";
            
            $tempFile['user_id'] = $user->id;
            $tempFile['username'] =  $user->name;

            // $tempFile['assigned_to'] =  $head->id; // assigned to Nick

            // if(File::where('credit_id', $credit->id)->first() === NULL){

            $file = File::create($tempFile);

            $file->credits = $creditsToFile;
            $file->front_end_id = $user->front_end_id;
            $file->temporary_file_id = $tempFileObj->id;

            if($user->test == 1){
                $file->test = 1;
            }

            if(env('APP_ENV') == 'live'){
                $file->on_dev = 0;
            }
            else if(env('APP_ENV') == 'local'){
                $file->on_dev = 0;
            }
            else{
                $file->on_dev = 1;
            }
            
            
            $file->assignment_time = Carbon::now();
            
            $modelToAdd = str_replace( '/', '', $file->model );

            if($file->original_file_id == NULL){
                $directoryToMake = public_path('uploads/ETF'.'/'.$file->brand.'/'.$modelToAdd.'/'.$file->id.'/');
            }
            else{
                $directoryToMake = public_path('uploads/ETF'.'/'.$file->brand.'/'.$modelToAdd.'/'.$file->original_file_id.'/');
            }
            
            if($file->original_file_id == NULL){
            
                if (!file_exists($directoryToMake)) {
                    $oldmask = umask(000);
                    mkdir( $directoryToMake , 0777, true);
                    umask($oldmask);        
                }
            }

            if(file_exists(public_path('uploads').'/'.$file->file_attached)){
                rename(public_path('uploads').'/'.$file->file_attached, $directoryToMake.$file->file_attached);
                // unlink(public_path('uploads').'/'.$file->file_attached);
            }
            
            if($file->acm_file){

                if(file_exists(public_path('uploads').'/'.$file->acm_file)){
                    rename(public_path('uploads').'/'.$file->acm_file, $directoryToMake.$file->acm_file);
                    // unlink(public_path('uploads').'/'.$file->acm_file);
                }
            }

            if($file->original_file_id){

                $file->file_path = '/uploads/ETF/'.$file->brand.'/'.$modelToAdd.'/'.$file->original_file_id.'/';

                $originalFile = File::findOrFail($file->original_file_id);
                $originalFile->support_status = "open";
                $originalFile->checked_by = "customer";
                $originalFile->save();

            }
            else{
                $file->file_path = '/uploads/ETF/'.$file->brand.'/'.$modelToAdd.'/'.$file->id.'/';
            }

            $file->save();

            $logs = Log::where('temporary_file_id', $fileID)->update( ['file_id' => $file->id, 'temporary_file_id' => 0 ]);
            $services = FileService::where('temporary_file_id', $fileID)->update( ['file_id' => $file->id, 'temporary_file_id' => 0 ]);
            
            $flexLabel = Tool::where('label', 'Flex')->where('type', 'slave')->first();

            if($file->tool_type == 'slave' && $file->tool_id == $flexLabel->id){

                (new MagicsportsMainController)->process($tempFile, $file, $directoryToMake);
            }

            $autoTurnerLabel = Tool::where('label', 'Autotuner')->where('type', 'slave')->first();

            if($file->tool_type == 'slave' && $file->tool_id == $autoTurnerLabel->id){

                (new AutotunerMainController)->process($tempFile, $file, $directoryToMake);
            }
            
            $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();
            if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id){

            $alientechFileFlag = AlientechFile::where('temporary_file_id', $fileID)->update( ['file_id' => $file->id, 'temporary_file_id' => 0 ]);

                if( $alientechFileFlag ){
                    
                    $alientechFile = AlientechFile::where('file_id', $file->id)->first();
                    $fileName = (new AlientechMainController)->process( $alientechFile->guid );
                    if($fileName){
                        $file->checking_status = 'unchecked';
                    }
                }
            }

            $tempComments = $tempFileObj->comments;

            if($tempComments){
                foreach($tempComments as $t){
                    $t->file_id = $file->id;
                    $t->save();

                }
            }
            
            $temporaryFileDelete = TemporaryFile::findOrFail($fileID)->delete();
            
            $credit = new Credit();

            $credit->credits = -1*$creditsToFile;
            $credit->price_payed = 0;
            $credit->front_end_id = $user->front_end_id;
            $credit->invoice_id = 'SPENT-'.$account->prefix.mt_rand(100,999);
            $credit->user_id = $user->id;
            
            $credit->created_at = Carbon::now()->addSecond(2);
            $credit->updated_at = Carbon::now()->addSecond(2);
            
            if($user->test == 1){
                $credit->test = 1;
            }
            
            $credit->file_id = $file->id;
            $credit->save();
                
            // }
            // else{
            //     return view('505');   
            // }

            $file->credit_id = $credit->id;
            $file->api = 1;
            $file->stage = Service::findOrFail($file->stages_services->service_id)->name;
            $file->is_credited = 1; // finally is_credited now ... 
            $file->save();
        }

        return response()->json([
            'message' => 'file is saved finally.',
            'file' => $file,
        ], 201);
    }

    public function saveFileStages(Request $request){

        $file = TemporaryFile::findOrfail($request->temporary_file_id);
        $stage = Service::findOrFail($request->stage);
        $frontendID = $request->front_end_id;

        FileService::where('type', 'stage')->where('temporary_file_id', $file->id)->delete();

        $servieCredits = 0;

        $fileService = new FileService();
        $fileService->type = 'stage';

        if($frontendID == 1){
            $servieCredits +=  $stage->credits;
            $fileService->credits = $stage->credits;
        }
        else if($frontendID == 3){
            if($file->tool_type == 'master'){
                $servieCredits +=  $stage->efiles_credits;
                $fileService->credits = $stage->efiles_credits;
            }
            else{
                $servieCredits +=  $stage->efiles_slave_credits;
                $fileService->credits = $stage->efiles_slave_credits;
            }
        }
        else{
            if($file->tool_type == 'master'){
                $servieCredits +=  $stage->tuningx_credits;
                $fileService->credits = $stage->tuningx_credits;
            }
            else{
                $servieCredits +=  $stage->tuningx_slave_credits;
                $fileService->credits = $stage->tuningx_slave_credits;
            }
        }
        
        $fileService->service_id = $stage->id;
        $fileService->temporary_file_id = $file->id;
        $fileService->save();

        return response()->json([
            'message' => 'stages are saved.',
            'credits' => $servieCredits,
        ], 201);

    }

    public function addStep1InforIntoTempFile(Request $request){

        $data['temporary_file_id'] = $request->temporary_file_id;
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['model_year'] = $request->model_year;
        $data['file_type'] = $request->file_type;
        $data['license_plate'] = $request->license_plate;
        $data['is_original'] = $request->is_original;
        $data['vin_number'] = $request->vin_number;
        $data['brand'] = $request->brand;
        $data['model'] = $request->model;
        $data['engine'] = $request->engine;
        $data['version'] = $request->version;
        $data['engine'] = $request->engine;
        $data['ecu'] = $request->ecu;
        $data['gear_box'] = $request->gear_box;
        $data['gearbox_ecu'] = $request->gearbox_ecu;
        $data['modification'] = $request->modification;
        $data['mention_modification'] = $request->mention_modification;
        $data['additional_comments'] = $request->additional_comments;

        $file = TemporaryFile::findOrFail($data['temporary_file_id']);

        $file->name = $data['name'];
        $file->email = $data['email'];
        $file->phone = $data['phone'];
        $file->model_year = $data['model_year'];
        $file->file_type = $data['file_type'];
        $file->license_plate = $data['license_plate'];
        $file->vin_number = $data['vin_number'];
        $file->brand = $data['brand'];
        $file->model = $data['model'];
        $file->engine = $data['engine'];
        $file->version = $data['version'];

        if(isset($data['ecu'])){
            $file->ecu = $data['ecu'];
            $file->gearbox_ecu = NULL;
        }

        if(isset($data['gearbox_ecu']) || $data['gearbox_ecu'] != ''){
            $file->gearbox_ecu = $data['gearbox_ecu'];
            $file->ecu = NULL;
        }

        $file->gear_box = $data['gear_box'];
        $file->is_original = ($data['is_original'] == 'yes') ? '1' : '0';

        if(isset($data['modification'])){
            $file->modification = implode(', ',$data['modification']);
        }

        if(isset($data['mention_modification'])){
            $file->mention_modification = $data['mention_modification'];
        }

        $file->additional_comments = $data['additional_comments'];
        
        $file['credits'] = 0;
        
        // if($fileUploaded){
        //     $fileName = $fileUploaded->getClientOriginalName();
        //     $fileName = $this->getFilename($fileName);
        //     $fileUploaded->move(public_path('uploads'),$fileName);
        //     $file->acm_file = $fileName;
        // }

        $file->save();

        return response()->json([
            'message' => 'temporary file information saved.',
            'file' => $file,
        ], 201);
    }

    public function createTemporaryFile(Request $request) {

        $user = User::findOrFail($request->user_id);
        $file = $request->file;
        $toolType = $request->tool_type;
        $toolID = $request->tool_id;
        $frontendID = $request->front_end_id;

        $fileName = $file->getClientOriginalName();
        // $extension = $file->getClientOriginalExtension();

        $fileName = $this->getFilename($fileName);

        // $path = $this->getPath($file);

        $tempFile = new TemporaryFile();
        $tempFile->tool_type = $toolType;
        $tempFile->file_path = "";
        $tempFile->user_id = $user->id;
        $tempFile->front_end_id = $frontendID;
        $tempFile->tool_id = $toolID;
        $tempFile->file_attached = $fileName;
        $tempFile->save();

        $tempFile->file_attached = $tempFile->id.'___'.$tempFile->file_attached;
        $tempFile->save();

        $file->move(public_path('uploads'),$tempFile->file_attached);

        return response()->json([
            'message' => 'temporary file created.',
            'tempFile' => $tempFile,
        ], 201);

    }

    public function getPythonFiles($fileAttached){
        $path = public_path('uploads').'/'.$fileAttached;

        
    }

    public function getFilename($fileName){

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        
        $fileName = str_replace('#', '_', $fileName);
        $fileName = str_replace('.', '_', $fileName);
        $fileName = str_replace(' ', '_', $fileName);

        $fileName = preg_replace('/[^a-z0-9_ ]/i', '', $fileName); 

        $serialNumber = Carbon::now()->format('YmdHis');

        if($extension != ''){
            $fileName = $fileName.'___'.$serialNumber.'.'.$extension;
        }

        return $fileName;

    }

    public function submitFile( Request $request ) {

        $kess3Label = Tool::where('label', 'Kess_V3')->where('type', 'slave')->first();
        
        $tool = Tool::findOrFail($request->tool_id);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $fileName = str_replace('#', '_', $fileName);
        $fileName = str_replace('.', '_', $fileName);
        $fileName = str_replace(' ', '_', $fileName);

        $fileName = preg_replace('/[^a-z0-9_ ]/i', '', $fileName); 
        
        $file = new File();
        $file->tool_id = $tool->id;
        $file->tool_type = $tool->type;
        $file->file_attached = $fileName;
        $file->name = $request->name;
        $file->email = $request->email;
        $file->phone = $request->phone;
        $file->model_year = $request->model_year;
        $file->file_type = $request->file_type;
        $file->license_plate = $request->license_plate;
        $file->vin_number = $request->vin_number;
        $file->brand = $request->brand;
        $file->model = $request->model;
        $file->engine = $request->engine;
        $file->version = $request->version;
        $file->ecu = $request->ecu;
        $file->gear_box = $request->gear_box;
        $file->additional_comments = $request->additional_comments;
        $file->checking_status == 'unchecked';

        $file->dtc_off_comments = $request->dtc_off_comments;
        $file->vmax_off_comments = $request->vmax_off_comments;

        $stage = Service::FindOrFail($request->stage);

        $price = Price::where('label', 'credit_price')->whereNull('subdealer_group_id')->first()->value;

        $servieCredits = 0;

        $customer = get_subdealer_user($request->subdealer_group_id);

        $manager = (new ReminderManagerController())->getManager($file->front_end_id);

        $head =  get_head();

        $creditsInAccount = $customer->credits->sum('credits');

        if($creditsInAccount >= $request->credits){

            $credit = new Credit();

            $credit->credits = -1*$servieCredits;
            $credit->price_payed = $servieCredits*$price;
            $credit->invoice_id = 'INV-'.$customer->stripe_payment_account()->prefix.mt_rand(1000,9999);
            $credit->user_id = $customer->id;
            $credit->country = code_to_country( $customer->country );
            $credit->save();

            if($customer->test == 1){
                $credit->test = 1;
            }

            $file->credit_id = $credit->id;
            $file->checked_by = "customer";
            $file->user_id = $customer->id;
            $file->username =  $customer->name;
            $file->assigned_to =  $head->id; // assigned to Nick

            if(File::where('credit_id', $credit->id)->first() === NULL){
                
                $file->credit_id = $credit->id;
                
                $file->assignment_time = Carbon::now();
                
                $modelToAdd = str_replace( '/', '', $file->model );
                $directoryToMake = public_path('uploads'.'/'.$file->brand.'/'.$modelToAdd.'/'.$file->id.'/');

                if (!file_exists($directoryToMake)) {
                    $oldmask = umask(000);
                    mkdir( $directoryToMake , 0777, true);
                    umask($oldmask);        
                }
                
                $file->file_path = '/uploads/'.$file->brand.'/'.$modelToAdd.'/'.$file->id.'/';
                $file->credits = 0;
                $file->save();

                $fileService = new FileService();
                $fileService->type = 'stage';
                $fileService->credits = $stage->credits;
                $servieCredits += $stage->credits;
                $fileService->service_id = $stage->id;
                $fileService->file_id = $file->id;
                $fileService->save();

                    if( $request->options && sizeof($request->options) > 0 ){
                        foreach($request->options as $option){

                            $optionService = Service::FindOrFail($option);
                            $fileOption = new FileService();
                            $fileOption->type = 'option';
                            $fileOption->credits = $optionService->credits;
                            $servieCredits += $optionService->credits;
                            $fileOption->service_id = $optionService->id;
                            $fileOption->file_id = $file->id;
                            $fileOption->save();
                        } 
                    }

                    $totalCredits = 0;

                    if($request->subdealer_group_id){
            
                    $stage = Service::findOrFail($file->stage_services->service_id);

                    if($stage->subdealerGroup){
                        $totalCredits += $stage->subdealerGroup->subdealer_credits;
                    }
                    else{
                        $totalCredits += $stage->credits;
                    }

                    if( $file->options_services && sizeof($file->options_services) > 0 ){
                        foreach($file->options_services as $option){
                            $optionService = Service::FindOrFail($option->service_id);
                            if($optionService->subdealerGroup){
                                $totalCredits += $optionService->subdealerGroup->master_credits;
                            }
                            else{
                                $totalCredits += $optionService->credits;
                            }
                        }
                    }
                }

                $file->credits = $servieCredits;
                $file->subdealer_credits = $totalCredits;
                $file->save();

                $credit->file_id = $file->id;
                $credit->save();
                
            }
            
            $file->stage = Service::findOrFail($file->stage_services->service_id)->name;
            $file->is_credited = 1; // finally is_credited now ... 
            $file->save();

            $count = File::where('checked_by', 'customer')->where('is_credited', 1)->count();
            // send to user using pusher
            Chatify::push("private-chatify.".env('LIVE_CHAT_ID'), 'file-uploaded', [
                'count' => $count
            ]);

            $admin = get_admin();
            
            $template = EmailTemplate::findOrFail(1);

            $html1 = $template->html;

            $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
            $html1 = str_replace("#customer_name", $customer->name ,$html1);
            $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine ,$html1);
            
            $tunningType = $this->emailStagesAndOption($file);
            
            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url",env('BACKEND_URL').'file/'.$file->id,$html1);

            $messageTemplate = MessageTemplate::findOrFail(1);
            
            $message = $messageTemplate->text;
            $message = str_replace("#customer", $customer->name,$message);
            
            $subject = "ECU Tech: Task Assigned!";

            if($manager['eng_assign_eng_email']){
                \Mail::to($head->email)->send(new \App\Mail\AllMails([ 'html' => $html1, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
            }
            if($manager['eng_assign_eng_sms']){
                $this->sendMessage($head->phone, $message, $file->front_end_id);
            }
            
            $template = EmailTemplate::findOrFail(2);

            $html = $template->html;

            $uploader = User::findOrFail($file->user_id);

            $html = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html);
            $html = str_replace("#customer_name", $uploader->name ,$html);
            $html = str_replace("#vehicle_name", $file->brand." ".$file->engine." " ,$html);
            
            $tunningType = $this->emailStagesAndOption($file);
            
            $html1 = str_replace("#tuning_type", $tunningType,$html1);
            $html1 = str_replace("#status", $file->status,$html1);
            $html1 = str_replace("#file_url",env('BACKEND_URL').'file/'.$file->id,$html1);

            $messageTemplate = MessageTemplate::findOrFail(2);

            $message = $messageTemplate->text;
            
            $message = str_replace("#customer", $uploader->name,$message);
            
            $subject = "ECU Tech: File Uploaded!";

            if($manager['file_upload_admin_email']){
                \Mail::to($admin->email)->send(new \App\Mail\AllMails(['html' => $html, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
            }

            if($manager['file_upload_admin_sms']){
                $this->sendMessage($admin->phone, $message, $file->front_end_id);
            }

            return response()->json('file submitted.');

        }

    }

    public function brands( Request $request ){
        $brandsObjects = Vehicle::OrderBy('make', 'asc')->select('make')->distinct()->get();
        $brands = [];
        foreach($brandsObjects as $b){
            if($b->make != '')
            $brands []= $b->make;
        }

        return response()->json($brands);
    }

    public function models(Request $request){
        $modelsObjects = Vehicle::OrderBy('model', 'asc')->select('model')->whereNotNull('model')->distinct()->where('make', '=', $request->brand)->get();

        $models = [];
        foreach($modelsObjects as $m){
            if($m->model != '')
            $models []= $m->model;
        }

        return response()->json($models);
    }

    public function versions(Request $request){

        $versionsObjects = Vehicle::OrderBy('generation', 'asc')->whereNotNull('generation')->select('generation')->distinct()
        ->where('Make', '=', $request->brand)
        ->where('Model', '=', $request->model)
        ->get();

        $versions = [];
        foreach($versionsObjects as $v){
            if($v->generation != '')
            $versions []= $v->generation;   
        }  

        return response()->json($versions);
    }

    public function engines(Request $request){

        $enginesObjects = Vehicle::OrderBy('engine', 'asc')
        ->whereNotNull('engine')->select('engine')->distinct()
        ->where('Make', '=', $request->brand)
        ->where('Model', '=', $request->model)
        ->where('Generation', '=', $request->version)
        ->get();

        $engines = [];
        foreach($enginesObjects as $e){
            if($e->engine != '')
            $engines []= $e->engine;   
        }   

        return response()->json($engines);
    }

    public function evcCreditsTable(Request $request){

        $user = User::findOrFail($request->user_id);
        $evcCredits = Credit::orderBy('created_at', 'desc')->where('is_evc', 1)->where('user_id', $user->id)->get();
        
        $creditsArr = [];

        foreach($evcCredits as $credit){
            $row = [];

            $row []= date('Y - m - d', strtotime( $credit->created_at));
            $row []= $credit->credits;
            $row [] = $credit->message_to_credit;
            $row [] = $credit->invoice_id;
            $row [] = $credit->price_payed.'€';

            $creditsArr []= $row;
        }


        return response()->json(['evc_credits_log' => $creditsArr], 200);
    }
    public function creditsTable(Request $request){

        $user = User::findOrFail($request->user_id);
        $credits = Credit::orderBy('created_at', 'desc')->where('is_evc', 0)->where('user_id', $user->id)->get();
        
        $creditsArr = [];

        foreach($credits as $credit){
            $row = [];

            $row []= date('Y - m - d', strtotime( $credit->created_at));
            $row []= $credit->credits;
            $row []= $credit->running_total($user);
            
            if(!$credit->file_id){
                $row []= $credit->message_to_credit;
            }
            else{
                $file = File::where('id', $credit->file_id)->first();

                if(!$file){
                    $row []= "File Deleted:".$credit->file_id;
                }
                else{
                        if($file->vehicle()){
                            $row []= $file->vehicle()->Name.$file->engine.$file->vehicle()->TORQUE_standard;
                        }
                        else {
                            $row []= $file->engine;
                        }
                    }
                }
                
                if($credit->credits > 0){
                    $row []=  $credit->invoice_id;
                }

                if(!$credit->file_id){
                    $row []=  $credit->price_payed."€";
                }

            $creditsArr []= $row;
        }

       return response()->json(['credits_log' => $creditsArr], 200);
    }
    

    public function editAccount(Request $request){

        $user = User::findOrFail($request->user_id);
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{

            $user->company_name = $request->company_name;
            $user->company_id = $request->company_id;
            $user->name = $request->name;
            $user->phone = $request->phone;
            
            $user->evc_customer_id = $request->evc_customer_id;
            $user->save();

            $files = File::where('user_id', $user->id)->get();

            foreach($files as $file){
                $file->name = $user->name;
                $file->phone = $user->phone;
                $file->email = $user->email;
                $file->save();
            }

            return response()->json(['message' => 'account updated.', 'user' => $user], 201);

        }


    }

    public function deleleAccount(Request $request){

        $user = User::findOrFail($request->user_id);
        $user->delete();
        
        return response()->json(['message' => 'user deleted.'], 201);
    }

    public function changePasswordAPI(Request $request){

        $user = User::findOrFail($request->user_id);
        
        $validator = Validator::make($request->all(),[
            'current_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'new_password_confirm' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else{
            
            if (Hash::check($request->current_password, $user->password)) {

                if($request->new_password == $request->new_password_confirm){
                    $user->password = Hash::make(trim($request->new_password));
                    $user->save();

                    return response()->json(['message' => 'password updated.'], 200);
                }
                else{
                    return response()->json(['error' => 'new Password does not match'], 400);
                }
                

            } else {
                return response()->json(['error' => 'Password does not match'], 400);
            }
        }
    }

    public function ecus(Request $request){

        $ecus = Vehicle::OrderBy('Engine_ECU', 'asc')->whereNotNull('Engine_ECU')->select('Engine_ECU')->distinct()
        ->where('Make', '=', $request->brand)
        ->where('Model', '=', $request->model)
        ->where('Generation', '=', $request->version)
        ->where('Engine', '=', $request->engine)
        ->get();

        $ecusArray = [];

        foreach($ecus as $e){
            $temp = explode(' / ', $e->Engine_ECU);
            $ecusArray = array_merge($ecusArray,$temp);
        }

        return response()->json($ecusArray);
    }

    public function usersFiles(Request $request){

        $files = File::where('user_id', $request->user_id)
        ->where('is_credited', 1)
        ->get();
        
        return response()->json($files);
    }

    public function usersCredits(Request $request){
        
        $credits = Credit::where('user_id', $request->user_id)->sum('credits');
        return response()->json($credits);
    }

    public function getUser(Request $request){
        $account = User::where('id', $request->user_id)->first();
        return response()->json($account);
    }

    public function usersInvoices(Request $request){
        
        $invoices = Credit::where('user_id', $request->user_id)->orderBy('created_at', 'desc')->where('price_payed', '>', 0)->get();
        return response()->json($invoices);
    }

    public function tools(Request $request){

        $toolUsers = UserTool::where('user_id', $request->user_id)->get();
        
        $tools = [];

        foreach($toolUsers as $t){
            $tools []= Tool::findOrFail($t->tool_id);
        }

        return response()->json($tools);
    }

    public function addSubdealersCredits(Request $request){

        $subdealer = get_subdealer_user($request->subdealer_group_id);

        $credit = new Credit();
        $credit->credits = $request->credits;
        $credit->user_id = $subdealer->id;
        $credit->country = code_to_country( $subdealer->country );
        $credit->stripe_id = $request->stripe_id;
        $credit->price_payed = $request->price_payed;
        $credit->invoice_id = 'Remote-'.mt_rand(1000,9999);
        $credit->save();

        return response()->json('credits added.');
    }

    public function subtractSubdealersCredits(Request $request){

        $subdealer = get_subdealer_user($request->subdealer_group_id);

        $credit = new Credit();
        $credit->credits = -1*($request->credits);
        $credit->user_id = $subdealer->id;
        $credit->country = code_to_country( $subdealer->country );
        $credit->stripe_id = $request->stripe_id;
        $credit->price_payed = 0;
        $credit->invoice_id = 'Remote-'.mt_rand(1000,9999);
        $credit->save();

        return response()->json('credits subtracted.');
    }

    public function subdealersTotalCredits(Request $request){
        $subdlear = get_subdealer_user($request->subdealer_group_id);
        $count = Credit::where('user_id', $subdlear->id)->sum('credits');
        return response()->json($count);
    }
    
    

    public function files($frontendID){

        $files = File::where('checking_status', 'unchecked')
        // ->where('type', 'master') // because slave files are supposed to decoded
        ->whereNull('subdealer_group_id')
        // ->where('front_end_id', $frontendID)
        ->get();

        $arrFiles = [];
        $temp = [];

        foreach($files as $file){

            $stage = NULL;

            if($file->custom_stage != NULL){
                $stage = \App\Models\Service::FindOrFail( $file->custom_stage )->label;
            }
            else{
                if($file->stage_services){
                    $stage = \App\Models\Service::FindOrFail( $file->stage_services->service_id )->label;
                }
            }

            $options = NULL;

            if($file->custom_options === NULL){

                if($file->options_services){
                    foreach($file->options_services as $o){
                        $options .= \App\Models\Service::FindOrFail( $o->service_id )->label.',';
                    }
                    $options = rtrim($options, ",");
                }
                else{
                    $options = null;
                }
            }
            else{

                if(!empty($file->custom_options)){
                    $customOptions = explode(',', $file->custom_options);
                    foreach($customOptions as $op){
                        if($op != 0){
                            $options .= \App\Models\Service::FindOrFail( $op )->label.',';
                        }
                    }
                    $options = rtrim($options, ",");
                }
                else{
                    
                    $options = "";
                }
            }
            
            if($stage != NULL){
                
                $temp['file_id'] = $file->id;
                $temp['inner_search'] = $file->inner_search;
                $temp['frontend'] = $file->front_end_id;

                $temp['stage'] = $stage;
                $temp['temporary_file_id'] = 0;
                $temp['options'] = $options;

                if($file->decoded_files->count() > 0){
                    if($file->front_end_id == 1){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->final_decoded_file();
                    }
                    else if($file->front_end_id == 3){
                    
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->final_decoded_file();
                    }
                    else if($file->front_end_id == 2){
                        // $temp['location'] = 'https://tuningx.test'.$file->file_path.$this->getFileToShowToLUA($file);
                        $temp['location'] = 'https://portal.tuning-x.com'.$file->file_path.$file->final_decoded_file();
                    }
                }
                else if ($file->magic_decrypted_files->count() > 0){

                    if($file->front_end_id == 1){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->final_magic_decoded_file();
                    }
                    else if($file->front_end_id == 3){
                    
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->final_magic_decoded_file();
                    }
                    else if($file->front_end_id == 2){
                        // $temp['location'] = 'https://tuningx.test'.$file->file_path.$this->getFileToShowToLUA($file);
                        $temp['location'] = 'https://portal.tuning-x.com'.$file->file_path.$file->final_magic_decoded_file();
                    }

                }
                else{

                    if($file->front_end_id == 1){
                    
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->file_attached;
                    }
                    else if($file->front_end_id == 3){
                    
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->file_attached;
                    }
                    else if($file->front_end_id == 2){
                        $temp['location'] = 'https://portal.tuning-x.com'.$file->file_path.$file->file_attached;
                    }

                }

                $temp['checked'] = $file->checking_status;

            }
            
            $arrFiles []= $temp;
        }

        return response()->json($arrFiles);
    }

    public function filesversions(){
    
        $files = File::where('checking_status_versions', '0')
        ->get();
    
        $arrFiles = [];
    
        foreach($files as $file){
    
            if($file->stage_services){
                $stage = \App\Models\Service::FindOrFail( $file->stage_services->service_id )->label;
            }
            else{
                $stage = $file->stages;
            }
    
            $options = NULL;
    
            if($file->custom_options == NULL){
    
                if($file->options_services){
                    foreach($file->options_services as $o){
                        $options .= \App\Models\Service::FindOrFail( $o->service_id )->label.',';
                    }
                    $options = rtrim($options, ",");
                }
                else{
                    $options = $file->options;
                }
            }
            else{
                if($file->custom_options !== ''){
                    $customOptions = explode(',', $file->custom_options);
                    foreach($customOptions as $op){
                        if($op != 0){
                            $options .= \App\Models\Service::FindOrFail( $op )->label.',';
                        }
                    }
                    $options = rtrim($options, ",");
                }
            }
                
                $temp = [];
                $temp['file_id'] = $file->id;
                $temp['temporary_file_id'] = 0;
                $temp['stage'] = $stage;
                $temp['options'] = $options;
    
                if($file->decoded_files->count() > 0){
                    if($file->front_end_id == 1){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->final_decoded_file();
                    }

                    else if($file->front_end_id == 2){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->final_decoded_file();
                    }
                    
                    else if($file->front_end_id == 3){
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->final_decoded_file();
                    }                    
                    
                }
                else if ($file->magic_decrypted_files->count() > 0){

                    if($file->front_end_id == 1){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->final_magic_decoded_file();
                    }
                    else if($file->front_end_id == 3){
                    
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->final_magic_decoded_file();
                    }
                    else if($file->front_end_id == 2){
                        // $temp['location'] = 'https://tuningx.test'.$file->file_path.$this->getFileToShowToLUA($file);
                        $temp['location'] = 'https://portal.tuning-x.com'.$file->file_path.$file->final_magic_decoded_file();
                    }

                }
                else{
                    if($file->front_end_id == 1 ){
                        $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->file_attached;
                    }
                    else if($file->front_end_id == 2){
                        $temp['location'] = 'https://portal.tuning-x.com'.$file->file_path.$file->file_attached;
                    }
                    else if($file->front_end_id == 3){
                        $temp['location'] = 'https://portal.e-tuningfiles.com'.$file->file_path.$file->file_attached;
                    }
                }
    
                $temp['checked'] = $file->checking_status;
                $temp['checked-versions'] = $file->checking_status_versions;
            
            $arrFiles []= $temp;
        }

        $temporaryFiles = TemporaryFile::join('users', 'users.id', '=', 'temporary_files.user_id')
        ->where('users.test_features','=', 1)
        ->where('temporary_files.checking_status_versions','=', 0)
        ->select('*', 'temporary_files.id as id')->get();

        foreach($temporaryFiles as $file){
            $temp = [];
            $temp['file_id'] = 0;
            $temp['temporary_file_id'] = $file->id;
            $temp['location'] = 'https://portal.ecutech.gr/uploads/'.$file->file_attached;
            $temp['checked'] = 'unchecked';
            $temp['stage'] = null;
            $temp['options'] = null;
            $temp['checked-versions'] = 0;

            $arrFiles []= $temp;
        }
    
        return response()->json($arrFiles);
    }

    public function getFileToShowToLUA($file){

        $name = "";

        foreach($file->decoded_files as $d){
            if($d->extension != '')
                $name = $d->name.'.'.$d->extension;
            else
                $name = $d->name;
        }

        return $name;
    }

    public function setCheckingStatus(Request $request){

        $file = File::findOrFail($request->file_id);

        $chatID = env('CHAT_USER_ID');

        $flexLabel = Tool::where('label', 'Flex')->where('type', 'slave')->first();
        $autotunerLabel = Tool::where('label', 'Autotuner')->where('type', 'slave')->first();

        if($file->checking_status == 'unchecked'){

            $file->checking_status = $request->checking_status;
            $flag = $file->save();

            if( $request->tuned_file && $request->tuned_file != '' && isset($request->tuned_file) ){

                $optionsMessage = '';

                if($file->options){
                    foreach($file->options()->get() as $option) {
                        $optionName = Service::findOrFail($option->service_id)->name;
                        $optionsMessage .= "".$optionName."_";
                    }
                }

                // $fileToSave = $request->tuned_file;

                $fileToSave = $file->brand.'_'.$file->model.'_'.$file->ecu.'_'.$file->stage.'_'.$optionsMessage.'_v'.$file->files->count()+1;

                $fileToSave = str_replace('/', '', $fileToSave);
                $fileToSave = str_replace('\\', '', $fileToSave);
                $fileToSave = str_replace('#', '', $fileToSave);
                $fileToSave = str_replace(' ', '_', $fileToSave);

                if($file->inner_search == 1){
                    $engineerFile = new DownloadLuaFile();
                    $engineerFile->request_file = $fileToSave;
                    $engineerFile->file_type = 'engineer_file';
                    $engineerFile->tool_type = 'not_relevant';
                    $engineerFile->master_tools = 'not_relevant';
                    $engineerFile->lua_command = $request->lua_command;
                    $engineerFile->file_id = $file->id;
                    $engineerFile->engineer = true;
                    $engineerFile->save();
                }
                else{
                    
                    $engineerFile = new RequestFile();
                    $engineerFile->request_file = $fileToSave;
                    $engineerFile->file_type = 'engineer_file';
                    $engineerFile->tool_type = 'not_relevant';
                    $engineerFile->master_tools = 'not_relevant';
                    $engineerFile->lua_command = $request->lua_command;
                    $engineerFile->file_id = $file->id;
                    $engineerFile->olsname = $request->olsname;
                    $engineerFile->engineer = true;
                    $engineerFile->save();
                

                    if($file->stage_services->service_id != 1){
                        $newRecord = new FileReplySoftwareService();
                        $newRecord->file_id = $file->id;
                        $newRecord->service_id = $file->stage_services->service_id;
                        $newRecord->software_id = 9;
                        $newRecord->reply_id = $engineerFile->id;
                        $newRecord->save();
                    }
                    
                    if(!$file->options_services()->get()->isEmpty()){

                        foreach($file->options_services()->get() as $option){

                            $newRecord = new FileReplySoftwareService();
                            $newRecord->file_id = $file->id;
                            $newRecord->service_id = $option->service_id;
                            $newRecord->software_id = 9;
                            $newRecord->reply_id = $engineerFile->id;
                            $newRecord->save();
                
                        }

                    }
                }

                    $middleName = $file->id;
                    $middleName .= date("dmy");
                    
                    foreach($file->softwares as $s){
                        if($s->service_id != 1){
                            if($s->reply_id == $engineerFile->id){
                                $middleName .= $s->service_id.$s->software_id;
                            }
                        }
                    }

                    $fileName = $file->brand.'_'.$file->model.'_'.$middleName.'_v'.$file->files->count()+1;

                    $fileToSave = str_replace('/', '', $fileName);
                    $fileToSave = str_replace('\\', '', $fileToSave);
                    $fileToSave = str_replace('#', '', $fileToSave);
                    $fileToSave = str_replace(' ', '_', $fileToSave);

                    $engineerFile->request_file = $fileToSave;
                    $engineerFile->save();

                    $tunnedFile = new TunnedFile();
                    $tunnedFile->file = $request->tuned_file;
                    $tunnedFile->file_id = $file->id;
                    $tunnedFile->save();


                    if($file->front_end_id == 1){

                        copy( public_path('/../../portal/public/uploads/filesready'.'/'.$request->tuned_file), 
                        public_path('/../../portal/public'.$file->file_path.$fileToSave) );

                        unlink( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file );

                        $path = public_path('/../../portal/public'.$file->file_path.$fileToSave);
                
                    }

                    else if($file->front_end_id == 3){

                        // copy( public_path('/../../e-tuningfiles/public/uploads/filesready'.'/'.$request->tuned_file), 
                        // public_path('/../../e-tuningfiles/public'.$file->file_path.$fileName) );

                        // unlink( public_path('/../../e-tuningfiles/public/uploads/filesready').'/'.$file->tunned_files->file );

                        copy( public_path('/../../portal/public/uploads/filesready'.'/'.$request->tuned_file), 
                        public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path.$fileToSave) );

                        unlink( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file );

                        $path = public_path('/../../portal.e-tuningfiles.com/public'.$file->file_path.$fileToSave);
                
                    }

                    else if($file->front_end_id == 2){

                        // copy( public_path('/../../tuningX/public/uploads/filesready'.'/'.$request->tuned_file), 
                        // public_path('/../../tuningX/public'.$file->file_path.$fileName) );

                        // unlink( public_path('/../../tuningX/public/uploads/filesready').'/'.$file->tunned_files->file );

                        copy( public_path('/../../portal/public/uploads/filesready'.'/'.$request->tuned_file), 
                        public_path('/../../tuningX/public'.$file->file_path.$fileToSave) );

                        unlink( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file );

                        $path = public_path('/../../tuningX/public'.$file->file_path.$fileToSave);

                    }

                    if($file->tool_type == 'slave' && $file->tool_id == $flexLabel->id){

                        // dd($file->final_magic_decoded_file());
                        $magicEncryptionType = 'int_flash';
                        (new MagicController)->magicEncrypt( $path, $file, $fileToSave, $engineerFile, $magicEncryptionType );
                    }

                    if($file->tool_type == 'slave' && $file->tool_id == $autotunerLabel->id){
                        
                        (new AutotunerController)->encrypt( $path, $file, $fileToSave, $engineerFile );
                    }

                    if($file->alientech_file){ // if slot id is assigned
                        $slotID = $file->alientech_file->slot_id;
                        $encodingType = $this->getEncodingType($file);
                        (new AlientechController)->saveGUIDandSlotIDToDownloadLaterForEncoding( $file, $path, $slotID, $encodingType, $engineerFile );


                        if($file->status == 'submitted'){
                            if($file->no_longer_auto == 0){
                                if($file->inner_search == 0){
                                    $file->status = 'completed';
                                    $file->support_status = "closed";
                                    $file->checked_by = 'engineer';
                                    $file->save();
                                }
                            }
                        }
                    }
                    else{
                        if($file->status == 'submitted'){

                            $file->status = 'completed';
                            $file->support_status = "closed";
                            $file->checked_by = 'engineer';
                            $file->save();
                            
                        }
                    }

                    if(!$file->response_time){
                        if($file->no_longer_auto == 0){
                            $file->reupload_time = Carbon::now();
                            $file->response_time = (new FilesController)->getResponseTimeAuto($file);
                            $file->save();
                        }
            
                    }

                    $file->automatic = 1;
                    $file->save();
                    
                    if($flag){

                        if($file->front_end_id == 1){

                            Chatify::push("private-chatify-download-portal-".$chatID, 'download-button', [
                                'status' => 'download',
                                'file_id' => $file->id
                            ]);
                
                        }

                        else if($file->front_end_id == 3){

                            Chatify::push("private-chatify-download-efiles-".$chatID, 'download-button', [
                                'status' => 'download',
                                'file_id' => $file->id
                            ]);
                
                        }

                        else if($file->front_end_id == 2){
                            
                            Chatify::push("private-chatify-download-tuningx-".$chatID, 'download-button', [
                                'status' => 'download',
                                'file_id' => $file->id
                            ]);
        
                        }

                        $this->sendMail($file);

                        return response()->json('file found.');
                    }
                }

                else{
            
                    if($file->front_end_id == 1){

                        Chatify::push("private-chatify-download-portal-".$chatID, 'download-button', [
                            'status' => 'fail',
                            'file_id' => $file->id
                        ]);
        
                    }

                    else if($file->front_end_id == 3){

                        Chatify::push("private-chatify-download-efiles-".$chatID, 'download-button', [
                            'status' => 'fail',
                            'file_id' => $file->id
                        ]);
            
                    }

                    else{
                        
                        Chatify::push("private-chatify-download-tuningx-".$chatID, 'download-button', [
                            'status' => 'fail',
                            'file_id' => $file->id
                        ]);
                    }
                    
                    return response()->json('search failed.');
                }

            }

            else{
        
            if($file->front_end_id == 1){

                Chatify::push("private-chatify-download-portal-".$chatID, 'download-button', [
                    'status' => 'fail',
                    'file_id' => $file->id
                ]);

            }

            else if($file->front_end_id == 3){

                Chatify::push("private-chatify-download-efiles-".$chatID, 'download-button', [
                    'status' => 'fail',
                    'file_id' => $file->id
                ]);
    
            }

            else{
                
                Chatify::push("private-chatify-download-tuningx-".$chatID, 'download-button', [
                    'status' => 'fail',
                    'file_id' => $file->id
                ]);
            }
            
            return response()->json('search failed.');
        }
        
    }

    public function sendMail($file){

        $customer = User::findOrFail($file->user_id);
        $admin = get_admin();
    
        // $template = EmailTemplate::where('name', 'File Uploaded from Engineer')->first();
        $template = EmailTemplate::findOrFail(6);

        $html1 = $template->html;

        $html1 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html1);
        $html1 = str_replace("#customer_name", $customer->name ,$html1);
        $html1 = str_replace("#vehicle_name", $file->brand." ".$file->engine." " ,$html1);
        
        $tunningType = $this->emailStagesAndOption($file);
        
        $html1 = str_replace("#tuning_type", $tunningType,$html1);
        $html1 = str_replace("#status", $file->status,$html1);
        $html1 = str_replace("#file_url", route('file', $file->id),$html1);

        $html2 = $template->html;

        $html2 = str_replace("#brand_logo", get_image_from_brand($file->brand) ,$html2);
        $html2 = str_replace("#customer_name", $file->name ,$html2);
        $html2 = str_replace("#vehicle_name", $file->brand." ".$file->engine." " ,$html2);
        
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
        $messageTemplate = MessageTemplate::findOrFail(7);

        $message = $messageTemplate->text;

        $message1 = str_replace("#customer", $customer->name ,$message);
        $message2 = str_replace("#customer", $file->name ,$message);

        if($file->front_end_id == 1){
            $subject = "ECU Tech: Engineer uploaded a file in reply.";
        }

        else if($file->front_end_id == 2){
            $subject = "TuningX: Engineer uploaded a file in reply.";
        }

        else if($file->front_end_id == 3){
            $subject = "E-TuningFiles: Engineer uploaded a file in reply.";
        }

        $manager = (new ReminderManagerController())->getAllManager();

        if($manager['eng_file_upload_cus_email'.$file->front_end_id]){
            \Mail::to($customer->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
        }
        if($manager['eng_file_upload_admin_email'.$file->front_end_id]){
            \Mail::to($admin->email)->send(new \App\Mail\AllMails([ 'html' => $html2, 'subject' => $subject, 'front_end_id' => $file->front_end_id]));
        }
        
        if($manager['eng_file_upload_admin_sms'.$file->front_end_id]){
            $this->sendMessage($admin->phone, $message2, $file->front_end_id);
        }

        if($manager['eng_file_upload_admin_sms'.$file->front_end_id]){
        
            $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
        }

        // if($manager['eng_file_upload_cus_sms']){
        //     $this->sendMessage($customer->phone, $message2);
        // }

        if($manager['eng_file_upload_cus_sms'.$file->front_end_id]){
            $this->sendMessage($customer->phone, $message2, $file->front_end_id);
        }

        if($manager['eng_file_upload_cus_whatsapp'.$file->front_end_id]){
            $this->sendWhatsapp($customer->name,$customer->phone, 'eng_file_upload', $file);
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
                        array("type"=> "text","text"=> $file->brand." ".$file->engine." "),
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
                        array("type"=> "text","text"=> $file->brand." ".$file->engine." ".$file->vehicle()->TORQUE_standard),
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

    public function sendMessage($receiver, $message, $frontendID)
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

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }
    
    public function getEncodingType($file){

        $e = '';

        $extensionArr = [];
        foreach($file->decoded_files as $d){
            $extensionArr []= $d->extension; 
        }

        foreach($extensionArr as $ex){
            if($ex == 'dec'){
                $e = 'dec';
            }
            else if($ex == 'mpc'){
                $e = 'micro';
            }
            
            else if($ex == 'fls'){
                $e = 'fls';
            }

        }

        return $e;
    }

    public function setStatusAndEmail(Request $request){

        $file = File::findOrFail($request->file_id);

        $file->status = 'completed';
        $file->support_status = "closed";
        $file->checked_by = 'engineer';
        $file->save();

        if(!$file->response_time){

            $file->reupload_time = Carbon::now();
            $file->save();

            $file->response_time = (new FilesController)->getResponseTimeAuto($file);
            $file->save();

        }

        // $this->sendMail($file);

        return response()->json('status changed and email sent to the client.');

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
