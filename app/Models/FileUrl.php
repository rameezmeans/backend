<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUrl extends Model
{
    use HasFactory;
    protected $fillables = ['file_url', 'file_url_attachment', 'file_id'];
}
