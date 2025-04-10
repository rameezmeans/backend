<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function translation(){
        return $this->hasOne( Translation::class, 'model_id', 'id')->where('model', 'Comment');
    }
}
