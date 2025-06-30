<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoFileInfo extends Model
{
    use HasFactory;

    public $table = "auto_file_info";

     protected $fillable = [

        'temporary_file_id', 'auto_searched_file_id', 'brand','model', 
        'version', 'engine', 'is_modified', 'modification', 'gearbox'
     ];
}
