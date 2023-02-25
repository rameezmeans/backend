<?php

namespace App\Http\Livewire;

use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class FilesDatatable extends LivewireDatatable
{
    public function builder()
    {
        if(Auth::user()->is_admin || Auth::user()->is_head){
            // $files = File::orderBy('support_status', 'desc')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('is_credited', 1)->get();
            $files = File::select('*')
            ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
            ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
            ->orderBy('ss', 'asc')
            ->orderBy('s', 'asc')
            ->where('is_credited', 1);
            
        }
        else if(Auth::user()->is_engineer){
            // $files = File::orderBy('support_status', 'desc')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('assigned_to', Auth::user()->id)->where('is_credited', 1)->get();
            $files = File::select('*')
            ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
            ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
            ->orderBy('ss', 'asc')
            ->orderBy('s', 'asc')
            ->where('is_credited', 1)
            ->where('assigned_to', Auth::user()->id);
        }

        return $files;
    }

    

    public function columns()
    {
        return [

            NumberColumn::callback(['id'], function ($id) {

                $file = File::findOrFail($id);

                if($file->frontend->id == 1){
                    return '<lable class="label bg-primary text-white">Task'.$id.'</lable>';
                }
                else{
                    return '<lable class="label bg-warning text-black">Task'.$id.'</lable>';
                }
                
            })->label('Task'),

            Column::name('name')
                ->label('Customer'),

            NumberColumn::callback(['id', 'name'], function ($id) {

                $file = File::findOrFail($id);

                return $file->brand.' '.$file->engine.' '.$file->vehicle()->TORQUE_standard;
                
                
            })->label('Vehicle'),

            Column::callback('support_status', function($supportStatus){
                if($supportStatus == 'open'){
                    return '<lable class="label bg-danger text-white">'.$supportStatus.'</lable>';
                }
                else{
                    return '<lable class="label bg-success text-black">'.$supportStatus.'</lable>';
                }
            })
            ->label('Support Status'),

            Column::callback('status', function($status){

                if($status == 'completed'){
                    return '<lable class="label label-success text-white">'.$status.'</lable>';
                }
                else if($status == 'rejected'){
                    return '<lable class="label label-danger text-white">'.$status.'</lable>';
                }
                else{
                    return '<lable class="label bg-blue-200 text-black">'.$status.'</lable>';
                }
            })
            ->label('Support Status'),

            Column::callback('stages', function($stages){
               
                if(\App\Models\Service::where('name', $stages)->first()){
                    return '<img alt="{{$file->stages}}" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::where('name', $stages)->first()->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $stages)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $stages)->first()->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.$stages.'</span>';
                }
            })
            ->label('Stage'),

            

            Column::callback(['id','options'], function($id,$op){
                $options = '';
                $file = File::findOrFail($id);
                foreach($file->options() as $option){
                    if(\App\Models\Service::where('name', $option)->first() != null){
                        $options .= '<img alt="'.$option.'" width="20" height="20" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon .'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option.'<br>';
                        }
                    }
                    return $options;
            })
            ->label('Options'),

            DateColumn::name('created_at')
                ->label('Upload Date')->sortable(),

            DateColumn::callback('assigned_to', function($id){
                return '<label class="label label-success">'.User::findOrFail($id)->name.'<label>';
            })->label('Assigned to'),

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

    public function rowClasses($row, $loop)
    {
        return 'redirect-click-file '.$row->id;
    }
}