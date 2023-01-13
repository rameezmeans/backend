<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'Make', 'Generation', 'Model', 'Engine', 'Engine_ECU', 'Engine_URL', 'Name'
    ];
}
