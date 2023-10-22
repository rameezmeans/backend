@extends('layouts.app')

@section('pagespecificstyles')
  <style>

#circle{
  display: inline-block;
  width: 16px;
  height: 16px;
  background:#f55753;
  border-radius:50%;
  -moz-border-radius:50%;
  -webkit-border-radius:50%;
  line-height:20px;
  vertical-align:middle;
  text-align:center;
  color:white;
  }

  .chat-view .chat-bubble {
      padding: 12px 24px !important;
  }

  .chat-view .chat-inner {
    background: #CFF5F2;
  }

  .chat-input {
    border: 1px lightgrey solid !important;
    margin-top: 10px;
  }

  .modal-open .select2-container {
    z-index: 9999;
  }

  .bg-warning-light {
    background-color: #fef6dd !important;
  }

  .bg-primary-light {
    background-color: #e2deef !important;
  }

  </style>
@endsection
@section('content')
<div class="page-content-wrapper ">
  <!-- START PAGE CONTENT -->
  <div class="content sm-gutter">
    <!-- START CONTAINER FLUID -->
    <div class="container-fluid   container-fixed-lg bg-white">
      @if(Session::has('success'))
        <div class="pgn-wrapper" data-position="top" style="top: 59px;">
          <div class="pgn push-on-sidebar-open pgn-bar">
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">×</span><span class="sr-only">Close</span>
              </button>
              {{ Session::get('success') }}
            </div>
          </div>
        </div>
      @endif

      @if($file->new_requests)
      <div class="card card-transparent">
        <ul class="nav nav-tabs nav-tabs-simple nav-tabs-right bg-white" id="tab-4" role="tablist">
            <li class="nav-item">
              <a href="#" class="active" data-toggle="tab" role="tab" data-target="#tab4hellowWorld">Task {{$file->id}}</a>
            </li>
          @foreach($file->new_requests as $row)
            <li class="nav-item">
              <a href="#" data-toggle="tab" role="tab" data-target="#tab4FollowUs">Task {{$row->id}} (New Request)</a>
            </li>
          @endforeach
          
        </ul>

        <div class="tab-content bg-white">
          <div class="tab-pane active" id="tab4hellowWorld">
            
          @endif

      <div class="card card-transparent m-t-40">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
          <li class="nav-item">
            <a href="#"  @if(!Session::has('tab')) class="active" @endif data-toggle="tab" data-target="#slide1"><span>Task</span></a>
          </li>

          @if( ($file->front_end_id == 1 && $file->subdealer_group_id == NULL) )
         
            <li class="nav-item">
              <a href="#" data-toggle="tab" @if(Session::get('tab') == 'chat') class="active" @endif data-target="#slide2"><span>Chat and Support</span></a>
            </li>
          
          @endif
          
          @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'admin-tasks'))

          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide3"><span>Admin Tasks</span></a>
          </li>
          
          @endif
          
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide4"><span>Logs</span></a>
          </li>

          {{-- @if($file->decoded_files->isEmpty()) --}}
            @if($file->tool_type == 'slave' && $file->tool_id != $kess3Label->id)
              <li class="nav-item">
                <a href="#" data-toggle="tab" data-target="#slide5"><span>Upload Slave Decrypted File</span></a>
              </li>
            @endif
          {{-- @endif --}}


        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane slide-left  @if(!Session::has('tab')) active @endif" id="slide1">
            <div class="row column-seperation">
              <div class="col-lg-12">
                
                <div class="widget-16 card no-border widget-loader-circle">
                  <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                    
                    @if($file->tool_type == 'slave')
                      @if(!$file->decoded_files->isEmpty())
                        <form method="POST" action="{{route('flip-decoded-mode')}}">
                          @csrf
                          <input type="hidden" name="file_id" value="{{$file->id}}">
                        <button type='submit' class="btn @if($file->decoded_mode == 1) btn-danger @else btn-success @endif">@if($file->decoded_mode == 1) Decoded Mode @else Normal Mode @endif</button>
                        </form>
                      @endif
                    @endif

                    <div class="text-center">
                      <div class="card-title">
                          <img src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="" style="width: 30%;">
                          <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                          @if($file->original_file_id)
                              
                              <a href="{{ route('download', [$file->original_file_id, $file->file_attached, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's File</span>
                              </a>
                            
                          @else
                              @if($file->decoded_mode == 0)
                                <a href="{{ route('download', [$file->id, $file->file_attached, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's File</span>
                                </a>
                              @endif

                            {{-- @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id) --}}
                            @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id || $file->tool_id != $kess3Label->id)
                              @if(!$file->decoded_files->isEmpty())
                                @foreach($file->decoded_files as $decodedFile)
                                  {{-- @php dd($decodedFile->name); @endphp --}}
                                  @if( $decodedFile->extension && $decodedFile->extension != "")
                                    <a href="{{ route('download', [$file->id, $decodedFile->name.'.'.$decodedFile->extension, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Decoded File ({{$decodedFile->extension}})</span>
                                    </a>
                                  @else
                                    <a href="{{ route('download', [$file->id, $decodedFile->name, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Decoded File</span>
                                    </a>
                                  @endif
                                @endforeach
                              @endif
                            @endif
                          @endif
                        </div>
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">

                    <div class="row m-t-40">
                      {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                        @if($decodedAvailable == true)
                          <p class="text-danger">This File will provide you facility to download additional Decoded Files. Please refresh the page once or twice. Thanks.</p>
                        @endif
                      @endif --}}
                      <div class="col-lg-6 m-t-30">
                        <h5 class="">General Information</h5>
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Status</p>
                          <div class="pull-right">
                            <span class="label @if($file->status == 'sumbitted') label-success @else label-danger @endif">{{$file->status}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Uploaded Time</p>
                          <div class="pull-right">
                            <span class="">{{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i: A')}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Task ID</p>
                          <div class="pull-right">
                            <span class="label label-success">Task{{$file->id}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Customer Name</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->user->name}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Frontend</p>
                          <div class="pull-right">
                            <span class="label @if($file->frontend->id == 1) text-white bg-primary @else text-black bg-warning @endif">{{$file->frontend->name}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Original File</p>
                          <div class="pull-right">
                            <span class="label @if($file->is_original == 1) text-white bg-danger @else text-white bg-success @endif">@if($file->is_original) Yes @else No @endif<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        
                        @if($file->request_type)

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Requste Type</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->request_type}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      
                        @endif

                        @if(Auth::user()->is_admin())

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Assigned To</p>
                          <div class="pull-right">
                            @if($file->assigned_to)
                              <span class="label label-success">{{$file->assigned->name}}<span>
                            @else
                              <span class="label label-success">No One<span>
                            @endif
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Assigment Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ \Carbon\Carbon::parse($file->assignment_time)->diffForHumans() }}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @if($file->response_time)
                        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Engineer Upload Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ \Carbon\Carbon::parse($file->reupload_time)->diffForHumans() }}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Response Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans()
                             }}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @endif

                        @endif

                        @if($file->additional_comments)

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <h4 class="pull-left text-bold text-danger">Important Comments from Client</h4>
                          <br>
                          <div class="m-l-10">
                            {{$file->additional_comments}}
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @endif
                        
                      </div>

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'customer-contact-information'))

                      <div class="col-lg-6  m-t-30">
                        <h5 class="">Contact Information</h5>

                      @if($file->name)
                          <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Customer Name</p>
                            <div class="pull-right">
                              <span class="label label-success">{{$file->name}}<span>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        @endif
                        @if($file->phone)
                          <div class="b-grey b-t p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Phone</p>
                            <div class="pull-right">
                              <span class="label label-success">{{$file->phone}}<span>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        @endif
                        @if($file->email)
                          <div class="b-grey b-t p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Email</p>
                            <div class="pull-right">
                              <span class="label label-success">{{$file->email}}<span>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        @endif
                      </div>
                      @endif
                      
                      <div class="col-lg-6  m-t-30">
                        <h5 class="">Vehicle Information</h5>
                        
                        @if($file->license_plate)
                          <div class=" b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">License Plate</p>
                            <div class="pull-right">
                              <span class="label label-success">{{$file->license_plate}}<span>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        @endif
                        @if($file->model_year)
                        <div class=" b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Model Year</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->model_year}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      @endif
                      @if($file->vin_number)
                      <div class="b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Vin Number</p>
                        <div class="pull-right">
                          <span class="label label-success">{{$file->vin_number}}<span>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    @endif

                    @if($file->file_type)
                    <div class="b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">File Type</p>
                      <div class="pull-right">
                        <span class="label label-success">{{$file->file_type}}<span>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    @endif

                        <div class="b-t b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Brand</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->brand}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Model</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->model}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Version</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->version}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Engine</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->engine}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
                        @if($file->ecu)
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">ECU</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->ecu}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @endif
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Gear Box</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->gear_box}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @if($file->getECUComment())
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <h5 class="pull-left">Engineer's Comments On ECU</h5>
                          <br>
                          <div class="m-l-10">
                            @if($file->getECUComment()){{$file->getECUComment()->notes}}@endif
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @endif

                        @if(Auth::user()->is_admin() or Auth::user()->is_head())
                          <div class="text-center m-t-20">                    
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('add-comments', [$vehicle->id, 'file='.$file->id])}}"><span class="bold">Go To Comments</span></a>
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('vehicle', $vehicle->id)}}"><span class="bold">Go To Vehicle</span></a>
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('edit-file', $file->id)}}"><span class="bold">Edit File</span></a>
                            
                              {{-- <form method="POST" action="{{route('delete-file')}}">
                                @csrf
                                <input type="hidden" value="{{$file->id}}" name="id"> --}}
                                <button type="button" class="btn btn-danger btn-delete btn-cons m-b-10" data-file_id={{$file->id}}><span class="bold">Delete File</span></button>
                              {{-- </form> --}}
                          </div>
                        @endif
                        
                      </div>
        
                      <div class="col-lg-6">
                        <h5 class="m-t-40">Reading Tool</h5>
        
                            
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Tool</p>
                          <div class="pull-right">
                              <img alt="{{$file->tool_id}}" width="50" height="" data-src-retina="{{ get_dropdown_image($file->tool_id) }}" data-src="{{ get_dropdown_image($file->tool_id) }}" src="{{ get_dropdown_image($file->tool_id) }}">
                              <span class="" style="top: 2px; position:relative;">{{ \App\Models\Tool::findOrFail( $file->tool_id )->name }}({{$file->tool_type}})</span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                     
        
                      <h5 class="m-t-40">Options And Credits</h5>

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'propose-options'))


                      @if($file->status == 'submitted')
                        <button id="btn-options-change" class="btn btn-success m-b-20">Change Options</button>
                      @endif

                      @endif
                        
                      
                        @if(\App\Models\Service::FindOrFail($file->stage_services->service_id))
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Stage</p>
                          <div class="pull-right">
                              <img alt="{{\App\Models\Service::FindOrFail($file->stage_services->service_id)->name}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon}}" data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}" src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}">
                              <span class="text-black" style="top: 2px; position:relative;">{{ \App\Models\Service::FindOrFail($file->stage_services->service_id)->name }}</span>
                              @php $stage = \App\Models\Service::FindOrFail($file->stage_services->service_id) @endphp
                              @if($file->front_end_id == 2)
                                  @if($file->tool_type == 'master')
                                    <span class="text-white label-danger label"> {{$stage->tuningx_credits}} </span>
                                  @else
                                    <span class="text-white label-danger label"> {{$stage->tuningx_slave_credits}} </span>
                                  @endif
                              @else
                                <span class="text-white label-danger label"> {{$stage->credits}} </span>
                              @endif
                              

                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @endif
                      

                      <div class="p-b-20">

                      @if(!$file->options_services()->get()->isEmpty())
                        <div class="b  b-grey p-l-20 p-r-20 p-t-10 p-b-10">
                          <p class="pull-left">Options</p>
                          <div class="clearfix"></div>
                        </div>
                        
                        @foreach($file->options_services()->get() as $option) 
                            @if(\App\Models\Service::where('id', $option->service_id)->first())
                              <div class="p-l-20 b-b b-grey b-t p-b-10 p-t-10 p-r-20"> 
                                <img alt="{{\App\Models\Service::where('id', $option->service_id)->first()->name}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}">
                                {{\App\Models\Service::where('id', $option->service_id)->first()->name}}  ({{\App\Models\Service::where('id', $option->service_id)->first()->vehicle_type}})
                                @php $option = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                                @if($file->front_end_id == 2)
                                  @if($file->tool_type == 'master')
                                    <span class="text-white label-danger label pull-right"> {{$option->optios_stage($file->stage_services->service_id)->first()->master_credits}} </span>
                                  @else
                                    <span class="text-white label-danger label pull-right"> {{$option->optios_stage($file->stage_services->service_id)->first()->slave_credits}} </span>
                                  @endif
                              @else
                                <span class="text-white label-danger label pull-right"> {{$option->credits}} </span>
                              @endif

                              </div>
                            @endif
                              
                            @if($comments)
                              @foreach($comments as $comment)
                                  
                                  @if($option->id == $comment->service_id)
                                    <div class="p-l-20 p-b-10 p-t-10"> 
                                      {{$comment->comments}}
                                    
                                    </div>
                                    <div class="p-l-20 p-b-10">Type: {{$comment->comment_type}}</div>
                                  @endif
                              @endforeach
                            @endif
                        @endforeach
                      @endif
                      
                      </div>
                      
                      @if($file->dtc_off_comments)
                      <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left text-danger">DTC OFF Comments</p>
                        <br>
                        <div class="m-l-20">
                          {{$file->dtc_off_comments}}
                        </div>
                        <div class="clearfix"></div>
                      </div>
                      @endif

                      @if($file->vmax_off_comments)
                      <div class="p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left text-danger">VMAX OFF Comments</p>
                        <br>
                        <div class="m-l-20">
                          {{$file->vmax_off_comments}}
                        </div>
                        <div class="clearfix"></div>
                      </div>
                      @endif
                     
                      <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Credits Paid</p>
                        <div class="pull-right">
                         
                          @if($file->assigned_from)
                            <span class="label label-danger">{{$file->subdealer_credits}}<span>
                          @else
                            <span class="label label-danger">{{$file->credits}}<span>
                          @endif
                        </div>
                        <div class="clearfix"></div>
                      </div>
        
                      </div>

                      

                      <div class="col-lg-6">
                        <h5 class="m-t-40">Uploaded Files</h5>

                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Revisions</p>
                          <div class="pull-right">
                           
                              <label class="label bg-info text-white">{{$file->files->count()}}</label>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                            @foreach($file->files_and_messages_sorted() as $message)
                              @if(isset($message['request_file']))
                                @if($message['engineer'] == 1)
                            <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                <p class="pull-left">{{$message['request_file']}}</p>
                                <div class="pull-right">
                                  @isset($message['type'])
                                 
                                 
                                  <a href="#" class="btn-sm btn-info btn-cons"> <span class="bold">{{$message['type']}}</span>
                                  </a>
                                  @endisset
                                    @if(!($file->front_end_id == 1 && $file->subdealer_group_id == NULL))
                                      @php
                                        $messageFile = \App\Models\RequestFile::findOrFail($message['id']);

                                        
                                      @endphp

                                      @if(count($messageFile->engineer_file_notes_have_unseen_messages))
                                      <span id="circle"></span>
                                      @endif
                                      <a target="_blank" href="{{route('support', $message['id'])}}" class="btn-sm btn-cons btn-info"><i class="fa fa-question text-white"></i> Support</a>
                                    @endif
                                    <a href="{{ route('download',[$message['file_id'], $message['request_file'], 0]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download</span>
                                    </a>
                                    <a href="#" class="btn-sm btn-cons btn-danger delete-uploaded-file" data-request_file_id="{{$message['id']}}"><i class="pg-trash text-white"></i></a>
                                </div>

                                <div class="clearfix"></div>
                                  @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id)
                                  <div>
                                    <p>Please click on "Download Encrypted" Button to download and test the system. This way user will get Encrypted file or you will get the error so that you can process the file, manually.</p>
                                  </div>
                                  <div class="text-center">
                                    <a href="{{ route('download-encrypted',[$message['file_id'], $message['request_file'], false]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download Encrypted</span>
                                    </a>
                                  </div>
                                  @endif
                                <div class="clearfix"></div>
                            </div>
        
                        @endif
                        @endif
                      @endforeach
                      </div>

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'propose-options'))


                      @if($file->stage_offer)

                      @php $proposedCredits = 0; @endphp

                      <div class="col-lg-6">
                        <h5 class="m-t-40">Proposed Stage and Options</h5>
                        
                          <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Stage</p>
                            <div class="pull-right">
                                <img alt="{{\App\Models\Service::FindOrFail($file->stage_offer->service_id)->name}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_offer->service_id)->icon}}" data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_offer->service_id)->icon }}" src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_offer->service_id)->icon }}">
                                <span class="text-black" style="top: 2px; position:relative;">{{ \App\Models\Service::FindOrFail($file->stage_offer->service_id)->name }}</span>
                                @php $stage = \App\Models\Service::FindOrFail($file->stage_offer->service_id) @endphp
                                @if($file->front_end_id == 2)
                                    @if($file->tool_type == 'master')
                                      <span class="text-white label-danger label"> {{$stage->tuningx_credits}} </span>
                                      @php $proposedCredits += $stage->tuningx_credits; @endphp
                                    @else
                                      <span class="text-white label-danger label"> {{$stage->tuningx_slave_credits}} </span>
                                      @php $proposedCredits += $stage->tuningx_slave_credits; @endphp
                                    @endif
                                @else
                                  <span class="text-white label-danger label"> {{$stage->credits}} </span>
                                  @php $proposedCredits += $stage->credits; @endphp
                                @endif
                                
                            </div>
                            <div class="clearfix"></div>
                          </div>

                          <div class="b-b  b-grey p-l-20 p-r-20 p-t-10 p-b-10">
                            <p class="pull-left">Options</p>
                            <div class="clearfix"></div>
                          </div>
                        
                          @foreach($file->options_offer as $option)
                              
                              @if(\App\Models\Service::FindOrFail($option->service_id))
                                <div class="p-l-20  p-r-20 b-b b-grey  p-t-10 p-b-10"> 
                                  <img alt="{{\App\Models\Service::FindOrFail($option->service_id)->name}}" width="40" height="40" 
                                  data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                  data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                  src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}">
                                  {{\App\Models\Service::FindOrFail($option->service_id)->name}}  
                                  @php $option1 = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                                  @if($file->front_end_id == 2)
                                    @if($file->tool_type == 'master')
                                      <span class="text-white label-danger label pull-right"> {{$option1->optios_stage($file->stage_services->service_id)->first()->master_credits}} </span>
                                      @php $proposedCredits += $option1->optios_stage($file->stage_services->service_id)->first()->master_credits @endphp
                                    @else
                                      <span class="text-white label-danger label pull-right"> {{$option1->optios_stage($file->stage_services->service_id)->first()->slave_credits}} </span>
                                      @php $proposedCredits += $option1->optios_stage($file->stage_services->service_id)->first()->slave_credits @endphp
                                    @endif
                                  @else
                                    <span class="text-white label-danger label pull-right"> {{$option1->credits}} </span>
                                    @php $proposedCredits += $option1->credits; @endphp
                                  @endif
                                </div>
                              @endif
                          @endforeach

                          <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Credits Proposed</p>
                            <div class="pull-right">
                             
                              
                                <span class="label label-warning text-black">{{$proposedCredits}}<span>
                              
                            </div>
                            <div class="clearfix"></div>
                          </div>

                          <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Credits Difference</p>
                            <div class="pull-right">
                             
                              
                                <span class="label label-info text-black">{{$file->credits-$proposedCredits}}<span>
                              
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        
                        </div>
                      @endif
                      @endif

                      {{-- <div class="col-xl-12">
                        <h5 class="m-t-40">Upload File</h5>
                        <!-- START card -->
                        <div class="card card-default">
                          <div class="card-header ">
                            <div class="card-title">
                              Drag n' drop uploader
                            </div>
                            <div class="tools">
                              <a class="collapse" href="javascript:;"></a>
                              <a class="config" data-toggle="modal" href="#grid-config"></a>
                              <a class="reload" href="javascript:;"></a>
                              <a class="remove" href="javascript:;"></a>
                            </div>
                          </div>
                          <div class="card-body no-scroll no-padding">
                            <form action="{{route('request-file-upload')}}" class="simple-dropzone dropzone no-margin">
                              @csrf
                              <input type="hidden" value="{{$file->id}}" name="file_id">
                              <div class="fallback">
                                <input name="file" type="file" />
                              </div>
                            </form>
                          </div>
                        </div>
                        <!-- END card -->
                      </div> --}}
                      
                      @if($file->status == 'submitted' || $file->status == 'completed')
                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file'))
                      <div class="col-xl-12 m-t-20">
                        <div class="card card-transparent flex-row">
                          <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-white" id="tab-3">

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                              @if($file->decoded_files)
                              <li class="nav-item">
                                <a href="#" class="active show" data-toggle="tab" data-target="#tab3hellowWorld">Encode</a>
                              </li>
                              @endif
                            @endif --}}

                            <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab3FollowUs" class="">Upload</a>
                            </li>
                            {{-- <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab3Inspire">Three</a>
                            </li> --}}
                          </ul>
                          <div class="tab-content bg-white full-width">

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3') --}}
                            
                           

                            <div class="tab-pane active show" id="tab3hellowWorld">
                              <div class="row column-seperation">
                                
                                <div class="col-xl-12 full-width">
                                  <h5 class="">Upload Decoded File to Encode</h5>
                                  <!-- START card -->
                                  <div class="card card-default">
                                    <div class="card-header ">
                                      <div class="card-title">
                                        Drag n' drop uploader
                                      </div>
                                      <div class="tools">
                                        <a class="collapse" href="javascript:;"></a>
                                        <a class="config" data-toggle="modal" href="#grid-config"></a>
                                        <a class="reload" href="javascript:;"></a>
                                        <a class="remove" href="javascript:;"></a>
                                      </div>
                                    </div>
                                    <div class="card-body no-scroll no-padding">
                                      <form action="{{route('encoded-file-upload')}}" class="encoded-dropzone dropzone no-margin">
                                        @csrf
                                        <input type="hidden" value="{{$file->id}}" name="file_id">
                                        @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id)
                                          <input type="hidden" value="1" name="encode">
                                            @if($file->decoded_file)
                                              @if($file->decoded_file->extension == 'dec')
                                                <input type="hidden" value="dec" name="encoding_type">
                                              @else
                                                <input type="hidden" value="micro" name="encoding_type">
                                              @endif
                                            @endif
                                          @else
                                            <input type="hidden" value="0" name="encode">
                                          @endif
                                       
                                        <div class="fallback">
                                          <input name="file" type="file" />
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                  <!-- END card -->
                                </div> 
                                    
                              </div>
                            </div>

                           
                            {{-- @endif
                            @endif --}}

                            {{-- <div class="tab-pane  @if(!$file->decoded_files) active show @endif" id="tab3FollowUs">
                              <div class="col-xl-12 full-width">
                                <h5 class="">Upload File</h5>
                                <!-- START card -->
                                <div class="card card-default">
                                  <div class="card-header ">
                                    <div class="card-title">
                                      Drag n' drop uploader
                                    </div>
                                    <div class="tools">
                                      <a class="collapse" href="javascript:;"></a>
                                      <a class="config" data-toggle="modal" href="#grid-config"></a>
                                      <a class="reload" href="javascript:;"></a>
                                      <a class="remove" href="javascript:;"></a>
                                    </div>
                                  </div>
                                  <div class="card-body no-scroll no-padding">
                                    <form action="{{route('request-file-upload')}}" class="simple-dropzone dropzone no-margin">
                                      @csrf
                                      <input type="hidden" value="{{$file->id}}" name="file_id">
                                      <input type="hidden" value="0" name="encode">
                                      <div class="fallback">
                                        <input name="file" type="file" />
                                      </div>
                                    </form>
                                  </div>
                                </div>
                                <!-- END card -->
                              </div> 
                            </div> --}}

                          </div>
                        </div>
                      </div>
                      @endif
                      @endif

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if(($file->front_end_id == 1 && $file->subdealer_group_id == NULL))
          <div class="tab-pane slide-left @if(Session::get('tab') == 'chat') active @endif" id="slide2">
            <div class="row">
              <div class="col-lg-12">
                <div class="widget-16 card no-border widget-loader-circle">
                  <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                    <div class="text-center">
                      <div class="card-title">
                          <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                          <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                        </div>
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">
                    <div class="view chat-view bg-white clearfix">
                      <!-- BEGIN Header  !-->
                      
                      <!-- END Header  !-->
                      <!-- BEGIN Conversation  !-->
                      @if(!empty($file->files_and_messages_sorted()))
          
                      <div class="chat-inner" id="my-conversation" style="overflow: scroll !important; height:500px;">
                        <!-- END From Me Message  !-->
                        <!-- BEGIN From Them Message  !-->
                        @foreach($file->files_and_messages_sorted() as $message)
                         
                          @if(isset($message['egnineers_internal_notes']))
                            @if($message['engineer'])
                            <div class="message clearfix">
                              <div class="chat-bubble bg-primary from-me text-white">
                                {{ $message['egnineers_internal_notes'] }} 
                                
                                <i data-note_id="{{$message['id']}}" data-message="{{$message['egnineers_internal_notes']}}" class="fa fa-edit m-l-20"></i> 
                                <i class="pg-trash delete-message" data-note_id="{{$message['id']}}"></i> 
                                <br>
                                @if(isset($message['engineers_attachement']))
                                  <div class="text-center m-t-10">
                                    <a href="{{route('download',[$message['file_id'], $message['engineers_attachement'], 0])}}" class="text-danger">Download</a>
                                  </div>
                                @endif
                                <br>
                                <small class="m-t-20" style="font-size: 8px; float:right">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small>
                              </div>
                            </div>
          
                            @else
                              <div class="message clearfix">
                                <div class="chat-bubble from-them bg-success">
                                    {{ $message['egnineers_internal_notes'] }}<br>
                                    @if(isset($message['engineers_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download',[$message['file_id'], $message['engineers_attachement'], 0])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
                                    <br>
                                    <br>
                                    <small class="m-t-20" style="font-size: 8px;float:right">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small>
                                </div>
                              </div>
                            @endif
                          @endif
                          @if(isset($message['file_url']))
                            
                            <div class="message clearfix">
                              <div class="chat-bubble bg-success from-them text-white">
                                {{ $message['file_url'] }}<br>
                                @if(isset($message['file_url_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download',[$message['file_id'], $message['file_url_attachement'], 0])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
                              </div>
                            </div>
          
                            
                          @endif
                        @endforeach
                        <!-- END From Them Message  !-->
                        <!-- BEGIN From Me Message  !-->
                        
                        {{-- <div class="message clearfix">
                          <div class="chat-bubble from-me">
                            Did you check out Pages framework  ?
                          </div>
                        </div> --}}
          
                      </div>
                      @endif
                      <!-- BEGIN Conversation  !-->
                      <!-- BEGIN Chat Input  !-->
                      <div class="b-t b-grey bg-white clearfix p-l-10 p-r-10 text-center">
                        <form method="POST" action="{{ route('file-engineers-notes') }}" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" value="{{$file->id}}" name="file_id">
                        <div class="row">
                            <div class="col-6 no-padding">
                              <input type="text" name="egnineers_internal_notes" class="form-control chat-input" data-chat-input="" data-chat-conversation="#my-conversation" placeholder="Reply to cusotmer." required>
                              @error('egnineers_internal_notes')
                                      <p class="text-danger" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </p>
                              @enderror
                             
                            </div>
                            <div  class="col-4 no-padding"> 
                              <input class="m-t-10" type="file" name="engineers_attachement" style="float: :left;">
                            </div>
                            <div class="col-2 link text-master m-t-15 p-l-10 b-l b-grey col-top">
                              <button class="btn btn-success" type="submit">Send</button>
                            </div>
                          
                        </div>
                      </form>
                      </div>
                      <!-- END Chat Input  !-->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'admin-tasks'))

            <div class="tab-pane slide-left" id="slide3">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                      <h4 class="m-t-20">Adminstrative Tasks</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  
                    <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">Assign This File to An Engineer</p>
                      <form action="{{route('assign-engineer')}}" method="POST">
                        @csrf
                        <input type="hidden" name="file_id" value="{{$file->id}}">
                        <div class="">
                          <select class="full-width" data-init-plugin="select2" name="assigned_to">
                            <option disabled >Not Assigned</option>
                            @foreach($engineers as $engineer)
                              <option @if(isset($file) && $file->assigned_to == $engineer->id) selected @endif value="{{$engineer->id}}">{{$engineer->name}}</option>
                            @endforeach
                          </select>
                          <div class="text-center m-t-20">                    
                            <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Assign Engineer</span></button>
                          </div>
                        </div>
                        
                      </form>
                      <div class="clearfix"></div>
                    </div>
                 
                  @if($file->status != 'rejected')
                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                    <p class="pull-left">File Status</p>
                    <form action="{{route('change-status-file')}}" method="POST">
                      @csrf
                      <input type="hidden" name="file_id" value="{{$file->id}}">
                      <div class="">
                        <select class="full-width" data-init-plugin="select2" name="status" id="select_status">
                            <option @if(isset($file) && $file->status == "submitted") selected @endif value="submitted">Submitted</option>
                            <option @if(isset($file) && $file->status == "rejected") selected @endif value="rejected">Rejected</option>
                            <option @if(isset($file) && $file->status == "completed") selected @endif value="completed">Completed</option>
                            <option @if(isset($file) && $file->status == "processing") selected @endif value="processing">Processing</option>
                            <option @if(isset($file) && $file->status == "on_hold") selected @endif value="on_hold">On Hold</option>
                        </select>
                        <div class="form-group m-t-10 hide" id="reason_to_reject">
                          <label>Reason To Reject</label>
                          <input type="text" class="form-control" name="reason_to_reject">
                        </div>
                        <div class="text-center m-t-20">                    
                          <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Update</span></button>
                        </div>
                      </div>
                      
                    </form>
                    <div class="clearfix"></div>
                  </div>
                  @endif
                  @if(Auth::user()->is_admin() or Auth::user()->is_head())
                    <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">Support Status</p>
                      <form action="{{route('change-support-status')}}" method="POST">
                        @csrf
                        <input type="hidden" name="file_id" value="{{$file->id}}">
                        <div class="">
                          <select class="full-width" data-init-plugin="select2" name="support_status">
                              <option disabled>Not Set</option>
                              <option @if(isset($file) && $file->support_status == "open") selected @endif value="open">Open</option>
                              <option @if(isset($file) && $file->support_status == "closed") selected @endif value="closed">Closed</option>
                          </select>
                          <div class="text-center m-t-20">                    
                            <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Update</span></button>
                          </div>
                        </div>
                        
                      </form>
                      <div class="clearfix"></div>
                    </div>
                  @endif
                  <br>
                </div>
              </div>
            </div>

            @endif

            <div class="tab-pane slide-left" id="slide4">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                      <h4 class="m-t-20">Logs</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="row" style="">

                @foreach($file->logs as $log)
                  <div class="col-12 col-xl-12 @if($log->type == 'error') bg-danger-light @else bg-success-light @endif text-white m-b-10 m-t-10 m-l-10" style="height: 50px;">
                    <p class="no-margin p-t-10 p-b-10">{{$log->message}}</p>
                  </div>
                @endforeach

              </div>
            </div>
           
            @if($file->tool_type == 'slave' && $file->tool_id != $kess3Label->id)
              <div class="tab-pane slide-left" id="slide5">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                    
                    </div>
                  </div>
                  <div class="card-body">
                    <h5>
                      Upload Decrypted File
                    </h5>
                    <form method="POST" action="{{route('search')}}" enctype="multipart/form-data" class="" role="form">
                      @csrf
                      <input type="hidden" name="file_id" value="{{$file->id}}">
                      <div class="form-group form-group-default required ">
                        <label>Decrypted File</label>
                        <input name="decrypted_file" type="file" class="form-control" required="">
                       
                      </div>
                      <div class="radio radio-success">
                        <input class="download_directly" type="radio" checked="checked" value="direct" name="download_directly" id="direct">
                        <label for="direct">Send Directly</label>
                        <input class="download_directly" type="radio"  value="download" name="download_directly" id="download">
                        <label for="download">Download with Custom Options</label>
                      </div>

                      <div class="stages-show hide">
                        <h5 class="m-t-20">Stages Options</h5>
                        @foreach($stages as $stage)
                          <div class="radio radio-success">
                            <input class="stages" type="radio" @if($file->stage_services->service_id == $stage->id) checked="checked" @endif value="{{$stage->id}}" name="custom_stage" id="{{$stage->id}}">
                            <label for="{{$stage->id}}">{{$stage->name}}</label>
                          </div>
                        @endforeach
                      </div>

                      <div class="options-show hide">
                      <h5 class="m-t-20">Custome Options</h5>
                      <div class="radio radio-success">
                         @if(!$file->options_services->isEmpty())
                          {{-- @foreach($file->options_services as $option) --}}
                            @if(!$options->isEmpty())
                              @foreach($options as $option)
                                <div class="checkbox check-success">
                                  <input @if(in_array($option->id, $selectedOptions)) checked @endif name="custom_options[]" type="checkbox" value="{{$option->id}}" id="{{$option->id}}">
                                  <label for="{{$option->id}}">{{$option->name}} - ({{$option->vehicle_type}})</label>
                                </div>
                              @endforeach
                            @endif
                          @else
                            <p>No Options.</p>
                          @endif
                      </div>
                      </div>

                      <button class="btn btn-success">Upload</button>
                    </form>
                  </div>
                </div>
              </div>
            @endif
        </div>
      </div>
      @if($file->new_requests)
    </div>
    @foreach($file->new_requests as $file)
    
    <div class="tab-pane " id="tab4FollowUs">
      <div class="card card-transparent m-t-40">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
          <li class="nav-item">
            <a href="#"  @if(!Session::has('tab')) class="active" @endif data-toggle="tab" data-target="#slide1{{$file->id}}"><span>Task</span></a>
          </li>

          @if( ($file->front_end_id == 1 && $file->subdealer_group_id == NULL) )
         
            <li class="nav-item">
              <a href="#" data-toggle="tab" @if(Session::get('tab') == 'chat') class="active" @endif data-target="#slide2{{$file->id}}"><span>Chat and Support</span></a>
            </li>
          
          @endif

          {{-- @php dd($file->subdealer_group_id); @endphp
          @if($file->subdealer_group_id == NULL)
          <li class="nav-item">
            <a href="#" data-toggle="tab" @if(Session::get('tab') == 'chat') class="active" @endif data-target="#slide2"><span>Chat and Support</span></a>
          </li>
          @endif --}}
          
          @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'admin-tasks'))

          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide3{{$file->id}}"><span>Admin Tasks</span></a>
          </li>
          
          @endif
          
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide4{{$file->id}}"><span>Logs</span></a>
          </li>

          {{-- @if($file->decoded_files->isEmpty()) --}}
            @if($file->tool_type == 'slave' && $file->tool_id != $kess3Label->id)
              <li class="nav-item">
                <a href="#" data-toggle="tab" data-target="#slide5{{$file->id}}"><span>Upload Slave Decrypted File</span></a>
              </li>
            @endif
          {{-- @endif --}}


        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane slide-left  @if(!Session::has('tab')) active @endif" id="slide1{{$file->id}}">
            <div class="row column-seperation">
              <div class="col-lg-12">
                
                <div class="widget-16 card no-border widget-loader-circle">
                  <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                    
                    @if($file->tool_type == 'slave')
                      @if(!$file->decoded_files->isEmpty())
                        <form method="POST" action="{{route('flip-decoded-mode')}}">
                          @csrf
                          <input type="hidden" name="file_id" value="{{$file->id}}">
                        <button type='submit' class="btn @if($file->decoded_mode == 1) btn-danger @else btn-success @endif">@if($file->decoded_mode == 1) Decoded Mode @else Normal Mode @endif</button>
                        </form>
                      @endif
                    @endif

                    <div class="text-center">
                      <div class="card-title">
                          <img src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="" style="width: 30%;">
                          <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                          @if($file->original_file_id)
                              
                              <a href="{{ route('download', [$file->original_file_id, $file->file_attached, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's File</span>
                              </a>
                            
                          @else
                              @if($file->decoded_mode == 0)
                                <a href="{{ route('download', [$file->id, $file->file_attached, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's File</span>
                                </a>
                              @endif

                            {{-- @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id) --}}
                            @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id || $file->tool_id != $kess3Label->id)
                              @if(!$file->decoded_files->isEmpty())
                                @foreach($file->decoded_files as $decodedFile)
                                  {{-- @php dd($decodedFile->name); @endphp --}}
                                  @if( $decodedFile->extension && $decodedFile->extension != "")
                                    <a href="{{ route('download', [$file->id, $decodedFile->name.'.'.$decodedFile->extension, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Decoded File ({{$decodedFile->extension}})</span>
                                    </a>
                                  @else
                                    <a href="{{ route('download', [$file->id, $decodedFile->name, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Decoded File</span>
                                    </a>
                                  @endif
                                @endforeach
                              @endif
                            @endif
                          @endif
                        </div>
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">

                    <div class="row m-t-40">
                      {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                        @if($decodedAvailable == true)
                          <p class="text-danger">This File will provide you facility to download additional Decoded Files. Please refresh the page once or twice. Thanks.</p>
                        @endif
                      @endif --}}
                      <div class="col-lg-6 m-t-30">
                        <h5 class="">General Information</h5>
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Status</p>
                          <div class="pull-right">
                            <span class="label @if($file->status == 'sumbitted') label-success @else label-danger @endif">{{$file->status}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Uploaded Time</p>
                          <div class="pull-right">
                            <span class="">{{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i: A')}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Task ID</p>
                          <div class="pull-right">
                            <span class="label label-success">Task{{$file->id}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Customer Name</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->user->name}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Frontend</p>
                          <div class="pull-right">
                            <span class="label @if($file->frontend->id == 1) text-white bg-primary @else text-black bg-warning @endif">{{$file->frontend->name}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Original File</p>
                          <div class="pull-right">
                            <span class="label @if($file->is_original == 1) text-white bg-danger @else text-white bg-success @endif">@if($file->is_original) Yes @else No @endif<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        
                        @if($file->request_type)

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Requste Type</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->request_type}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      
                        @endif

                        @if(Auth::user()->is_admin())

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Assigned To</p>
                          <div class="pull-right">
                            @if($file->assigned_to)
                              <span class="label label-success">{{$file->assigned->name}}<span>
                            @else
                              <span class="label label-success">No One<span>
                            @endif
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Assigment Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ \Carbon\Carbon::parse($file->assignment_time)->diffForHumans() }}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @if($file->response_time)
                        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Engineer Upload Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ \Carbon\Carbon::parse($file->reupload_time)->diffForHumans() }}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Response Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans()
                             }}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @endif

                        @endif

                        @if($file->additional_comments)

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <h4 class="pull-left text-bold text-danger">Important Comments from Client</h4>
                          <br>
                          <div class="m-l-10">
                            {{$file->additional_comments}}
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @endif
                        
                      </div>

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'customer-contact-information'))

                      <div class="col-lg-6  m-t-30">
                        <h5 class="">Contact Information</h5>

                      @if($file->name)
                          <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Customer Name</p>
                            <div class="pull-right">
                              <span class="label label-success">{{$file->name}}<span>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        @endif
                        @if($file->phone)
                          <div class="b-grey b-t p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Phone</p>
                            <div class="pull-right">
                              <span class="label label-success">{{$file->phone}}<span>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        @endif
                        @if($file->email)
                          <div class="b-grey b-t p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Email</p>
                            <div class="pull-right">
                              <span class="label label-success">{{$file->email}}<span>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        @endif
                      </div>
                      @endif
                      
                      <div class="col-lg-6  m-t-30">
                        <h5 class="">Vehicle Information</h5>
                        
                        @if($file->license_plate)
                          <div class=" b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">License Plate</p>
                            <div class="pull-right">
                              <span class="label label-success">{{$file->license_plate}}<span>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        @endif
                        @if($file->model_year)
                        <div class=" b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Model Year</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->model_year}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      @endif
                      @if($file->vin_number)
                      <div class="b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Vin Number</p>
                        <div class="pull-right">
                          <span class="label label-success">{{$file->vin_number}}<span>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    @endif

                    @if($file->file_type)
                    <div class="b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">File Type</p>
                      <div class="pull-right">
                        <span class="label label-success">{{$file->file_type}}<span>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    @endif

                        <div class="b-t b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Brand</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->brand}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Model</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->model}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Version</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->version}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Engine</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->engine}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
                        @if($file->ecu)
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">ECU</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->ecu}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @endif
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Gear Box</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->gear_box}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @if($file->getECUComment())
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <h5 class="pull-left">Engineer's Comments On ECU</h5>
                          <br>
                          <div class="m-l-10">
                            @if($file->getECUComment()){{$file->getECUComment()->notes}}@endif
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @endif

                        @if(Auth::user()->is_admin() or Auth::user()->is_head())
                          <div class="text-center m-t-20">                    
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('add-comments', [$vehicle->id, 'file='.$file->id])}}"><span class="bold">Go To Comments</span></a>
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('vehicle', $vehicle->id)}}"><span class="bold">Go To Vehicle</span></a>
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('edit-file', $file->id)}}"><span class="bold">Edit File</span></a>
                            
                              {{-- <form method="POST" action="{{route('delete-file')}}">
                                @csrf
                                <input type="hidden" value="{{$file->id}}" name="id"> --}}
                                <button type="button" class="btn btn-danger btn-delete btn-cons m-b-10" data-file_id={{$file->id}}><span class="bold">Delete File</span></button>
                              {{-- </form> --}}
                          </div>
                        @endif
                        
                      </div>
        
                      <div class="col-lg-6">
                        <h5 class="m-t-40">Reading Tool</h5>
        
                            
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Tool</p>
                          <div class="pull-right">
                              <img alt="{{$file->tool_id}}" width="50" height="" data-src-retina="{{ get_dropdown_image($file->tool_id) }}" data-src="{{ get_dropdown_image($file->tool_id) }}" src="{{ get_dropdown_image($file->tool_id) }}">
                              <span class="" style="top: 2px; position:relative;">{{ \App\Models\Tool::findOrFail( $file->tool_id )->name }}({{$file->tool_type}})</span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                     
        
                      <h5 class="m-t-40">Options And Credits</h5>

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'propose-options'))


                      @if($file->status == 'submitted')
                        <button id="btn-options-change" class="btn btn-success m-b-20">Change Options</button>
                      @endif

                      @endif
                        
                      
                        @if(\App\Models\Service::FindOrFail($file->stage_services->service_id))
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Stage</p>
                          <div class="pull-right">
                              <img alt="{{\App\Models\Service::FindOrFail($file->stage_services->service_id)->name}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon}}" data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}" src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}">
                              <span class="text-black" style="top: 2px; position:relative;">{{ \App\Models\Service::FindOrFail($file->stage_services->service_id)->name }}</span>
                              @php $stage = \App\Models\Service::FindOrFail($file->stage_services->service_id) @endphp
                              @if($file->front_end_id == 2)
                                  @if($file->tool_type == 'master')
                                    <span class="text-white label-danger label"> {{$stage->tuningx_credits}} </span>
                                  @else
                                    <span class="text-white label-danger label"> {{$stage->tuningx_slave_credits}} </span>
                                  @endif
                              @else
                                <span class="text-white label-danger label"> {{$stage->credits}} </span>
                              @endif
                              

                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @endif
                      

                      <div class="p-b-20">

                      @if(!$file->options_services()->get()->isEmpty())
                        <div class="b  b-grey p-l-20 p-r-20 p-t-10 p-b-10">
                          <p class="pull-left">Options</p>
                          <div class="clearfix"></div>
                        </div>
                        
                        @foreach($file->options_services()->get() as $option) 
                            @if(\App\Models\Service::where('id', $option->service_id)->first())
                              <div class="p-l-20 b-b b-grey b-t p-b-10 p-t-10 p-r-20"> 
                                <img alt="{{\App\Models\Service::where('id', $option->service_id)->first()->name}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}">
                                {{\App\Models\Service::where('id', $option->service_id)->first()->name}}  ({{\App\Models\Service::where('id', $option->service_id)->first()->vehicle_type}})
                                @php $option = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                                @if($file->front_end_id == 2)
                                  @if($file->tool_type == 'master')
                                    <span class="text-white label-danger label pull-right"> {{$option->optios_stage($file->stage_services->service_id)->first()->master_credits}} </span>
                                  @else
                                    <span class="text-white label-danger label pull-right"> {{$option->optios_stage($file->stage_services->service_id)->first()->slave_credits}} </span>
                                  @endif
                              @else
                                <span class="text-white label-danger label pull-right"> {{$option->credits}} </span>
                              @endif

                              </div>
                            @endif
                              
                            @if($comments)
                              @foreach($comments as $comment)
                                  
                                  @if($option->id == $comment->service_id)
                                    <div class="p-l-20 p-b-10 p-t-10"> 
                                      {{$comment->comments}}
                                    
                                    </div>
                                    <div class="p-l-20 p-b-10">Type: {{$comment->comment_type}}</div>
                                  @endif
                              @endforeach
                            @endif
                        @endforeach
                      @endif
                      
                      </div>
                      
                      @if($file->dtc_off_comments)
                      <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left text-danger">DTC OFF Comments</p>
                        <br>
                        <div class="m-l-20">
                          {{$file->dtc_off_comments}}
                        </div>
                        <div class="clearfix"></div>
                      </div>
                      @endif

                      @if($file->vmax_off_comments)
                      <div class="p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left text-danger">VMAX OFF Comments</p>
                        <br>
                        <div class="m-l-20">
                          {{$file->vmax_off_comments}}
                        </div>
                        <div class="clearfix"></div>
                      </div>
                      @endif
                     
                      <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Credits Paid</p>
                        <div class="pull-right">
                         
                          @if($file->assigned_from)
                            <span class="label label-danger">{{$file->subdealer_credits}}<span>
                          @else
                            <span class="label label-danger">{{$file->credits}}<span>
                          @endif
                        </div>
                        <div class="clearfix"></div>
                      </div>
        
                      </div>

                      

                      <div class="col-lg-6">
                        <h5 class="m-t-40">Uploaded Files</h5>

                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Revisions</p>
                          <div class="pull-right">
                           
                              <label class="label bg-info text-white">{{$file->files->count()}}</label>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                            @foreach($file->files_and_messages_sorted() as $message)
                              @if(isset($message['request_file']))
                                @if($message['engineer'] == 1)
                            <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                <p class="pull-left">{{$message['request_file']}}</p>
                                <div class="pull-right">
                                  @isset($message['type'])
                                 
                                 
                                  <a href="#" class="btn-sm btn-info btn-cons"> <span class="bold">{{$message['type']}}</span>
                                  </a>
                                  @endisset
                                    @if(!($file->front_end_id == 1 && $file->subdealer_group_id == NULL))
                                      @php
                                        $messageFile = \App\Models\RequestFile::findOrFail($message['id']);

                                        
                                      @endphp

                                      @if(count($messageFile->engineer_file_notes_have_unseen_messages))
                                      <span id="circle"></span>
                                      @endif
                                      <a target="_blank" href="{{route('support', $message['id'])}}" class="btn-sm btn-cons btn-info"><i class="fa fa-question text-white"></i> Support</a>
                                    @endif
                                    <a href="{{ route('download',[$message['file_id'], $message['request_file'], 0]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download</span>
                                    </a>
                                    <a href="#" class="btn-sm btn-cons btn-danger delete-uploaded-file" data-request_file_id="{{$message['id']}}"><i class="pg-trash text-white"></i></a>
                                </div>

                                <div class="clearfix"></div>
                                  @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id)
                                  <div>
                                    <p>Please click on "Download Encrypted" Button to download and test the system. This way user will get Encrypted file or you will get the error so that you can process the file, manually.</p>
                                  </div>
                                  <div class="text-center">
                                    <a href="{{ route('download-encrypted',[$message['file_id'], $message['request_file'], false]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download Encrypted</span>
                                    </a>
                                  </div>
                                  @endif
                                <div class="clearfix"></div>
                            </div>
        
                        @endif
                        @endif
                      @endforeach
                      </div>

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'propose-options'))


                      @if($file->stage_offer)

                      @php $proposedCredits = 0; @endphp

                      <div class="col-lg-6">
                        <h5 class="m-t-40">Proposed Stage and Options</h5>
                        
                          <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Stage</p>
                            <div class="pull-right">
                                <img alt="{{\App\Models\Service::FindOrFail($file->stage_offer->service_id)->name}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_offer->service_id)->icon}}" data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_offer->service_id)->icon }}" src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_offer->service_id)->icon }}">
                                <span class="text-black" style="top: 2px; position:relative;">{{ \App\Models\Service::FindOrFail($file->stage_offer->service_id)->name }}</span>
                                @php $stage = \App\Models\Service::FindOrFail($file->stage_offer->service_id) @endphp
                                @if($file->front_end_id == 2)
                                    @if($file->tool_type == 'master')
                                      <span class="text-white label-danger label"> {{$stage->tuningx_credits}} </span>
                                      @php $proposedCredits += $stage->tuningx_credits; @endphp
                                    @else
                                      <span class="text-white label-danger label"> {{$stage->tuningx_slave_credits}} </span>
                                      @php $proposedCredits += $stage->tuningx_slave_credits; @endphp
                                    @endif
                                @else
                                  <span class="text-white label-danger label"> {{$stage->credits}} </span>
                                  @php $proposedCredits += $stage->credits; @endphp
                                @endif
                                
                            </div>
                            <div class="clearfix"></div>
                          </div>

                          <div class="b-b  b-grey p-l-20 p-r-20 p-t-10 p-b-10">
                            <p class="pull-left">Options</p>
                            <div class="clearfix"></div>
                          </div>
                        
                          @foreach($file->options_offer as $option)
                              
                              @if(\App\Models\Service::FindOrFail($option->service_id))
                                <div class="p-l-20  p-r-20 b-b b-grey  p-t-10 p-b-10"> 
                                  <img alt="{{\App\Models\Service::FindOrFail($option->service_id)->name}}" width="40" height="40" 
                                  data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                  data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                  src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}">
                                  {{\App\Models\Service::FindOrFail($option->service_id)->name}}  
                                  @php $option1 = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                                  @if($file->front_end_id == 2)
                                    @if($file->tool_type == 'master')
                                      <span class="text-white label-danger label pull-right"> {{$option1->optios_stage($file->stage_services->service_id)->first()->master_credits}} </span>
                                      @php $proposedCredits += $option1->optios_stage($file->stage_services->service_id)->first()->master_credits @endphp
                                    @else
                                      <span class="text-white label-danger label pull-right"> {{$option1->optios_stage($file->stage_services->service_id)->first()->slave_credits}} </span>
                                      @php $proposedCredits += $option1->optios_stage($file->stage_services->service_id)->first()->slave_credits @endphp
                                    @endif
                                  @else
                                    <span class="text-white label-danger label pull-right"> {{$option1->credits}} </span>
                                    @php $proposedCredits += $option1->credits; @endphp
                                  @endif
                                </div>
                              @endif
                          @endforeach

                          <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Credits Proposed</p>
                            <div class="pull-right">
                             
                              
                                <span class="label label-warning text-black">{{$proposedCredits}}<span>
                              
                            </div>
                            <div class="clearfix"></div>
                          </div>

                          <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Credits Difference</p>
                            <div class="pull-right">
                             
                              
                                <span class="label label-info text-black">{{$file->credits-$proposedCredits}}<span>
                              
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        
                        </div>
                      @endif
                      @endif

                      {{-- <div class="col-xl-12">
                        <h5 class="m-t-40">Upload File</h5>
                        <!-- START card -->
                        <div class="card card-default">
                          <div class="card-header ">
                            <div class="card-title">
                              Drag n' drop uploader
                            </div>
                            <div class="tools">
                              <a class="collapse" href="javascript:;"></a>
                              <a class="config" data-toggle="modal" href="#grid-config"></a>
                              <a class="reload" href="javascript:;"></a>
                              <a class="remove" href="javascript:;"></a>
                            </div>
                          </div>
                          <div class="card-body no-scroll no-padding">
                            <form action="{{route('request-file-upload')}}" class="simple-dropzone dropzone no-margin">
                              @csrf
                              <input type="hidden" value="{{$file->id}}" name="file_id">
                              <div class="fallback">
                                <input name="file" type="file" />
                              </div>
                            </form>
                          </div>
                        </div>
                        <!-- END card -->
                      </div> --}}
                      
                      @if($file->status == 'submitted' || $file->status == 'completed')
                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file'))
                      <div class="col-xl-12 m-t-20">
                        <div class="card card-transparent flex-row">
                          <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-white" id="tab-3">

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                              @if($file->decoded_files)
                              <li class="nav-item">
                                <a href="#" class="active show" data-toggle="tab" data-target="#tab3hellowWorld">Encode</a>
                              </li>
                              @endif
                            @endif --}}

                            <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab3FollowUs" class="">Upload</a>
                            </li>
                            {{-- <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab3Inspire">Three</a>
                            </li> --}}
                          </ul>
                          <div class="tab-content bg-white full-width">

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3') --}}
                            
                           

                            <div class="tab-pane active show" id="tab3hellowWorld">
                              <div class="row column-seperation">
                                
                                <div class="col-xl-12 full-width">
                                  <h5 class="">Upload Decoded File to Encode</h5>
                                  <!-- START card -->
                                  <div class="card card-default">
                                    <div class="card-header ">
                                      <div class="card-title">
                                        Drag n' drop uploader
                                      </div>
                                      <div class="tools">
                                        <a class="collapse" href="javascript:;"></a>
                                        <a class="config" data-toggle="modal" href="#grid-config"></a>
                                        <a class="reload" href="javascript:;"></a>
                                        <a class="remove" href="javascript:;"></a>
                                      </div>
                                    </div>
                                    <div class="card-body no-scroll no-padding">
                                      <form action="{{route('encoded-file-upload')}}" id="encoded-dropzone-new-req{{$file->id}}" class=" dropzone no-margin">
                                        @csrf
                                        <input type="hidden" value="{{$file->id}}" name="file_id">
                                        @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id)
                                          <input type="hidden" value="1" name="encode">
                                            @if($file->decoded_file)
                                              @if($file->decoded_file->extension == 'dec')
                                                <input type="hidden" value="dec" name="encoding_type">
                                              @else
                                                <input type="hidden" value="micro" name="encoding_type">
                                              @endif
                                            @endif
                                          @else
                                            <input type="hidden" value="0" name="encode">
                                          @endif
                                       
                                        <div class="fallback">
                                          <input name="file" type="file" />
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                  <!-- END card -->
                                </div> 
                                    
                              </div>
                            </div>

                           
                            {{-- @endif
                            @endif --}}

                            {{-- <div class="tab-pane  @if(!$file->decoded_files) active show @endif" id="tab3FollowUs">
                              <div class="col-xl-12 full-width">
                                <h5 class="">Upload File</h5>
                                <!-- START card -->
                                <div class="card card-default">
                                  <div class="card-header ">
                                    <div class="card-title">
                                      Drag n' drop uploader
                                    </div>
                                    <div class="tools">
                                      <a class="collapse" href="javascript:;"></a>
                                      <a class="config" data-toggle="modal" href="#grid-config"></a>
                                      <a class="reload" href="javascript:;"></a>
                                      <a class="remove" href="javascript:;"></a>
                                    </div>
                                  </div>
                                  <div class="card-body no-scroll no-padding">
                                    <form action="{{route('request-file-upload')}}" class="simple-dropzone dropzone no-margin">
                                      @csrf
                                      <input type="hidden" value="{{$file->id}}" name="file_id">
                                      <input type="hidden" value="0" name="encode">
                                      <div class="fallback">
                                        <input name="file" type="file" />
                                      </div>
                                    </form>
                                  </div>
                                </div>
                                <!-- END card -->
                              </div> 
                            </div> --}}

                          </div>
                        </div>
                      </div>
                      @endif
                      @endif

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if(($file->front_end_id == 1 && $file->subdealer_group_id == NULL))
          <div class="tab-pane slide-left @if(Session::get('tab') == 'chat') active @endif" id="slide2{{$file->id}}">
            <div class="row">
              <div class="col-lg-12">
                <div class="widget-16 card no-border widget-loader-circle">
                  <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                    <div class="text-center">
                      <div class="card-title">
                          <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                          <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                        </div>
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">
                    <div class="view chat-view bg-white clearfix">
                      <!-- BEGIN Header  !-->
                      
                      <!-- END Header  !-->
                      <!-- BEGIN Conversation  !-->
                      @if(!empty($file->files_and_messages_sorted()))
          
                      <div class="chat-inner" id="my-conversation" style="overflow: scroll !important; height:500px;">
                        <!-- END From Me Message  !-->
                        <!-- BEGIN From Them Message  !-->
                        @foreach($file->files_and_messages_sorted() as $message)
                         
                          @if(isset($message['egnineers_internal_notes']))
                            @if($message['engineer'])
                            <div class="message clearfix">
                              <div class="chat-bubble bg-primary from-me text-white">
                                {{ $message['egnineers_internal_notes'] }} 
                                
                                <i data-note_id="{{$message['id']}}" data-message="{{$message['egnineers_internal_notes']}}" class="fa fa-edit m-l-20"></i> 
                                <i class="pg-trash delete-message" data-note_id="{{$message['id']}}"></i> 
                                <br>
                                @if(isset($message['engineers_attachement']))
                                  <div class="text-center m-t-10">
                                    <a href="{{route('download',[$message['file_id'], $message['engineers_attachement'], 0])}}" class="text-danger">Download</a>
                                  </div>
                                @endif
                                <br>
                                <small class="m-t-20" style="font-size: 8px; float:right">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small>
                              </div>
                            </div>
          
                            @else
                              <div class="message clearfix">
                                <div class="chat-bubble from-them bg-success">
                                    {{ $message['egnineers_internal_notes'] }}<br>
                                    @if(isset($message['engineers_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download',[$message['file_id'], $message['engineers_attachement'], 0])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
                                    <br>
                                    <br>
                                    <small class="m-t-20" style="font-size: 8px;float:right">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small>
                                </div>
                              </div>
                            @endif
                          @endif
                          @if(isset($message['file_url']))
                            
                            <div class="message clearfix">
                              <div class="chat-bubble bg-success from-them text-white">
                                {{ $message['file_url'] }}<br>
                                @if(isset($message['file_url_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download',[$message['file_id'], $message['file_url_attachement'], 0])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
                              </div>
                            </div>
          
                            
                          @endif
                        @endforeach
                        <!-- END From Them Message  !-->
                        <!-- BEGIN From Me Message  !-->
                        
                        {{-- <div class="message clearfix">
                          <div class="chat-bubble from-me">
                            Did you check out Pages framework  ?
                          </div>
                        </div> --}}
          
                      </div>
                      @endif
                      <!-- BEGIN Conversation  !-->
                      <!-- BEGIN Chat Input  !-->
                      <div class="b-t b-grey bg-white clearfix p-l-10 p-r-10 text-center">
                        <form method="POST" action="{{ route('file-engineers-notes') }}" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" value="{{$file->id}}" name="file_id">
                        <div class="row">
                            <div class="col-6 no-padding">
                              <input type="text" name="egnineers_internal_notes" class="form-control chat-input" data-chat-input="" data-chat-conversation="#my-conversation" placeholder="Reply to cusotmer." required>
                              @error('egnineers_internal_notes')
                                      <p class="text-danger" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </p>
                              @enderror
                             
                            </div>
                            <div  class="col-4 no-padding"> 
                              <input class="m-t-10" type="file" name="engineers_attachement" style="float: :left;">
                            </div>
                            <div class="col-2 link text-master m-t-15 p-l-10 b-l b-grey col-top">
                              <button class="btn btn-success" type="submit">Send</button>
                            </div>
                          
                        </div>
                      </form>
                      </div>
                      <!-- END Chat Input  !-->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'admin-tasks'))

            <div class="tab-pane slide-left" id="slide3{{$file->id}}">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                      <h4 class="m-t-20">Adminstrative Tasks</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  
                    <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">Assign This File to An Engineer</p>
                      <form action="{{route('assign-engineer')}}" method="POST">
                        @csrf
                        <input type="hidden" name="file_id" value="{{$file->id}}">
                        <div class="">
                          <select class="full-width" data-init-plugin="select2" name="assigned_to">
                            <option disabled >Not Assigned</option>
                            @foreach($engineers as $engineer)
                              <option @if(isset($file) && $file->assigned_to == $engineer->id) selected @endif value="{{$engineer->id}}">{{$engineer->name}}</option>
                            @endforeach
                          </select>
                          <div class="text-center m-t-20">                    
                            <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Assign Engineer</span></button>
                          </div>
                        </div>
                        
                      </form>
                      <div class="clearfix"></div>
                    </div>
                 
                  @if($file->status != 'rejected')
                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                    <p class="pull-left">File Status</p>
                    <form action="{{route('change-status-file')}}" method="POST">
                      @csrf
                      <input type="hidden" name="file_id" value="{{$file->id}}">
                      <div class="">
                        <select class="full-width" data-init-plugin="select2" name="status" id="select_status">
                            <option @if(isset($file) && $file->status == "submitted") selected @endif value="submitted">Submitted</option>
                            <option @if(isset($file) && $file->status == "rejected") selected @endif value="rejected">Rejected</option>
                            <option @if(isset($file) && $file->status == "completed") selected @endif value="completed">Completed</option>
                            <option @if(isset($file) && $file->status == "processing") selected @endif value="processing">Processing</option>
                            <option @if(isset($file) && $file->status == "on_hold") selected @endif value="on_hold">On Hold</option>
                        </select>
                        <div class="form-group m-t-10 hide" id="reason_to_reject">
                          <label>Reason To Reject</label>
                          <input type="text" class="form-control" name="reason_to_reject">
                        </div>
                        <div class="text-center m-t-20">                    
                          <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Update</span></button>
                        </div>
                      </div>
                      
                    </form>
                    <div class="clearfix"></div>
                  </div>
                  @endif
                  @if(Auth::user()->is_admin() or Auth::user()->is_head())
                    <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">Support Status</p>
                      <form action="{{route('change-support-status')}}" method="POST">
                        @csrf
                        <input type="hidden" name="file_id" value="{{$file->id}}">
                        <div class="">
                          <select class="full-width" data-init-plugin="select2" name="support_status">
                              <option disabled>Not Set</option>
                              <option @if(isset($file) && $file->support_status == "open") selected @endif value="open">Open</option>
                              <option @if(isset($file) && $file->support_status == "closed") selected @endif value="closed">Closed</option>
                          </select>
                          <div class="text-center m-t-20">                    
                            <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Update</span></button>
                          </div>
                        </div>
                        
                      </form>
                      <div class="clearfix"></div>
                    </div>
                  @endif
                  <br>
                </div>
              </div>
            </div>

            @endif

            <div class="tab-pane slide-left" id="slide4{{$file->id}}">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                      <h4 class="m-t-20">Logs</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="row" style="">

                @foreach($file->logs as $log)
                  <div class="col-12 col-xl-12 @if($log->type == 'error') bg-danger-light @else bg-success-light @endif text-white m-b-10 m-t-10 m-l-10" style="height: 50px;">
                    <p class="no-margin p-t-10 p-b-10">{{$log->message}}</p>
                  </div>
                @endforeach

              </div>
            </div>
           
            @if($file->tool_type == 'slave' && $file->tool_id != $kess3Label->id)
              <div class="tab-pane slide-left" id="slide5{{$file->id}}">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                    
                    </div>
                  </div>
                  <div class="card-body">
                    <h5>
                      Upload Decrypted File
                    </h5>
                    <form method="POST" action="{{route('search')}}" enctype="multipart/form-data" class="" role="form">
                      @csrf
                      <input type="hidden" name="file_id" value="{{$file->id}}">
                      <div class="form-group form-group-default required ">
                        <label>Decrypted File</label>
                        <input name="decrypted_file" type="file" class="form-control" required="">
                       
                      </div>
                      <div class="radio radio-success">
                        <input class="download_directly" type="radio" checked="checked" value="direct" name="download_directly" id="direct">
                        <label for="direct">Send Directly</label>
                        <input class="download_directly" type="radio"  value="download" name="download_directly" id="download">
                        <label for="download">Download with Custom Options</label>
                      </div>

                      <div class="stages-show hide">
                        <h5 class="m-t-20">Stages Options</h5>
                        @foreach($stages as $stage)
                          <div class="radio radio-success">
                            <input class="stages" type="radio" @if($file->stage_services->service_id == $stage->id) checked="checked" @endif value="{{$stage->id}}" name="custom_stage" id="{{$stage->id}}">
                            <label for="{{$stage->id}}">{{$stage->name}}</label>
                          </div>
                        @endforeach
                      </div>

                      <div class="options-show hide">
                      <h5 class="m-t-20">Custome Options</h5>
                      <div class="radio radio-success">
                         @if(!$file->options_services->isEmpty())
                          {{-- @foreach($file->options_services as $option) --}}
                            @if(!$options->isEmpty())
                              @foreach($options as $option)
                                <div class="checkbox check-success">
                                  <input @if(in_array($option->id, $selectedOptions)) checked @endif name="custom_options[]" type="checkbox" value="{{$option->id}}" id="{{$option->id}}">
                                  <label for="{{$option->id}}">{{$option->name}} - ({{$option->vehicle_type}})</label>
                                </div>
                              @endforeach
                            @endif
                          @else
                            <p>No Options.</p>
                          @endif
                      </div>
                      </div>

                      <button class="btn btn-success">Upload</button>
                    </form>
                  </div>
                </div>
              </div>
            @endif
        </div>
      </div>
    </div>

    <script>

window.onload = function() {

      let engineerFileDrop1 = new Dropzone("#encoded-dropzone-new-req{{$file->id}}", {
        accept: function(file, done) {
                  console.log(file);
                  if (file.type == "application/zip" || file.type == "application/x-rar") {
                      console.log('failed');
                      window.alert("Can not upload zip.");                
                  }
                  else{
                      done();
                  }
                  
              }
      });
      
      engineerFileDrop1.on("success", function(file) {

            console.log('mog');
      
            engineerFileDrop1.removeFile(file);
            
            location.reload();
          })
          .on("complete", function(file) {
            location.reload();
          }).on('error', function(e){
            
          });

        };
      
      </script>

    @endforeach
    
  </div>
</div>

@endif

    </div>
  </div>
</div>

<div class="modal fade slide-up disable-scroll" style="z-index: 9999;" id="editModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" action="{{route('edit-message')}}" method="POST">
            @csrf
            <input type="hidden" id="edit-modal-id" name="id" value="">
            <input type="hidden" name="file_id" value="{{$file->id}}">
            
            <div class="form-group-attached ">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Message</label>
                    <textarea id="edit-modal" name="message" required style="height: 100px;" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
         
          <div class="row">
            <div class="col-md-4 m-t-10 sm-m-t-10 text-center">
              <button type="submit" class="btn btn-success btn-block m-t-5">Edit Message</button>
            </div>
          </div>
        </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div class="modal fade slide-up disable-scroll" style="z-index: 9999;" id="engineerOptionsModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <p>File Credits: {{$file->credits}}</p>
          <p>Proposed Credits:<span id="proposed_credits">0</span></p>
          <p>Difference:<span id="credits_difference">0</span></p>
        </div>
        <div class="modal-body">
          <form role="form" action="{{route('add-options-offer')}}" method="POST">
            @csrf
            
            <input type="hidden" name="file_id" value="{{$file->id}}">
            
            <div class="form-group-attached ">
              <h5>Propose Stages and Options</h5>
              <div class="row">
                <div class="col-md-12">
                  <div class="">
                    <select class="full-width form-control" data-init-plugin="select2" name="proposed_stage" id="proposed_stage">
                      
                      @foreach($stages as $stage)
                        <option value="{{$stage->id}}" data-credits="@if($file->tool_type == 'master'){{$stage->tuningx_credits}}@else{{$stage->tuningx_slave_credits}}@endif" @if($file->stage_services->service_id == $stage->id) selected="selected" @endif>{{$stage->name}}</option>
                      @endforeach
                      
                    </select>
                </div>
                <div class="col-md-12">
                  <div class="">
                    
                    <select class=" full-width" data-init-plugin="select2" multiple name="proposed_options[]" id="proposed_options">
                      @foreach($options as $option1)
                        <option value="{{$option1->id}}" @if(in_array($option1->id, $selectedOptions)) selected="selected" @endif  >{{$option1->name}} ({{$option1->vehicle_type}})</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
         
          <div class="row">
            <div class="col-md-4 m-t-10 sm-m-t-10 text-center">
              <button type="submit" class="btn btn-success btn-block m-t-5">Propose</button>
            </div>
          </div>
        </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
    
@endsection

@section('pagespecificscripts')
  <script type="text/javascript">
  $(document).ready(function(){

    function calculate_proposed_credits(){

      let proposed_stage = $('#proposed_stage').val();
      let proposed_options = $('#proposed_options').val();
      let tool_type = '{{$file->tool_type}}';
      let file_credits = {{$file->credits}};
      let frontend_id = {{$file->front_end_id}};

      $.ajax({
            url: "/get_total_proposed_credits",
            type: "POST",
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'tool_type': tool_type,
                'proposed_stage': proposed_stage, 
                'proposed_options': proposed_options, 
                'frontend_id': frontend_id
            },
            success: function(proposed_credits) {
              console.log(proposed_credits);

              let difference = proposed_credits - file_credits;

              $('#proposed_credits').html(proposed_credits);
              $('#credits_difference').html(difference);
            }
        });

    }

    $(document).on('change', '#proposed_options', function(e){

      calculate_proposed_credits();

    });

    $(document).on('change', '#proposed_stage', function(e){

      calculate_proposed_credits();

    });

    $(document).on('click', '#btn-options-change', function(e){
      calculate_proposed_credits();
      $('#engineerOptionsModal').modal('show');
    });

    $(document).on('change', '#select_status', function(e){

      console.log($(this).val());

      if($(this).val() == 'rejected')
      {
        $('#reason_to_reject').removeClass('hide');
      }
      else{
        $('#reason_to_reject').addClass('hide');
      }


    });

    $(".download_directly").change(function(e){
      let val = $(this).val();

      if(val == 'direct'){
        $('.options-show').addClass('hide');
        $('.stages-show').addClass('hide');
      }
      else{
        $('.options-show').removeClass('hide');
        $('.stages-show').removeClass('hide');
      }
    });

    $(document).on('click', '.fa-edit', function(e){

      e.preventDefault();
      let note_id = $(this).data('note_id');
      let message = $(this).data('message');

      console.log(note_id+ ' '+ message);

      $('#edit-modal').val(message);
      $('#edit-modal-id').val(note_id);

      $('#editModal').modal('show');

    });

    $(document).on('click', '.delete-message', function(e){

    e.preventDefault();
    let note_id = $(this).data('note_id');

    const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'No, cancel!',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
        
        $.ajax({
                  url: "/delete-message",
                  type: "POST",
                  headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                  data: {
                      'note_id': note_id
                  },
                  success: function(d) {
                    swalWithBootstrapButtons.fire(
                      'Deleted!',
                      'Your Message has been deleted.',
                      'success'
                    );

                    location.reload();
                  }
              });

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            'Cancelled',
            'Message is safe :)',
            'error'
          )
        }
      });

    });

    $(document).on('click', '.btn-delete', function(e){
      e.preventDefault();

      let file_id = $(this).data('file_id');
      
  const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
  })

    swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
      console.log(file_id);
      $.ajax({
                url: "/delete_file",
                type: "POST",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'id': file_id
                },
                success: function(d) {
                  swalWithBootstrapButtons.fire(
                    'Deleted!',
                    'Your File has been deleted.',
                    'success'
                  );

                  window.location.href = '/files';  
                }
            });

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Uploaded file is safe :)',
          'error'
        )
      }
    });

    });

    $(document).on('click', '.delete-uploaded-file', function(e){
      e.preventDefault();

      let request_file_id = $(this).data('request_file_id');
      
  const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
  })

  swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
      console.log(request_file_id);
      $.ajax({
                url: "/delete-request-file",
                type: "POST",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'request_file_id': request_file_id
                },
                success: function(d) {
                  swalWithBootstrapButtons.fire(
                    'Deleted!',
                    'Your File has been deleted.',
                    'success'
                  );

                  location.reload();
                }
            });

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Uploaded file is safe :)',
          'error'
        )
      }
    });
      
    });
   
    // let engineerEncodedFileDrop= new Dropzone(".simple-dropzone", {});

    // engineerEncodedFileDrop.on("complete", function(file) {
    //   engineerEncodedFileDrop.removeFile(file);
    //   // location.reload();
    // });

    });
  </script>

<script>

  let engineerFileDrop= new Dropzone(".encoded-dropzone", {
    accept: function(file, done) {
              console.log(file);
              if (file.type == "application/zip" || file.type == "application/x-rar") {
                  console.log('failed');
                  window.alert("Can not upload zip.");                
              }
              else{
                  done();
              }
              
          }
  });
  
      engineerFileDrop.on("success", function(file) {
  
      engineerFileDrop.removeFile(file);
        
        location.reload();
      })
      .on("complete", function(file) {
        // location.reload();
      }).on('error', function(e){
        
      });
  
  </script>

@endsection