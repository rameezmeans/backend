<?php

namespace App\Http\Controllers;

use App\Models\AlientechFile;
use App\Models\File;
use App\Models\Key;
use App\Models\RequestFile;
use App\Models\TunnedFile;
use Illuminate\Http\Request;

class FilesAPIController extends Controller
{
    public function files(){

        $undecidedFiles = File::where('checking_status', 'undecided')
        ->get();

        foreach($undecidedFiles as $file){

                if(AlientechFile::where('file_id', $file->id)->first()){
                    
                    if($file->alientech_files->isEmpty()){
                       
                        (new FilesController)->saveFiles( $file->id );
                        
                        $file->checking_status = 'unchecked';
                        $file->save();
                    }
                

            }
        }

        $files = File::where('checking_status', 'unchecked')->get();

        $arrFiles = [];

        foreach($files as $file){
            $temp = [];
            $temp['file_id'] = $file->id;
            $temp['stage'] = $file->stages;
            $temp['options'] = $file->options;
            $temp['location'] = 'https://portal.ecutech.gr'.$file->file_path.$file->file_attached;
            $temp['checked'] = $file->checking_status;

            $arrFiles []= $temp;
        }

        return response()->json($arrFiles);
    }

    public function setCheckingStatus(Request $request){

        $file = File::findOrFail($request->file_id);
        $file->checking_status = $request->checking_status;
        
        $flag = $file->save();

        if(isset($request->tuned_file) && $request->tuned_file){

            $tunnedFile = new TunnedFile();
            $tunnedFile->file = $request->tuned_file;
            $tunnedFile->file_id = $file->id;
            $tunnedFile->save();

            copy( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file, 
            public_path('/../../portal/public'.$file->file_path.$file->tunned_files->file) );

            unlink( public_path('/../../portal/public/uploads/filesready').'/'.$file->tunned_files->file );

            $path = public_path('/../../portal/public'.$file->file_path.$file->tunned_files->file);
            $slotID = AlientechFile::where('key', 'slotGUID')->where('file_id', $file->id)->first()->value;
            $token = Key::where('key', 'alientech_access_token')->first()->value;


            // $encodingType = '';

            // $response = (new FilesController())->uploadFileToEncode($token, $path, $slotID, $encodingType);

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
                $file->save();
            }

        }

        if($flag){
            return response()->json('status changed.');
        }
        
        return response()->json('status not changed.');

    }
}
