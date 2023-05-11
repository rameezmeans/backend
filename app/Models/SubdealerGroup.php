<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubdealerGroup extends Model
{
    use HasFactory;

    public function subdealers(){
        return $this->hasMany(Subdealer::class, 'subdealer_own_group_id', 'id');
    }
}
