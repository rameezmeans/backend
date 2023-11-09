<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    public function files(){
        return $this->hasMany(RequestFile::class); 
    }

    public function files_and_messages_sorted(){
        $withoutTypeArray = $this->files->toArray();
        $unsortedTimelineObjects = [];

        foreach($withoutTypeArray as $r) {
            $fileReq = RequestFile::findOrFail($r['id']);
            if($fileReq->file_feedback){
                $r['type'] = $fileReq->file_feedback->type;
            }
            $unsortedTimelineObjects []= $r;
        } 
        
        $createdTimes = [];

        foreach($this->files->toArray() as $t) {
            $createdTimes []= $t['created_at'];
        } 
    
        foreach($this->engineer_file_notes->toArray() as $a) {
            $unsortedTimelineObjects []= $a;
            $createdTimes []= $a['created_at'];
        }   

        foreach($this->file_internel_events->toArray() as $b) {
            $unsortedTimelineObjects []= $b;
            $createdTimes []= $b['created_at'];
        } 

        foreach($this->file_urls->toArray() as $b) {
            $unsortedTimelineObjects []= $b;
            $createdTimes []= $b['created_at'];
        } 

        array_multisort($createdTimes, SORT_ASC, $unsortedTimelineObjects);

        return $unsortedTimelineObjects;
    }

    public function logs(){
        return $this->hasMany(Log::class); 
    }

    public function new_requests(){
        return $this->hasMany(File::class, 'original_file_id', 'id'); 
    }

    public function tunned_files(){
        return $this->hasOne(TunnedFile::class); 
    }

    public function alientech_files(){
        return $this->hasMany(AlientechFile::class)->where('purpose', 'download'); 
    }

    public function frontend(){
        return $this->belongsTo(FrontEnd::class,'front_end_id', 'id'); 
    }
    
    public function first_engineer_file(){
        return RequestFile::orderBy('created_at', 'desc')
        ->where('file_id', $this->id)
        ->where('engineer', 1)
        ->first(); 
    }

    public function assigned(){
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function engineer_file_notes(){
        return $this->hasMany(EngineerFileNote::class); 
    }
    
    public function file_internel_events(){
        return $this->hasMany(FileInternalEvent::class);
    }

    public function file_urls(){
        return $this->hasMany(FileUrl::class);
    }

    public function stage_offer(){
        return $this->hasOne(EngineerOptionsOffer::class, 'file_id', 'id')->where('type', 'stage');
    }

    public function options_offer(){
        return $this->hasMany(EngineerOptionsOffer::class, 'file_id', 'id')->where('type', 'option');
    }

    public function vehicle(){
        return Vehicle::where('Make', '=', $this->brand)
        ->where('Model', '=', $this->model)
        ->where('Generation', '=', $this->version)
        ->where('Engine', '=', $this->engine)
        ->whereNotNull('Brand_image_url')
        ->first();
    }

    // public function vehicle(){
    //     return Vehicle::where('Make', '=', $this->brand)
    //     ->first(); // removing image for time being
    // }

    public function getECUComment(){
        
        $note = null;
        
        if($this->ecu){
            $note = VehiclesNote::where('make', $this->brand)->where('ecu', $this->ecu)->first();
        }

        return $note;
    }

    public function stage_services(){
        return $this->hasOne(FileService::class, 'file_id', 'id')->where('type', 'stage');
    }

    public function alientech_file(){
        return $this->hasOne(AlientechFile::class, 'file_id', 'id');
    }

    public function decoded_files(){
        return $this->hasMany(ProcessedFile::class, 'file_id', 'id')->where('type', 'decoded');
    }

    public function final_decoded_file(){

        if($this->decoded_files->count() > 0){

            $sizeArray = [];

            foreach($this->decoded_files as $d){

                if($d->extension != ''){
                    $name = $d->name.'.'.$d->extension;
                }
                else{
                    $name = $d->name;
                }

                if($this->front_end_id == 1){
                    $path = public_path('/../../portal/public'.$this->file_path.$name);
                }
                else{
                    $path = public_path('/../../tuningX/public'.$this->file_path.$name);
                }
                
                $temp ['size']= filesize($path);
                $temp ['file_name']= $name;
                $sizeArray []= $temp;

            }

            if(sizeOf($sizeArray) == 1){

                return $sizeArray[0]['file_name'];
            }
            else{
    
                usort($sizeArray, array($this,'sortById'));
                return $sizeArray[0]['file_name'];
    
            }

        }

        return null;
        
    }

    public function sortById($x, $y) {

        return $y['size'] - $x['size'];
    }

    public function decoded_file(){
        return $this->hasOne(ProcessedFile::class, 'file_id', 'id')->where('type', 'decoded');
    }

    public function options(){

        return $this->hasMany(FileService::class, 'file_id', 'id')->where('type', 'option');

    }

    public function reading_tool($type){
        return $this->hasOne(Tool::class, 'file_id', 'id')->where('type', $type);
    }

    public function options_services(){
        return $this->hasMany(FileService::class, 'file_id', 'id')->where('type', 'option');
    }
    
}
