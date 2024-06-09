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

    public function softwares(){
        return FileReplySoftwareService::where('service_id', $this->id)->distinct()->get('software_id');
    }

    public function revisions($softwareID, $ecu, $brand){

        $files = File::where('files.ecu', $ecu)->where('files.brand', $brand)
        ->join('file_services', 'file_services.file_id', '=', 'files.id')
        ->where('file_services.service_id', $this->id)
        ->select('*', 'files.id AS file_id')
        ->get();

        // dd($files);

        $totalRevisions = 0;

        foreach($files as $file){
            $totalRevisions += $file->files->count();
        }

        $fileProcessedWithSoftware = File::where('files.ecu', $ecu)->where('files.brand', $brand)
        ->join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        // ->where('file_reply_software_service.service_id', $this->id)
        ->where('file_reply_software_service.service_id', $this->id)
        ->where('file_reply_software_service.software_id', $softwareID)
        ->select('*', 'files.id AS file_id')
        ->distinct()->count('file_reply_software_service.reply_id');

        // $fileProcessedWithSoftware = File::join('file_reply_software_service', 'files.id', '=', 'file_reply_software_service.file_id')
        // ->where('file_reply_software_service.service_id', $this->id)
        // // ->where('files.ecu', $ecu)->where('files.brand', $brand)
        // ->where('file_reply_software_service.software_id', $softwareID)
        // ->get();
        // ->where('files.ecu', $ecu)->where('files.brand', $brand)
        // ->distinct()->count('file_reply_software_service.reply_id');

        // dd($fileProcessedWithSoftware);

        if($fileProcessedWithSoftware == 0){
            return 0;
        }
        else{

            return round(($fileProcessedWithSoftware / $totalRevisions) * 100);

        }

    }

    public function stages_option($optionID){
        
        return $this->hasOne( StagesOptionsCredit::class, 'stage_id', 'id' )->where('option_id', $optionID);
    }

    public function optios_stage($stageID){
        
        return $this->hasOne( StagesOptionsCredit::class, 'option_id', 'id' )->where('stage_id', $stageID);
    }
    
}
