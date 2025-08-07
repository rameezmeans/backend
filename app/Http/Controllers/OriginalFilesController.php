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

    public function delete(Request $request){
        $originalFile = OriginalFile::findOrFail($request->id);
        $originalFile->delete();
    }

    public function update(Request $request){

        $originalFile = OriginalFile::findOrFail($request->id);

        $originalFile->Producer = $request->Producer;
        $originalFile->Series = $request->Series;
        $originalFile->Model = $request->Model;
        $originalFile->Displacement = $request->Displacement;
        $originalFile->Output = $request->Output;
        $originalFile->Gear = $request->Gear;
        $originalFile->ProducerECU = $request->ProducerECU;
        $originalFile->BuildECU = $request->BuildECU;
        $originalFile->ECUNrProd = $request->ECUNrProd;
        $originalFile->ECUNrECU = $request->ECUNrECU;
        $originalFile->Software = $request->Software;
        $originalFile->SWVersion = $request->SWVersion;
        $originalFile->ProjectSize = $request->ProjectSize;
        $originalFile->File = $request->File;
        $originalFile->save();

        return redirect()->route('original-files')->with(['success' => 'File edited, successfully.']);
    }

    public function edit($id){

        $originalFile = OriginalFile::findOrFail($id);
        return view('original_files.edit', ['originalFile' => $originalFile]);
    }

    public function live(){

        return view('original_files.live');

    }

    public function index(){

        $producerObjects = OriginalFile::OrderBy('Producer', 'desc')->select('Producer')->whereNotNull('Producer')->distinct('Producer')->get();
        $modelsObjects = null;
        $seriesObjects = null;
        
        $originalFiles = OriginalFile::OrderBy('Producer', 'desc')->paginate(10)->withQueryString();

        return view('original_files.index', [

            'originalFiles' => $originalFiles, 
            'producerObjects' => $producerObjects,
            'modelsObjects' => $modelsObjects,
            'seriesObjects' => $seriesObjects

        ]);
    }

    public function deleteOriginalFiles(Request $request){

        $ids = $request->ids;
        $files = OriginalFile::whereIn('id', $ids)->get();

        foreach($files as $file){
            $file->delete();
        }

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
        
        $originalFiles = $originalFilesObject->paginate(10)->withQueryString();;

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

    public function getModels(Request $request){

        $producer = $request->producer;
        $series = $request->series;
        
        $models = OriginalFile::OrderBy('Model', 'asc')
        ->select('Model')->
        whereNotNull('Model')
        ->distinct()
        ->where('Producer', '=', $producer)
        ->where('Series', '=', $series)
        ->get();
        
        return response()->json( [ 'models' => $models ] );

    }

    public function getSeries(Request $request)
    {
        $producer = $request->producer;
        
        $series = OriginalFile::OrderBy('Series', 'asc')->select('Series')->whereNotNull('Series')->distinct()->where('Producer', '=', $producer)->get();
        
        return response()->json( [ 'series' => $series ] );
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
