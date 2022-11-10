<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerFileNote extends Model
{
    use HasFactory;
    protected $fillables = ['egnineers_internal_notes', 'engineers_attachement', 'file_id'];
}
