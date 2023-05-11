<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'vehicle_type',  'type', 'credits', 'icon_url', 'description'];
    
}
