@extends('layouts.app')

@section('pagespecificstyles')
  <style>

.swal2-confirm {

margin-bottom: 10px !important;

}

.btn-transparent{
  background: transparent;
}

.bg-danger-light{
  background-color: #f77975 !important;
}

.bg-info-light {
    background-color: #626c75 !important;
}

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
      
      {{-- @if(!empty($file->new_requests)) --}}
      <div class="card card-transparent">
        <ul class="nav nav-tabs nav-tabs-simple nav-tabs-right bg-white" id="tab-4" role="tablist">
            <li class="nav-item">
              <a href="#" data-toggle="tab" role="tab" data-target="#tab4hellowWorld" @if(sizeof($file->new_requests) == 0) class="active show" aria-selected="true" @endif>Task {{$file->id}}</a>
            </li>

            @php 
              $newreqs = count($file->new_requests);
              $countn = 1;
            @endphp

          @foreach($file->new_requests as $row)
            <li class="nav-item">
              <a href="#" data-toggle="tab" class="@if($countn == $newreqs) active show @endif" role="tab" data-target="#tab4FollowUs{{$row->id}}" aria-selected="true">Task {{$row->id}} (New Request)</a>
            </li>

            @php 
              
              $countn++;
            @endphp
          @endforeach
          
        </ul>
        
        <div class="tab-content bg-white" style="border-top: 1px solid rgba(0, 0, 0, 0.1); border-left: 1px solid rgba(0, 0, 0, 0.1);">
          <div class="tab-pane slide-left @if(sizeof($file->new_requests) == 0) active show @endif" id="tab4hellowWorld">
            
      {{-- @endif --}}

      <div class="card card-transparent m-t-40">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
          <li class="nav-item">
            <a href="#"  @if(!Session::has('tab')) class="active" @endif data-toggle="tab" data-target="#slide1"><span>Task</span></a>
          </li>

          @if( $file->subdealer_group_id == NULL)
         
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
          
          {{-- @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'admin-tasks')) --}}
          


          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide3"><span>Admin Tasks</span></a>
          </li>
          

          {{-- @endif --}}
          
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide4"><span>Logs</span></a>
          </li>

          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide23"><span>Status Logs</span></a>
          </li>

          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide24"><span>Engineer Assignment Logs</span></a>
          </li>

          {{-- @if($file->decoded_files->isEmpty()) --}}
            {{-- @if($file->tool_type == 'slave' && $file->tool_id != $kess3Label->id) --}}
              <li class="nav-item">
                <a href="#" data-toggle="tab" data-target="#slide5"><span>Upload New File</span></a>
              </li>
            {{-- @endif --}}
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
                  <div class="card-header @if($file->frontend->id == 1) bg-primary-light @elseif($file->frontend->id == 3) bg-info-light @else bg-warning-light @endif">

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
                          <img src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="" style="width: 30%;">
                          <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }} 
                            @if($file->vehicle()){{ $file->vehicle()->TORQUE_standard }}@endif</h3>
                          
                          @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'download-client-file'))
                          
                          @if($file->original_file_id)
                              
                                <a href="{{ route('download', [$file->original_file_id, $file->file_attached, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's File</span>
                                </a>
                              
                            @else
                                
                            @if($file->decoded_mode == 0)
                              <a href="{{ route('download', [$file->id, $file->file_attached, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's File</span>
                              </a>
                            @endif

                            @if($file->acm_file)
                            <a href="{{ route('download', [$file->id, $file->acm_file, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's ACM MCM/ECM File</span>
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

                            @if($file->tool_type == 'slave' && $file->tool_id == $flexLabel->id || $file->tool_id != $kess3Label->id)
                            
                              @if(!$file->magic_decrypted_files->isEmpty())
                                @foreach($file->magic_decrypted_files as $magicDecodedFile)

                                    <a href="{{ route('download', [$file->id, $magicDecodedFile->name, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download {{$magicDecodedFile->name}}</span>
                                    </a>
                                 
                                @endforeach
                              @endif
                              
                            @endif

                          @endif

                          @endif 

                          @if($file->assigned_to == NULL)
                          <form method="POST" action="{{route('assigned-to-me')}}">
                            @csrf
                            <input type="hidden" name="file_id" value="{{$file->id}}">
                            <button class="btn btn-danger" type="submit">Assigned To Me</button>
                          </form>
                          @endif

                        </div>
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">

                    @if($file->disable_customers_download)
                      <div class="row m-t-40">
                        <div class="col-12 col-xl-12 bg-danger-light text-white m-b-10 m-t-10 m-l-10" style="height: 100%;">
                          <p class="no-margin p-t-10 p-b-10">Because AlientTech could not encrypt these files so any Auto reply from system or manual reply from engineer will not appear on Customer's side. We do not want them to download raw file without encoding. Now please delete all the files in revision and upload encoded file manually. After doing that please click on the button to enable download. After you will click on that button, the state of revisions will be visible to Customer to download. Please be careful. Thanks.</p>
                          <form action="{{route('enable-download')}}" method="POST" class="text-center"> @csrf <input type="hidden" value="{{$file->id}}" name="id"> <button class="btn btn-info m-b-10" type="submit" >Enable Download on Customer Side</button></form>
                        </div>
                      </div>
                    @endif

                    @if($file->no_longer_auto)
                      <div class="row m-t-40">
                        <div class="col-12 col-xl-12 bg-danger-light text-white m-b-10 m-t-10 m-l-10" style="height: 100%;">
                          <p class="no-margin p-t-10 p-b-10">During auto encoding, an error occured. Please check which file is not encoded and make a decision accordingly to share it with customer or not.</p>
                          
                        </div>
                      </div>
                    @endif

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
                          @if($file->user->flag == NULL)
                            <div><button id="add-customer-comment" class="btn btn-transparent btn-sm"><i class="fa-solid fa-flag"></i></button></div>
                          @else
                            <div><i class="fa-solid fa-flag"></i></div>
                          @endif
                        </div>

                        {{-- <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Total Tasks</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->user_files_count()}}</span>
                            <a target="_blank" href="{{route('show-files', $file->user->id)}}" class="btn-tag btn-tag-light btn-tag-rounded">Show</a>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Canceld Tasks</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->user_rejected_files_count()}}</span>
                            <a target="_blank" href="{{route('show-rejected-files', $file->user->id)}}" class="btn-tag btn-tag-light btn-tag-rounded">Show</a>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Registerd Since</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->user_registered_since()}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div> --}}

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">
                            
                            <span class="label label-success"><a class="text-white" target="_blank" href="{{route('show-files', $file->user->id)}}">{{$file->user_files_count()}}</a></span>
                            <span class="label label-danger"><a class="text-white" target="_blank" href="{{route('show-rejected-files', $file->user->id)}}">{{$file->user_rejected_files_count()}}</a></span>
                            <span class="label label-info">{{$file->user_registered_since()}}</span>
                            
                          </p>
                          <div class="pull-right">
                            <span class="">{{getFlags($file->user->country).' '.code_to_country($file->user->country)}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Customer Email</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->user->email}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Frontend</p>
                          <div class="pull-right">
                            <span class="label @if($file->frontend->id == 1) text-white bg-primary @elseif($file->frontend->id == 3) text-white bg-info @else text-black bg-warning @endif">{{$file->frontend->name}}<span>
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

                        @if($file->assignment_time)
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Assigment Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ date_format(new DateTime($file->assignment_time),"d/m/Y H:i:s") }}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @endif

                        @if($file->response_time)
                        
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Engineer Upload Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ date_format(new DateTime($file->reupload_time),"d/m/Y H:i:s") }}<span>
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

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Tool</p>
                          <div class="pull-right">
                              <img alt="{{$file->tool_id}}" width="50" height="" data-src-retina="{{ get_dropdown_image($file->tool_id) }}" data-src="{{ get_dropdown_image($file->tool_id) }}" src="{{ get_dropdown_image($file->tool_id) }}">
                              <span class="" style="top: 2px; position:relative;">{{ \App\Models\Tool::findOrFail( $file->tool_id )->name }}({{$file->tool_type}})</span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        

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
                        
                        @if(!$file->gearbox_ecu)
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
                        @endif

								  @if($file->gearbox_ecu)
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Gearbox ECu</p>
                          <div class="pull-right">
                            @if($file->gearbox_ecu)
                              <span class="label bg-warning">{{App\Models\ECU::findOrFail($file->gearbox_ecu)->type}}<span>
                            @else
                              <span class="label label-danger">NO ECU<span>
                            @endif
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

                            @if($file->ecu && $vehicle !== NULL)
                              <a class="btn btn-success btn-cons m-b-10" href="{{route('add-comments', [$vehicle->id, 'file='.$file->id])}}"><span class="bold">Go To Comments</span></a>
                            @endif

                            @endif

                            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'view-vehicles'))

                              @if($vehicle !== NULL)

                                <a class="btn btn-success btn-cons m-b-10" href="{{route('vehicle', $vehicle->id)}}"><span class="bold">Go To Vehicle</span></a>

                              @endif

                            @endif

                            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-file'))

                              <a class="btn btn-success btn-cons m-b-10" href="{{route('edit-file', $file->id)}}"><span class="bold">Edit File</span></a>
                            
                            @endif

                            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-file'))
                              
                                <button type="button" class="btn btn-danger btn-delete btn-cons m-b-10" data-file_id={{$file->id}}><span class="bold">Delete File</span></button>

                            @endif
                            
                          </div> 
                          
                          <h5 class="m-t-40">Brand ECU Options Comment</h5>
                           @foreach($optionsCommentsRecords as $record) 
                            <div class="alert alert-info" role="alert">
                              <p class="pull-left">Service: {{$record->service_label}}</p>
                              
                              <p class="pull-right">Software: {{$record->software}}</p>
                              
                              <div class="clearfix"></div>
                              <br>
                              <p>{{$record->comments}}</p>
                              <br>
                              <p>{{$record->results}}</p>
                              </div>
                            @endforeach

                            
                      </div>

                      <div class="col-lg-6">

                        @if($file->modification)
                            
                            <h5 class="m-t-40 text-danger">File Modifications</h5>


                            <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                              <p class="pull-left">Modification</p>
                              <div class="pull-right">
                                <p>{{$file->modification}}</p>
                              </div>
                              <div class="clearfix"></div>
                            </div>

                            <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                              @if($file->mention_modification)
                                <p class="pull-left">Mentioned Modification</p>
                                <div class="pull-right">
                                  <p>{{$file->mention_modification}}</p>
                                </div>
                                <div class="clearfix"></div>
                              @endif
                              
                            </div>

                        @endif


                        {{-- <h5 class="m-t-40">Reading Tool</h5>
        
                            
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Tool</p>
                          <div class="pull-right">
                              <img alt="{{$file->tool_id}}" width="50" height="" data-src-retina="{{ get_dropdown_image($file->tool_id) }}" data-src="{{ get_dropdown_image($file->tool_id) }}" src="{{ get_dropdown_image($file->tool_id) }}">
                              <span class="" style="top: 2px; position:relative;">{{ \App\Models\Tool::findOrFail( $file->tool_id )->name }}({{$file->tool_type}})</span>
                          </div>
                          <div class="clearfix"></div>
                        </div> --}}
                     
        
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

                                @elseif($file->front_end_id == 3)

                                @if($file->tool_type == 'master')
                                <span class="text-white label-danger label"> {{$stage->efiles_credits}} </span>
                                @else
                                  <span class="text-white label-danger label"> {{$stage->efiles_slave_credits}} </span>
                                @endif


                                @else
                                  <span class="text-white label-danger label"> {{$stage->credits}} </span>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                          </div>
                          <div class="b-t b-grey p-b-10 p-t-10">

                            @php

                                $records = \App\Models\FileReplySoftwareService::join('files', 'files.id', '=', 'file_reply_software_service.file_id')
                                ->where('file_reply_software_service.service_id', $stage->id)
                                ->where('files.ecu', $file->ecu)
                                ->where('files.brand', $file->brand)
                                ->select('file_reply_software_service.software_id')
                                ->distinct('file_reply_software_service.software_id')->get();

                                

                            @endphp
                          
                          @foreach($records as $record)

                          @php

                              $totals = all_files_with_this_ecu_brand_and_service_and_software($file->brand, $file->ecu, $stage->id, $record->software_id);
                              $revised = all_files_with_this_ecu_brand_and_service_and_software_revisions($file->brand, $file->ecu, $stage->id, $record->software_id);

                          @endphp

                          {{-- <div style="display: flow-root;" class="b-b b-grey">
                            <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                              <div class="pull-right">
                                No of File: {{$totals}}
                              </div>
                          </div> --}}

                          
                          <div style="display: flow-root;" class="b-b b-grey">
                          <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                          
                            @if($totals != 0)
                            <div class="pull-right">
                              {{round((($totals - $revised) / $totals)*100, 2).'%'}}
                            </div>
                            @endif

                            @if($totals != 0)
                            <div class="pull-right">
                              <span class="label label-success m-r-5">{{$totals}}</span>
                            </div>
                            @endif

                          </div>
                          

                          @endforeach
                           
                            
                            
                          </div>
                        @endif
                      @else

                          @if($file->stage_services)
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

                                  @elseif($file->front_end_id == 3)

                                @if($file->tool_type == 'master')
                                <span class="text-white label-danger label"> {{$stage->efiles_credits}} </span>
                                @else
                                  <span class="text-white label-danger label"> {{$stage->efiles_slave_credits}} </span>
                                @endif

                              @else
                                <span class="text-white label-danger label"> {{$stage->credits}} </span>
                              @endif
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-t b-grey p-b-10 p-t-10">

                          @php

                                $records = \App\Models\FileReplySoftwareService::join('files', 'files.id', '=', 'file_reply_software_service.file_id')
                                ->where('file_reply_software_service.service_id', $stage->id)
                                ->where('files.ecu', $file->ecu)
                                ->where('files.brand', $file->brand)
                                ->select('file_reply_software_service.software_id')
                                ->distinct('file_reply_software_service.software_id')->get();

                                

                            @endphp
                          
                          @foreach($records as $record)

                          @php

                              $totals = all_files_with_this_ecu_brand_and_service_and_software($file->brand, $file->ecu, $stage->id, $record->software_id);
                              $revised = all_files_with_this_ecu_brand_and_service_and_software_revisions($file->brand, $file->ecu, $stage->id, $record->software_id);

                          @endphp

                          {{-- <div style="display: flow-root;" class="b-b b-grey">
                            <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                              <div class="pull-right">
                                No of File: {{$totals}}
                              </div>
                          </div> --}}

                         
                          <div style="display: flow-root;" class="b-b b-grey">
                          <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                          
                          @if($totals != 0)
                          <div class="pull-right">
                            {{round((($totals - $revised) / $totals)*100, 2).'%'}}
                          </div>
                          @endif

                          @if($totals != 0)
                          <div class="pull-right">
                            <span class="label label-success m-r-5">{{$totals}}</span>
                          </div>
                          @endif
                          
                          </div>
                          

                          @endforeach
                          
                        </div>
                        
                        @endif
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
                                {{\App\Models\Service::where('id', $option->service_id)->first()->name}}  ({{\App\Models\Service::where('id', $option->service_id)->first()->vehicle_type}}) (@if(\App\Models\Service::findOrFail( $option->service_id )->active == 1) {{'ECU Tech'}} @elseif(\App\Models\Service::findOrFail( $option->service_id )->tuningx_active == 1) {{'TuningX'}} @else {{'E-files'}} @endif)
                                @php $optionInner = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                                @if($file->front_end_id == 2 || $file->front_end_id == 3)
                                  @if($file->tool_type == 'master')
                                    <span class="text-white label-danger label pull-right"> {{$optionInner->optios_stage($file->stage_services->service_id)->first()->master_credits}} </span>
                                  @else
                                    <span class="text-white label-danger label pull-right"> {{$optionInner->optios_stage($file->stage_services->service_id)->first()->slave_credits}} </span>
                                  @endif
                              @else
                                <span class="text-white label-danger label pull-right"> {{$optionInner->credits}} </span>
                              @endif
                              </div>
                              <div class="b-t b-grey p-b-10 p-t-10">

                                

                                @php

                                $records = \App\Models\FileReplySoftwareService::join('files', 'files.id', '=', 'file_reply_software_service.file_id')
                                ->where('file_reply_software_service.service_id', $optionInner->id)
                                ->where('files.ecu', $file->ecu)
                                ->where('files.brand', $file->brand)
                                ->select('file_reply_software_service.software_id')
                                ->distinct('file_reply_software_service.software_id')->get();

                                

                            @endphp
                          
                          @foreach($records as $record)

                          @php

                              $totals = all_files_with_this_ecu_brand_and_service_and_software($file->brand, $file->ecu, $optionInner->id, $record->software_id);
                              $revised = all_files_with_this_ecu_brand_and_service_and_software_revisions($file->brand, $file->ecu, $optionInner->id, $record->software_id);

                          @endphp

                          <div style="display: flow-root;" class="b-b b-grey">
                          <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                          
                            @if($totals != 0)
                            <div class="pull-right">
                              {{round((($totals - $revised) / $totals)*100, 2).'%'}}
                            </div>
                            @endif

                            @if($totals != 0)
                            <div class="pull-right">
                              <span class="label label-success m-r-5">{{$totals}}</span>
                            </div>
                            @endif
                          
                          </div>
                          

                          @endforeach

                                
                              </div>
                              @foreach($file->comments as $c)
                              @if($c->service_id == $option->service_id)
                                <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                  <p class="pull-left text-danger">{{$optionInner->name}} Customers Comments</p>
                                  <br>
                                  <div class="m-l-20 text-danger">
                                    {{$c->comment}}
                                  </div>
                                  <div class="clearfix"></div>
                                </div>
                              @endif
                            @endforeach
                            @endif

                                {{-- @php
                                  dd($comments);
                                @endphp --}}

                            @if($comments)
                              @foreach($comments as $comment)
                                  
                                  @if($optionInner->id == $comment->service_id)
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

                              @foreach($file->comments as $c)
                              @if($c->service_id == $option->service_id)
                                <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                  <p class="pull-left text-danger">{{$optionInner->name}} Customers Comments</p>
                                  <br>
                                  <div class="m-l-20 text-danger">
                                    {{$c->comment}}
                                  </div>
                                  <div class="clearfix"></div>
                                </div>
                              @endif
                            @endforeach

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
                      
                      {{-- @if($file->dtc_off_comments)
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
                      @endif --}}

                      {{-- <div class="b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Show Comments</p>
                        <div class="pull-right">

                          <input data-file_id={{$file->id}} class="show_comments" type="checkbox" data-init-plugin="switchery" @if($file->show_comments) checked="checked" @endif onclick="show_comments_flip()"/>
                        </div>
                        <div class="clearfix"></div>
                      </div> --}}
                     
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

                      @if($file->acm_file)

                      {{-- <div class="col-lg-6">
                        <h5 class="m-t-40">Upload ACM MCM/ECM file's reply</h5>
                        
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          
                          <div class="pull-right">
                            <form action="{{ route('upload-acm-reply') }}" method="POST" enctype="multipart/form-data">
                              @csrf

                              <input type="hidden" name="file_id" id="file_id" value="{{$file->id}}">
                              <input type="file" name="acm_file" id="acm_file" required>
                              
                              <input type="submit" value="Upload" class="btn btn-success">
                            </form>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <h5 class="m-t-40">Uploaded ACM MCM/ECM Files</h5>

                            <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                              <p class="pull-left">Revisions</p>
                              <div class="pull-right">
                              
                                  <label class="label bg-info text-white">{{$file->acm_files->count()}}</label>
                              </div>
                              <div class="clearfix"></div>
                            </div>

                            @foreach($file->acm_files as $acm_file)
                              <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                  <p class="pull-left">{{$acm_file->acm_file}}</p>
                                  <div class="pull-right">
                                    

                                      <a href="{{ route('download',[$file->id, $acm_file->acm_file, 0]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download</span>
                                      </a>
                                      <a href="#" class="btn-sm btn-cons btn-danger delete-acm-file" data-acm_file_id="{{$acm_file->id}}"><i class="pg-trash text-white"></i></a>
                                  </div>

                                  <div class="clearfix"></div>
                                    
                                    

                                    
                                  <div class="clearfix"></div>
                              </div>
                            @endforeach
                      </div> --}}

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

                              @elseif($file->front_end_id == 3)
                                    @if($file->tool_type == 'master')
                                      <span class="text-white label-danger label"> {{$stage->efiles_credits}} </span>
                                      @php $proposedCredits += $stage->efiles_credits; @endphp
                                    @else
                                      <span class="text-white label-danger label"> {{$stage->efiles_slave_credits}} </span>
                                      @php $proposedCredits += $stage->efiles_slave_credits; @endphp
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

                                  @elseif($file->front_end_id == 3)

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

                      <div class="col-lg-12">
                        <h5 class="m-t-40">Versions</h5>

                        @if($file->status == 'submitted' || $file->status == 'ready_to_send' || $file->status == 'completed')
                          
                          @if($activeFeedType == 'danger') 
                            <button class="btn btn-success m-b-20 btn-show-message-form" data-file_id="{{$file->id}}">Upload Version.</button>
                          @else
                            <button class="btn btn-success m-b-20 btn-show-software-form" data-file_id="{{$file->id}}">Upload Version.</button>
                          @endif
                          
                        @else
                          <h5 class="text-danger">File Status must be sumbitted or completed.</h5>
                        @endif

                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Number of Versions</p>
                          <div class="pull-right">
                           
                              <label class="label bg-info text-white">{{$file->files->count()}}</label>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                            @foreach($file->files->toArray() as $message)

                            <div class="card">

                              <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                              <li class="nav-item">
                                <a href="#" class="active" data-toggle="tab" data-target="#reply_data_{{$message['id']}}"><span>Version Information</span></a>
                              </li>
                              @if($vehicle)

                                @if($vehicle->type == 'truck' || $vehicle->type == 'machine' || $vehicle->type == 'agri')
                                              <li class="nav-item">
                                                <a href="#" data-toggle="tab" data-target="#acm_data_{{$message['id']}}"><span>ACM Information</span></a>
                                              </li>
                                @endif
                              @endif

                              @if($file->softwares->isNotEmpty())
                              <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#software_data_{{$message['id']}}"><span>Software Information</span></a>
                              </li>
                              @endif
                            </ul>
                            <!-- Tab panes -->
    
                            <div class="tab-content">
                              <div class="tab-pane slide-left active" id="reply_data_{{$message['id']}}" style="height: 100%;">

                                {{-- @if($file->status == 'completed') --}}
                                  {{-- @if($file->id == 8993) --}}
                                    @if($message['show_later'] == 1)
                                      <button style="float: right;" class="btn btn-info m-b-2 btn-show-send-file-form m-l-10" data-file_id="{{$file->id}}" data-request_file_id="{{$message['id']}}">Send File To Customer</button>
                                    @endif
                                  {{-- @endif --}}
                                    <button style="float: right;" class="btn btn-success m-b-20 btn-show-software-edit-form" data-file_id="{{$file->id}}" data-new_request_id="{{$message['id']}}">Edit Processiong Softwares</button>
                                {{-- @endif --}}

                          @php
                            if(isset($message['request_file'])){
                              $messageFile = \App\Models\RequestFile::findOrFail($message['id']);
                            }

                          @endphp
                          
                              @if(isset($message['request_file']))
                                @if($message['engineer'] == 1)
                            <div class="p-l-20 p-r-20 p-b-10 p-t-10">
                              <p class="pull-left">{{$message['request_file']." "}} @if($message['user_id']){{' (Uploaded by: '.App\Models\User::findOrFail($message['user_id'])->name.')'}}@endif </p>@if($message['old_name'])<br><p class="hint-text">({{$message['old_name']}})</p>@endif
                                
                                         
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


                                                $data = json_decode($message['olsname'], true);

                                                // dd($message);

                                                // $data = json_decode($message['lua_command'], true);
                                                
                                                // if ($message['lua_command'] === null){

                                                //   if($file->automatic){

                                                //     if($file->lua_version){

                                                //     $item = json_decode($file->lua_version->Respons);

                                                //     if($item != null){

                                                //     if(sizeof($item) > 0){

                                                //       foreach ($item as $key => $value) {

                                                        // dd($value);
                                                        
                                                        // if( end($value) == $file->stage ){

                                                    ?>
                                                       <p class="pull-left"><? echo $message['olsname'];?></p>

                                                    <br/>
                                                    <?php
                                                //           break;
                                                //         }

                                                //       }
                                                //     }
                                                //   }

                                                //     }

                                                //   }

                                                  
                                                // }
                                                // else{
                                                //     foreach ($data as $item) {
                                                      // if($item->{'1'} == $file->stage){
                                                      ?>
                                                        {{-- <p class="pull-left"><? // echo $item['mod'] . ' => ' . $item['name'];?></p> --}}

                                    <br/>
                                                        <?
                                                      // }
                                                    // }
                                                  // }
                                                ?>
                                                
                                                <?
                                                            // $data = json_decode($message['lua_command_fdb'], true);
                                                            
                                                            // if ($message['lua_command_fdb'] === null){
                                                              
                                                            // }else{
                                                            //     foreach ($data as $item) {
                                                                  ?>
                                                                    {{-- <p class="pull-left"><? //echo $item['mod'] . ' => ' . $item['name'];?><b> FDB FILE</b></p> --}}
                                                                    <?
                                                                // }
                                                              // }
                                                            ?>                                                  
                                
                                <div class="pull-right">
                                  @isset($message['type'])
                                 
                                 
                                  <a href="#" class="btn-sm btn-info btn-cons"> <span class="bold">{{$message['type']}}</span>
                                  </a>
                                  @endisset
                                    @if(!($file->front_end_id == 1 && $file->subdealer_group_id == NULL))
                                     

                                      {{-- @if(count($messageFile->engineer_file_notes_have_unseen_messages))
                                      <span id="circle"></span>
                                      @endif --}}
                                      {{-- <a target="_blank" href="{{route('support', $message['id'])}}" class="btn-sm btn-cons btn-info"><i class="fa fa-question text-white"></i> Support</a> --}}
                                    @endif

                                    

                                    @if($showComments)
                                    <div class="checkbox check-success checkbox-circle">
                                      <input class="show_comments" type="checkbox" @if($message['show_comments']) checked="checked"  value="1" @endif data-id="{{$message['id']}}" id="checkbox_{{$message['id']}}">
                                      <label for="checkbox_{{$message['id']}}">Show Comments</label>
                                    </div>
                                    @endif

                                    

                                    @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id)

                                    @if($message['uploaded_successfully'] == 0)
                                    <div class="checkbox check-success checkbox-circle">
                                      <input class="show_file" type="checkbox" @if($message['is_kess3_slave']) value="0" @else checked="checked" value="1" @endif data-id="{{$message['id']}}" id="checkbox_n{{$message['id']}}">
                                      <label for="checkbox_n{{$message['id']}}">Show File As it is</label>
                                    </div>
                                    @endif

                                    @endif

                                    @if($file->tool_type == 'slave' && $file->tool_id == $flexLabel->id)

                                    @if($message['uploaded_successfully'] == 0)
                                    <div class="checkbox check-success checkbox-circle">
                                      <input class="show_file" type="checkbox" @if($message['is_flex_file']) value="0" @else checked="checked" value="1" @endif data-id="{{$message['id']}}" id="checkbox_n{{$message['id']}}">
                                      <label for="checkbox_n{{$message['id']}}">Show File As it is</label>
                                    </div>
                                    @endif

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

                                  @if($message['uploaded_successfully'])

                                  <div>
                                    <p>Please click on "Download Encrypted" Button to download and test the system. This way user will get Encrypted file or you will get the error so that you can process the file, manually.</p>
                                  </div>
                                  <div class="text-center">
                                    <a href="{{ route('download-encrypted',[$message['file_id'], $message['request_file'], false]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download Encrypted</span>
                                    </a>
                                  </div>

                                  @endif
                                  @endif

                                  @if($file->tool_type == 'slave' && $file->tool_id == $flexLabel->id)
                                    <div>
                                      <p>Please click on "Download Encrypted Magic File" Button to download and test the system. This way user will get Encrypted file or you will get the error so that you can process the file, manually.</p>
                                    </div>
                                    <div class="text-center m-b-20">
                                      <a href="{{ route('download-magic',[$message['file_id'], $message['id']]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download Encrypted Magic File</span>
                                      </a>
                                    </div>
                                  @endif

                                  

                                  @if($file->tool_type == 'slave' && $file->tool_id == $autotunerLabel->id)
                                    <div>
                                      <p>Please click on "Download Encrypted Autoturner File" Button to download and test the system. This way user will get Encrypted file or you will get the error so that you can process the file, manually.</p>
                                    </div>
                                    <div class="text-center m-b-20">
                                      <a href="{{ route('download-autotuner',[$message['file_id'], $message['id']]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download Encrypted Autotuner File</span>
                                      </a>
                                    </div>
                                  @endif
                                  
                                  <span class="btn-sm btn-cons btn-success m-t-50">{{ "Uploaded At:". date('H:i:s d/m/Y', strtotime($message['created_at']))}} </span>
                                  @if($message['downloaded_at'])<span class="btn-sm btn-cons btn-danger m-t-50">{{  "Downloaded At:". date('H:i:s d/m/Y', strtotime($message['downloaded_at']))}} </span>@endif
                                  <div class="full-width">

                                    <form action="{{route('set-new-request-comment')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="new_request_id" value="{{$message['id']}}">
                                      <label class="m-t-10">Comment</label>
                                      <br>
                                      <textarea name="comment" style="display: block; width: 100%;">@if(\App\Models\NewRequestComment::where('new_request_id', $message['id'])->first() != NULL){{\App\Models\NewRequestComment::where('new_request_id', $message['id'])->first()->comment}}@endif</textarea>
                                      <br>
                                      <input type="submit" class="btn-sm btn-cons btn-success m-t-10" value="Update">
                                    </form>

                                  </div>

                            </div>
                              </div>

                            <div class="tab-pane slide-left" id="acm_data_{{$message['id']}}" style="height: 300px;">
                                  
                                  @if(isset($message['request_file']))

                                <div class="clearfix"></div>

                                <h5 class="m-t-40">Upload ACM File reply</h5>
                        
                                <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                  
                                  <div class="">
                                    <form action="{{ route('upload-acm-reply') }}" method="POST" enctype="multipart/form-data">
                                      @csrf
        
                                      <input type="hidden" name="file_id" id="file_id" value="{{$file->id}}">
                                      <input type="hidden" name="request_file_id" id="request_file_id" value="{{$message['id']}}">
                                      <input type="file" name="acm_file" id="acm_file" required>
                                      
                                      <input type="submit" value="Upload" class="btn btn-success">
                                    </form>
                                  </div>
                                  <div class="clearfix"></div>
                                </div>

                                <div class="clearfix"></div>

                                @foreach($messageFile->acm_files as $acm_file)
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                      <p class="pull-left">{{$acm_file->acm_file}}</p>
                                      <div class="pull-right">
                                        

                                          <a href="{{ route('download',[$file->id, $acm_file->acm_file, 0]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download</span>
                                          </a>
                                          <a href="#" class="btn-sm btn-cons btn-danger delete-acm-file" data-acm_file_id="{{$acm_file->id}}"><i class="pg-trash text-white"></i></a>
                                      </div>

                                      <div class="clearfix"></div>
                                        
                                  </div>
                                @endforeach

                                {{-- <div class="clearfix"></div> --}}

                                @endif
                            
        
                        @endif
                        @endif

                      </div>

                      @if($file->softwares->isNotEmpty())

                      <div class="tab-pane slide-left" id="software_data_{{$message['id']}}" style="height: 300px;">
                      
                        @if($file->softwares->isNotEmpty())

                        <div class="card-body">
                          <div class="table-responsive" style="
                          
                          overflow:hidden;
    overflow-y: scroll;
    height: 200px;

                          ">
 
                        <table class="table table-hover" id="basicTable">
                         <thead>
                           <tr>
                             
                             
                             <th style="width:20%">Stage or Option</th>
                             <th style="width:20%">Software</th>
                             
                           </tr>
                         </thead>
                         <tbody>
                           @foreach($file->softwares as $software)
                           @if($software->reply_id == $message['id'])

                           <tr>
                             
                             <td class="v-align-middle ">
                               @if(\App\Models\Service::where('id', $software->service_id)->first() != NULL)
                                <p>{{ \App\Models\Service::findOrFail( $software->service_id )->name}}</p>
                                @else
                                  <p>Service is not available anymore.</p>
                                @endif
                             </td>
                             <td class="v-align-middle">
                               <p>{{\App\Models\ProcessingSoftware::findOrFail( $software->software_id )->name}}</p>
                             </td>
                           
                           </tr>

                           @endif
                           @endforeach
                         </tbody>
                       </table>

                          </div>
                        </div>

                        @endif
 
                       </div>

                       @endif
                    
                    </div>
                            </div>
                      @endforeach
                      </div>

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
                      
                      {{-- @if($file->status == 'submitted' || $file->status == 'completed')

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file'))
                      
                      <div class="col-xl-12 m-t-20">
                        <div class="card card-transparent flex-row">
                          <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-white" id="tab-3"> --}}

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                              @if($file->decoded_files)
                              <li class="nav-item">
                                <a href="#" class="active show" data-toggle="tab" data-target="#tab3hellowWorld">Encode</a>
                              </li>
                              @endif
                            @endif --}}

                            {{-- <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab3FollowUs" class="">Upload</a>
                            </li> --}}
                            {{-- <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab3Inspire">Three</a>
                            </li> --}}
                          {{-- </ul>
                          <div class="tab-content bg-white full-width"> --}}

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                            @if($file->decoded_files) --}}

                            {{-- <div class="tab-pane active show" id="tab3hellowWorld">
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

                          </div>
                        </div>
                      </div> --}}
                      {{-- @endif
                      @endif --}}

                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if(($file->subdealer_group_id == NULL))
          <div class="tab-pane slide-left @if(Session::get('tab') == 'chat') active @endif" id="slide2">
            <div class="row">
              <div class="col-lg-12">
                <div class="widget-16 card no-border widget-loader-circle">
                  <div class="card-header @if($file->frontend->id == 1) bg-primary-light @elseif($file->frontend->id == 3) bg-info-light @else bg-warning-light @endif">
                    <div class="text-center">
                      <div class="card-title">
                          <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                          <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
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

                        @php
                          // echo "two";
                          // dd($file->files_and_messages_sorted());
                        @endphp

                        @foreach($file->files_and_messages_sorted() as $message)

                        
                        <!-- Tab panes -->

                        
                         
                          @if(isset($message['egnineers_internal_notes']))
                           
                          @if($message['engineer'] == 1)
                            <div class="message clearfix">
                              <div class="chat-bubble bg-primary from-me text-white">
                               
                                <p class="" style="font-size: 8px;float:left">@if($message['request_file_id'] != NULL){{ \App\Models\RequestFile::findOrFail($message['request_file_id'])->request_file }}@endif</p>
                                <br>
                                <p>{!! $message['egnineers_internal_notes'] !!} </p>
                                
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
                                @if($message['user_id'])<small class="m-t-20" style="font-size: 8px; float:left">{{  \App\Models\User::findOrFail($message['user_id'])->name  }}</small>@endif
                              </div>
                            </div>
          
                            @elseif($message['engineer'] == 0)
                              <div class="message clearfix">
                                <div class="chat-bubble from-them bg-success">
                                  {{-- @php
                                    dd($message['request_file_id']);
                                  @endphp --}}
                                  <p class="" style="font-size: 8px;float:left">@if($message['request_file_id'] != NULL){{ \App\Models\RequestFile::findOrFail($message['request_file_id'])->request_file }}@endif</p>
                                  <br>  
                                  <p>{{ $message['egnineers_internal_notes'] }}</p><br>
                                    @if(isset($message['engineers_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download',[$message['file_id'], $message['engineers_attachement'], 0])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
                                    <br>
                                    <br>
									<small class="m-t-20" style="font-size: 8px;float:left">
										@if(is_text_english($message['egnineers_internal_notes']))
										<button class="btn btn-default btn-xs translate" href="#" data-id="{{$message['id']}}"><i class="fa fa-language" aria-hidden="true"></i></button>
										@endif
										
									</small>
                                    <small class="m-t-20" style="font-size: 8px;float:right">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small>
                                    {{-- <small class="m-t-20" style="font-size: 8px;float:left">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small> --}}
                                </div>
                              </div>
                            @endif

                          @endif

                          @if(isset($message['events_internal_notes']))
                           
                          
                              <div class="message clearfix">
                                <div class="chat-bubble from-them bg-success">
                                  <br>  
                                    {{ $message['events_internal_notes'] }}<br>
                                    @if(isset($message['events_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download',[$message['file_id'], $message['events_attachement'], 0])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
                                    <br>
                                    <br>
									<small class="m-t-20" style="font-size: 8px;float:left">
										@if(is_text_english($message['events_internal_notes']))
										<button class="btn btn-default btn-xs translate" href="#" data-id="{{$message['id']}}"><i class="fa fa-language" aria-hidden="true"></i></button>
										@endif
									</small>
                                    <small class="m-t-20" style="font-size: 8px;float:right">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small>
                                </div>
                              </div>
                           
                            
                          @endif

                          {{-- @if(isset($message['file_url']))
                            
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
          
                            
                          @endif --}}

                          
                                  
                            

                        


                        

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
                      <div class="row">
                      <div class="col-lg-9">
                        <form method="POST" action="{{ route('file-engineers-notes') }}" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" value="{{$file->id}}" name="file_id">
                            <div class="row">
                                <div class="col-6 no-padding">
                                  <textarea name="egnineers_internal_notes" class="form-control" placeholder="Reply to cusotmer." required></textarea>
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
                        <div class="col-lg-3">
                          <span style="display: flex; float:right;" class="m-t-15">
                            @if($file->status != 'on_hold')
                              <form method="POST" action="{{ route('set-file-on-hold') }}">
                                @csrf
                                <input type="hidden" value="{{$file->id}}" name="file_id">
                                <button class="btn btn-info" type="submit">On Hold</button>
                              </form>
                            @endif

                            <a class="btn btn-info m-l-5" href="{{route('dtc-lookup')}}" target="_blank">DTC</a>
                          </span>
                        </div>
                        </div>

                      

                      </div>

                      
                      <div class="b-t b-grey bg-white m-t-15 clearfix">
                        <span style="display: flex; float:right;" class="p-t-5">
                          @if($file->customer_message == NULL)
                            <button data-file_id="{{$file->id}}" class="btn btn-info btn-msg-later" type="button">Save a Message to send Later</button>
                          @else
                            <button data-file_id="{{$file->id}}" class="btn btn-success m-l-5 btn-msg-sent" type="button">Send Saved Message</button>
                          @endif
                          </span>
                      </div>
                      <!-- END Chat Input  !-->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          {{-- @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'admin-tasks')) --}}
          
          
          
          <div class="tab-pane slide-left" id="slide3">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
                      <h4 class="m-t-20">Adminstrative Tasks</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  

                  @if($file->status != "rejected")

                  {{-- @if(Auth::user()->is_admin()) --}}
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
                    {{-- @endif --}}
                    
                  

                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                    <p class="pull-left">File Status</p>
                    <form action="{{route('change-status-file')}}" method="POST">
                      @csrf
                      <input type="hidden" name="file_id" value="{{$file->id}}">
                      <div class="">
                        <select class="full-width" data-init-plugin="select2" name="status" id="select_status">
                            <option @if(isset($file) && $file->status == "submitted") selected @endif value="submitted">Submitted</option>
                            <option @if(isset($file) && $file->status == "ready_to_send") selected @endif value="ready_to_send">Ready To Send</option>
                            <option @if(isset($file) && $file->status == "rejected") selected @endif value="rejected">Canceled</option>
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

                  {{-- @if(Auth::user()->is_admin() or Auth::user()->is_head()) --}}
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
                  {{-- @endif --}}
                  <br>
                </div>
              </div>
            </div>
            
            {{-- @endif --}}
            <div class="tab-pane slide-left" id="slide4">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
                      <h4 class="m-t-20">Logs</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="row" style="">

                @foreach($file->logs as $log)
                  @if($log->request_type == 'alientech' || $log->request_type == 'magic' || $log->request_type == 'autotuner')
                    <div class="col-12 col-xl-12 @if($log->type == 'error') bg-danger-light @else bg-success-light @endif text-white m-b-10 m-t-10 m-l-10" style="height: 50px;">
                      <p class="no-margin p-t-10 p-b-10">{{$log->request_type.": ".$log->message}}</p>
                    </div>
                  @endif
                @endforeach

              </div>

              

            </div>

            <div class="tab-pane slide-left" id="slide23">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
                      <h4 class="m-t-20">Logs</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              

              <div class="row m-t-40">
                <div class="table-responsive">
                  <div id="condensedTable_wrapper" class="dataTables_wrapper no-footer"><table class="table table-hover table-condensed dataTable no-footer" id="condensedTable" role="grid">
                    <thead>
                      <tr role="row">
                        <th style="width:10%" class="sorting_asc" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Type</th>
                        <th style="width: 20%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column ascending">From</th>
                        <th style="width: 20%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">To</th>
                        <th style="width: 40%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Desc</th>
                        <th style="width: 10%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Changed By</th>
                        <th style="width: 10%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Changed At</th>
                      </tr>
                    </thead>
                      <tbody>

                        @foreach($file->status_logs as $s)
                        
                            <tr role="row" class="odd">
                                <td class="v-align-middle semi-bold sorting_1">{{$s->type}}</td>
                                <td class="v-align-middle">{{$s->from}}</td>
                                <td class="v-align-middle semi-bold">{{$s->to}}</td>
                                <td class="v-align-middle semi-bold">{{$s->desc}}</td>
                                <td class="v-align-middle semi-bold">{{\App\Models\User::findOrFail($s->changed_by)->name}}</td>
                                <td class="v-align-middle semi-bold">{{$s->created_at->diffForHumans()}}</td>
                            </tr>

                        @endforeach

                  </tbody>
              </table>
            </div>
          </div>

              </div>
              
            </div>

            <div class="tab-pane slide-left" id="slide24">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
                      <h4 class="m-t-20">Logs</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              

              <div class="row m-t-40">
                <div class="table-responsive">
                  <div id="condensedTable_wrapper" class="dataTables_wrapper no-footer"><table class="table table-hover table-condensed dataTable no-footer" id="condensedTable" role="grid">
                    <thead>
                      <tr role="row">
                        <th style="width:10%" class="sorting_asc" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Assigned From</th>
                        <th style="width: 20%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column ascending">Assigned to</th>
                        <th style="width: 20%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Assigned BY</th>
                        <th style="width: 10%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Assigned At</th>
                      </tr>
                    </thead>
                      <tbody>

                        @foreach($file->assignment_log as $t)
                        
                            <tr role="row" class="odd">
                                <td class="v-align-middle semi-bold">{{$t->assigned_from}}</td>
                                <td class="v-align-middle semi-bold">{{$t->assigned_to}}</td>
                                <td class="v-align-middle semi-bold">{{$t->assigned_by}}</td>
                                <td class="v-align-middle semi-bold">{{$t->created_at->diffForHumans()}}</td>
                            </tr>

                        @endforeach

                  </tbody>
              </table>
            </div>
          </div>

              </div>
              
            </div>
           
            {{-- @if($file->tool_type == 'slave' && $file->tool_id != $kess3Label->id) --}}
              <div class="tab-pane slide-left" id="slide5">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                    
                    </div>
                  </div>
                  <div class="card-body">
                    <h5>
                      Upload New File
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
            {{-- @endif --}}
            
            
            
            
            
            
           
                       
                        <div class="tab-pane slide-left" id="slide6">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }} {{ $file->engine }}</h3>
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
                                            <img alt="{{$file->stages}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
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
                                          <img alt="{{\App\Models\Service::FindOrFail($file->stage_services->service_id)->name}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon}}" data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}" src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}">
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
                                            <img alt="{{\App\Models\Service::where('id', $option->service_id)->first()->name}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}">
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
                                            data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                            data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                            src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}">
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
                            $servername = env('DB_HOST');
                            $username = env('DB_USERNAME');
                            $password = env('DB_PASSWORD');
                            $dbname = env('DB_DATABASE');
                            $socket = env('DB_SOCKET');


                            // $arrayversionlua = NULL;
                            
                            // Create a PDO instance
                            try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname;unix_socket=$socket", $username, $password);
                                // $conn = new PDO("mysql:host=$servername;dbname=$dbname;", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                // Query to get the latest version from the table 'lua_versions' where file_id = 1
                                $query = "SELECT * FROM lua_versions WHERE File_Id = " . $file->id . " ORDER BY Id DESC LIMIT 1";
                            
                                // Execute the query
                                $result = $conn->query($query);
                            
                                // Fetch the result as an associative array
                                $latestVersion = $result->fetch(PDO::FETCH_ASSOC);
                            
                                // Declare and initialize the $arrayversionslua variable as an empty array
                                $arrayversionslua = [];

                                // dd($latestVersion);
                            
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

                                          // dd($arrayversionlua);
                                            
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

                                            // dd($arrayversionlua);
                                            ?>
                                                
                                                
                                              <?

                                              // dd($arrayversionlua);
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
                                    <button id="submitButton" class="btn btn-success m-t-20">Submit</button>
                                    
                                  </div>

                                  <div class="col-md-12" style="margin-top: 100px;">
                                    <div class="clearfix"></div>
                                      @foreach($file->downloadLuaFiles as $df)
                                      <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                      <span class="pull-left">{{$df->request_file}}</span>
                                        <div class="pull-right">
                                          

                                            <a href="{{ route('download',[$file->id, $df->request_file, 0]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download</span>
                                            </a>
                                          
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                      @endforeach
                                      
                                  </div>
                              
                            </div>
                          </div>
                        </div>           
                       
                        <div class="tab-pane slide-left" id="slide7">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }} {{ $file->engine }}</h3>
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
                                  <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }} {{ $file->engine }}</h3>
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
                                            // dd($arrayversionlua);
                                            ?>
                                                
                                                
                                              <?
                                              // dd($arrayversionlua);
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
                                    {{-- <button id="submitButtonFDB">Submit</button> --}}
                                  </div>
                              
                            </div>

                            
                          </div>
                        </div>                      
                                   

        </div>


    @if($file->new_requests)
    </div>

    @php 
      $newreqs = count($file->new_requests);
      $count = 1;
    @endphp

    @foreach($file->new_requests as $file)
    
    <div class="tab-pane slide-left @if($count == $newreqs) show active @endif" id="tab4FollowUs{{$file->id}}">

      @php 
        $count++;
      @endphp
      <div class="card card-transparent m-t-40">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
          <li class="nav-item">
            <a href="#"  @if(!Session::has('tab')) class="active" @endif data-toggle="tab" data-target="#slide1{{$file->id}}"><span>Task</span></a>
          </li>

          @if( $file->subdealer_group_id == NULL) 
         
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
          
          {{-- @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'admin-tasks')) --}}
          
          

          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide3{{$file->id}}"><span>Admin Tasks</span></a>
          </li>

          
          {{-- @endif --}}
          
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide4{{$file->id}}"><span>Logs</span></a>
          </li>
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide23{{$file->id}}"><span>Status Logs</span></a>
            
          </li>

          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide24{{$file->id}} 7"><span>Engineer Assignment Logs</span></a>
          </li>

          {{-- @if($file->decoded_files->isEmpty()) --}}
            {{-- @if($file->tool_type == 'slave' && $file->tool_id != $kess3Label->id) --}}
              <li class="nav-item">
                <a href="#" data-toggle="tab" data-target="#slide5{{$file->id}}"><span>Upload New File</span></a>
              </li>
            {{-- @endif --}}
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
                  <div class="card-header @if($file->frontend->id == 1) bg-primary-light @elseif($file->frontend->id == 3) bg-info-light @else bg-warning-light @endif">

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
                          <img src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="" style="width: 30%;">
                          
                          <h4>{{$file->brand}} {{$file->model}} {{ $file->engine }} (New Request)</h4>
                          
                          @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'download-client-file'))
                          
                          @if($file->original_file_id)

                          {{-- @php
                            $oriFile = \App\Models\File::findOrFail($file->original_file_id);
                          @endphp --}}
                              
                                <a href="{{ route('download', [$file->id, $file->file_attached, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's File</span>
                                </a>
                              
                            
                                
                            {{-- @if($oriFile->decoded_mode == 0)
                              <a href="{{ route('download', [$oriFile->id, $oriFile->file_attached, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's File</span>
                              </a>
                            @endif --}}

                            @if($file->acm_file)
                              <a href="{{ route('download', [$file->id, $file->acm_file, 0]) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download Client's ACM MCM/ECM File</span>
                              </a>
                              @endif

                              

                            {{-- @if($oriFile->tool_type == 'slave' && $oriFile->tool_id == $kess3Label->id) --}}
                            @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id || $file->tool_id != $kess3Label->id)
                            {{-- here we are. --}}
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

                          @if($file->assigned_to == NULL)
                            <form method="POST" action="{{route('assigned-to-me')}}">
                              @csrf
                              <input type="hidden" name="file_id" value="{{$file->id}}">
                              <button class="btn btn-danger" type="submit">Assigned To Me</button>
                            </form>
                          @endif

                        </div>
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">

                    @if($file->disable_cusno_longer_autotomers_download)
                      <div class="row m-t-40">
                        <div class="col-12 col-xl-12 bg-danger-light text-white m-b-10 m-t-10 m-l-10" style="height: 100%;">
                          <p class="no-margin p-t-10 p-b-10">Because AlientTech could not encrypt these files so any Auto reply from system or manual reply from engineer will not appear on Customer's side. We do not want them to download raw file without encoding. Now please delete all the files in revision and upload encoded file manually. After doing that please click on the button to enable download. After you will click on that button, the state of revisions will be visible to Customer to download. Please be careful. Thanks.</p>
                          <form action="{{route('enable-download')}}" method="POST" class="text-center"> @csrf <input type="hidden" value="{{$file->id}}" name="id"> <button class="btn btn-info m-b-10" type="submit" >Enable Download on Customer Side</button></form>
                        </div>
                      </div>
                    @endif

                    @if($file->no_longer_auto)
                      <div class="row m-t-40">
                        <div class="col-12 col-xl-12 bg-danger-light text-white m-b-10 m-t-10 m-l-10" style="height: 100%;">
                          <p class="no-margin p-t-10 p-b-10">During auto encoding, an error occured. Please check which file is not encoded and make a decision accordingly to share it with customer or not.</p>
                          
                        </div>
                      </div>
                    @endif

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
                            <span class="label label-success 123">{{$file->user->name}}<span>
                          </div>
                          <div class="clearfix"></div>
                          <div><i class="fa-solid fa-flag"></i></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Customer Email</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->user->email}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Frontend</p>
                          <div class="pull-right">
                            <span class="label @if($file->frontend->id == 1) text-white bg-primary @elseif($file->frontend->id == 3) text-white bg-info @else text-black bg-warning @endif">{{$file->frontend->name}}<span>
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

                        @if($file->assignment_time)
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Assigment Time</p>
                          <div class="pull-right">
                            <span class="label label-success">{{ \Carbon\Carbon::parse($file->assignment_time)->diffForHumans() }}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        @endif

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

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Tool</p>
                          <div class="pull-right">
                              <img alt="{{$file->tool_id}}" width="50" height="" data-src-retina="{{ get_dropdown_image($file->tool_id) }}" data-src="{{ get_dropdown_image($file->tool_id) }}" src="{{ get_dropdown_image($file->tool_id) }}">
                              <span class="" style="top: 2px; position:relative;">{{ \App\Models\Tool::findOrFail( $file->tool_id )->name }}({{$file->tool_type}})</span>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        

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
        
                        @if(!$file->gearbox_ecu)
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
                        @endif
                        
						@if($file->gearbox_ecu)
                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Gearbox ECu</p>
                          <div class="pull-right">
                            @if($file->gearbox_ecu)
                              <span class="label bg-warning">{{App\Models\ECU::findOrFail($file->gearbox_ecu)->type}}<span>
                            @else
                              <span class="label label-danger">NO Gear Box ECU<span>
                            @endif
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
                            @if($vehicle)
                              @if($file->ecu)                  
                                <a class="btn btn-success btn-cons m-b-10" href="{{route('add-comments', [$vehicle->id, 'file='.$file->id])}}"><span class="bold">Go To Comments</span></a>
                              @endif
                            
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('vehicle', $vehicle->id)}}"><span class="bold">Go To Vehicle</span></a>
                            @endif
                            <a class="btn btn-success btn-cons m-b-10" href="{{route('edit-file', $file->id)}}"><span class="bold">Edit File</span></a>
                            
                              
                                <button type="button" class="btn btn-danger btn-delete btn-cons m-b-10" data-file_id={{$file->id}}><span class="bold">Delete File</span></button>
                              
                          </div>
                        
                        
                      </div>
        
                      <div class="col-lg-6">
                        {{-- <h5 class="m-t-40">Reading Tool</h5>
        
                            
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Tool</p>
                          <div class="pull-right">
                              <img alt="{{$file->tool_id}}" width="50" height="" data-src-retina="{{ get_dropdown_image($file->tool_id) }}" data-src="{{ get_dropdown_image($file->tool_id) }}" src="{{ get_dropdown_image($file->tool_id) }}">
                              <span class="" style="top: 2px; position:relative;">{{ \App\Models\Tool::findOrFail( $file->tool_id )->name }}({{$file->tool_type}})</span>
                          </div>
                          <div class="clearfix"></div>
                        </div> --}}
                     
        
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

                                    @elseif($file->front_end_id == 3)

                                @if($file->tool_type == 'master')
                                <span class="text-white label-danger label"> {{$stage->efiles_credits}} </span>
                                @else
                                  <span class="text-white label-danger label"> {{$stage->efiles_slave_credits}} </span>
                                @endif

                                @else
                                  <span class="text-white label-danger label"> {{$stage->credits}} </span>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                          </div>
                          <div class="b-t b-grey p-b-10 p-t-10">

                            
                            @php

                                $records = \App\Models\FileReplySoftwareService::join('files', 'files.id', '=', 'file_reply_software_service.file_id')
                                ->where('file_reply_software_service.service_id', $stage->id)
                                ->where('files.ecu', $file->ecu)
                                ->where('files.brand', $file->brand)
                                ->select('file_reply_software_service.software_id')
                                ->distinct('file_reply_software_service.software_id')->get();

                                

                            @endphp
                          
                          @foreach($records as $record)

                          @php

                              $totals = all_files_with_this_ecu_brand_and_service_and_software($file->brand, $file->ecu, $stage->id, $record->software_id);
                              $revised = all_files_with_this_ecu_brand_and_service_and_software_revisions($file->brand, $file->ecu, $stage->id, $record->software_id);

                          @endphp

                          {{-- <div style="display: flow-root;" class="b-b b-grey">
                            <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                              <div class="pull-right">
                                No of File: {{$totals}}
                              </div>
                          </div> --}}
                          
                          <div style="display: flow-root;" class="b-b b-grey">
                          <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                          
                          @if($totals != 0)
                            <div class="pull-right">
                              {{round((($totals - $revised) / $totals)*100, 2).'%'}}
                            </div>
                          @endif

                          @if($totals != 0)
                          <div class="pull-right">
                            <span class="label label-success m-r-5">{{$totals}}</span>
                          </div>
                          @endif
                          
                          </div>
                          

                          @endforeach
                            
                            
                           
                          </div>
                        @endif
                      @else

                      @if($file->stage_services)
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

                                  @elseif($file->front_end_id == 3)

                                @if($file->tool_type == 'master')
                                <span class="text-white label-danger label"> {{$stage->efiles_credits}} </span>
                                @else
                                  <span class="text-white label-danger label"> {{$stage->efiles_slave_credits}} </span>
                                @endif

                              @else
                                <span class="text-white label-danger label"> {{$stage->credits}} </span>
                              @endif
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        <div class="b-t b-grey p-b-10 p-t-10">
                          

                          @php

                                $records = \App\Models\FileReplySoftwareService::join('files', 'files.id', '=', 'file_reply_software_service.file_id')
                                ->where('file_reply_software_service.service_id', $stage->id)
                                ->where('files.ecu', $file->ecu)
                                ->where('files.brand', $file->brand)
                                ->select('file_reply_software_service.software_id')
                                ->distinct('file_reply_software_service.software_id')->get();

                                

                            @endphp
                          
                          @foreach($records as $record)

                          @php

                              $totals = all_files_with_this_ecu_brand_and_service_and_software($file->brand, $file->ecu, $stage->id, $record->software_id);
                              $revised = all_files_with_this_ecu_brand_and_service_and_software_revisions($file->brand, $file->ecu, $stage->id, $record->software_id);

                          @endphp

                          {{-- <div style="display: flow-root;" class="b-b b-grey">
                            <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                              <div class="pull-right">
                                No of File: {{$totals}}
                              </div>
                          </div> --}}

                         
                          <div style="display: flow-root;" class="b-b b-grey">
                          <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                          
                          @if($totals != 0)
                            <div class="pull-right">
                              {{round((($totals - $revised) / $totals)*100, 2).'%'}}
                            </div>
                          @endif

                          @if($totals != 0)
                          <div class="pull-right">
                            <span class="label label-success m-r-5">{{$totals}}</span>
                          </div>
                          @endif
                          
                          </div>
                          

                          @endforeach

                          
                        </div>
                        @endif
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
                                {{\App\Models\Service::where('id', $option->service_id)->first()->name}}  ({{\App\Models\Service::where('id', $option->service_id)->first()->vehicle_type}}) (@if(\App\Models\Service::findOrFail( $option->service_id )->active == 1) {{'ECU Tech'}} @elseif(\App\Models\Service::findOrFail( $option->service_id )->tuningx_active == 1) {{'TuningX'}} @else {{'E-files'}} @endif)
                                @php $optionInner = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                                @if($file->front_end_id == 2 || $file->front_end_id == 3)
                                  @if($file->tool_type == 'master')
                                    <span class="text-white label-danger label pull-right"> {{$optionInner->optios_stage($file->stage_services->service_id)->first()->master_credits}} </span>
                                  @else
                                    <span class="text-white label-danger label pull-right"> {{$optionInner->optios_stage($file->stage_services->service_id)->first()->slave_credits}} </span>
                                  @endif

                              @else
                                <span class="text-white label-danger label pull-right"> {{$optionInner->credits}} </span>
                              @endif
                              </div>

                              <div class="b-t b-grey p-b-10 p-t-10">
                                

                                @php

                                $records = \App\Models\FileReplySoftwareService::join('files', 'files.id', '=', 'file_reply_software_service.file_id')
                                ->where('file_reply_software_service.service_id', $optionInner->id)
                                ->where('files.ecu', $file->ecu)
                                ->where('files.brand', $file->brand)
                                ->select('file_reply_software_service.software_id')
                                ->distinct('file_reply_software_service.software_id')->get();

                                

                            @endphp
                          
                          @foreach($records as $record)

                          @php

                              $totals = all_files_with_this_ecu_brand_and_service_and_software($file->brand, $file->ecu, $optionInner->id, $record->software_id);
                              $revised = all_files_with_this_ecu_brand_and_service_and_software_revisions($file->brand, $file->ecu, $optionInner->id, $record->software_id);

                          @endphp

                          {{-- <div style="display: flow-root;" class="b-b b-grey">
                            <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                              <div class="pull-right">
                                No of File: {{$totals}}
                              </div>
                          </div> --}}
                          
                          <div style="display: flow-root;" class="b-b b-grey">
                          <div class=" pull-left">{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</div>
                          
                            @if($totals != 0)
                            <div class="pull-right">
                              {{round((($totals - $revised) / $totals)*100, 2).'%'}}
                            </div>
                            @endif

                            @if($totals != 0)
                            <div class="pull-right">
                              <span class="label label-success m-r-5">{{$totals}}</span>
                            </div>
                            @endif
                          
                          </div>
                          

                          @endforeach
                                
                                
                              </div>

                              @foreach($file->comments as $c)
                              @if($c->service_id == $option->service_id)
                                <div class="b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                  <p class="pull-left text-danger">{{$optionInner->name}} Customers Comments</p>
                                  <br>
                                  <div class="m-l-20 text-danger">
                                    {{$c->comment}}
                                  </div>
                                  <div class="clearfix"></div>
                                </div>
                              @endif
                            @endforeach

                            @endif
                            
                            @if($comments)
                              @foreach($comments as $comment)

                                  @if($optionInner->id == $comment->service_id)
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

                      {{-- <div class="b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Show Comments</p>
                        <div class="pull-right">

                          <input data-file_id={{$file->id}} class="show_comments" type="checkbox" data-init-plugin="switchery" @if($file->show_comments) checked="checked" @endif onclick="show_comments_flip()"/>
                        </div>
                        <div class="clearfix"></div>
                      </div> --}}
                     
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

                      <div class="col-lg-12">
                        <h5 class="m-t-40">Versions</h5>

                        @if($file->status == 'submitted' || $file->status == 'ready_to_send' || $file->status == 'completed')
                        
                        @if($activeFeedType == 'danger') 
                          <button class="btn btn-success m-b-20 btn-show-message-form" data-file_id="{{$file->id}}">Upload Version.</button>
                        @else
                          
                          <button class="btn btn-success m-b-20 btn-show-software-form" data-file_id="{{$file->id}}">Upload Version.</button>
                        
                        @endif

                        @else
                          <h5 class="text-danger">File Status must be sumbitted or completed.</h5>
                        @endif

                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Number of Versions</p>
                          <div class="pull-right">
                           
                              <label class="label bg-info text-white">{{$file->files->count()}}</label>
                          </div>
                          <div class="clearfix"></div>
                        </div>

                            @foreach($file->files->toArray() as $message)


                            <div class="card">

                              <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                              <li class="nav-item">
                                <a href="#" class="active" data-toggle="tab" data-target="#reply_data_{{$message['id']}}"><span>Version Information</span></a>
                              </li>
								@if($vehicle->type == 'truck' || $vehicle->type == 'machine' || $vehicle->type == 'agri')
                              <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#acm_data_{{$message['id']}}"><span>ACM Information</span></a>
                              </li>
								@endif
                              @if($file->softwares->isNotEmpty())
                              <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#software_data_{{$message['id']}}"><span>Software Information</span></a>
                              </li>
                              @endif
                            </ul>
                            <!-- Tab panes -->
    
                            <div class="tab-content">
                              <div class="tab-pane slide-left active" id="reply_data_{{$message['id']}}" style="height: 100%;">

                                @if($message['show_later'] == 1)
                                      <button style="float: right;" class="btn btn-info m-b-2 btn-show-send-file-form m-l-10" data-file_id="{{$file->id}}" data-request_file_id="{{$message['id']}}">Send File To Customer</button>
                                @endif

                                {{-- @if($file->status == 'completed') --}}
                                    <button style="float: right;" class="btn btn-success m-b-20 btn-show-software-edit-form" data-file_id="{{$file->id}}" data-new_request_id="{{$message['id']}}">Edit Processiong Softwares</button>
                                {{-- @endif --}}

                              @if(isset($message['request_file']))
                                @if($message['engineer'] == 1)
                            <div class="p-l-20 p-r-20 p-b-10 p-t-10">
                              <p class="pull-left">{{$message['request_file']." "}}@if($message['user_id']){{' (Uploaded By: '.App\Models\User::findOrFail($message['user_id'])->name.')'}}@endif</p>@if($message['old_name'])<br><p class="hint-text">({{$message['old_name']}})</p>@endif
                                
                                         
                              <?php

                                $madeproject = DB::table('lua_make_project')
                                ->where('requestfile', $message['id'])
                                ->limit(1)
                                ->select('id', 'orifile', 'modfile', 'name','requestfile','olsname')
                                ->first();

                                // dd($madeproject);
                                
                                
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
                                                $data = json_decode($message['olsname'], true);

                                                // dd($message);
                                                
                                                // if ($message['lua_command'] === null){
                                                  
                                                // }else{
                                                //     foreach ($data as $item) {
                                                      ?>
                                                        <p class="pull-left"><? echo $data;?></p>

                                    <br/>
                                                        <?
                                                  //   }
                                                  // }
                                                ?>
                                                
                                                <?
                                                            // $data = json_decode($message['lua_command_fdb'], true);
                                                            
                                                            // if ($message['lua_command_fdb'] === null){
                                                              
                                                            // }else{
                                                            //     foreach ($data as $item) {
                                                                  ?>
                                                                    {{-- <p class="pull-left"><? // echo $item['mod'] . ' => ' . $item['name'];?><b> FDB FILE</b></p> --}}
                                                                    <?
                                                              //   }
                                                              // }
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

                                      {{-- @if(count($messageFile->engineer_file_notes_have_unseen_messages))
                                      <span id="circle"></span>
                                      @endif --}}
                                      {{-- <a target="_blank" href="{{route('support', $message['id'])}}" class="btn-sm btn-cons btn-info"><i class="fa fa-question text-white"></i> Support</a> --}}
                                    @endif

                                    @if($showComments)
                                    <div class="checkbox check-success checkbox-circle">
                                      <input class="show_comments" type="checkbox" @if($message['show_comments']) checked="checked"  value="1" @endif data-id="{{$message['id']}}" id="checkbox_{{$message['id']}}">
                                      <label for="checkbox_{{$message['id']}}">Show Comments</label>
                                    </div>
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
                                @if($file->no_longer_auto == 0)
                                  @if($file->tool_type == 'slave' && $file->tool_id == $kess3Label->id)
                                  <div>
                                    <p>Please click on "Download Encrypted" Button to download and test the system. This way user will get Encrypted file or you will get the error so that you can process the file, manually.</p>
                                  </div>
                                  <div class="text-center m-b-20">
                                    <a href="{{ route('download-encrypted',[$message['file_id'], $message['request_file'], false]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download Encrypted</span>
                                    </a>
                                  </div>
                                  @endif
                                  @endif
                                  @if($file->tool_type == 'slave' && $file->tool_id == $flexLabel->id)
                                    <div>
                                      <p>Please click on "Download Encrypted Magic File" Button to download and test the system. This way user will get Encrypted file or you will get the error so that you can process the file, manually.</p>
                                    </div>
                                    <div class="text-center">
                                      <a href="{{ route('download-magic',[$message['file_id'], $message['id']]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download Encrypted Magic File</span>
                                      </a>
                                    </div>
                                  @endif
                                  @if($file->tool_type == 'slave' && $file->tool_id == $autotunerLabel->id)
                                    <div>
                                      <p>Please click on "Download Encrypted Autoturner File" Button to download and test the system. This way user will get Encrypted file or you will get the error so that you can process the file, manually.</p>
                                    </div>
                                    <div class="text-center m-b-20">
                                      <a href="{{ route('download-autotuner',[$message['file_id'], $message['id']]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download Encrypted Autotuner File</span>
                                      </a>
                                    </div>
                                  @endif
                                <div class="clearfix"></div>
                                <span class="btn-sm btn-cons btn-success m-t-50">{{ "Uploaded At:". date('H:i:s d/m/Y', strtotime($message['created_at']))}} </span>
                                @if($message['downloaded_at'])<span class="btn-sm btn-cons btn-danger m-t-50">{{ "Downloaded At:". date('H:i:s d/m/Y', strtotime($message['created_at']))}} </span>@endif
                                
                                <div class="full-width">

                                  <form action="{{route('set-new-request-comment')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="new_request_id" value="{{$message['id']}}">
                                    <label class="m-t-10">Comment</label>
                                    <br>
                                    <textarea name="comment" style="display: block; width: 100%;">@if(\App\Models\NewRequestComment::where('new_request_id', $message['id'])->first() != NULL){{\App\Models\NewRequestComment::where('new_request_id', $message['id'])->first()->comment}}@endif</textarea>
                                    <br>
                                    <input type="submit" class="btn-sm btn-cons btn-success m-t-10" value="Update">
                                  </form>
                                </div>

                            </div>
        
                        @endif
                        @endif

                              </div>


                              <div class="tab-pane slide-left" id="acm_data_{{$message['id']}}" style="height: 300px;">
                                  
                                @if(isset($message['request_file']))

                              <div class="clearfix"></div>

                              <h5 class="m-t-40">Upload ACM File reply</h5>
                      
                              <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                
                                <div class="">
                                  <form action="{{ route('upload-acm-reply') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
      
                                    <input type="hidden" name="file_id" id="file_id" value="{{$file->id}}">
                                    <input type="hidden" name="request_file_id" id="request_file_id" value="{{$message['id']}}">
                                    <input type="file" name="acm_file" id="acm_file" required>
                                    
                                    <input type="submit" value="Upload" class="btn btn-success">
                                  </form>
                                </div>
                                <div class="clearfix"></div>
                              </div>

                              <div class="clearfix"></div>

                              @if(isset($messageFile->acm_files))
                              @foreach($messageFile->acm_files as $acm_file)
                                <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">{{$acm_file->acm_file}}</p>
                                    <div class="pull-right">
                                      

                                        <a href="{{ route('download',[$file->id, $acm_file->acm_file, 0]) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download</span>
                                        </a>
                                        <a href="#" class="btn-sm btn-cons btn-danger delete-acm-file" data-acm_file_id="{{$acm_file->id}}"><i class="pg-trash text-white"></i></a>
                                    </div>

                                    <div class="clearfix"></div>
                                      
                                </div>
                              @endforeach
                              @endif

                              {{-- <div class="clearfix"></div> --}}

                             
                          
      
                      @endif
                      

                    </div>

                    @if($file->softwares->isNotEmpty())

                     <div class="tab-pane slide-left" id="software_data_{{$message['id']}}" style="height: 300px;">
                      
                      @if($file->softwares->isNotEmpty())

                       <div class="card-body">
                        <div class="table-responsive"
                        
                        style="
                          
                          overflow:hidden;
    overflow-y: scroll;
    height: 200px;

                          "

                        >

                       <table class="table table-hover" id="basicTable">
                        <thead>
                          <tr>
                            
                            
                            <th style="width:20%">Stage or Option</th>
                            <th style="width:20%">Software</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($file->softwares as $software)

                          @if($software->reply_id == $message['id'])

                            <tr>
                              
                              <td class="v-align-middle ">
                                @if(\App\Models\Service::where('id', $software->service_id)->first() != NULL)
                                 <p>{{ \App\Models\Service::findOrFail( $software->service_id )->name}}</p>
                                 @else
                                   <p>Service is not available anymore.</p>
                                 @endif
                              </td>
                              <td class="v-align-middle">
                                <p>{{\App\Models\ProcessingSoftware::findOrFail( $software->software_id )->name}}</p>
                              </td>
                            
                            </tr>

                            @endif
                          @endforeach
                        </tbody>
                      </table>

                        
                        </div>
                       </div>
                        

                       @endif

                      </div>

                      @endif

                  
                  </div>
                          </div>


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
                                @elseif($file->front_end_id == 3)
                                  @if($file->tool_type == 'master')
                                  <span class="text-white label-danger label"> {{$stage->efiles_credits}} </span>
                                  @php $proposedCredits += $stage->efiles_credits; @endphp
                                  @else
                                    <span class="text-white label-danger label"> {{$stage->efiles_slave_credits}} </span>
                                    @php $proposedCredits += $stage->efiles_slave_credits; @endphp
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
                      
                      {{-- @if($file->status == 'submitted' || $file->status == 'completed')

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file')) --}}
                      
                      {{-- <div class="col-xl-12 m-t-20">
                        <div class="card card-transparent flex-row">
                          <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-white" id="tab-3"> --}}

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                              @if($file->decoded_files)
                              <li class="nav-item">
                                <a href="#" class="active show" data-toggle="tab" data-target="#tab3hellowWorld">Encode</a>
                              </li>
                              @endif
                            @endif --}}

                            {{-- <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab3FollowUs" class="">Upload</a>
                            </li> --}}
                            {{-- <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab3Inspire">Three</a>
                            </li> --}}
                          {{-- </ul>
                          <div class="tab-content bg-white full-width"> --}}

                            {{-- @if($file->tool_type == 'slave' && $file->tool == 'Kess_V3')
                            @if($file->decoded_files) --}}

                            {{-- <div class="tab-pane active show" id="tab3hellowWorld">
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
                            </div> --}}

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

                          {{-- </div>
                        </div>
                      </div>
                      @endif
                      @endif --}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if(( $file->subdealer_group_id == NULL))
          <div class="tab-pane slide-left @if(Session::get('tab') == 'chat') active @endif" id="slide2{{$file->id}}">
            <div class="row">
              <div class="col-lg-12">
                <div class="widget-16 card no-border widget-loader-circle">
                  <div class="card-header @if($file->frontend->id == 1) bg-primary-light @elseif($file->frontend->id == 3) bg-info-light @else bg-warning-light @endif">
                    <div class="text-center">
                      <div class="card-title">
                          <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                          <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
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

                        {{-- @php
                          echo "one";
                          dd($file->files_and_messages_sorted());
                        @endphp --}}

                        @foreach($file->files_and_messages_sorted() as $message)
                        
                         
                          @if(isset($message['egnineers_internal_notes']))
                            @if($message['engineer'] == 1)
                            <div class="message clearfix">
                              <div class="chat-bubble bg-primary from-me text-white">
                                
                                <p class="" style="font-size: 8px;float:left">@if($message['request_file_id'] != NULL){{ \App\Models\RequestFile::findOrFail($message['request_file_id'])->request_file }}@endif</p>
                                <br>
                                <p>{!! $message['egnineers_internal_notes'] !!} </p>
                                
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
                                @if($message['user_id'])<small class="m-t-20" style="font-size: 8px; float:right">{{  \App\Models\User::findOrFail($message['user_id'])->name }}</small>@endif
                              </div>
                            </div>
          
                            @elseif($message['engineer'] == 0)
                              <div class="message clearfix">
                                <div class="chat-bubble from-them bg-success">
                                  {{-- @php
                                    echo 'one';
                                    dd($message['request_file_id']);
                                  @endphp --}}
                                  <p class="" style="font-size: 8px;float:left">@if($message['request_file_id'] != NULL){{ \App\Models\RequestFile::findOrFail($message['request_file_id'])->request_file }}@endif</p>
                                  <br>  
                                  <p>{{ $message['egnineers_internal_notes'] }}</p><br>
                                    @if(isset($message['engineers_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download',[$message['file_id'], $message['engineers_attachement'], 0])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
                                    <br>
                                    <br>
                                    <small class="m-t-20" style="font-size: 8px;float:left">
                                      @if(is_text_english($message['egnineers_internal_notes']))
                                      <button class="btn btn-default btn-xs translate" href="#" data-id="{{$message['id']}}"><i class="fa fa-language" aria-hidden="true"></i></button>
                                    @endif
                                    </small>
                                    <small class="m-t-20" style="font-size: 8px;float:right">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small>
                                </div>
                              </div>
                            @endif
                          @endif


                          @if(isset($message['events_internal_notes']))
                            
                            <div class="message clearfix">
                              <div class="chat-bubble bg-success from-them text-white">
                                {{ $message['events_internal_notes'] }}<br>
                                @if(isset($message['events_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download',[$message['file_id'], $message['events_attachement'], 0])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
                              </div>
                            </div>
          
                            
                          @endif
                        @endforeach
                      </div>
                      @endif
                      <!-- BEGIN Conversation  !-->
                      <!-- BEGIN Chat Input  !-->
                      <div class="b-t b-grey bg-white clearfix p-l-10 p-r-10 text-center">
                        <div class="row">
                          <div class="col-lg-9">
                        <form method="POST" action="{{ route('file-engineers-notes') }}" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" value="{{$file->id}}" name="file_id">
                        <div class="row">
                            <div class="col-6 no-padding">
                              <textarea name="egnineers_internal_notes" class="form-control" placeholder="Reply to cusotmer." required></textarea>
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
                    <div class="col-lg-3">
                      <span style="display: flex; float:right;" class="m-t-15">

                        @if($file->status != 'on_hold')
                          <form method="POST" action="{{ route('set-file-on-hold') }}">
                            @csrf
                            <input type="hidden" value="{{$file->id}}" name="file_id">
                            <button class="btn btn-info" type="submit">On Hold</button>
                          </form>
                        @endif
  
                        <a class="btn btn-info m-l-5" href="{{route('dtc-lookup')}}" target="_blank">DTC</a>
                      </span>
                    </div>
                    </div> 
                      

                        <div class="b-t b-grey bg-white m-t-15 clearfix">
                          <span style="display: flex; float:right;" class="p-t-5">
                            @if($file->customer_message == NULL)
                              <button data-file_id="{{$file->id}}" class="btn btn-info btn-msg-later" type="button">Save a Message to send Later</button>
                            @else
                              <button data-file_id="{{$file->id}}" class="btn btn-success m-l-5 btn-msg-sent" type="button">Send Saved Message</button>
                            @endif
                          </span>
                        </div>

                      </div>
                      <!-- END Chat Input  !-->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          {{-- @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'admin-tasks')) --}}
          <div class="tab-pane slide-left" id="slide3{{$file->id}}">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
                      <h4 class="m-t-20">Adminstrative Tasks</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  
                  @if($file->status != "rejected")

                  {{-- @if(Auth::user()->is_admin()) --}}
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
                  {{-- @endif --}}

                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                    <p class="pull-left">File Status</p>
                    <form action="{{route('change-status-file')}}" method="POST">
                      @csrf
                      <input type="hidden" name="file_id" value="{{$file->id}}">
                      <div class="">
                        <select class="full-width" data-init-plugin="select2" name="status" id="select_status">
                            <option @if(isset($file) && $file->status == "submitted") selected @endif value="submitted">Submitted</option>
                            <option @if(isset($file) && $file->status == "rejected") selected @endif value="rejected">Canceled</option>
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
                  
                  {{-- @if(Auth::user()->is_admin() or Auth::user()->is_head()) --}}
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
                  {{-- @endif --}}
                  <br>
                </div>
              </div>
            </div>
            {{-- @endif --}}
           
            <div class="tab-pane slide-left" id="slide4{{$file->id}}">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
                      <h4 class="m-t-20">Logs</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="row" style="">

                @foreach($file->logs as $log)
                  @if($log->request_type == 'alientech' || $log->request_type == 'magic')
                    <div class="col-12 col-xl-12 @if($log->type == 'error') bg-danger-light @else bg-success-light @endif text-white m-b-10 m-t-10 m-l-10" style="height: 50px;">
                      <p class="no-margin p-t-10 p-b-10">{{$log->message}}</p>
                    </div>
                  @endif
                @endforeach

              </div>
            </div>

            <div class="tab-pane slide-left" id="slide24{{$file->id}}">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
                      <h4 class="m-t-20">Logs</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              

              <div class="row m-t-40">
                <div class="table-responsive">
                  <div id="condensedTable_wrapper" class="dataTables_wrapper no-footer"><table class="table table-hover table-condensed dataTable no-footer" id="condensedTable" role="grid">
                    <thead>
                      <tr role="row">
                        <th style="width:10%" class="sorting_asc" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Assigned From</th>
                        <th style="width: 20%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column ascending">Assigned to</th>
                        <th style="width: 20%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Assigned BY</th>
                        <th style="width: 10%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Assigned At</th>
                      </tr>
                    </thead>
                      <tbody>

                        @foreach($file->assignment_log as $t)
                        
                            <tr role="row" class="odd">
                                <td class="v-align-middle semi-bold">{{$t->assigned_from}}</td>
                                <td class="v-align-middle semi-bold">{{$t->assigned_to}}</td>
                                <td class="v-align-middle semi-bold">{{$t->assigned_by}}</td>
                                <td class="v-align-middle semi-bold">{{$t->created_at->diffForHumans()}}</td>
                            </tr>

                        @endforeach

                  </tbody>
              </table>
            </div>
          </div>

              </div>
              
            </div>

            <div class="tab-pane slide-left" id="slide23{{$file->id}}">
              <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }}</h3>
                      <h4 class="m-t-20">Logs</h4>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              
              <div class="row m-t-40">
                <div class="table-responsive">
                  <div id="condensedTable_wrapper" class="dataTables_wrapper no-footer"><table class="table table-hover table-condensed dataTable no-footer" id="condensedTable" role="grid">
                    <thead>
                      <tr role="row">
                        <th style="width:10%" class="sorting_asc" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Type</th>
                        <th style="width: 20%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column ascending">From</th>
                        <th style="width: 20%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">To</th>
                        <th style="width: 40%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Desc</th>
                        <th style="width: 10%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Changed By</th>
                        <th style="width: 10%;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Condensed: activate to sort column ascending">Changed At</th>
                      </tr>
                    </thead>
                      <tbody>

                        @foreach($file->status_logs as $s)
                        
                            <tr role="row" class="odd">
                                <td class="v-align-middle semi-bold sorting_1">{{$s->type}}</td>
                                <td class="v-align-middle">{{$s->from}}</td>
                                <td class="v-align-middle semi-bold">{{$s->to}}</td>
                                <td class="v-align-middle semi-bold">{{$s->desc}}</td>
                                <td class="v-align-middle semi-bold">{{\App\Models\User::findOrFail($s->changed_by)->name}}</td>
                                <td class="v-align-middle semi-bold">{{$s->created_at->diffForHumans()}}</td>
                            </tr>

                        @endforeach

                  </tbody>
              </table>
            </div>
          </div>

              </div>


            </div>
           
            {{-- @if($file->tool_type == 'slave' && $file->tool_id != $kess3Label->id) --}}
              <div class="tab-pane slide-left" id="slide5{{$file->id}}">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                    
                    </div>
                  </div>
                  <div class="card-body">
                    <h5>
                      Upload New File
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
                            @if($file->stage_services)
                            <input class="stages" type="radio" @if($file->stage_services->service_id == $stage->id) checked="checked" @endif value="{{$stage->id}}" name="custom_stage" id="{{$stage->id}}">
                            <label for="{{$stage->id}}">{{$stage->name}}</label>
                            @endif
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
            {{-- @endif --}}

                        <div class="tab-pane slide-left" id="slide6{{$file->id}}">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }} {{ $file->engine }}</h3>
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
                                            <img alt="{{$file->stages}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
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
                                          <img alt="{{\App\Models\Service::FindOrFail($file->stage_services->service_id)->name}}" width="33" height="" data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon}}" data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}" src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($file->stage_services->service_id)->icon }}">
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
                                            <img alt="{{\App\Models\Service::where('id', $option->service_id)->first()->name}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('id', $option->service_id)->first()->icon }}">
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
                                            data-src-retina="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                            data-src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}" 
                                            src="{{ url('icons').'/'.\App\Models\Service::FindOrFail($option->service_id)->icon }}">
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
                            $servername = env('DB_HOST');
                            $username = env('DB_USERNAME');
                            $password = env('DB_PASSWORD');
                            $dbname = env('DB_DATABASE');
                            $socket = env('DB_SOCKET');

                            // dd($socket);
                            
                            // Create a PDO instance
                            try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname;unix_socket=$socket", $username, $password);
                                // $conn = new PDO("mysql:host=$servername;dbname=$dbname;", $username, $password);
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

                                          // dd($arrayversionlua);
                                            
                                            ?>
                                            <div class="col-lg-12">
                                                <h5>
                                                    <?php
                                                    echo $arrayversionlua['name'] . ' // ' . $arrayversionlua['percentage'];
                                                    ?>
                            
                                                </h5>
                                                <?php

                                                // dd($arrayversionlua);
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
                                            // dd($arrayversionlua);
                                            ?>
                                                
                                                
                                              <?
                                              // dd($arrayversionlua);
                                              foreach ($arrayversionlua as $key => $value) {
                                                  if (is_numeric($key) && $value !== 'Original') {
                                                    $modifiedString = str_replace('/', '-', $value);
                                                    // dd($arrayversionlua);
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
                                    <button id="submitButton" class="btn btn-success m-t-20">Submit</button>
                                    
                                  </div>
                              
                            </div>
                          </div>
                        </div>           
                       
                        <div class="tab-pane slide-left" id="slide7{{$file->id}}">
                          <div class="card-header @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
                            <div class="text-center">
                              <div class="card-title">
                                  <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }} {{ $file->engine }}</h3>
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
                                  <img style="width: 30%;" src="@if($file->vehicle()){{ $file->vehicle()->Brand_image_URL }}@endif" alt="{{$file->brand}}" class="">
                                  <h3>{{$file->brand}} {{$file->model}} {{ $file->engine }} {{ $file->engine }}</h3>
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
                                                    // dd($arrayversionlua);
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
      
                  console.log(file);
            
                  engineerFileDrop1.removeFile(file);
                  
                  location.reload();
                })
                .on("complete", function(file) {
                  location.reload();
                }).on('error', function(e){
                  
                });
      
              };
            
            </script>

<!-- Modal -->
<div class="modal fade slide-up disable-scroll " id="MessageModal-{{$file->id}}" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-lg" class="width:90% !important;">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Upload File <span class="semi-bold"> Wihout notifying Customers.</span></h5>
          <p class="p-b-10">You can upload the file without notifying the customer with a Message. When you will upload later. Customer will be notified and Message will go in Chat.</p>
        </div>
        <div class="modal-body">
          <form role="form" id="QuestionForm-{{$file->id}}">
            <input type="hidden" name="file_id" value="{{$file->id}}">
            <div class="form-group-attached">
              <div class="row">
                <div class="col-md-8">
                
                  <div class="radio radio-success">
                    <input type="radio" checked="checked" value="now" name="notifyFile" id="notifyNow" data-file_id="{{$file->id}}">
                    <label for="notifyNow">Notify Customer Now</label>
                    <input type="radio" value="later" name="notifyFile" id="notifyLater" data-file_id="{{$file->id}}">
                    <label for="notifyLater">Notify Customer Later</label>
                  </div>

                </div>
                

              </div>
              
                <div id="later-area-{{$file->id}}" class="hide">
                  <div class="row">
                    <form role="form" id="addMessageForm-{{$file->id}}">
                      <input type="hidden" name="file_id" value="{{$file->id}}">
                      {{-- <div class="col-md-8">
                        <p class="text-danger" id="validation_{{$file->id}}"></p>
                        <textarea style="width: 100%;" id="customer_message_{{$file->id}}" class="form-control" placeholder="Add Message for customer to show him later." required></textarea>
                      </div> --}}
                      <div class="col-md-12 m-t-10 sm-m-t-10">
                        <button type="button" class="btn btn-success btn-block m-t-5 btn-add-message" data-file_id="{{$file->id}}">Add Softwares</button>
                      </div>
                    
                    </form>
                  </div>
                </div>
                
                <div id="now-area-{{$file->id}}">
                  <div class="row">
                    <div class="col-md-8 m-t-10 sm-m-t-10">
                      <button type="button" class="btn btn-success btn-block m-t-5 btn-show-software-form" data-file_id="{{$file->id}}">Just Go To Next Step</button>
                    </div>
                  </div>
                </div>
              

            </div>
          </form>

        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
<!-- /.modal-dialog -->
<!-- MODAL SLIDE UP SMALL  -->
<!-- Modal -->


            <!-- Modal -->
<div class="modal fade slide-up disable-scroll " id="softwareOptionsModal-{{$file->id}}" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-lg" class="width:90% !important;">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Software Processing <span class="semi-bold">Information and Uploading File in next step.</span></h5>
          <p class="p-b-10">You need to tell the system about Softwares you used to process the file before uploading the file itself.</p>
        </div>
        <div class="modal-body">
          <form role="form" id="softwareForm-{{$file->id}}">
            <input type="hidden" name="file_id" value="{{$file->id}}">
            <div class="form-group-attached">
              <div class="row">
                @php $stage = \App\Models\Service::FindOrFail($file->stage_services->service_id) @endphp
                @if($stage->name != 'Stage 0')
                  <div class="col-md-10">
                    <div class="form-group form-group-default">
                      <label><b>Stage:</b> {{$stage->name}}</label>
                      <input type="hidden" name="service_id" value="{{$stage->id}}">
                      <label class="m-t-10">Processing Software</label>
                      <select class="full-width" data-placeholder="Select Country" data-init-plugin="select2" name="processing-software-{{$stage->id}}">
                        @foreach($prossingSoftwares as $ps)  
                          <option value="{{$ps->id}}">{{$ps->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="checkbox check-success">
                      <input type="checkbox" name="exclude_service[]" value="{{$stage->id}}" id="exclude_stage">
                      <label for="exclude_stage">Exclude</label>
                    </div>
                  </div>
                @endif
                @if(!$file->options_services()->get()->isEmpty())

                  @foreach($file->options_services()->get() as $option)
                    
                  @php $optionInner = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                  <div class="col-md-10 m-t-10">
                    <div class="form-group form-group-default">
                      <label><b>Option:</b> {{$optionInner->name}}</label>
                      <label class="m-t-10">Processing Software</label>
                      <input type="hidden" name="service_id" value="{{$optionInner->id}}">
                      <select class="full-width" data-placeholder="Select Software" data-init-plugin="select2" name="processing-software-{{$optionInner->id}}">
                        @foreach($prossingSoftwares as $ps)  
                          <option value="{{$ps->id}}">{{$ps->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="checkbox check-success">
                      <input type="checkbox" name="exclude_service[]" value="{{$optionInner->id}}" id="exclude_option_{{$optionInner->id}}">
                      <label for="exclude_option_{{$optionInner->id}}">Exclude</label>
                    </div>
                  </div>
                  @endforeach
                @endif

                <div class="col-md-8">
                
                </div>

                <div class="col-md-4 m-t-10 sm-m-t-10">
                  <button type="button" class="btn btn-success btn-block m-t-5 show-file-upload-section" data-file_id="{{$file->id}}">Submit</button>
                </div>

              </div>
            </div>
          </form>

          <div class="row hide" id="fileUploadForm-{{$file->id}}">
            
            @if($file->status == 'submitted' || $file->status == 'ready_to_send' || $file->status == 'completed')
            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file'))
            <div class="col-xl-12 m-t-10">
              <div class="card card-transparent flex-row full-width">
    
                  
                    <div class="row column-seperation full-width">
                      
                      <div class="col-xl-12">
                        
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
            </div>
            @endif
            @endif
          
        </div>

          
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
<!-- /.modal-dialog -->
<!-- MODAL SLIDE UP SMALL  -->
<!-- Modal -->

    @endforeach

  </div>
</div>

@endif

      
    </div>
  </div>
</div>
							
<div class="modal fade slide-up disable-scroll" style="z-index: 9999;" id="translateModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          
        </div>
        <div class="modal-body">
          <p id="translated_text"></p>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div class="modal fade slide-up disable-scroll" style="z-index: 9999;" id="sendFile" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <h5>Send File to Customer</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                  {{-- <div class="form-group">
                    <form role="form" action="{{route('edit-customer-message')}}" method="POST">
                      @csrf
                      <input type="hidden" id="request_file_id_send_file" name="request_file_id" value="">
                      <div class="row">
                        <div class="col-md-8">
                          <textarea id="customer_message_textarea" name="message" required style="height: 100px;" class="form-control"></textarea>
                        </div>
                        <div class="col-md-4">
                          <button type="submit" class="btn btn-success btn-block m-t-5">Edit Message</button>
                        </div>
                      </div>
                    </form>
                  </div> --}}

                  <div class="col-md-12">
                    <div class="form-group">
                      <form role="form" action="{{route('send-customer-file')}}" method="POST">
                        @csrf
                        <input type="hidden" id="request_file_id_send_file_2" name="request_file_id" value="">
                        <input type="hidden" id="file_id_to_send_file" name="file_id" value="">
                        <div class="row">
                          
                          <div class="col-md-12">
                            <button type="submit" class="btn btn-success btn-block m-t-5">Send File To Customer</button>
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

<div class="modal fade slide-up disable-scroll" style="z-index: 9999;" id="addMessageModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" action="{{route('add-later-message')}}" method="POST">
            @csrf
            
            <input type="hidden" name="file_id" id="file_id_message_later">
            
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
              <button type="submit" class="btn btn-success btn-block m-t-5">Add Message</button>
            </div>
          </div>
        </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div class="modal fade slide-up disable-scroll" style="z-index: 9999;" id="sendMessageModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" action="{{route('send-message-to-customer')}}" method="POST">
            @csrf
            
            <input type="hidden" name="file_id" id="file_id_message_sent">
            
            <div class="form-group-attached ">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Message</label>
                    <textarea id="message_to_send" name="message" required style="height: 100px;" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
         
          <div class="row">
            <div class="col-md-8 m-t-10 sm-m-t-10 text-center">
              <button type="submit" class="btn btn-success btn-block m-t-5">Send Message To Customer</button>
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
                <h5>Change Options</h5>
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

  <!-- Modal -->
<div class="modal fade slide-up disable-scroll " id="MessageModal-{{$o_file->id}}" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-lg" class="width:90% !important;">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Upload File <span class="semi-bold"> Wihout notifying Customers.</span></h5>
          <p class="p-b-10">You can upload the file without notifying the customer. When you will upload later. Customer will be notified and Message will go in Chat.</p>
        </div>
        <div class="modal-body">
          <form role="form" id="QuestionForm-{{$o_file->id}}">
            <input type="hidden" name="file_id" value="{{$o_file->id}}">
            <div class="form-group-attached">
              <div class="row">
                <div class="col-md-8">
                
                  <div class="radio radio-success">
                    <input type="radio" checked="checked" value="now" name="notifyFile" id="notifyNow" data-file_id="{{$o_file->id}}">
                    <label for="notifyNow">Notify Customer Now</label>
                    <input type="radio" value="later" name="notifyFile" id="notifyLater" data-file_id="{{$o_file->id}}">
                    <label for="notifyLater">Notify Customer Later</label>
                  </div>

                </div>
                

              </div>
              
                <div id="later-area-{{$o_file->id}}" class="hide">
                  <div class="row">
                    <form role="form" id="addMessageForm-{{$o_file->id}}">
                      <input type="hidden" name="file_id" value="{{$o_file->id}}">
                      {{-- <div class="col-md-8">
                        <p class="text-danger" id="validation_{{$o_file->id}}"></p>
                        <textarea style="width: 100%;" id="customer_message_{{$o_file->id}}" class="form-control" placeholder="Add Message for customer to show him later." required></textarea>
                      </div> --}}
                      <div class="col-md-12 m-t-10 sm-m-t-10">
                        <button type="button" class="btn btn-success btn-block m-t-5 btn-add-message" data-file_id="{{$o_file->id}}">Add Softwares</button>
                      </div>
                    
                    </form>
                  </div>
                </div>
                
                <div id="now-area-{{$o_file->id}}">
                  <div class="row">
                    <div class="col-md-8 m-t-10 sm-m-t-10">
                      <button type="button" class="btn btn-success btn-block m-t-5 btn-show-software-form" data-file_id="{{$o_file->id}}">Just Go To Next Step</button>
                    </div>
                  </div>
                </div>
              

            </div>
          </form>

        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
<!-- /.modal-dialog -->
<!-- MODAL SLIDE UP SMALL  -->
<!-- Modal -->



  <div class="modal fade slide-up disable-scroll " id="softwareOptionsModal-{{$o_file->id}}" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg" class="width:90% !important;">
      <div class="modal-content-wrapper">
        <div class="modal-content">
          <div class="modal-header clearfix text-left">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
            </button>
            <h5>Software Processing <span class="semi-bold">Information</span></h5>
            <p class="p-b-10">You need to tell the system about Softwares you used to process the file before uploading the file itself.</p>
          </div>
          <div class="modal-body">
            <form role="form" id="softwareForm-{{$o_file->id}}">
              <input type="hidden" name="file_id" value="{{$o_file->id}}">
              <div class="form-group-attached">
                <div class="row">
                  @php $stage = \App\Models\Service::FindOrFail($o_file->stage_services->service_id) @endphp
                  @if($stage->name != 'Stage 0')
                  <div class="col-md-10">
                    <div class="form-group form-group-default">
                      <label><b>Stage:</b> {{$stage->name}}</label>
                      <input type="hidden" name="service_id" value="{{$stage->id}}">
                      <label class="m-t-10">Processing Software</label>
                      <select class="full-width" data-placeholder="Select Country" data-init-plugin="select2" name="processing-software-{{$stage->id}}">
                        @foreach($prossingSoftwares as $ps)  
                          <option value="{{$ps->id}}">{{$ps->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="checkbox check-success">
                      <input type="checkbox" name="exclude_service[]" value="{{$stage->id}}" id="exclude_stage">
                      <label for="exclude_stage">Exclude</label>
                    </div>
                  </div>
                  @endif
                  @if(!$o_file->options_services()->get()->isEmpty())
  
                    @foreach($o_file->options_services()->get() as $option)
                      
                    @php $optionInner = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                    <div class="col-md-10 m-t-10">
                      <div class="form-group form-group-default">
                        <label><b>Option:</b> @if($optionInner != NULL){{$optionInner->name}}@else Option is not available. @endif</label>
                        <label class="m-t-10">Processing Software</label>
                        @if($optionInner != NULL)
                        <input type="hidden" name="service_id" value="{{$optionInner->id}}">
                        <select class="full-width" data-placeholder="Select Software" data-init-plugin="select2" name="processing-software-{{$optionInner->id}}">
                          @foreach($prossingSoftwares as $ps)  
                            <option value="{{$ps->id}}">{{$ps->name}}</option>
                          @endforeach
                        </select>
                        @endif
                      </div>
                    </div>
                    @if($optionInner != NULL)
                    <div class="col-md-2">
                      <div class="checkbox check-success">
                        <input type="checkbox" name="exclude_service[]" value="{{$optionInner->id}}" id="exclude_option_{{$optionInner->id}}">
                        <label for="exclude_option_{{$optionInner->id}}">Exclude</label>
                      </div>
                    </div>
                    @endif
                    @endforeach
                  @endif
                    
                  

                    <div class="col-md-4 m-t-10 sm-m-t-10">
                      <button type="button" class="btn btn-success btn-block m-t-5 show-file-upload-section" data-file_id="{{$o_file->id}}">Submit</button>
                    </div>
   
                </div>
              </div>
            </form>
            <div class="row hide" id="fileUploadForm-{{$o_file->id}}">
              
                @if($o_file->status == 'submitted' || $o_file->status == 'ready_to_send' || $o_file->status == 'completed')
                @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file'))
                <div class="col-xl-12 m-t-10">
                  <div class="card card-transparent flex-row full-width">
        
                      
                        <div class="row column-seperation full-width">
                          
                          <div class="col-xl-12">
                            
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
                                  <input type="hidden" value="{{$o_file->id}}" name="file_id">
                                  @if($o_file->tool_type == 'slave' && $o_file->tool_id == $kess3Label->id)
                                    
                                    <input type="hidden" value="1" name="encode">
                                      @if($o_file->decoded_file)
                                        @if($o_file->decoded_file->extension == 'dec')
                                          <input type="hidden" value="dec" name="encoding_type">
                                        @else
                                          <input type="hidden" value="micro" name="encoding_type">
                                        @endif
                                      @endif
                                    
                                    @elseif($o_file->tool_type == 'slave' && $o_file->tool_id == $flexLabel->id)
                                      <input type="hidden" value="1" name="magic">

                                      <div class="col-md-8">
                                        <div class="form-group form-group-default">
                                          <label class="m-t-10">Flex Processing Option Encryption Type:</label>
                                          <select class="full-width" data-placeholder="Select Flex Option Processing Encryption Type" data-init-plugin="select2" name="magic_encryption_type">
                                            
                                              <option value="int_flash" selected>int_flash</option>
                                              <option value="ext_flash">ext_flash</option>
                                              <option value="int_eeprom">int_eeprom</option>
                                              <option value="ext_eeprom">ext_eeprom</option>
                                              <option value="maps">maps</option>
                                              <option value="full_dump">full_dump</option>
                                            
                                          </select>
                                        </div>
                                      </div>

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
                </div>
                @endif
                @endif
              
            </div>
            
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
  <!-- /.modal-dialog -->
  <!-- MODAL SLIDE UP SMALL  -->
  <!-- Modal -->


  <div class="modal fade slide-up disable-scroll " id="softwareOptionsEditModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg" class="width:90% !important;">
      <div class="modal-content-wrapper">
        <div class="modal-content">
          <div class="modal-header clearfix text-left">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
            </button>
            <h5>Software Processing <span class="semi-bold">Edit Information about Processing Software.</span></h5>
            <p class="p-b-10">If You have entered the wrong information about Processing Software. Please edit it.</p>
          </div>
          <div class="modal-body">
            <form role="form" id="" method="POST" action="{{route('update-processing-software')}}">
              @csrf
              <input type="hidden" name="file_id" value="" id="edit_software_file_id">
              <input type="hidden" name="reply_id" value="" id="edit_software_new_request_id">
              <div class="form-group-attached">
                <div class="row">
                  @php $stage = \App\Models\Service::FindOrFail($file->stage_services->service_id) @endphp
                  @if($stage->name != 'Stage 0')
                    <div class="col-md-12">
                      <div class="form-group form-group-default">
                        <label><b>Stage:</b> {{$stage->name}}</label>
                        <input type="hidden" name="service_id" value="{{$stage->id}}">
                        <label class="m-t-10">Processing Software</label>
                        <select class="full-width" data-placeholder="Select Software" data-init-plugin="select2" name="stage_software" id="processing-software-stage-edit">
                          
                        </select>
                      </div>
                    </div>
                  @endif
                  @if(!$file->options_services()->get()->isEmpty())
  
                    @foreach($file->options_services()->get() as $option)
                      
                    @php $optionInner = \App\Models\Service::where('id', $option->service_id)->first(); @endphp
                    <div class="col-md-12 m-t-10">
                      <div class="form-group form-group-default">
                        @if($optionInner != NULL)
                          <label><b>Option:</b> {{$optionInner->name}}</label>
                        
                          <label class="m-t-10">Processing Software</label>
                          <input type="hidden" name="option_id[]" value="{{$optionInner->id}}">
  
                        <select class="full-width" data-placeholder="Select Software" data-init-plugin="select2" name="option_softwares[]" id="processing-software-edit-option-{{$optionInner->id}}">
                          
                        </select>
  
                        @endif
                        
                      </div>
                    </div>
                    @endforeach
                  @endif
  
                  <div class="col-md-8">
                  
                  </div>
  
                  <div class="col-md-4 m-t-10 sm-m-t-10">
                    <button type="submit" class="btn btn-success btn-block m-t-5 show-file-upload-section" data-file_id="{{$file->id}}">Submit</button>
                  </div>
  
                </div>
              </div>
            </form>
  
            <div class="row hide" id="fileUploadForm-{{$file->id}}">
              
              @if($file->status == 'submitted' || $file->status == 'ready_to_send' || $file->status == 'completed')
              @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'submit-file'))
              <div class="col-xl-12 m-t-10">
                <div class="card card-transparent flex-row full-width">
      
                    
                      <div class="row column-seperation full-width">
                        
                        <div class="col-xl-12">
                          
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
              </div>
              @endif
              @endif
            
          </div>
  
            
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
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
        var baseUrl = env('BACKEND_URL')."makelua"; // Replace with the base URL
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
        var baseUrl = env('BACKEND_URL')."makelua"; // Replace with the base URL
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
      
      
      
          var baseUrl = env('BACKEND_URL')."makelua"; // Replace with the base URL
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
        var baseUrl = env('BACKEND_URL')."makelua"; // Replace with the base URL

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
            var baseUrl = env('BACKEND_URL')."makelua";
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
            var baseUrl = env('BACKEND_URL')."makelua";
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
        var baseUrl = env('BACKEND_URL')."makelua"; // Replace with the base URL
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
        var baseUrl = env('BACKEND_URL')."makelua"; // Replace with the base URL
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
        var baseUrl = env('BACKEND_URL')."makelua"; // Replace with the base URL
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

        console.log('formdata');
        console.log(formData);

        for (var pair of formData.entries()) {
          console.log(pair[0]+ ', ' + pair[1]); 
        }

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

    $(document).on('click', '#add-customer-comment', function(e) {
      console.log('here we are');
    }):

    $("input:radio[name=notifyFile]").click(function() { 

      let value = $(this).val();
      let file_id = $(this).data('file_id');

      if(value == 'now'){

        $('#now-area-'+file_id).removeClass('hide');
        $('#later-area-'+file_id).addClass('hide');
        
      }
      else if(value == 'later'){

        $('#later-area-'+file_id).removeClass('hide');
        $('#now-area-'+file_id).addClass('hide');

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

$(document).on('click', '.btn-add-message', function(e){
    
    let file_id = $(this).data('file_id');
    // let message = $("#customer_message_"+file_id).val();

    // if(message === ""){
    //   $("#validation_"+file_id).html('please add Message for Customer.');
    // }
    // else{

    $.ajax({
      url: "/add_upload_later_record",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'file_id': file_id,
      },
      success: function(d) {
        $('#softwareOptionsModal-'+file_id).modal('show');
      }
    });

    // }
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

$(document).on('click', '.btn-show-software-form', function(e){

let file_id = $(this).data('file_id');

console.log(file_id);

removeNullSoftwareRecords(file_id);

$('#softwareOptionsModal-'+file_id).modal('show');

});

$(document).on('click', '.btn-show-message-form', function(e){

let file_id = $(this).data('file_id');

console.log(file_id);

removeNullUploadLaterRecords(file_id);

$('#MessageModal-'+file_id).modal('show');

});

$(document).on('click', '.btn-show-send-file-form', function(e){
  let request_file_id = $(this).data('request_file_id');
  let file_id = $(this).data('file_id');
  $('#request_file_id_send_file').val(request_file_id);
  $('#request_file_id_send_file_2').val(request_file_id);
  $('#file_id_to_send_file').val(file_id);

  // $.ajax({
  //     url: "/get_customer_message",
  //     type: "POST",
  //     headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  //     data: {
  //         'request_file_id': request_file_id
  //     },
  //     success: function(d) {
  //       $('#customer_message_textarea').val(d.message);
        
  //     }
  // });


  $('#sendFile').modal('show');
});

$(document).on('click', '.btn-show-software-edit-form', function(e){

  let file_id = $(this).data('file_id');
  let new_request_id = $(this).data('new_request_id');

  console.log(file_id);

  $.ajax({
      url: "/fill_null_software_records",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'file_id': file_id,
          'new_request_id': new_request_id
      },
      success: function(d) {

        $('#edit_software_file_id').val(file_id);
        $('#edit_software_new_request_id').val(new_request_id);
        $('#processing-software-stage-edit').html(d.strStage);

        $.each(d.opArr, function(index, value) { 
          
          $('#processing-software-edit-option-'+index).html(value);
        });

        $('#softwareOptionsEditModal').modal('show');
      }
  });

  

});

function removeNullSoftwareRecords(file_id){

$.ajax({
      url: "/remove_null_software_records",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'file_id': file_id
      },
      success: function(d) {
        console.log(d);
        
      }
  });
}

function removeNullMessageRecords(file_id){

$.ajax({
      url: "/remove_null_message_records",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'file_id': file_id
      },
      success: function(d) {
        console.log(d);
        
      }
  });
}

function removeNullUploadLaterRecords(file_id){

$.ajax({
      url: "/remove_null_upload_later_records",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'file_id': file_id
      },
      success: function(d) {
        console.log(d);
        
      }
  });
}

$(document).on('click', '.show-file-upload-section', function(e){

let file_id = $(this).data('file_id');

console.log(file_id);

let formElements = $("#softwareForm-"+file_id).serializeArray();

// console.log(formElements);

let formJson = JSON.stringify(formElements);

$.ajax({
      url: "/add_softwares_services",
      type: "POST",
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
      data: {
          'form_data': formJson
      },
      success: function(d) {
        console.log(d);
        $("#softwareForm-"+d.file_id).addClass('hide');
        $("#fileUploadForm-"+d.file_id).removeClass('hide');
      }
  });


});

$(document).on('click', '.btn-msg-sent', function(e){

    let file_id = $(this).data('file_id');
    $('#file_id_message_sent').val(file_id);

    $.ajax({
        url: "/get_customer_message",
        type: "POST",
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'file_id': file_id
        },
        success: function(d) {
          $('#message_to_send').html(d.message);
          
        }
    });

    $('#sendMessageModal').modal('show');

});

$(document).on('click', '.btn-msg-later', function(e){
    let file_id = $(this).data('file_id');
    $('#file_id_message_later').val(file_id);
    $('#addMessageModal').modal('show');
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

    $(document).on('click', '.delete-acm-file', function(e){
      e.preventDefault();

      let acm_file_id = $(this).data('acm_file_id');
      
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
      console.log(acm_file_id);
      $.ajax({
                url: "/delete_acm_file",
                type: "POST",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'acm_file_id': acm_file_id
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

 
@if($showComments)

@foreach($file->files as $f)

@if($f->show_comments == 0)
@if($f->comments_denied == 0)


<script type="text/javascript">
  $(document).ready(function(){

    const Popup = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
  });
    
    Popup.fire({
    title: "Showing Comments to Cusotmer on files",
    text: "File: "+"'{{$f->request_file}}'"+" is not enabled to show comments to customers. Do you want to enable comments on it?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Allow Customer to see Comments!',
    cancelButtonText: 'No, Please do not show comments to Customer!',
        // reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
        
          $.ajax({
                url: "/flip_show_comments",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": {{$f->id}},
                    "showCommentsOnFile": true,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                  location.reload();
                }
            }); 
            
            

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          // swalWithBootstrapButtons.fire(
          //   'Cancelled',
          //   'Message is safe :)',
          //   'error'
          // );

          $.ajax({
                url: "/decline_comments",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": {{$f->id}},
                    
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                  location.reload();
                }
            }); 

          
        }
      });
  });
</script>

@break

@endif
@endif

@endforeach

@endif

  @foreach($file->files as $f)

@if($f->is_kess3_slave == 1 || $f->is_flex_file == 1)
@if($f->uploaded_successfully == 0 && $f->show_file_denied == 0)

<script type="text/javascript">
  $(document).ready(function(){

    const Popup = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
  });
    
    Popup.fire({
    title: "Alientech API Failed to Encode the File",
    text: "File: "+"'{{$f->request_file}}'"+" is not encoded by API. Do you want customer to download it anyway?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Allow Customer to Downoad it!',
    cancelButtonText: 'No, Please do not show it to Customer!',
        // reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
        
          $.ajax({
                url: "/flip_show_file",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": {{$f->id}},
                    "showFile": true,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                  location.reload();
                }
            }); 
            
            

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          // swalWithBootstrapButtons.fire(
          //   'Cancelled',
          //   'Message is safe :)',
          //   'error'
          // );

          $.ajax({
                url: "/decline_show_file",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": {{$f->id}},
                    
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                  location.reload();
                }
            }); 

          
        }
      });
  });
</script>

@break

@endif
@endif

@endforeach

<script>

  $( document ).ready(function(event) {
	  
	  
	  

	  $(document).on('click', '.translate', function(e) {
                    console.log('translate');

                    let id = $(this).data('id');

                    $.ajax({
                        url: "/translate_message",
                        type: "POST",
                        data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                      },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
							$('#translated_text').html(response);
                          $('#translateModal').modal('show');
                      }
                    });  
              });
	  

    let showFile = false;
    
              $(document).on('change', '.show_file', function(e) {
                  let engineer_file_id = $(this).data('id');
                  console.log(engineer_file_id);
                  if ($(this).is(':checked')) {
                    showFile = $(this).is(':checked');
                      console.log(showFile);
                  }
                  else {
                    showFile = $(this).is(':checked');
                      console.log(showFile);
                  }

                  flip_show_file(engineer_file_id, showFile);
              });
      
      let showCommentsOnFile = true;
              $(document).on('change', '.show_comments', function(e) {
                  let engineer_file_id = $(this).data('id');
                  console.log(engineer_file_id);
                  if ($(this).is(':checked')) {
                    showCommentsOnFile = $(this).is(':checked');
                      console.log(showCommentsOnFile);
                  }
                  else {
                    showCommentsOnFile = $(this).is(':checked');
                      console.log(showCommentsOnFile);
                  }

                  flip_show_comments(engineer_file_id, showCommentsOnFile);
              });



  function flip_show_comments(engineer_file_id, showCommentsOnFile){
      $.ajax({
                url: "/flip_show_comments",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": engineer_file_id,
                    "showCommentsOnFile": showCommentsOnFile,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    
                }
            });  
    }

  function flip_show_file(engineer_file_id, showFile){
      $.ajax({
                url: "/flip_show_file",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": engineer_file_id,
                    "showFile": showFile,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    
                }
            });  
    }

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
      location.reload();
    }).on('error', function(e){
      
    });
	  
	  });
	  
	  
</script>



@endsection
