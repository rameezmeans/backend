<?php

namespace App\Http\Livewire;

use App\DataTables\DatetimeColumn;
use App\Models\File;
use App\Models\FrontEnd;
use App\Models\Service;
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

            Column::callback(['user_id'], function($userID){
                return User::findOrFail($userID)->name;
            })->label('Customer')
            ->filterable(User::where('is_customer', 1)->get(['id', 'name']))
            ->searchable(),

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

            Column::callback('stages', function($stages){
               
                if(\App\Models\Service::where('name', $stages)->first()){
                    return '<img alt="{{$file->stages}}" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::where('name', $stages)->first()->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $stages)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $stages)->first()->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.$stages.'</span>';
                }
            })
            ->filterable(Service::where('type', 'tunning')->pluck('name')->toArray())
            ->label('Stage')->searchable(),

            Column::callback(['id','options'], function($id,$op){
                $options = '';
                $file = File::findOrFail($id);
                foreach($file->options() as $option){
                    if(\App\Models\Service::where('name', $option)->first() != null){
                        $options .= '<img alt="'.$option.'" width="30" height="30" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon .'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">';
                        }
                    }
                    return $options;
            })
            ->label('Options'),

            Column::callback('credits', function($credits){
                return '<lable class="label bg-danger text-white">'.$credits.'</lable>';
            }) ->label('Credits'),

            DatetimeColumn::name('created_at')
                ->label('Upload Date')->sortable()->format('d/m/Y h:i A')->filterable(),

            Column::callback(['assigned_to'], function($assigned_to){
                return User::findOrFail($assigned_to)->name;
            })->label('Assigned to')
            ->filterable(User::where('is_engineer', 1)->get(['id', 'name']))
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
    {   if($row->checked_by == 'customer'){
            return 'bg-gray-500 hover:bg-gray-300 divide-x divide-gray-100 text-sm text-white redirect-click-file '.$row->id;
        }

            return 'hover:bg-gray-300 divide-x divide-gray-100 text-sm text-gray-900 ' . ($loop->even ? 'bg-gray-100' : 'bg-gray-50').' redirect-click-file '.$row->id;
    }
}