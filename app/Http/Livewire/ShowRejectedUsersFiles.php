<?php

namespace App\Http\Livewire;

use App\Models\File;
use App\Models\FrontEnd;
use App\Models\Service;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ShowRejectedUsersFiles extends LivewireDatatable
{
    public function builder()
    {
        $id = $this->params;
        $files = File::where('is_credited', 1)->where('status', 'rejected')->where('user_id', $id);

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
                if($frontEndID == 3){
                    return '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else{
                    return '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
            })->label('Front End')
            ->filterable(FrontEnd::get(['id', 'name']))
            ->searchable(),
            Column::name('username')->label('Customer')->searchable(),

            

            Column::name('brand')->label('Brand')->searchable(),
            
            Column::name('model')->label('Model')->searchable(),

            Column::name('ecu')->label('ECU')->searchable(),

                

            Column::callback('support_status', function($supportStatus){
                if($supportStatus == 'open'){
                    return '<label class="label bg-danger text-white">'.$supportStatus.'</label>';
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
                    return '<lable class="label label-danger text-white">'.'canceled'.'</lable>';
                }
                else{
                    return '<lable class="label bg-blue-200 text-black">'.$status.'</lable>';
                }
            })
            ->filterable(File::groupBy('status')->pluck('status')->toArray())
            ->label('Status')->searchable(),

            Column::callback(['id','stage'], function($id,$stage){

                // return '<lable class="label label-success text-white">'.$stage.'</lable>';
                $file = File::findOrFail($id);
                
                if($file->stage_services){
                return '<img alt="{{$file->stage}}" width="33" height="33" data-src-retina="'. url("icons").'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon .'" data-src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'" src="'.url('icons').'/'.\App\Models\Service::findOrFail($file->stage_services->service_id)->icon.'">
                                        <span class="text-black" style="top: 2px; position:relative;">'.\App\Models\Service::findOrFail($file->stage_services->service_id)->name.'</span>';
                }
            
            })
            ->filterable(Service::where('type', 'tunning')->pluck('name')->toArray())
            ->label('Stage')->searchable(),

            Column::callback(['id', 'tool_id'], function($id){
                $options = '';
                $file = File::findOrFail($id);
                
                foreach($file->options_services as $option){
                    $service = \App\Models\Service::where('id',$option->service_id)->first();
                    if($service != null){
                        

                            if($service){
                                $options .= '<img class="parent-adjusted" alt="'.$service->name.'" width="30" height="30" data-src-retina="'.url('icons').'/'.$service->icon .'" data-src="'.url('icons').'/'.$service->icon .'" src="'.url('icons').'/'.$service->icon.'">';
                            }
                            else{
                                $options.= "<span>Service Deleted.</span>";
                            }
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
                
                if(User::where('id',$assigned_to)->first()){
                    return User::findOrFail($assigned_to)->name;
                }
                else{
                    return "NONE";
                }
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
}