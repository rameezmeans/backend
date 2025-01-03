<?php

namespace App\Http\Livewire;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class VehicleTable extends LivewireDatatable
{
    public function builder()
    {
        return Vehicle::select('*');
    }

    public function columns()
    {
        return [

            Column::name('Engine')
            ->label('Engine'),
            
            Column::callback(['id', 'name'], function ($id) {

                $vehicle = Vehicle::findOrFail($id);

                return $vehicle->Make.' '.$vehicle->Engine.' '.$vehicle->TORQUE_standard;
                
                
            })->label('Name')->sortable(),

            Column::name('Model')
            ->label('Model'),

            Column::name('Generation')
            ->label('Generation'),

            Column::name('Make')
            ->label('Make'),


        ];
    }

    public function rowClasses($row, $loop)
    {
        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-vehicles')){
            return 'redirect-click-vehicle '.$row->id;
        }
    }
}