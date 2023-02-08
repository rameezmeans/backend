<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, $model, $texts)
    {
        $modelInstance = Translation::where('model_id', $id)->where('model', $model)->first();
        
        if($modelInstance){
            $modelInstance->english = $texts['english'];    
            $modelInstance->greek = $texts['greek'];    
            $modelInstance->model = $model;    
            $modelInstance->model_id = $id;
            $modelInstance->save();    
        }
        else{
            $newIns = new Translation();
            $newIns->english = $texts['english'];    
            $newIns->greek = $texts['greek'];    
            $newIns->model = $model;    
            $newIns->model_id = $id;
            $newIns->save();  
        }

        $record[ $texts['english'] ] = $texts['greek'];

        // dd($texts);

        $this->removeKey($texts['english']);
        $this->appendRecord($record);


    }

    public function appendRecord($record) {
        $json = file_get_contents(public_path("/../../portal/resources/lang/gr.json"));
        $data = json_decode($json, true);
        $data = array_merge($data,$record);
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents(public_path("/../../portal/resources/lang/gr.json"), $json);
    }

    public function removeKey($key) {

        $json = file_get_contents(public_path("/../../portal/resources/lang/gr.json"));
        $data = json_decode($json, true);

        // unset($data[$key]);

        // dd($data);
        
        // $data = array_filter($data, function ($item) use ($key) {
        //     dd($item);
        //     dd($key);
        //     return !array_key_exists($key, $item);
        // });
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents(public_path("/../../portal/resources/lang/gr.json"), $json);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function show(Translation $translation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function edit(Translation $translation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Translation $translation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Translation $translation)
    {
        //
    }
}
