<?php

namespace App\Http\Controllers;

use App\Models\AlientechFile;
use App\Models\File;
use App\Models\Key;
use App\Models\RequestFile;
use App\Models\TunnedFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Chatify\Facades\ChatifyMessenger as Chatify;

class FilesAPIController extends Controller
{
    public function files(){

        $files = File::where('checking_status', 'unchecked')->get();

        $arrFiles = [];

        foreach($files as $file){

            if($file->stage_services){
                $stage = \App\Models\Service::FindOrFail( $file->stage_services->service_id )->name;
            }
            else{
                $stage = $file->stages;
            }

            $options = NULL;

            if($file->options_services){
                foreach($file->options_services as $o){
                    $options .= \App\Models\Service::FindOrFail( $o->service_id )->name.',';
                }
                $options = rtrim($options, ",");
            }
            else{
                $options = $file->options;
            }

            

                $temp = [];
                $temp['file_id'] = $file->id;
                $temp['stage'] = $stage;
                $temp['options'] = $options;

                if($file->decoded_files){
                    $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->decoded_file;
                }
                else{
                    $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->file_attached.$this->getFileToShowToLUA($file);

                }

                $temp['checked'] = $file->checking_status;
            
            $arrFiles []= $temp;
        }

        return response()->json($arrFiles);
    }

    public function getFileToShowToLUA($file){

        $name = "";

        foreach($file->decoded_files as $d){
            if($d->extension == 'dec'){
                $name = $d->name.'.'.$d->extension;
            }
            else if ($d->extension == 'mpc'){
                $name = $d->name.'.'.$d->extension;
            }
        }

        return $name;
    }

    public function setCheckingStatus(Request $request){
        
        $file = File::findOrFail($request->file_id);

        if($file->checking_status == 'unchecked'){
            $file->checking_status = $request->checking_status;
            $flag = $file->save();

            if( $request->tuned_file && $request->tuned_file != '' && isset($request->tuned_file) ){

                $tunnedFile = new TunnedFile();
                $tunnedFile->file = $request->tuned_file;
                $tunnedFile->file_id = $file->id;
                $tunnedFile->save();

                copy( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file, 
                public_path('/../../portal/public'.$file->file_path.$file->tunned_files->file) );

                unlink( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file );

                $path = public_path('/../../portal/public'.$file->file_path.$file->tunned_files->file);

                if($file->alientech_file){ // if slot id is assigned
                    $slotID = $file->alientech_file->slot_id;
                    $encodingType = $this->getEncodingType($file);
                    (new AlientechController)->saveGUIDandSlotIDToDownloadLaterForEncoding( $file, $path, $slotID, $encodingType );
                }
                
                $engineerFile = new RequestFile();
                $engineerFile->request_file = $request->tuned_file;
                $engineerFile->file_type = 'engineer_file';
                $engineerFile->tool_type = 'not_relevant';
                $engineerFile->master_tools = 'not_relevant';
                $engineerFile->file_id = $file->id;
                $engineerFile->engineer = true;
                $engineerFile->save();

                if($file->status == 'submitted'){
                    $file->status = 'completed';
                    $file->support_status = "closed";
                    $file->checked_by = 'engineer';
                    $file->save();
                }

                if(!$file->response_time){

                    $file->reupload_time = Carbon::now();
                    $file->save();
        
                    $file->response_time = (new FilesController)->getResponseTimeAuto($file);
                    $file->save();
        
                }

                if($flag){

                    Chatify::push("private-chatify-download", 'download-button', [
                        'status' => 'completed',
                        'file_id' => $file->id
                    ]);
    
                    return response()->json('status changed.');
                }

            }
        }

        Chatify::push("private-chatify-download", 'download-button', [
            'status' => 'fail',
            'file_id' => $file->id
        ]);
        
        return response()->json('status not changed.');

    }

    public function getEncodingType($file){

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

        }

        return $e;
    }
}
