<?php

namespace App\Http\Controllers;

use App\Models\OriginalFile;
use Illuminate\Http\Request;

class OriginalFilesController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function index(){
        $originalFiles = OriginalFile::orderby('created_at', 'asc')->paginate(10);
        
        return view('original_files.index', ['originalFiles' => $originalFiles]);
    }

    public function download($id){

        $originalFile = OriginalFile::findOrFail($id);
        
        $fileName = '';

        if($originalFile->Software){
            $fileName = $fileName.$originalFile->Software;
        }

        $fileName = $fileName.'--'.preg_replace('/\.\w+$/', '', $originalFile->File);
        
        $filePath = public_path('/../../original_files/').$fileName;

        return response()->download($filePath);

    }

    // public function renaming(){

    //     $folder = public_path('/../../original_files_renaming_folder');

    //     foreach(glob($folder.'/*.*') as $file) {
    //         rename($file, str_replace('--', '-', $file));
    //     }

    //     foreach(glob($folder.'/*.*') as $file) {
    //         rename($file, str_replace('--', '-', $file));

    //     }

    //     foreach(glob($folder.'/*.*') as $file) {
    //         rename($file, str_replace('-_', '_', $file));

    //     }

    // }

}
