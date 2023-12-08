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

  .modal-open .select2-container {
    z-index: 9999;
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
    <div class="container-fluid container-fixed-lg bg-white m-t-50">
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
        
        <div class="tab-content bg-white" style="border-top: 1px solid rgba(0, 0, 0, 0.1); border-left: 1px solid rgba(0, 0, 0, 0.1);">
          <div class="tab-pane slide-left active" id="tab4hellowWorld">
            
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

          {{-- @php dd($file->subdealer_group_id); @endphp
          @if($file->subdealer_group_id == NULL)
          <li class="nav-item">
            <a href="#" data-toggle="tab" @if(Session::get('tab') == 'chat') class="active" @endif data-target="#slide2"><span>Chat and Support</span></a>
          </li>
          @endif --}}
          
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

          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide6"><span>Lua make file</span></a>
          </li>
          
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide7"><span>Lua actions</span></a>
          </li>
          
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide8"><span>Lua actions other databases</span></a>
          </li>          

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
                          
                          @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'download-client-file'))
                          
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
                      <div class="col-lg-6  m-t-30">
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

                      @if(get_engineers_permission(Auth::user()->id, 'customer-contact-information'))

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
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">ECU</p>
                          <div class="pull-right">
                            @if($file->ecu && $file->ecu != '')
                              <span class="label bg-warning">{{$file->ecu}}<span>
                            @else
                              <span class="label label-danger">NO ECU<span>
                            @endif
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Gear Box</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->gear_box}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @if( $file->getECUComment() )
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <h5 class="pull-left">Engineer's Comments On ECU</h5>
                          <br>
                          
                            <div class="m-l-10">
                              {{$file->getECUComment()->notes}}
                            </div>
                          
                          <div class="clearfix"></div>
                        </div>
                        @endif

                        
                          <div class="text-center m-t-20">  

                            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'see-comments'))

                              <a class="btn btn-success btn-cons m-b-10" href="{{route('add-comments', [$vehicle->id, 'file='.$file->id])}}"><span class="bold">Go To Comments</span></a>

                            @endif

                            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'view-vehicles'))

                            <a class="btn btn-success btn-cons m-b-10" href="{{route('vehicle', $vehicle->id)}}"><span class="bold">Go To Vehicle</span></a>

                            @endif

                            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-file'))

                              <a class="btn btn-success btn-cons m-b-10" href="{{route('edit-file', $file->id)}}"><span class="bold">Edit File</span></a>
                            
                            @endif

                            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-file'))
                              
                                <button type="button" class="btn btn-danger btn-delete btn-cons m-b-10" data-file_id={{$file->id}}><span class="bold">Delete File</span></button>

                            @endif
                            
                          </div>
                        
                        
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
                        <button data-file_id="{{$file->id}}" class="btn btn-success m-b-20 btn-options-change">Propose Options</button>
                      @endif

                      @if($file->status == 'completed')
                        <button class="btn btn-success m-b-20 btn-options-change-force" data-file_id="{{$file->id}}">Change Options</button>
                      @endif

                      @endif
                        
                      @if($file->stages)
                        @if(\App\Models\Service::where('name', $file->stages)->first())
                          <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Stage</p>
                            <div class="pull-right">
                                <img alt="{{$file->stages}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
                                <span class="text-black" style="top: 2px; position:relative;">{{ $file->stages }}</span>
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
                      @else
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
                      @endif

                      <div class="p-b-20">

                      @if(!$file->options_services()->get()->isEmpty())
                        <div class="b  b-grey p-l-20 p-r-20 p-t-10 p-b-10">
                          <p class="pull-left">Options</p>
                          <div class="clearfix"></div>
                        </div>
                        
                        @foreach($file->options_services()->get() as $option) 
                            @if(\App\Models\Service::where('id', $option->service_id)->first())
                              <div class="p-l-20 b-b b-grey b-t p-b-10 p-t-10"> 
                                <img alt="{{\App\Models\Service::where('id', $option->service_id)->first()->name}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}">
                                {{\App\Models\Service::where('id', $option->service_id)->first()->name}}  ({{\App\Models\Service::where('id', $option->service_id)->first()->vehicle_type}}) (@if(\App\Models\Service::findOrFail( $option->service_id )->active == 1) {{'ECU Tech'}} @elseif(\App\Models\Service::findOrFail( $option->service_id )->tuningx_active == 1) {{'TuningX'}} @endif)
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
                      @else
                              
                        <div class="b  b-grey p-l-20 p-r-20 p-t-10">
                          <p class="pull-left">Options</p>
                          <div class="clearfix"></div>
                        </div>
                      
                        @foreach($file->options_services as $option)
                            
                            @if(\App\Models\Service::FindOrFail($option->service_id))
                              <div class="p-l-20 b-b b-grey"> 
                                <img alt="{{\App\Models\Service::FindOrFail($option->service_id)->name}}" width="40" height="40" 
                                data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}">
                                {{\App\Models\Service::FindOrFail($option->service_id)->name}}  
                              </div>
                            @endif
                            @if($comments)
                              @foreach($comments as $comment)
                                  @if(\App\Models\Service::FindOrFail($option->service_id)->name == $comment->option)
                                    <div class="p-l-20 p-b-10"> 
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
                      <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
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
                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file'))
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
                                         
                              <?
                                $madeproject = DB::table('lua_make_project')
                                ->where('requestfile', $message['id'])
                                ->limit(1)
                                ->select('id', 'orifile', 'modfile', 'name','requestfile','olsname')
                                ->first();
                                
                                
                                if(!empty($madeproject)){
                                  ?>
                                
                                  <p class="pull-right">
                                     <?
                                     echo $madeproject->olsname;
                                     ?>
                                  </p>  
                                  <?
                                }else{
                                  
                                  
                                $file_path = $file->file_path; // Replace this with the actual file path
                                
                                // Get the file extension
                                $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
                                
                                // Check if the file extension is "slave"
                                  if ($file_extension !== "slave") {
                                  ?>
                                
                                  <p class="pull-right">
                                    <a href="#" class="btn-sm btn-info btn-cons makeproject" id="makeproject" data-requestfileid="<? echo $message['id'];?>"  data-moddedfile="<? echo $message['request_file'];?>" data-original="<? echo $file->file_attached;?>" data-path="<? echo $file->file_path;?>">
                                      Make project and set ok
                                    </a>
                                  </p>
                                  <?
                                
                                  } else {
                                  $file_path = $message['request_file']; // Replace this with the actual file path
                                  $new_extension = "bin";
                                  
                                  // Use pathinfo to extract the file's base name
                                  $file_info = pathinfo($file_path);
                                  $base_name = $file_info['filename'];
                                  
                                  // Concatenate the new extension
                                  $new_file_path = $file_info['dirname'] . '/' . $base_name . '.' . $new_extension;
                                  
                                  $file_path = $file->file_attached; // Replace this with the actual file path
                                  $new_extension = "bin";
                                  
                                  // Use pathinfo to extract the file's base name
                                  $file_info = pathinfo($file_path);
                                  $base_name = $file_info['filename'];
                                  
                                  // Concatenate the new extension
                                  $new_file_path_ori = $file_info['dirname'] . '/' . $base_name . '.' . $new_extension;
                                  
                                  ?>
                                
                                  <p class="pull-right">
                                    <a href="#" class="btn-sm btn-info btn-cons makeproject" id="makeproject" data-requestfileid="<? echo $message['id'];?>"  data-moddedfile="<? echo $new_file_path;?>" data-original="<? echo $new_file_path_ori;?>" data-path="<? echo $file->file_path;?>">
                                      Make project and set ok
                                    </a>
                                  </p>  
                                
                                  <?
                                  }                 
                                  
                                  
                                }
                                
                                ?>
                                
                                <?
                                  if($message['visible'] == "0"){
                                  ?>
                                
                                <p class="pull-right">
                                  <a href="#" class="btn-sm btn-success btn-cons m-b-10" id="setvisible" data-id="<? echo  $message['id'];?>">
                                    set visible
                                  </a>
                                </p>
                                
                                  <?
                                  }
                                ?>
                                <br/>
                                                <?
                                                $data = json_decode($message['lua_command'], true);
                                                
                                                if ($message['lua_command'] === null){
                                                  
                                                }else{
                                                    foreach ($data as $item) {
                                                      ?>
                                                        <p class="pull-left"><? echo $item['mod'] . ' => ' . $item['name'];?></p>

                                    <br/>
                                                        <?
                                                    }
                                                  }
                                                ?>
                                                
                                                <?
                                                            $data = json_decode($message['lua_command_fdb'], true);
                                                            
                                                            if ($message['lua_command_fdb'] === null){
                                                              
                                                            }else{
                                                                foreach ($data as $item) {
                                                                  ?>
                                                                    <p class="pull-left"><? echo $item['mod'] . ' => ' . $item['name'];?><b> FDB FILE</b></p>
                                                                    <?
                                                                }
                                                              }
                                                            ?>                                                  
                                
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
                                    
                                    <?
                                      if($message['visible'] == "0"){
                                      ?>
                                    
                                      <a href="#" class="btn-sm btn-success btn-cons m-b-10" id="setvisible" data-id="<? echo  $message['id'];?>">
                                        set visible
                                      </a>
                                    
                                      <?
                                      }
                                    ?>                                    
                                    
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

                      @endif

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

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                            @if($file->decoded_files) --}}

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
            
            
            
            
            
            
           
                       
                        <div class="tab-pane slide-left" id="slide6">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{ $file->engine }} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                                  <h4 class="m-t-20">Lua</h4>
                                </div>
                              </div>
                              
                              <div class="clearfix"></div>
                          </div>
                          <div class="row" style="">
                            <div class="col-md-3">
                              <?
                                $mods = array();
                              ?>
                              
                                    @if($file->stages)
                                    @if(\App\Models\Service::where('name', $file->stages)->first())
                                      <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                        <div class="pull-right">
                                            <img alt="{{$file->stages}}" width="33" height="" data-src-retina="{{ url('icons2').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons2').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons2').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
                                            <span class="text-black" style="top: 2px; position:relative;">{{ $file->stages }}</span>
                                            
                                            <?
                                              $value = \App\Models\Service::FindOrFail($file->stage_services->service_id)->label;
                                            
                                              array_push($mods, $value);
                                            ?>                                
                                        </div>
                                        <div class="clearfix"></div>
                                      </div>
                                    @endif
                                  @else
                                    @if(\App\Models\Service::FindOrFail($file->stage_services->service_id))
                                    <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                      <div class="pull-right">
                                          <img alt="{{\App\Models\Service::FindOrFail($file->stage_services->service_id)->name}}" width="33" height="" data-src-retina="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon}}" data-src="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}" src="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}">
                                          <span class="text-black" style="top: 2px; position:relative;">{{ \App\Models\Service::FindOrFail($file->stage_services->service_id)->name }}</span>
                                          
                                          <?
                                            $value = \App\Models\Service::FindOrFail($file->stage_services->service_id)->label;
                                          
                                            array_push($mods, $value);
                                          ?>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    @endif
                                  @endif
                                  
                                 @if(!$file->options_services()->get()->isEmpty())
                                    <div class="b  b-grey p-l-20 p-r-20 p-t-10 p-b-10">
                                    </div>
                                    
                                    @foreach($file->options_services()->get() as $option) 
                                        @if(\App\Models\Service::where('id', $option->service_id)->first())
                                          <div class="p-l-20 b-b b-grey b-t p-b-10 p-t-10"> 
                                            <img alt="{{\App\Models\Service::where('id', $option->service_id)->first()->name}}" width="40" height="40" data-src-retina="{{ url('icons2').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" data-src="{{ url('icons2').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" src="{{ url('icons2').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}">
                                            {{\App\Models\Service::where('id', $option->service_id)->first()->label}}
                                            <?
                                            $value = \App\Models\Service::where('id', $option->service_id)->first()->label;
            
                                            array_push($mods, $value);
                                            ?>
                                          </div>
                                        @endif
                                        @if($comments)
                                          @foreach($comments as $comment)
                                  
                                              @if($option->service_id == $comment->service_id)
                                                <div class="p-l-20 p-b-10 p-t-10"> 
                                                  {{$comment->comments}}
                                                
                                                </div>
                                                <div class="p-l-20 p-b-10">Type: {{$comment->comment_type}}</div>
                                              @endif
                                          @endforeach
                                        @endif
                                    @endforeach
                                  @else
                                          
                                    <div class="b  b-grey p-l-20 p-r-20 p-t-10">
                                    </div>
                                  
                                    @foreach($file->options_services as $option)
                                        
                                        @if(\App\Models\Service::FindOrFail($option->service_id))
                                          <div class="p-l-20 b-b b-grey"> 
                                            <img alt="{{\App\Models\Service::FindOrFail($option->service_id)->name}}" width="40" height="40" 
                                            data-src-retina="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                            data-src="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                            src="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}">
                                            {{\App\Models\Service::FindOrFail($option->service_id)->label}}  
                                            <?
                                            $value = \App\Models\Service::FindOrFail($option->service_id)->label;
                                            
                                            array_push($mods, $value);
                                            ?>                              </div>
                                        @endif
                                        @if($comments)
                                          @foreach($comments as $comment)
                                              @if(\App\Models\Service::FindOrFail($option->service_id)->name == $comment->option)
                                                <div class="p-l-20 p-b-10"> 
                                                  {{$comment->comments}}
                                                
                                                </div>
                                                <div class="p-l-20 p-b-10">Type: {{$comment->comment_type}}</div>
                                              @endif
                                          @endforeach
                                        @endif
                                    @endforeach
                                  
                                  @endif                      
                                  
                                  
                            </div>
                            <div class="col-md-4">
<?php
                            $servername = "127.0.0.1";
                            $username = "admin_ecu_portal";
                            $password = "e24BTBDTQMRBmC";
                            $dbname = "admin_ecu_portal_db";
                            
                            // Create a PDO instance
                            try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                // Query to get the latest version from the table 'lua_versions' where file_id = 1
                                $query = "SELECT * FROM lua_versions WHERE File_Id = " . $file->id . " ORDER BY Id DESC LIMIT 1";
                            
                                // Execute the query
                                $result = $conn->query($query);
                            
                                // Fetch the result as an associative array
                                $latestVersion = $result->fetch(PDO::FETCH_ASSOC);
                            
                                // Declare and initialize the $arrayversionslua variable as an empty array
                                $arrayversionslua = [];
                            
                                // Display the result
                                if ($latestVersion) {
                                    $arrayversionslua = json_decode($latestVersion['Respons'], true);
                                    $jsonError = json_last_error();
                                    $jsonErrorMsg = json_last_error_msg();
                            
                                    if ($jsonError !== JSON_ERROR_NONE) {
                                        echo "JSON Error: $jsonErrorMsg (Code: $jsonError)";
                                    }
                            
                                    if ($arrayversionslua === null) {
                                        echo 'error decoding';
                                    } else {
                                        foreach ($arrayversionslua as $arrayversionlua) {
                                            
                                            ?>
                                            <div class="col-lg-12">
                                                <h5>
                                                    <?php
                                                    echo $arrayversionlua['name'] . ' // ' . $arrayversionlua['percentage'];
                                                    ?>
                            
                                                </h5>
                                                <?php
                                                foreach ($arrayversionlua as $key => $value) {
                                                    if (is_numeric($key) && $value !== 'Original') {
                                                        ?>
                                                        <p class="pull-left"><?php echo $value; ?></p>
                                                        <div class="clearfix lijn"></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                            
                                            </div>
                            
                                            <?php
                            
                                        }
                                    }
                                } else {
                                    echo "No Lua versions found with file_id 1.";
                                }
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }
                            
                            // Close the connection
                            $conn = null;
                            ?>

                              
                              
                              
                              
                            </div>
                            <div class="col-md-5">
                              <h4>Make new version</h4>
                                  <?
                                  foreach ($mods as $mod){
                                    ?>
                                      <div class="col-md-12">
                                        <h5>
                                      <?
                                      print($mod);
                                      ?>
                                        </h5>
            
                                    <?
            
                                    if ($arrayversionslua === null) {
                                          // Handle JSON decoding error
                                      } else {
                                        ?>
                                        <select name="makelua[]" class="form-select">
                                          <option value="">Nothing</option>
                                          <?
                                          foreach ($arrayversionslua as $arrayversionlua){
                                            ?>
                                                
                                                
                                              <?
                                              foreach ($arrayversionlua as $key => $value) {
                                                  if (is_numeric($key) && $value !== 'Original') {
                                                    $modifiedString = str_replace('/', '-', $value);
                                                      ?>
                                                      <option value="<? echo $mod;?> // <? echo $arrayversionlua['name'];?> // <? echo $key;?>">
                                                      <?php echo $arrayversionlua['name'].' // '.$arrayversionlua['percentage'].'% // '.$modifiedString;?>
                                                      </option>
                                                      <?
                                                  }
                                              }                                
                                              ?>
                                              
                                            
                                            <?
                                            
                                          }
                                          
                                      }                        
                                      ?>
                                        </select>
                                      
                                      </div>
            
                                    <?
                                  }
                                  ?>
                                  <div class="col-md-12">
                                    <h5>Send as version</h5>
                                    <input type="radio" name="sendversion" value="1" id="sendversion">
                                    <label>Yes</label>
                                    <input type="radio" name="sendversion" value="0" id="sendversion">
                                    <label>No</label>                        
                                  </div>
                                  <div class="col-md-12">
                                    <h5>Name for version</h5>
                                    <input type="text" name="nameforluacreation" id="nameforluacreation" value=""/><br/>
                                    <button id="submitButton">Submit</button>
                                  </div>
                              
                            </div>
                          </div>
                        </div>           
                       
                        <div class="tab-pane slide-left" id="slide7">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{ $file->engine }} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                                  <h4 class="m-t-20">Lua</h4>
                                </div>
                              </div>
                              
                              <div class="clearfix"></div>
                          </div>
                          <div class="row" style="">
                            <div class="col-md-12">
                              <h2>Restart actions this file</h2>
                              
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="getallversions"><span class="bold">Get all versions</span></a>
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="restartall"><span class="bold">Get all versions and retry lua</span></a>
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="Make other version"><span class="bold">Make other version</span></a>
                              
                              
                            </div>
                            
                            <div class="col-md-12">
                              <h2>Make project</h2>
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="copyaversiontoalloriginals"><span class="bold">Copy a project version to all originals</span></a>
                            </div>                
                            
                            <div class="col-md-12">
                              <h2>Restart actions all files</h2>
                              
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="getallversionsalldefault"><span class="bold">Get all versions default database</span></a>
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="getallversionsalldefaultFDB"><span class="bold">Get all versions FDB database</span></a>
                              
                              
                            </div>                
                            
                            
                          </div>
                        </div>                      
                       
                       
                       
                       
                        <div class="tab-pane slide-left" id="slide8">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{ $file->engine }} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                                  <h4 class="m-t-20">Lua</h4>
                                </div>
                              </div>
                              
                              <div class="clearfix"></div>
                          </div>
                          <div class="row" style="">
                            <div class="col-md-12">
                              <h2>Restart actions</h2>
                              
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="getallversionsdatabase"><span class="bold">Get all versions from FDB</span></a>
                              
                              
                            </div>
            
                            <div class="col-md-12">
                              <h2>Make version FDB</h2>
            <?
                              $uncheckedRecords2 = DB::table('lua_versions_others')
                                  ->where('dbname', 'Filesdatabase')
                                  ->where('File_id', $file->id) // Replace $file->id with the actual file ID you're searching for
                                  ->orderBy('id', 'desc') // Order the results by id in descending order
                                  ->select('id', 'dbname', 'File_id', 'Respons')
                                  ->first();
                              
                              if ($uncheckedRecords2) {
                                  $jsonResponse = $uncheckedRecords2->Respons; // Access the 'Respons' property
                              
                                  // Parse the JSON response
                                  $data = json_decode($jsonResponse, true);
                              
                                  // Initialize the maximum dynamic field index
                                  $maxIndex = 0;
                                  
                                  // Determine the maximum dynamic field index from the JSON data
                                  foreach ($data as $row) {
                                      foreach ($row as $key => $value) {
                                          if (is_numeric($key) && $key > $maxIndex) {
                                              $maxIndex = $key;
                                          }
                                      }
                                  }
                              
                                  // Start generating HTML table
                                  echo '<table>';
                                  echo '<tr><th>Name</th><th>Percentage</th>';
                              
                                  // Generate headers for dynamic fields
                                  for ($i = 0; $i <= $maxIndex; $i++) {
                                      echo '<th>Version ' . $i . '</th>';
                                  }
                              
                                  echo '</tr>';
                              
                                  // Iterate through the data and populate table rows
                                  foreach ($data as $row) {
                                      echo '<tr>';
                                      echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                      echo '<td>' . htmlspecialchars($row['percentage']) . '</td>';
                              
                                      // Populate dynamic fields
                                      for ($i = 0; $i <= $maxIndex; $i++) {
                                          if (isset($row[$i])) {
                                              echo '<td>' . htmlspecialchars($row[$i]) . '</td>';
                                          } else {
                                              echo '<td></td>';
                                          }
                                      }
                              
                                      echo '</tr>';
                                  }
                              
                                  // Close the table
                                  echo '</table>';
                              } else {
                                  echo "No records found.";
                              }
                              ?>
                                  
                                  
                                </div>
                                                
                              
                            </div>
                            
                            
            
            
            
                            <div class="col-md-12">
                              <h4>Make new version</h4>
                                  <?
                                  if ($uncheckedRecords2){
                                    foreach ($mods as $mod){
                                    ?>
                                      <div class="col-md-12">
                                        <h5>
                                      <?
                                      print($mod);
                                      ?>
                                        </h5>
                            
                                    <?
                            
                                    if ($data === null) {
                                          // Handle JSON decoding error
                                      } else {
                                        ?>
                                        <select name="makelua2[]" class="form-select">
                                          <option value="">Nothing</option>
                                          <?
                                          foreach ($data as $arrayversionlua){
                                            ?>
                                                
                                                
                                              <?
                                              foreach ($arrayversionlua as $key => $value) {
                                                  if (is_numeric($key) && $value !== 'Original') {
                                                    $modifiedString = str_replace('/', '-', $value);
                                                      ?>
                                                      <option value="<? echo $mod;?> // <? echo $arrayversionlua['name'];?> // <? echo $key;?>">
                                                      <?php echo $arrayversionlua['name'].' // '.$arrayversionlua['percentage'].'% // '.$modifiedString;?>
                                                      </option>
                                                      <?
                                                  }
                                              }                                
                                              ?>
                                              
                                            
                                            <?
                                            
                                          }
                                          
                                      }                        
                                      ?>
                                        </select>
                                      
                                      </div>
                            
                                    <?
                                    }
                                  }
                                  ?>
                                  <div class="col-md-12">
                                    <h5>Send as version</h5>
                                    <input type="radio" name="sendversion2" value="1" id="sendversion2">
                                    <label>Yes</label>
                                    <input type="radio" name="sendversion2" value="0" id="sendversion2">
                                    <label>No</label>                        
                                  </div>
                                  <div class="col-md-12">
                                    <h5>Name for version</h5>
                                    <input type="text" name="nameforluacreation2" id="nameforluacreation2" value=""/><br/>
                                    <button id="submitButtonFDB">Submit</button>
                                  </div>
                              
                            </div>

                            
                          </div>
                        </div>                      
                                   

        </div>


    @if($file->new_requests)
    </div>
    @foreach($file->new_requests as $file)
    
    <div class="tab-pane slide-left" id="tab4FollowUs">


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

          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide6{{$file->id}}"><span>Lua make file</span></a>
          </li>
          
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide7{{$file->id}}"><span>Lua actions</span></a>
          </li>
          
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide8{{$file->id}}"><span>Lua actions other databases</span></a>
          </li>          

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
                          
                          @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'download-client-file'))
                          
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
                      <div class="col-lg-6  m-t-30">
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

                      @if(get_engineers_permission(Auth::user()->id, 'customer-contact-information'))

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
        
                        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">ECU</p>
                          <div class="pull-right">
                            @if($file->ecu && $file->ecu != '')
                              <span class="label bg-warning">{{$file->ecu}}<span>
                            @else
                              <span class="label label-danger">NO ECU<span>
                            @endif
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        
        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Gear Box</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->gear_box}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @if( $file->getECUComment() )
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <h5 class="pull-left">Engineer's Comments On ECU</h5>
                          <br>
                          
                            <div class="m-l-10">
                              {{$file->getECUComment()->notes}}
                            </div>
                          
                          <div class="clearfix"></div>
                        </div>
                        @endif

                        
                          <div class="text-center m-t-20">                    
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('add-comments', [$vehicle->id, 'file='.$file->id])}}"><span class="bold">Go To Comments</span></a>
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('vehicle', $vehicle->id)}}"><span class="bold">Go To Vehicle</span></a>
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('edit-file', $file->id)}}"><span class="bold">Edit File</span></a>
                            
                              
                                <button type="button" class="btn btn-danger btn-delete btn-cons m-b-10" data-file_id={{$file->id}}><span class="bold">Delete File</span></button>
                              
                          </div>
                        
                        
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
                        <button data-file_id="{{$file->id}}" class="btn btn-success m-b-20 btn-options-change">Propose Options</button>
                      @endif

                      @if($file->status == 'completed')
                        <button class="btn btn-success m-b-20 btn-options-change-force" data-file_id="{{$file->id}}">Change Options</button>
                      @endif

                      @endif
                        
                      @if($file->stages)
                        @if(\App\Models\Service::where('name', $file->stages)->first())
                          <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                            <p class="pull-left">Stage</p>
                            <div class="pull-right">
                                <img alt="{{$file->stages}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
                                <span class="text-black" style="top: 2px; position:relative;">{{ $file->stages }}</span>
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
                      @else
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
                      @endif

                      <div class="p-b-20">

                      @if(!$file->options_services()->get()->isEmpty())
                        <div class="b  b-grey p-l-20 p-r-20 p-t-10 p-b-10">
                          <p class="pull-left">Options</p>
                          <div class="clearfix"></div>
                        </div>
                        
                        @foreach($file->options_services()->get() as $option) 
                            @if(\App\Models\Service::where('id', $option->service_id)->first())
                              <div class="p-l-20 b-b b-grey b-t p-b-10 p-t-10"> 
                                <img alt="{{\App\Models\Service::where('id', $option->service_id)->first()->name}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}">
                                {{\App\Models\Service::where('id', $option->service_id)->first()->name}}  ({{\App\Models\Service::where('id', $option->service_id)->first()->vehicle_type}}) (@if(\App\Models\Service::findOrFail( $option->service_id )->active == 1) {{'ECU Tech'}} @elseif(\App\Models\Service::findOrFail( $option->service_id )->tuningx_active == 1) {{'TuningX'}} @endif)
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
                      @else
                              
                        <div class="b  b-grey p-l-20 p-r-20 p-t-10">
                          <p class="pull-left">Options</p>
                          <div class="clearfix"></div>
                        </div>
                      
                        @foreach($file->options_services as $option)
                            
                            @if(\App\Models\Service::FindOrFail($option->service_id))
                              <div class="p-l-20 b-b b-grey"> 
                                <img alt="{{\App\Models\Service::FindOrFail($option->service_id)->name}}" width="40" height="40" 
                                data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}">
                                {{\App\Models\Service::FindOrFail($option->service_id)->name}}  
                              </div>
                            @endif
                            @if($comments)
                              @foreach($comments as $comment)
                                  @if(\App\Models\Service::FindOrFail($option->service_id)->name == $comment->option)
                                    <div class="p-l-20 p-b-10"> 
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
                      <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
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
                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file'))

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
                                         
                              <?
                                $madeproject = DB::table('lua_make_project')
                                ->where('requestfile', $message['id'])
                                ->limit(1)
                                ->select('id', 'orifile', 'modfile', 'name','requestfile','olsname')
                                ->first();
                                
                                
                                if(!empty($madeproject)){
                                  ?>
                                
                                  <p class="pull-right">
                                     <?
                                     echo $madeproject->olsname;
                                     ?>
                                  </p>  
                                  <?
                                }else{
                                  
                                  
                                $file_path = $file->file_path; // Replace this with the actual file path
                                
                                // Get the file extension
                                $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
                                
                                // Check if the file extension is "slave"
                                  if ($file_extension !== "slave") {
                                  ?>
                                
                                  <p class="pull-right">
                                    <a href="#" class="btn-sm btn-info btn-cons makeproject" id="makeproject" data-requestfileid="<? echo $message['id'];?>"  data-moddedfile="<? echo $message['request_file'];?>" data-original="<? echo $file->file_attached;?>" data-path="<? echo $file->file_path;?>">
                                      Make project and set ok
                                    </a>
                                  </p>
                                  <?
                                
                                  } else {
                                  $file_path = $message['request_file']; // Replace this with the actual file path
                                  $new_extension = "bin";
                                  
                                  // Use pathinfo to extract the file's base name
                                  $file_info = pathinfo($file_path);
                                  $base_name = $file_info['filename'];
                                  
                                  // Concatenate the new extension
                                  $new_file_path = $file_info['dirname'] . '/' . $base_name . '.' . $new_extension;
                                  
                                  $file_path = $file->file_attached; // Replace this with the actual file path
                                  $new_extension = "bin";
                                  
                                  // Use pathinfo to extract the file's base name
                                  $file_info = pathinfo($file_path);
                                  $base_name = $file_info['filename'];
                                  
                                  // Concatenate the new extension
                                  $new_file_path_ori = $file_info['dirname'] . '/' . $base_name . '.' . $new_extension;
                                  
                                  ?>
                                
                                  <p class="pull-right">
                                    <a href="#" class="btn-sm btn-info btn-cons makeproject" id="makeproject" data-requestfileid="<? echo $message['id'];?>"  data-moddedfile="<? echo $new_file_path;?>" data-original="<? echo $new_file_path_ori;?>" data-path="<? echo $file->file_path;?>">
                                      Make project and set ok
                                    </a>
                                  </p>  
                                
                                  <?
                                  }                 
                                  
                                  
                                }
                                
                                ?>
                                
                                <?
                                  if($message['visible'] == "0"){
                                  ?>
                                
                                <p class="pull-right">
                                  <a href="#" class="btn-sm btn-success btn-cons m-b-10" id="setvisible" data-id="<? echo  $message['id'];?>">
                                    set visible
                                  </a>
                                </p>
                                
                                  <?
                                  }
                                ?>
                                <br/>
                                                <?
                                                $data = json_decode($message['lua_command'], true);
                                                
                                                if ($message['lua_command'] === null){
                                                  
                                                }else{
                                                    foreach ($data as $item) {
                                                      ?>
                                                        <p class="pull-left"><? echo $item['mod'] . ' => ' . $item['name'];?></p>

                                    <br/>
                                                        <?
                                                    }
                                                  }
                                                ?>
                                                
                                                <?
                                                            $data = json_decode($message['lua_command_fdb'], true);
                                                            
                                                            if ($message['lua_command_fdb'] === null){
                                                              
                                                            }else{
                                                                foreach ($data as $item) {
                                                                  ?>
                                                                    <p class="pull-left"><? echo $item['mod'] . ' => ' . $item['name'];?><b> FDB FILE</b></p>
                                                                    <?
                                                                }
                                                              }
                                                            ?>                                                  
                                
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
                                    
                                    <?
                                      if($message['visible'] == "0"){
                                      ?>
                                    
                                      <a href="#" class="btn-sm btn-success btn-cons m-b-10" id="setvisible" data-id="<? echo  $message['id'];?>">
                                        set visible
                                      </a>
                                    
                                      <?
                                      }
                                    ?>                                    
                                    
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

                      @endif

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

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                            @if($file->decoded_files) --}}

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
                                      <form action="{{route('encoded-file-upload')}}" id="encoded-dropzone-new-req{{$file->id}}" class="dropzone no-margin">
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
            
            
            
            
            
            
           
                       
                        <div class="tab-pane slide-left" id="slide6{{$file->id}}">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{ $file->engine }} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                                  <h4 class="m-t-20">Lua</h4>
                                </div>
                              </div>
                              
                              <div class="clearfix"></div>
                          </div>
                          <div class="row" style="">
                            <div class="col-md-3">
                              <?
                                $mods = array();
                              ?>
                              
                                    @if($file->stages)
                                    @if(\App\Models\Service::where('name', $file->stages)->first())
                                      <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                        <div class="pull-right">
                                            <img alt="{{$file->stages}}" width="33" height="" data-src-retina="{{ url('icons2').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons2').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons2').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
                                            <span class="text-black" style="top: 2px; position:relative;">{{ $file->stages }}</span>
                                            
                                            <?
                                              $value = \App\Models\Service::FindOrFail($file->stage_services->service_id)->label;
                                            
                                              array_push($mods, $value);
                                            ?>                                
                                        </div>
                                        <div class="clearfix"></div>
                                      </div>
                                    @endif
                                  @else
                                    @if(\App\Models\Service::FindOrFail($file->stage_services->service_id))
                                    <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                      <div class="pull-right">
                                          <img alt="{{\App\Models\Service::FindOrFail($file->stage_services->service_id)->name}}" width="33" height="" data-src-retina="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon}}" data-src="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}" src="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}">
                                          <span class="text-black" style="top: 2px; position:relative;">{{ \App\Models\Service::FindOrFail($file->stage_services->service_id)->name }}</span>
                                          
                                          <?
                                            $value = \App\Models\Service::FindOrFail($file->stage_services->service_id)->label;
                                          
                                            array_push($mods, $value);
                                          ?>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    @endif
                                  @endif
                                  
                                 @if(!$file->options_services()->get()->isEmpty())
                                    <div class="b  b-grey p-l-20 p-r-20 p-t-10 p-b-10">
                                    </div>
                                    
                                    @foreach($file->options_services()->get() as $option) 
                                        @if(\App\Models\Service::where('id', $option->service_id)->first())
                                          <div class="p-l-20 b-b b-grey b-t p-b-10 p-t-10"> 
                                            <img alt="{{\App\Models\Service::where('id', $option->service_id)->first()->name}}" width="40" height="40" data-src-retina="{{ url('icons2').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" data-src="{{ url('icons2').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" src="{{ url('icons2').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}">
                                            {{\App\Models\Service::where('id', $option->service_id)->first()->label}}
                                            <?
                                            $value = \App\Models\Service::where('id', $option->service_id)->first()->label;
            
                                            array_push($mods, $value);
                                            ?>
                                          </div>
                                        @endif
                                        @if($comments)
                                          @foreach($comments as $comment)
                                  
                                              @if($option->service_id == $comment->service_id)
                                                <div class="p-l-20 p-b-10 p-t-10"> 
                                                  {{$comment->comments}}
                                                
                                                </div>
                                                <div class="p-l-20 p-b-10">Type: {{$comment->comment_type}}</div>
                                              @endif
                                          @endforeach
                                        @endif
                                    @endforeach
                                  @else
                                          
                                    <div class="b  b-grey p-l-20 p-r-20 p-t-10">
                                    </div>
                                  
                                    @foreach($file->options_services as $option)
                                        
                                        @if(\App\Models\Service::FindOrFail($option->service_id))
                                          <div class="p-l-20 b-b b-grey"> 
                                            <img alt="{{\App\Models\Service::FindOrFail($option->service_id)->name}}" width="40" height="40" 
                                            data-src-retina="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                            data-src="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                            src="{{ url('icons2').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}">
                                            {{\App\Models\Service::FindOrFail($option->service_id)->label}}  
                                            <?
                                            $value = \App\Models\Service::FindOrFail($option->service_id)->label;
                                            
                                            array_push($mods, $value);
                                            ?>                              </div>
                                        @endif
                                        @if($comments)
                                          @foreach($comments as $comment)
                                              @if(\App\Models\Service::FindOrFail($option->service_id)->name == $comment->option)
                                                <div class="p-l-20 p-b-10"> 
                                                  {{$comment->comments}}
                                                
                                                </div>
                                                <div class="p-l-20 p-b-10">Type: {{$comment->comment_type}}</div>
                                              @endif
                                          @endforeach
                                        @endif
                                    @endforeach
                                  
                                  @endif                      
                                  
                                  
                            </div>
                            <div class="col-md-4">
<?php
                            $servername = "127.0.0.1";
                            $username = "admin_ecu_portal";
                            $password = "e24BTBDTQMRBmC";
                            $dbname = "admin_ecu_portal_db";
                            
                            // Create a PDO instance
                            try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                // Query to get the latest version from the table 'lua_versions' where file_id = 1
                                $query = "SELECT * FROM lua_versions WHERE File_Id = " . $file->id . " ORDER BY Id DESC LIMIT 1";
                            
                                // Execute the query
                                $result = $conn->query($query);
                            
                                // Fetch the result as an associative array
                                $latestVersion = $result->fetch(PDO::FETCH_ASSOC);
                            
                                // Declare and initialize the $arrayversionslua variable as an empty array
                                $arrayversionslua = [];
                            
                                // Display the result
                                if ($latestVersion) {
                                    $arrayversionslua = json_decode($latestVersion['Respons'], true);
                                    $jsonError = json_last_error();
                                    $jsonErrorMsg = json_last_error_msg();
                            
                                    if ($jsonError !== JSON_ERROR_NONE) {
                                        echo "JSON Error: $jsonErrorMsg (Code: $jsonError)";
                                    }
                            
                                    if ($arrayversionslua === null) {
                                        echo 'error decoding';
                                    } else {
                                        foreach ($arrayversionslua as $arrayversionlua) {
                                            
                                            ?>
                                            <div class="col-lg-12">
                                                <h5>
                                                    <?php
                                                    echo $arrayversionlua['name'] . ' // ' . $arrayversionlua['percentage'];
                                                    ?>
                            
                                                </h5>
                                                <?php
                                                foreach ($arrayversionlua as $key => $value) {
                                                    if (is_numeric($key) && $value !== 'Original') {
                                                        ?>
                                                        <p class="pull-left"><?php echo $value; ?></p>
                                                        <div class="clearfix lijn"></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                            
                                            </div>
                            
                                            <?php
                            
                                        }
                                    }
                                } else {
                                    echo "No Lua versions found with file_id 1.";
                                }
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }
                            
                            // Close the connection
                            $conn = null;
                            ?>

                              
                              
                              
                              
                            </div>
                            <div class="col-md-5">
                              <h4>Make new version</h4>
                                  <?
                                  foreach ($mods as $mod){
                                    ?>
                                      <div class="col-md-12">
                                        <h5>
                                      <?
                                      print($mod);
                                      ?>
                                        </h5>
            
                                    <?
            
                                    if ($arrayversionslua === null) {
                                          // Handle JSON decoding error
                                      } else {
                                        ?>
                                        <select name="makelua[]" class="form-select">
                                          <option value="">Nothing</option>
                                          <?
                                          foreach ($arrayversionslua as $arrayversionlua){
                                            ?>
                                                
                                                
                                              <?
                                              foreach ($arrayversionlua as $key => $value) {
                                                  if (is_numeric($key) && $value !== 'Original') {
                                                    $modifiedString = str_replace('/', '-', $value);
                                                      ?>
                                                      <option value="<? echo $mod;?> // <? echo $arrayversionlua['name'];?> // <? echo $key;?>">
                                                      <?php echo $arrayversionlua['name'].' // '.$arrayversionlua['percentage'].'% // '.$modifiedString;?>
                                                      </option>
                                                      <?
                                                  }
                                              }                                
                                              ?>
                                              
                                            
                                            <?
                                            
                                          }
                                          
                                      }                        
                                      ?>
                                        </select>
                                      
                                      </div>
            
                                    <?
                                  }
                                  ?>
                                  <div class="col-md-12">
                                    <h5>Send as version</h5>
                                    <input type="radio" name="sendversion" value="1" id="sendversion">
                                    <label>Yes</label>
                                    <input type="radio" name="sendversion" value="0" id="sendversion">
                                    <label>No</label>                        
                                  </div>
                                  <div class="col-md-12">
                                    <h5>Name for version</h5>
                                    <input type="text" name="nameforluacreation" id="nameforluacreation" value=""/><br/>
                                    <button id="submitButton">Submit</button>
                                  </div>
                              
                            </div>
                          </div>
                        </div>           
                       
                        <div class="tab-pane slide-left" id="slide7{{$file->id}}">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{ $file->engine }} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                                  <h4 class="m-t-20">Lua</h4>
                                </div>
                              </div>
                              
                              <div class="clearfix"></div>
                          </div>
                          <div class="row" style="">
                            <div class="col-md-12">
                              <h2>Restart actions this file</h2>
                              
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="getallversions"><span class="bold">Get all versions</span></a>
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="restartall"><span class="bold">Get all versions and retry lua</span></a>
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="Make other version"><span class="bold">Make other version</span></a>
                              
                              
                            </div>
                            
                            <div class="col-md-12">
                              <h2>Make project</h2>
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="copyaversiontoalloriginals"><span class="bold">Copy a project version to all originals</span></a>
                            </div>                
                            
                            <div class="col-md-12">
                              <h2>Restart actions all files</h2>
                              
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="getallversionsalldefault"><span class="bold">Get all versions default database</span></a>
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="getallversionsalldefaultFDB"><span class="bold">Get all versions FDB database</span></a>
                              
                              
                            </div>                
                            
                            
                          </div>
                        </div>                      
                       
                       
                       
                       
                        <div class="tab-pane slide-left" id="slide8{{$file->id}}">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{ $file->engine }} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                                  <h4 class="m-t-20">Lua</h4>
                                </div>
                              </div>
                              
                              <div class="clearfix"></div>
                          </div>
                          <div class="row" style="">
                            <div class="col-md-12">
                              <h2>Restart actions</h2>
                              
                                <a class="btn btn-success btn-cons m-b-10" href="#" id="getallversionsdatabase"><span class="bold">Get all versions from FDB</span></a>
                              
                              
                            </div>
            
                            <div class="col-md-12">
                              <h2>Make version FDB</h2>
            <?
                              $uncheckedRecords2 = DB::table('lua_versions_others')
                                  ->where('dbname', 'Filesdatabase')
                                  ->where('File_id', $file->id) // Replace $file->id with the actual file ID you're searching for
                                  ->orderBy('id', 'desc') // Order the results by id in descending order
                                  ->select('id', 'dbname', 'File_id', 'Respons')
                                  ->first();
                              
                              if ($uncheckedRecords2) {
                                  $jsonResponse = $uncheckedRecords2->Respons; // Access the 'Respons' property
                              
                                  // Parse the JSON response
                                  $data = json_decode($jsonResponse, true);
                              
                                  // Initialize the maximum dynamic field index
                                  $maxIndex = 0;
                                  
                                  // Determine the maximum dynamic field index from the JSON data
                                  foreach ($data as $row) {
                                      foreach ($row as $key => $value) {
                                          if (is_numeric($key) && $key > $maxIndex) {
                                              $maxIndex = $key;
                                          }
                                      }
                                  }
                              
                                  // Start generating HTML table
                                  echo '<table>';
                                  echo '<tr><th>Name</th><th>Percentage</th>';
                              
                                  // Generate headers for dynamic fields
                                  for ($i = 0; $i <= $maxIndex; $i++) {
                                      echo '<th>Version ' . $i . '</th>';
                                  }
                              
                                  echo '</tr>';
                              
                                  // Iterate through the data and populate table rows
                                  foreach ($data as $row) {
                                      echo '<tr>';
                                      echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                      echo '<td>' . htmlspecialchars($row['percentage']) . '</td>';
                              
                                      // Populate dynamic fields
                                      for ($i = 0; $i <= $maxIndex; $i++) {
                                          if (isset($row[$i])) {
                                              echo '<td>' . htmlspecialchars($row[$i]) . '</td>';
                                          } else {
                                              echo '<td></td>';
                                          }
                                      }
                              
                                      echo '</tr>';
                                  }
                              
                                  // Close the table
                                  echo '</table>';
                              } else {
                                  echo "No records found.";
                              }
                              ?>
                                  
                                  
                                </div>
                                                
                              
                            </div>
                            
                            
            
            
            
                            <div class="col-md-12">
                              <h4>Make new version</h4>
                                  <?
                                  if ($uncheckedRecords2){
                                    foreach ($mods as $mod){
                                    ?>
                                      <div class="col-md-12">
                                        <h5>
                                      <?
                                      print($mod);
                                      ?>
                                        </h5>
                            
                                    <?
                            
                                    if ($data === null) {
                                          // Handle JSON decoding error
                                      } else {
                                        ?>
                                        <select name="makelua2[]" class="form-select">
                                          <option value="">Nothing</option>
                                          <?
                                          foreach ($data as $arrayversionlua){
                                            ?>
                                                
                                                
                                              <?
                                              foreach ($arrayversionlua as $key => $value) {
                                                  if (is_numeric($key) && $value !== 'Original') {
                                                    $modifiedString = str_replace('/', '-', $value);
                                                      ?>
                                                      <option value="<? echo $mod;?> // <? echo $arrayversionlua['name'];?> // <? echo $key;?>">
                                                      <?php echo $arrayversionlua['name'].' // '.$arrayversionlua['percentage'].'% // '.$modifiedString;?>
                                                      </option>
                                                      <?
                                                  }
                                              }                                
                                              ?>
                                              
                                            
                                            <?
                                            
                                          }
                                          
                                      }                        
                                      ?>
                                        </select>
                                      
                                      </div>
                            
                                    <?
                                    }
                                  }
                                  ?>
                                  <div class="col-md-12">
                                    <h5>Send as version</h5>
                                    <input type="radio" name="sendversion2" value="1" id="sendversion2">
                                    <label>Yes</label>
                                    <input type="radio" name="sendversion2" value="0" id="sendversion2">
                                    <label>No</label>                        
                                  </div>
                                  <div class="col-md-12">
                                    <h5>Name for version</h5>
                                    <input type="text" name="nameforluacreation2" id="nameforluacreation2" value=""/><br/>
                                    <button id="submitButtonFDB">Submit</button>
                                  </div>
                              
                            </div>

                            
                          </div>
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
@if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'propose-options'))
<div class="modal fade slide-up disable-scroll" style="z-index: 9999;" id="engineerOptionsModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        
        <div class="modal-header clearfix text-left">
          <div class="progress-propose">
            <div class="progress-bar-indeterminate" style=""></div>
          </div>
          <div id="propose-header" class="hide">
            <p>File Credits: <span id="file_credits">0</span></p>
            <p>Proposed Credits:<span id="proposed_credits">0</span></p>
            <p>Difference:<span id="credits_difference">0</span></p>
          </div>
          <div id="force-header" class="hide">
            <p>File Credits: <span id="force_file_credits">0</span></p>
            <p>Proposed Credits:<span id="force_proposed_credits">0</span></p>
            <p>Difference:<span id="force_credits_difference">0</span></p>
          </div>
        </div>
        <div class="modal-body">
          
          <div id="propose-form" class="hide">
          <form role="form" action="{{route('add-options-offer')}}" method="POST">
            @csrf
            
            <input type="hidden" name="file_id" id="proposed_file_id" value="">
            
            <div class="form-group-attached ">
              <h5>Propose Stages and Options</h5>
              <div class="row">
                <div class="col-md-12">
                  <div class="">
                    <select class="full-width form-control" data-init-plugin="select2" name="proposed_stage" id="proposed_stage">
                    
                    </select>
                </div>
              </div>
                <div class="col-md-12">
                  <div class="">
                    
                    <select class=" full-width" data-init-plugin="select2" multiple name="proposed_options[]" id="proposed_options">
                      
                    </select>
                  </div>
                </div>
              
            </div>
         
          <div class="row">
            <div class="col-md-4 m-t-10 sm-m-t-10 text-center">
              <button type="submit" class="btn btn-success btn-block m-t-5">Propose</button>
            </div>
          </div>
            </div>
          </form>

          </div>
          

          <div id="force-form" class="hide">

            <form role="form" action="{{route('force-options-offer')}}" method="POST">
              @csrf
              
              <input type="hidden" name="file_id" id="force_proposed_file_id" value="">
              
              <div class="form-group-attached ">
                <h5>Propose Options</h5>
                <div class="row">
                  
                  <div class="col-md-12">
                    <div class="">
                      
                      <select class=" full-width" data-init-plugin="select2" multiple name="force_proposed_options[]" id="force_proposed_options">
                        
                      </select>
                    </div>
                  </div>
                
              </div>
           
            <div class="row">
              <div class="col-md-4 m-t-10 sm-m-t-10 text-center">
                <button type="submit" class="btn btn-success btn-block m-t-5">Change</button>
              </div>
            </div>
              </div>
            </form>

          </div>
          
        </div>
          
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  @endif
@endsection

@section('pagespecificscripts')



<script>
    document.getElementById("submitButtonFDB").addEventListener("click", function () {
        var selectElements = document.querySelectorAll('select[name="makelua2[]"]');
        var selectedSendVersion = document.querySelector('input[name="sendversion2"]:checked').value;
        var selectedOptions = [];
        
        selectElements.forEach(function (selectElement) {
            selectedOptions.push(selectElement.options[selectElement.selectedIndex].value);
        });

        var formData = new FormData();
        selectedOptions.forEach(function(option) {
            formData.append("selectedOptions[]", option);
        });

        var file_id = "<? echo  $file->id;?>"; // Adding the variable lol with the value "ok"
        formData.append("file_id", file_id);
        
        var numberofDecodedFiles = "<? echo $file->decoded_files->count();?>";

        console.log(numberofDecodedFiles);

        /*
          if(numberofDecodedFiles > 0) 
          then 
          var file_loc ="<? echo $file->final_decoded_file();?>";
        */

        var file_loc ="<? echo $file->file_attached;?>";
        
        formData.append("file_loc", file_loc);
        formData.append("database", 'FDB');
        
        var nameForLuaCreation = document.getElementById("nameforluacreation2").value; // Get the value from the input
        formData.append("nameforluacreation", nameForLuaCreation);
        formData.append("sendversion", selectedSendVersion);


        fetch("/makelua", {
            method: "POST",
            body: formData
        }).then(response => response.text())
          .then(data => console.log(data))
          .catch(error => console.error(error));
    });
</script>



<script>
    document.getElementById("getallversionsalldefault").addEventListener("click", function() {
        var baseUrl = "https://backend.ecutech.gr/makelua"; // Replace with the base URL
        var fileid = "<? echo  $file->id;?>"; // Replace with the ID you want to send
        var restart ="getallversionsalldefault"
        // Construct the URL with query parameters
        var url = baseUrl + "?restart=" + restart;
        
        // Send GET request using fetch
        fetch(url, {
            method: 'GET',
            mode: 'no-cors' // This mode allows the request to be made without blocking by CORS policy
        }).then(function(response) {
            // Handle response here if needed
        }).catch(function(error) {
            console.error('Error:', error);
        });
    });
</script>

<script>
    document.getElementById("getallversionsalldefaultFDB").addEventListener("click", function() {
        var baseUrl = "https://backend.ecutech.gr/makelua"; // Replace with the base URL
        var fileid = "<? echo  $file->id;?>"; // Replace with the ID you want to send
        var restart ="getallversionsalldefaultFDB"
        // Construct the URL with query parameters
        var url = baseUrl + "?restart=" + restart;
        
        // Send GET request using fetch
        fetch(url, {
            method: 'GET',
            mode: 'no-cors' // This mode allows the request to be made without blocking by CORS policy
        }).then(function(response) {
            // Handle response here if needed
        }).catch(function(error) {
            console.error('Error:', error);
        });
    });
</script>

<script>
    document.getElementById("getallversionsdatabase").addEventListener("click", function() {
      
      
      
          var baseUrl = "https://backend.ecutech.gr/makelua"; // Replace with the base URL
          var fileid = "<? echo  $file->id;?>"; // Replace with the ID you want to send
          var restart ="versions2";
          // Construct the URL with query parameters
          var url = baseUrl + "?fileid=" + fileid +"&restart=" + restart;
          
          // Send GET request using fetch
          fetch(url, {
              method: 'GET',
              mode: 'no-cors' // This mode allows the request to be made without blocking by CORS policy
          }).then(function(response) {
              // Handle response here if needed
          }).catch(function(error) {
              console.error('Error:', error);
          });
        
      });
</script>


<script>
    document.getElementById("copyaversiontoalloriginals").addEventListener("click", function() {
        var baseUrl = "https://backend.ecutech.gr/makelua"; // Replace with the base URL

        var action = "copytooriginals";
        
        var name = prompt("Please enter the name off the version how you want to save:"); // Ask for a name value
        var versionname = prompt("Please enter the winols version name:"); // Ask for a name value
        var winolsname = prompt("Please enter the winols filname:"); // Ask for a name value
        var requestfile = 1; // Ask for a name value
      
        if (name === null) {
            return; // If user cancels the prompt, exit the function
        }

        var data = new FormData();
        data.append("name", name);
        data.append("action", action);
        data.append("winolsname", winolsname);
        data.append("versionname", versionname);
        data.append("requestfile", requestfile);

        // Send POST request using fetch
        fetch(baseUrl, {
            method: 'POST',
            body: data,
            mode: 'no-cors' // This mode allows the request to be made without blocking by CORS policy
        }).then(function(response) {
            // Handle response here if needed
        }).catch(function(error) {
            console.error('Error:', error);
        });
    });
</script>

<script>
    // Add a common class "copy-button" to all the buttons you want to attach this functionality to
    var copyButtons = document.querySelectorAll(".copy-button");

    // Attach a click event listener to a common ancestor element (e.g., a parent div)
    document.addEventListener("click", function(event) {
        // Check if the clicked element has the "copy-button" class
        if (event.target.classList.contains("copy-button")) {
            var baseUrl = "https://backend.ecutech.gr/makelua";
            var button = event.target;

            var winolsname = button.getAttribute("data-winolsname");
            var versionname = button.getAttribute("data-versionname");
            var requestfile = button.getAttribute("data-requestfile");
            var action = "copytooriginals";

            var name = prompt("Please enter a name:");

            if (name === null) {
                return;
            }

            var data = new FormData();
            data.append("name", name);
            data.append("action", action);
            data.append("winolsname", winolsname);
            data.append("versionname", versionname);
            data.append("requestfile", requestfile);

            fetch(baseUrl, {
                method: 'POST',
                body: data,
                mode: 'no-cors'
            }).then(function(response) {
                // Handle response here if needed
            }).catch(function(error) {
                console.error('Error:', error);
            });
        }
    });
</script>




<script>
    // Add a common class to all buttons you want to target
    var buttons = document.querySelectorAll(".makeproject");

    // Attach a click event listener to a common ancestor of these buttons (like a parent container)
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("makeproject")) {
            var baseUrl = "https://backend.ecutech.gr/makelua";
            var button = event.target;
            var moddedfile = button.getAttribute("data-moddedfile");
            var original = button.getAttribute("data-original");
            var path = button.getAttribute("data-path");
            var requestfileid = button.getAttribute("data-requestfileid");

            var name = prompt("Please enter a name:");

            if (name === null) {
                return;
            }

            var data = new FormData();
            data.append("name", name);
            data.append("moddedfile", moddedfile);
            data.append("original", original);
            data.append("path", path);
            data.append("requestfileid", requestfileid);
            data.append("makeproject", 'yes');

            fetch(baseUrl, {
                method: 'POST',
                body: data,
                mode: 'no-cors'
            }).then(function(response) {
                // Handle response here if needed
            }).catch(function(error) {
                console.error('Error:', error);
            });
        }
    });
</script>

<script>
        document.getElementById("setvisible").addEventListener("click", function() {
        var baseUrl = "https://backend.ecutech.gr/makelua"; // Replace with the base URL
        var button = document.getElementById("setvisible");
        var setvisible ="yes"

        var inputValue = button.getAttribute("data-id");
        
        // Construct the URL with query parameter
        var url = baseUrl + "?id=" + inputValue + "&setvisible=" + setvisible;
        
        // Send GET request using fetch
        fetch(url, {
            method: 'GET',
            mode: 'no-cors' // This mode allows the request to be made without blocking by CORS policy
        }).then(function(response) {
            // Handle response here if needed
        }).catch(function(error) {
            console.error('Error:', error);
        });
    });
</script>


<script>
    document.getElementById("getallversions").addEventListener("click", function() {
        var baseUrl = "https://backend.ecutech.gr/makelua"; // Replace with the base URL
        var fileid = "<? echo  $file->id;?>"; // Replace with the ID you want to send
        var restart ="versions"
        // Construct the URL with query parameters
        var url = baseUrl + "?fileid=" + fileid +"&restart=" + restart;
        
        // Send GET request using fetch
        fetch(url, {
            method: 'GET',
            mode: 'no-cors' // This mode allows the request to be made without blocking by CORS policy
        }).then(function(response) {
            // Handle response here if needed
        }).catch(function(error) {
            console.error('Error:', error);
        });
    });
</script>

    <script>
    document.getElementById("restartall").addEventListener("click", function() {
        var baseUrl = "https://backend.ecutech.gr/makelua"; // Replace with the base URL
        var fileid = "<? echo  $file->id;?>"; // Replace with the ID you want to send
        var restart ="all"
        // Construct the URL with query parameters
        var url = baseUrl + "?fileid=" + fileid +"&restart=" + restart;
        
        // Send GET request using fetch
        fetch(url, {
            method: 'GET',
            mode: 'no-cors' // This mode allows the request to be made without blocking by CORS policy
        }).then(function(response) {
            // Handle response here if needed
        }).catch(function(error) {
            console.error('Error:', error);
        });
    });
</script>

<script>
    document.getElementById("submitButton").addEventListener("click", function () {
        var selectElements = document.querySelectorAll('select[name="makelua[]"]');
        var selectedSendVersion = document.querySelector('input[name="sendversion"]:checked').value;
        var selectedOptions = [];
        
        selectElements.forEach(function (selectElement) {
            selectedOptions.push(selectElement.options[selectElement.selectedIndex].value);
        });

        var formData = new FormData();
        selectedOptions.forEach(function(option) {
            formData.append("selectedOptions[]", option);
        });

        var file_id = "<? echo  $file->id;?>";
        formData.append("file_id", file_id);
        
        <?php
        if($file->final_decoded_file() !== null){
          ?>
        var file_loc ="<? echo $file->final_decoded_file();?>"; //this need to be the decrypted file
          <?php
        }else{
          ?>
        var file_loc ="<? echo $file->file_attached;?>"; //this need to be the decrypted file
        <?php
      }
      ?>
        
        
        formData.append("file_loc", file_loc);
        
        var nameForLuaCreation = document.getElementById("nameforluacreation").value;
        formData.append("nameforluacreation", nameForLuaCreation);
        formData.append("sendversion", selectedSendVersion);


        fetch("/makelua", {
            method: "POST",
            body: formData
        }).then(response => response.text())
          .then(data => console.log(data))
          .catch(error => console.error(error));
    });
</script>




  <script type="text/javascript">
  $(document).ready(function(){

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

    function force_re_calculate_proposed_credits(file_id){

      
let force_proposed_options = $('#force_proposed_options').val();

$.ajax({
      url: "/force_only_total_proposed_credits",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'force_proposed_options': force_proposed_options,
          'file_id': file_id,
          
      },
      success: function(res) {

        let difference = res.proposed_credits - res.file_credits;

        $('#force_file_credits').html(res.file_credits);
        $('#force_proposed_credits').html(res.proposed_credits);
        $('#force_credits_difference').html(difference);

      }
  });

}

function re_calculate_proposed_credits(file_id){

let proposed_stage = $('#proposed_stage').val();
let proposed_options = $('#proposed_options').val();

$.ajax({
      url: "/only_total_proposed_credits",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'proposed_stage': proposed_stage,
          'file_id': file_id,
          'proposed_options': proposed_options,
      },
      success: function(res) {

        let difference = res.proposed_credits - res.file_credits;

        $('#file_credits').html(res.file_credits);
        $('#proposed_credits').html(res.proposed_credits);
        $('#credits_difference').html(difference);

      }
  });

}

function calculate_proposed_credits(file_id){

$('.progress-propose').removeClass('hide');

$.ajax({
      url: "/get_total_proposed_credits",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'file_id': file_id
      },
      success: function(res) {

        $('#proposed_stage').html(res.stageOptions);
        $('#proposed_options').html(res.optionOptions);
        
        let difference = res.proposed_credits - res.file_credits;

        console.log(difference);

        $('#file_credits').html(res.file_credits);
        $('#proposed_credits').html(res.proposed_credits);
        $('#credits_difference').html(difference);

        $('.progress-propose').addClass('hide');

      }
  });

}

function force_calculate_proposed_credits(file_id){

$('#force_proposed_options').html('');

$.ajax({
      url: "/get_total_proposed_credits",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'file_id': file_id
      },
      success: function(res) {

        $('#force_proposed_options').html(res.forceOptions);

        let difference = res.proposed_credits - res.file_credits;

        console.log(difference);

        $('#force_file_credits').html(res.file_credits);
        $('#force_proposed_credits').html(res.proposed_credits);
        $('#force_credits_difference').html(difference);

      }
  });

}

$(document).on('change', '#force_proposed_options', function(e){
let file_id = $('#force_proposed_file_id').val();
force_re_calculate_proposed_credits(file_id);

});

$(document).on('change', '#proposed_options', function(e){
let file_id = $('#proposed_file_id').val();
re_calculate_proposed_credits(file_id);

});

$(document).on('change', '#proposed_stage', function(e){
let file_id = $('#proposed_file_id').val();
re_calculate_proposed_credits(file_id);

});

$(document).on('click', '.btn-options-change', function(e){

$('#proposed_stage').html('');
$('#proposed_options').html('');

let file_id = $(this).data('file_id');

calculate_proposed_credits(file_id);

$('#proposed_file_id').val($(this).data('file_id'));

if($('#propose-header').hasClass('hide')){
  
  $('#propose-header').removeClass('hide');
  $('#force-header').addClass('hide');
}

if($('#propose-form').hasClass('hide')){
  
  $('#propose-form').removeClass('hide');
  $('#force-form').addClass('hide');
}

$('#engineerOptionsModal').modal('show');

});

$(document).on('click', '.btn-options-change-force', function(e){

$('#proposed_options_force').html('');

let file_id = $(this).data('file_id');

force_calculate_proposed_credits(file_id);

$('#force_proposed_file_id').val($(this).data('file_id'));

if($('#force-header').hasClass('hide')){
  
  $('#force-header').removeClass('hide');
  $('#propose-header').addClass('hide');
}

if($('#force-form').hasClass('hide')){
  
  $('#force-form').removeClass('hide');
  $('#force-header').addClass('hide');
}

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