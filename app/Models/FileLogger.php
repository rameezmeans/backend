<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLogger extends Model
{
    use HasFactory;

    public $table = "file_logger";
}
