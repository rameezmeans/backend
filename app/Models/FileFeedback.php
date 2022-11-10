<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileFeedback extends Model
{
    protected $fillables = ['type', 'request_file_id', 'file_id'];
    use HasFactory;
}
