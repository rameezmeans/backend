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
        // if(Auth::user()->is_admin() || Auth::user()->is_head()){
            // $files = File::orderBy('support_status', 'desc')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('is_credited', 1)->get();
            $files = File::select('*')
            ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
            ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
            ->orderBy('ss', 'asc')
            ->orderBy('s', 'asc')
            ->where('is_credited', 1)
            ->whereNull('subdealer_group_id');
            
        // }
        // else if(Auth::user()->is_engineer()){
        //     // $files = File::orderBy('support_status', 'desc')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('assigned_to', Auth::user()->id)->where('is_credited', 1)->get();
        //     $files = File::select('*')
        //     ->addSelect(DB::raw('CASE WHEN status = "submitted" THEN 1 WHEN status = "on_hold" THEN 2 WHEN status = "processing" THEN 3 ELSE 4 END AS s'))
        //     ->addSelect(DB::raw('CASE WHEN support_status = "open" THEN 1 ELSE 2 END AS ss'))
        //     ->orderBy('ss', 'asc')
        //     ->orderBy('s', 'asc')
        //     ->where('is_credited', 1)
        //     ->where('assigned_to', Auth::user()->id);
        // }
        
        return $files;
    }

    public function saveFiles($id){

        $token = Key::where('key', 'alientech_access_token')->first()->value;

        $file = File::findOrFail($id);

        $alientechGUID = AlientechFile::where('key', 'guid')->where('file_id', $id)->first()->value;
        
        $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$alientechGUID;

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $token,
        ];
  
        $response = Http::withHeaders($headers)->get($getsyncOpURL);
        $responseBody = json_decode($response->getBody(), true);

        $slotGuid = $responseBody['slotGUID'];
        
        $result = $responseBody['result'];

        if($result['kess3Mode'] == 'OBD'){

            if( isset($result['obdDecodedFileURL']) ){
            
                $url = $result['obdDecodedFileURL'];

                $headers = [
                    'X-Alientech-ReCodAPI-LLC' => $token,
                ];
        
                $response = Http::withHeaders($headers)->get($url);
                $responseBody = json_decode($response->getBody(), true);

                $base64_string = $responseBody['data'];

                // specify the path and filename for the downloaded file
                $filepath = $responseBody['name'];

                // save the decoded string to a file
                $flag = file_put_contents($filepath, $base64_string);

                $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

                $headers = [
                // 'Content-Type' => 'multipart/form-data',
                'X-Alientech-ReCodAPI-LLC' => $token,
                ];

                $response = Http::withHeaders($headers)->post($url, []);

                $extension = pathinfo($responseBody['name'], PATHINFO_EXTENSION);

                $obj = new AlientechFile();
                $obj->key = $extension;
                $obj->value = $file->file_attached.'.'.$extension;
                $obj->purpose = "download";
                $obj->file_id = $file->id;
                $obj->save();

            }
        }
        else if($result['kess3Mode'] == 'BootBench'){
            foreach($result['bootBenchComponents'] as $row){

                $url = $row['decodedFileURL'];

                $headers = [
                    'X-Alientech-ReCodAPI-LLC' => $token,
                ];
        
                $response = Http::withHeaders($headers)->get($url);
                $responseBody = json_decode($response->getBody(), true);

                $base64_string = $responseBody['data'];

                // specify the path and filename for the downloaded file
                $filepath = $responseBody['name'];

                // save the decoded string to a file
                $flag = file_put_contents($filepath, $base64_string);

                $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

                $headers = [
                // 'Content-Type' => 'multipart/form-data',
                'X-Alientech-ReCodAPI-LLC' => $token,
                ];

                $response = Http::withHeaders($headers)->post($url, []);

                $extension = pathinfo($responseBody['name'], PATHINFO_EXTENSION);

                $obj = new AlientechFile();
                $obj->key = $extension;
                $obj->value = $file->file_attached.'.'.$extension;
                $obj->purpose = "download";
                $obj->file_id = $file->id;
                $obj->save();

            }
        }
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

            Column::callback('credits', function($credits){
                return '<lable class="label bg-danger text-white">'.$credits.'</lable>';
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
    {   if($row->checked_by == 'customer'){
            return 'bg-gray-500 hover:bg-gray-300 divide-x divide-gray-100 text-sm text-white redirect-click-file '.$row->id;
        }

            return 'hover:bg-gray-300 divide-x divide-gray-100 text-sm text-gray-900 ' . ($loop->even ? 'bg-gray-100' : 'bg-gray-50').' redirect-click-file '.$row->id;
    }
}