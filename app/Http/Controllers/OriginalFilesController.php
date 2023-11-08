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
        $originalFiles = OriginalFile::orderby('created_at', 'asc')->get();
        
        return view('original_files.index', ['originalFiles' => $originalFiles]);
    }

    public function download($id){

        $originalFile = OriginalFile::findOrFail($id);

        $fileName = $originalFile->Make;
        $fileName = $fileName.'-'.$originalFile->Generation;
        $fileName = $fileName.'-'.str_replace(' ', '_', $originalFile->Model);

        
        $fileName = $fileName.'-'.str_replace('/', '-', str_replace(' ', '_', $originalFile->ProducerECU));

        if($originalFile->BuildECU)
            $fileName = $fileName.'-'.$originalFile->BuildECU;

        if($originalFile->ECUNrProd)
            $fileName = $fileName.'-'.str_replace(' ', '_', $originalFile->ECUNrProd );

        if($originalFile->ECUNrECU)
            $fileName = $fileName.'-'.str_replace(' ', '_', $originalFile->ECUNrECU );

        if($originalFile->SWVersion)
            $fileName = $fileName.'-'.$originalFile->SWVersion;

        if($originalFile->Software)
            $fileName = $fileName.'-'.$originalFile->Software;

        $fileName = $fileName.'_('.$originalFile->File.')';
        
        $filePath = public_path('/../../original_files/').$fileName;

        return response()->download($filePath);

    }

    public function renaming(){

        $folder = public_path('/../../original_files_renaming_folder');

        foreach(glob($folder.'/*.*') as $file) {
            rename($file, str_replace('--', '-', $file));
        }

        foreach(glob($folder.'/*.*') as $file) {
            rename($file, str_replace('--', '-', $file));

        }

        foreach(glob($folder.'/*.*') as $file) {
            rename($file, str_replace('-_', '_', $file));

        }

    }

}
