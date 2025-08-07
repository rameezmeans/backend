<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function frontend(){
        return $this->belongsTo(FrontEnd::class, 'front_end_id', 'id'); 
    }
}
