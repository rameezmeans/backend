<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FilesAPIController extends Controller
{
    public function getFiles(){
        
        $files = File::all();

        $arrFiles = [];

        foreach($files as $file){
            $arrFiles['file_id'] = $file->id;
            $arrFiles['stage'] = $file->stages;
            $arrFiles['options'] = $file->options;
            $arrFiles['location'] = $file->file_path.'/'.$file->file_attached;
        }

        return response()->json($arrFiles);
    }
}
