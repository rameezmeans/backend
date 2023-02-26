<?php

namespace App\Http\Livewire;

use App\Models\File;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class FileEngineerTable extends LivewireDatatable
{
    public function builder()
    {
        return File::select('*');
    }

    public function columns()
    {
        return [
            Column::index($this),
            Column::callback(['id', 'name'], function ($id) {
                $file = File::findOrFail($id);
                return $file->brand.' '.$file->engine.' '.$file->vehicle()->TORQUE_standard;
            })->label('Vehicle'),
            Column::callback('id,stages,options', function($id,$stages, $op){
                $all = "";
                $file = File::findOrFail($id);

                if(\App\Models\Service::where('name', $stages)->first()){
                    $all .= '<img alt="{{$file->stages}}" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::where('name', $stages)->first()->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $stages)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $stages)->first()->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.$stages.'</span>';
                }
                
                foreach($file->options() as $option){
                    if(\App\Models\Service::where('name', $option)->first() != null){
                        $all .= '<img alt="'.$option.'" width="20" height="20" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon .'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option.'<br>';
                        }
                    }
                return $all;
            })
            ->label('Stage and Options'),
            Column::callback('credits', function($credits){
                return '<lable class="label bg-danger text-white">'.$credits.'</lable>';
            }) ->label('Credits'),
            Column::callback(['assigned_to'], function($id){
                return User::findOrFail($id)->name;
            })->label('Assigned to')->filterable(User::get('name')->pluck('name')->toArray())->searchable(),
            // Column::callback('assigned_to', function($id){
            //     return '<label class="label label-success">'.User::findOrFail($id)->name.'<label>';
            // })->label('Assigned to')->filterable(collect(User::findOrFail($id))->map( function($value, $key) {
            //     return ['id' => $key, 'name' => $value];
            // }))->searchable(),
            DateColumn::name('created_at')
                ->label('Upload Date')->sortable()->filterable(),
            DateColumn::callback('response_time', function($rt){
                if($rt == null ){
                    return '<label class="label label-success">Not Responsed<label>';
                }
                else{
                    
                    return '<label class="label label-success">'.\Carbon\CarbonInterval::seconds($rt)->cascade()->forHumans().'<label>';
                }
            })->label('Response Time'),
        ];
    }
}