<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileInternalEvent extends Model
{
    use HasFactory;
    protected $fillables = ['events_internal_notes', 'events_attachement', 'file_id'];
}
