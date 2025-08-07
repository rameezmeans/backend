<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoFileStageOptions extends Model
{
    use HasFactory;

    public $table = "auto_file_stage_options";

     protected $fillable = [
        'temporary_file_id', 'auto_searched_file_id', 'stage','options','credits'
     ];
}
