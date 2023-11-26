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

        $producerObjects = OriginalFile::OrderBy('Producer', 'desc')->select('Producer')->whereNotNull('Producer')->distinct('Producer')->get();
        $modelsObjects = null;
        $seriesObjects = null;
        
        $originalFiles = OriginalFile::limit(0)->paginate(10);

        return view('original_files.index', [

            'originalFiles' => $originalFiles, 
            'producerObjects' => $producerObjects,
            'modelsObjects' => $modelsObjects,
            'seriesObjects' => $seriesObjects

        ]);
    }

    public function filterOriginalFiles(Request $request){
    
        $producer = $request->Producer;
        $series = $request->Series;
        $model = $request->Model;
        
        $originalFilesObject = OriginalFile::orderBy('created_at', 'asc')->where('Producer', $producer)->whereNotNull('Producer');
            
        if($series){
            $originalFilesObject->where('Series', $series);
        }

        if($model){
            $originalFilesObject->where('Model', $model);
        }
        
        $originalFiles = $originalFilesObject->paginate(10);

        $producerObjects = OriginalFile::OrderBy('Producer', 'asc')->select('Producer')->whereNotNull('Producer')->distinct('Producer')->get();

        $seriesObjects = null;

        if($producer){
            $seriesObjects = OriginalFile::OrderBy('Series', 'asc')->select('Series')->whereNotNull('Series')->distinct('Series')->where('Producer', $producer)->get();
        }

        $modelsObjects = null;
        
        if($series && $producer){
        
            $modelsObjects = OriginalFile::OrderBy('Model', 'asc')->select('Model')->whereNotNull('Model')->distinct('Model')->where('Producer', $producer)->where('Series', $series)->get();

        }
        
        return view('original_files.index', [
            'originalFiles' => $originalFiles, 
            'producerObjects' => $producerObjects,
            'seriesObjects' => $seriesObjects,
            'modelsObjects' => $modelsObjects,
            'model' => $model,
            'series' => $series,
            'producer' => $producer
        ]);

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
