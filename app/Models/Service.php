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

    public function stages_option($optionID){
        
        return $this->hasOne( StagesOptionsCredit::class, 'stage_id', 'id' )->where('option_id', $optionID);
    }
    
}
