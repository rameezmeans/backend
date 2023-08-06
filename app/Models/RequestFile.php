<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestFile extends Model
{

    protected $fillable = [
        'request_file', 'file_type', 'ecu_file_select', 'gearbox_file_select', 'master_tools', 'file_id', 'tool_type'
    ];
    use HasFactory;

    function file_feedback(){
        return $this->hasOne(FileFeedback::class, 'request_file_id', 'id');
    }

    function engineer_file_notes(){
        return $this->hasMany(EngineerFileNote::class, 'request_file_id', 'id');
    }

    public function file_internel_events(){
        return $this->hasMany(FileInternalEvent::class, 'request_file_id', 'id');
    }
}
