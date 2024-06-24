<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'vehicle_type',  'type', 'credits', 'icon_url', 'description'];

    public function hasSubdealer(){
        return $this->hasOne( ServiceSubdealerGroup::class ); 
    }

    public function stages_options_credits(){
        return $this->hasMany( StagesOptionsCredit::class, 'option_id', 'id' );
    }

    public function softwares($ecu, $brand){

        // return FileReplySoftwareService::where('service_id', $this->id)->distinct()->get('software_id');

        $fileProcessed = File::where('files.ecu', $ecu)->where('files.brand', $brand)
        ->join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        ->where('file_reply_software_service.service_id', $this->id)
        ->distinct()->get('file_reply_software_service.software_id');

        return $fileProcessed;
    }

    public function revisions($softwareID, $ecu, $brand){

        // $totalFiles = all_files_with_this_ecu_brand_and_service($ecu, $brand, $this->id, $softwareID);
        $fileProcessedWithSoftware = all_replies_with_this_ecu_brand_and_service_inner($ecu, $brand, $this->id, $softwareID);

        // if($totalFiles == 0){
        //     return 0;
        // }

        // if($fileProcessedWithSoftware == 0){
        //     return 0;
        // }

        $lastRecord = File::where('files.ecu', $ecu)
        ->where('files.brand', $brand)
        ->join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        ->where('file_reply_software_service.service_id', $this->id)
        ->where('file_reply_software_service.software_id', $softwareID)
        ->select('file_reply_software_service.reply_id', 'file_reply_software_service.id as reply_id')
        ->orderBy('file_reply_software_service.created_at', 'desc')
        ->limit(1)
        ->first();

        $thisRecord = File::where('files.ecu', $ecu)
        ->where('files.brand', $brand)
        ->join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        ->where('file_reply_software_service.service_id', $this->id)
        ->where('file_reply_software_service.software_id', $softwareID)
        ->select('file_reply_software_service.reply_id', 'file_reply_software_service.id as reply_id')
        ->first();

        if($lastRecord->reply_id != $thisRecord->reply_id){
            $fileProcessedWithSoftware++;
        }
        

        return (int) $fileProcessedWithSoftware;

    }

    public function stages_option($optionID){
        
        return $this->hasOne( StagesOptionsCredit::class, 'stage_id', 'id' )->where('option_id', $optionID);
    }

    public function optios_stage($stageID){
        
        return $this->hasOne( StagesOptionsCredit::class, 'option_id', 'id' )->where('stage_id', $stageID);
    }
    
}
