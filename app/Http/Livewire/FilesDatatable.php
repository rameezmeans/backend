<?php

namespace App\Http\Livewire;

use App\DataTables\DatetimeColumn;
use App\Models\AlientechFile;
use App\Models\File;
use App\Models\FrontEnd;
use App\Models\Key;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class FilesDatatable extends LivewireDatatable
{
    public function builder()
    {
        $files = File::select('*', 'id as row_id')
        ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
        ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
        ->orderBy('ss', 'asc')
        ->orderBy('s', 'asc')
        ->where('is_credited', 1)
        ->where(function ($query) {
            $query->where('type', '=', 'master')
                    ->orWhereNotNull('assigned_from');
        });
        
        return $files;
    }
    
    public function columns()
    {
        return [

            NumberColumn::name('id')->label('Task ID'),

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

            Column::name('username')->label('Customer')->searchable(),

            Column::callback(['id', 'brand'], function ($id) {

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
            ->filterable(File::groupBy('support_status')->pluck('support_status')->toArray())
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
            ->filterable(File::groupBy('status')->pluck('status')->toArray())
            ->label('Status')->searchable(),

            Column::callback(['id'], function($id){

                $file = File::findOrFail($id);
                
                if($file->stage_services){
                    return '<img alt="{{$file->stages}}" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                }
            
            })
            ->filterable(Service::where('type', 'tunning')->pluck('name')->toArray())
            ->label('Stage')->searchable(),

            Column::callback(['id', 'tool_id'], function($id){
                $options = '';
                $file = File::findOrFail($id);
                foreach($file->options_services as $option){
                    if(\App\Models\Service::findOrFail($option->service_id) != null){
                        $options .= '<img class="parent-adjusted" alt="'.\App\Models\Service::findOrFail($option->service_id)->name.'" width="30" height="30" data-src-retina="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon .'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($option->service_id)->icon.'">';
                        }
                    }
                
                return $options;
            })
            ->label('Options'),

            Column::callback(['id', 'credits'], function($id){
                $file = File::findOrFail($id);
                
                if($file->assigned_from)
                    return '<lable class="label bg-danger text-white">'.$file->subdealer_credits.'</lable>';
                else
                    return '<lable class="label bg-danger text-white">'.$file->credits.'</lable>';

            }) ->label('Credits'),

            DatetimeColumn::name('created_at')
                ->label('Upload Date')->sortable()->format('d/m/Y h:i A')->filterable(),

            Column::callback(['assigned_to'], function($assigned_to){
                return User::findOrFail($assigned_to)->name;
            })->label('Assigned to')
            // ->filterable(User::where('is_engineer', 1)->get(['id', 'name']))
            ->searchable(),
    
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
        if($row->checked_by == 'customer'){
            return 'bg-gray-500 hover:bg-gray-300 divide-x divide-gray-100 text-sm text-white '.$row->row_id.' redirect-click-file '.$row->row_id;
        }

            return 'hover:bg-gray-300 divide-x divide-gray-100 text-sm text-gray-900 ' . ($loop->even ? 'bg-gray-100' : 'bg-gray-50').' '.$row->row_id.' redirect-click-file '.$row->row_id;
    }
}