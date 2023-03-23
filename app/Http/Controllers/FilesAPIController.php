<?php

namespace App\Http\Controllers;

use App\Models\File;
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
            $temp['location'] = $file->file_path.'/'.$file->file_attached;
            $temp['checked'] = $file->checking_status;

            $arrFiles []= $temp;
        }

        return response()->json($arrFiles);
    }
}
