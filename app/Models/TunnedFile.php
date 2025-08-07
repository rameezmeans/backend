<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TunnedFile extends Model
{
    use HasFactory;
    protected $fillable = ['file', 'file_id'];
}
