<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdealer extends Model
{
    use HasFactory;

    public function subdealers_data(){
        return $this->hasOne(SubdealersData::class, 'subdealer_id', 'id');
    }
}
