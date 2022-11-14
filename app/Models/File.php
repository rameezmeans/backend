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

    public function stages(){
        return $this->stages;
    }

    public function options(){
        return explode(',',$this->options);
    }
}
