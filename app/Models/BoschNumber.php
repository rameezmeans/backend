<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoschNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_number', 'ecu'
    ];
}
