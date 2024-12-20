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

    public function file_feedback(){
        return $this->hasOne(FileFeedback::class, 'request_file_id', 'id');
    }

    public function acm_files(){
        return $this->hasMany(ACMFile::class, 'request_file_id', 'id');
    }

    public function engineer_file_notes(){
        return $this->hasMany(EngineerFileNote::class, 'request_file_id', 'id');
    }

    public function engineer_file_notes_have_unseen_messages(){
        return $this->hasMany(EngineerFileNote::class, 'request_file_id', 'id')->where('sent_by', 'engineer');
    }

    public function file_internel_events(){
        return $this->hasMany(FileInternalEvent::class, 'request_file_id', 'id');
    }
}
