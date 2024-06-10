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

        $totalRevisions = all_files_with_this_ecu_brand_and_service($ecu, $brand, $this->id);
        $fileProcessedWithSoftware = all_files_with_this_ecu_brand_and_service_and_software($ecu, $brand, $this->id, $softwareID);

        if($totalRevisions == 0){
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
