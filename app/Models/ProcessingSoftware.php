<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessingSoftware extends Model
{
    use HasFactory;

    protected $table = "processing_softwares";

    public function files(){
        return $this->hasMany(FileReplySoftwareService::class, 'software_id', 'id');
    }
}
