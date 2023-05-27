<?php

namespace App\Http\Livewire;

use App\DataTables\DatetimeColumn;
use App\Models\File;
use App\Models\FileFeedback;
use App\Models\FrontEnd;
use App\Models\Service;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class FeedbackTable extends LivewireDatatable
{
    public function builder()
    {
        return File::join('request_files', 'files.id', '=' , 'request_files.file_id')
            ->leftjoin('file_feedback', 'request_files.id', '=' , 'file_feedback.request_file_id');
        
    }

    public function columns()
    {

        $feedbacks = FileFeedback::groupBy('type')->pluck('type');

        return [
            Column::index($this),
            Column::callback(['front_end_id'], function($frontEndID){
                if($frontEndID == 1){
                    return '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else{
                    return '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
            })->label('Front End')
            ->filterable(FrontEnd::get(['id', 'name']))
            ->searchable(),
            NumberColumn::name('request_files.file_id')->label('Task ID'),
            Column::name('brand')->label('Brand'),
            Column::name('model')->label('Model'),
            Column::name('engine')->label('Engine'),
            Column::callback(['id','stage'], function($id,$stage){

                // return '<lable class="label label-success text-white">'.$stage.'</lable>';
                $file = File::findOrFail($id);

                $all = "";

                if($file->stage_services){
                    $all .= '<img alt="{{$file->stage}}" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                    <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                }
                
                foreach($file->options_services as $option){
                    if(\App\Models\Service::findOrFail($option->service_id) != null){
                        $all .= '<img class="parent-adjusted" alt="'.\App\Models\Service::findOrFail($option->service_id)->name.'" width="30" height="30" data-src-retina="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon .'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon.'">';
                        }
                    }
                return $all;
                
                // // if($file->stage_services){
                // return '<img alt="{{$file->stage}}" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                //                         <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                // // }
            
            })
            ->filterable(Service::where('type', 'tunning')->pluck('name')->toArray())
            ->label('Stage')->searchable(),

            Column::name('ecu')->label('ecu'),
            Column::callback(['id'], function($id){
                $all = "";
                $file = File::findOrFail($id);

                if($file->stage_services){
                    $all .= '<img alt="{{$file->stages}}" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                    <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                }
                
                foreach($file->options_services as $option){
                    if(\App\Models\Service::findOrFail($option->service_id) != null){
                        $all .= '<img class="parent-adjusted" alt="'.\App\Models\Service::findOrFail($option->service_id)->name.'" width="30" height="30" data-src-retina="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon .'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon.'">';
                        }
                    }
                return $all;
            })->label('Stage and Options'),
            Column::callback('file_feedback.type', function($type){
                if($type == 'ok'){
                    return '<span class="label bg-blue-200">'.$type.'</span>';
                }
                else if($type == 'happy' or $type == 'good'){
                    return '<span class="label bg-success">'.$type.'</span>';
                }
                else if($type == 'sad' or $type == 'angry'){
                    return '<span class="label bg-danger text-white">'.$type.'</span>';
                }
                else{
                    return '<span class="label bg-white">No Feedback</span>';
                }

            })->label('Feedback')
            ->filterable($feedbacks),
            Column::callback(['assigned_to'], function($assigned_to){
                return User::findOrFail($assigned_to)->name;
            })->label('Assigned to'),
            // ->filterable(User::where('is_engineer', 1)->get(['id', 'name']))
            // ->searchable(),
            DatetimeColumn::name('created_at')
            ->label('Date')->sortable()->format('d/m/Y h:i A')->filterable(),
        ];
    }

    public function rowClasses($row, $loop){
        return 'hover:bg-gray-300 divide-x divide-gray-100 text-sm text-gray-900 ' . ($loop->even ? 'bg-gray-100' : 'bg-gray-50').' redirect-click-file '.$row->{"request_files.file_id"} ;
    }
}