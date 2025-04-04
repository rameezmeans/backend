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
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

use function PHPUnit\Framework\returnSelf;

class FilesDatatable extends LivewireDatatable
{
    public function builder()
    {

        if(Auth::user()->is_admin()){
            $files = $this->getAllFiles();
        }
        else{

            if(get_engineers_permission(Auth::user()->id, 'show-all-files')){
                $files = $this->getAllFiles();
            }
            else{

                $files = File::select('*', 'id as row_id')
                // ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
                ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "processing" THEN 2 ELSE 3 END AS s'))
                ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
                ->orderBy('ss', 'asc')
                ->orderBy('s', 'asc')
                ->where('is_credited', 1)
                // ->where('delayed', 0)
                ->whereNull('original_file_id')
                ->where('assigned_to', Auth::user()->id);
                
            }


        }
        
        return $files;
    }

    public function getAllFiles(){

            $files = File::select('*', 'id as row_id')
                // ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
                ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "processing" THEN 2 WHEN status = "ready_to_send" THEN 3 ELSE 4 END AS s'))
                ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
                ->orderBy('ss', 'asc')
                ->orderBy('s', 'asc')
                ->where('is_credited', 1)
                // ->where('delayed', 0)
                ->whereNull('original_file_id')
                ->where(function ($query) {
                $query->where('type', '=', 'master')
                        ->orWhereNotNull('assigned_from')->where('type', '=', 'subdealer');
            });

            return $files;
    }
    
    public function columns()
    {
        return [

            NumberColumn::name('id')->label('Task ID'),

            Column::callback(['id'], function($id){

                $file = File::findOrFail($id);

                if($file->delayed == 1){
                    return '<span class="label label-danger text-white m-r-5">Late</span>';
                }

                $returnStr = "";

                if($file->timer != NULL){

                    $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
                    $fodt = Key::where('key', 'file_open_delay_time')->first()->value;

                    if($file->support_status == 'open'){

                        $openTimeLeft = (strtotime($file->timer)+($fodt*60)) - strtotime(now());

                    }

                    if($file->support_status == 'open'){
                        if($openTimeLeft > 0){
                            $returnStr .='<lable class="label label-danger text-white m-r-5 open" id="o_'.$file->id.'" data-seconds="'.$openTimeLeft.'"></lable>';
                        }
                    }
                    
                    
                }

                if($file->submission_timer != NULL){

                    $fsdt = Key::where('key', 'file_submitted_delay_time')->first()->value;
                    $fodt = Key::where('key', 'file_open_delay_time')->first()->value;
                    

                    if($file->status == 'submitted'){
                        $submissionTimeLeft = (strtotime($file->submission_timer)+($fsdt*60)) - strtotime(now());
                    }
                    else if($file->status == 'on_hold'){
                        $onHoldTime =  strtotime($file->submission_timer)+($fsdt*60) - strtotime(now());
                        $file->on_hold_time = $onHoldTime;
                        $file->save();
                    }

                    if($file->status == 'submitted' ||  $file->status == 'on_hold'){

                        if($file->status == 'submitted'){
                            if($submissionTimeLeft > 0){
                                $returnStr .='<span class="label label-info text-white m-r-5 submission" id="s_'.$file->id.'" data-seconds="'.$submissionTimeLeft.'"></span>';
                            }
                        }
                        else if($file->status == 'on_hold'){
                            if($$onHoldTime > 0){
                                $returnStr .='<span class="label label-info text-white m-r-5 submission-stoped" id="s_'.$file->id.'" data-seconds="'.$file->on_hold_time.'"></span>';
                            }
                        }
                    }
                    
                }

                return $returnStr;

            })
            ->label('Submission Countdown / Reply Countdown'),
            
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

    public function rowClasses($row, $loop)
    {  
        // if($row->delayed == 1){
        //     return 'bg-red-200 hover:bg-red-200 divide-x divide-red-100 text-sm text-white '.$row->row_id.' redirect-click-file '.$row->row_id;
        // }

        if($row->red == 1){
            return 'bg-red-200 hover:bg-red-200 divide-x divide-red-100 text-sm text-white '.$row->row_id.' redirect-click-file '.$row->row_id;
        }

        if($row->checked_by == 'customer'){
            return 'bg-gray-500 hover:bg-gray-300 divide-x divide-gray-100 text-sm text-white '.$row->row_id.' redirect-click-file '.$row->row_id;
        }

            return 'hover:bg-gray-300 divide-x divide-gray-100 text-sm text-gray-900 ' . ($loop->even ? 'bg-gray-100' : 'bg-gray-50').' '.$row->row_id.' redirect-click-file '.$row->row_id;
    }
}