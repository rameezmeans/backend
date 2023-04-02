<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\RequestFile;
use App\Models\TunnedFile;
use Illuminate\Http\Request;

class FilesAPIController extends Controller
{
    public function files(){

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

            unlink( public_path('/../../portal/uploads/filesready').'/'.$request->tuned_file );

            $engineerFile = new RequestFile();
            $engineerFile->request_file = $request->tuned_file;
            $engineerFile->file_type = 'engineer_file';
            $engineerFile->tool_type = 'not_relevant';
            $engineerFile->master_tools = 'not_relevant';
            $engineerFile->file_id = $file->id;
            $engineerFile->engineer = true;
            $engineerFile->save();
        }

        if($flag){
            return response()->json('status changed.');
        }
        
        return response()->json('status not changed.');

    }
}
