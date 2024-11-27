<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeRecord extends Model
{
    use HasFactory;

    private $table = 'stripe_records';
}
