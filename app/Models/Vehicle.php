<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'Make', 'Generation', 'Model', 'Engine', 'Engine_ECU', 'Engine_URL', 'Name',
         'type', 'Brand_image_URL', 'BHP_standard', 'BHP_tuned', 'BHP_difference',
         'TORQUE_standard', 'TORQUE_tuned', 'TORQUE_difference', 'Type_of_fuel'
    ];

    public function getComment($ecu){
        return VehiclesNote::where('make', $this->Make)->where('ecu', $ecu)->first();
    }
}
