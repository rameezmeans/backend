<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTCLookup extends Model
{
    use HasFactory;

    public $table = 'dtc_lookup';

    protected $fillable = [
        'code', 'desc'
    ];
}
