<?php

namespace App\Http\Livewire;

use App\Models\File;
use App\Models\FrontEnd;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Action;
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
            Column::callback(['front_end_id'], function($frontEndID){
                if($frontEndID == 1){
                    return '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else{
                    return '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
            })
            ->exportCallback(function($frontEndID){
                return FrontEnd::findOrFail($frontEndID)->name;
            })
            ->label('Front End')
            ->filterable(FrontEnd::get(['id', 'name']))
            ->searchable(),

            Column::callback(['id', 'name'], function ($id) {
                $file = File::findOrFail($id);
                return $file->brand.' '.$file->engine.' '.$file->vehicle()->TORQUE_standard;
            })
            ->label('Vehicle'),

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
            ->exportCallback(function($id,$stages, $op){
                $file = File::findOrFail($id);

                $all = "";

                if(\App\Models\Service::where('name', $stages)->first()){
                    $all .= $stages.', ';
                }

                foreach($file->options() as $option){
                    if(\App\Models\Service::where('name', $option)->first() != null){
                        $all .= $option;    
                    }
                }

                return $all;
            })
            ->label('Stage and Options'),

            Column::callback('credits', function($credits){
                return '<lable class="label bg-danger text-white">'.$credits.'</lable>';
            })
            ->exportCallback(function($credits){
                return $credits;
            })
            ->label('Credits'),

            Column::callback(['assigned_to'], function($id){
                return User::findOrFail($id)->name;
            })->label('Assigned to'),
            // ->filterable(User::where('is_engineer', 1)->get(['id', 'name']))->searchable(),

            DateColumn::name('created_at')
                ->label('Upload Date')->sortable()->filterable(),
            DateColumn::callback('response_time', function($rt){
                if($rt == null ){
                    return '<label class="label label-success">Not Responsed<label>';
                }
                else{
                    
                    return '<label class="label label-success">'.\Carbon\CarbonInterval::seconds($rt)->cascade()->forHumans().'<label>';
                }
            })
            ->exportCallback(function($rt){
                if($rt == null ){
                    return 'Not Responded';
                }
                else{
                    return \Carbon\CarbonInterval::seconds($rt)->cascade()->forHumans();
                }
            })
            ->label('Response Time'),
        ];
    }

    public function rowClasses($row, $loop){
        return 'hover:bg-gray-300 divide-x divide-gray-100 text-sm text-gray-900 ' . ($loop->even ? 'bg-gray-100' : 'bg-gray-50');
    }

    public function getExportStylesProperty()
    {
        return [
            '1'  => ['font' => ['bold' => true]],
            'B2' => ['font' => ['italic' => true]],
            'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function getExportWidthsProperty()
    {
        return [
            'A' => 55,
            'B' => 45,
        ];
    }

    public function buildActions()
    {
        return [

            Action::groupBy('Export Options', function () {
                return [
                    Action::value('csv')->label('Export CSV')->export('EngineersReport.csv'),
                    Action::value('xlsx')->label('Export XLSX')->export('EngineersReport.xlsx')->styles($this->exportStyles)->widths($this->exportWidths)
                ];
            }),
        ];
    }
}