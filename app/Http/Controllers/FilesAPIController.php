<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FilesAPIController extends Controller
{
    public function getFile($id){
        $file = File::findOrFail($id);
        return response()->json([
            
            'file_id' => $file->id,
            'stage' => $file->stages,
            'options' => $file->options,
            'location' => $file->file_path.'/'.$file->file_attached
        ]);
    }
}
