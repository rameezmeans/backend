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

    public function vehicle(){
        return Vehicle::where('Make', '=', $this->brand)->whereNotNull('Brand_image_url')
        ->first();
    }

    // public function vehicle(){
    //     return Vehicle::where('Make', '=', $this->brand)
    //     ->first(); // removing image for time being
    // }

    public function getECUComment(){
        
        $vehicle = Vehicle::where('Make', '=', $this->brand)
        ->where('Model', '=', $this->model)
        ->where('Generation', '=', $this->version)
        ->where('Engine', '=', $this->engine)
        ->where('Engine_ECU', '=', $this->ecu)
        ->first(); 

        return $vehicle->getComment($this->ecu);
    }

    public function stages(){
        return $this->stages;
    }

    public function options(){
        return explode(',',$this->options);
    }
}
