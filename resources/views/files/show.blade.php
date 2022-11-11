@extends('layouts.app')

@section('pagespecificstyles')
  <style>

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

  </style>
@endsection
@section('content')
<div class="page-content-wrapper ">
  <!-- START PAGE CONTENT -->
  <div class="content sm-gutter">
    <!-- START CONTAINER FLUID -->
    <div class=" container-fluid   container-fixed-lg bg-white">

      <div class="card card-transparent m-t-40">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
          <li class="nav-item">
            <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>Task</span></a>
          </li>
          <li class="nav-item">
            <a href="#" data-toggle="tab" data-target="#slide2"><span>Chat and Support</span></a>
          </li>
          
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane slide-left active" id="slide1">
            <div class="row column-seperation">
              <div class="col-lg-12">
                
                <div class="widget-16 card no-border widget-loader-circle">
                  <div class="card-header ">
                      <div class="card-title"><h4>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h4>
                      </div>
                      <div class="pull-right">
                        <div class="col-xs-12">
                            <a href="{{ route('download', $file->file_attached) }}" class="btn btn-success btn-cons m-b-10"><i class="pg-download"></i> <span class="bold">Download</span>
                            </a>
                        </div>
                        </div>
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">

                    <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">Task ID</p>
                      <div class="pull-right">
                        <span class="label label-success">Task{{$file->id}}<span>
                      </div>
                      <div class="clearfix"></div>
                    </div>

                    <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">Customer Name</p>
                      <div class="pull-right">
                        <span class="label label-success">{{$file->name}}<span>
                      </div>
                      <div class="clearfix"></div>
                    </div>

                    <h5 class="m-t-40">Vehicle Information</h5>
                    <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
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
                      <p class="pull-left">Gear Box</p>
                      <div class="pull-right">
                        <span class="label label-success">{{$file->gear_box}}<span>
                      </div>
                      <div class="clearfix"></div>
                    </div>

                    <h5 class="m-t-40">Options And Credits</h5>

                    @if($file->stages)
                      <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Stage</p>
                        <div class="pull-right">
                            <img alt="{{$file->stages}}" width="33" height="33" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
                            <span class="text-black" style="top: 2px; position:relative;">{{ $file->stages }}</span>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    @endif

                    @if($file->options)
                      <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Options</p>
                        <div class="pull-right">
                            {{-- <img alt="{{$file->stages}}" width="33" height="33" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}"> --}}
                            @foreach($file->options() as $option)
                              <span class="label label-warning-darker m-l-10" class="text-black" style="top: 2px; position:relative;">
                                <img alt="{{$option}}" width="20" height="20" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}">

                                {{ $option }}
                              </span>
                            @endforeach
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    @endif
                   
                    
                    <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">Credits Paid</p>
                      <div class="pull-right">
                        <span class="label label-danger">{{$file->credits}}<span>
                      </div>
                      <div class="clearfix"></div>
                    </div>

                    <h5 class="m-t-40">Uploaded Files</h5>
                    @foreach($messages as $message)
                      @if(isset($message['request_file']))
                        @if($message['engineer'] == 1)
                    <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                      <p class="pull-left">{{$message['request_file']}}</p>
                      <div class="pull-right">
                    
                        
                        <a href="{{ route('download', $message['request_file']) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download</span>
                        </a>
                          
                       

                  </div>
                  <div class="clearfix"></div>
                </div>

                @endif
                @endif
              @endforeach

                    <div class="m-t-40">
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
                          <form action="{{route('request-file-upload')}}" class="dropzone no-margin">
                            @csrf
                            <input type="hidden" value="{{$file->id}}" name="file_id">
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
          </div>
          <div class="tab-pane slide-left" id="slide2">
            <div class="row">
              <div class="col-lg-12">
                <div class="widget-16 card no-border widget-loader-circle">
                  <div class="card-header ">
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">
                    <div class="view chat-view bg-white clearfix">
                      <!-- BEGIN Header  !-->
                      
                      <!-- END Header  !-->
                      <!-- BEGIN Conversation  !-->
                      @if(!empty($messages))
          
                      <div class="chat-inner" id="my-conversation">
                        <!-- END From Me Message  !-->
                        <!-- BEGIN From Them Message  !-->
                        @foreach($messages as $message)
                         
                          @if(isset($message['request_file']))
                            @if($message['engineer'] == 0)

                                <div class="chat-bubble from-them bg-success">
                              
                                  File Type: {{ ucfirst($message['file_type']) }}<br>
                                  @if(isset($message['ecu_file_select']))
                                    ECU: {{ str_replace("_"," ",ucfirst($message['ecu_file_select']))}}<br>
                                  @endif
                                  @if(isset($message['gearbox_file_select']))
                                    Gearbox: {{ str_replace("_"," ",ucfirst($message['gearbox_file_select']))}}<br>
                                  @endif
                                  File Type: {{ ucfirst($message['tool_type']) }}<br>
                                  Tools: {{ ucfirst($message['master_tools']) }}<br>
                                  <div class="text-center  m-t-10"><a href="{{route('download', $message['request_file'])}}" class="text-danger">Download</a></div>
                                  </div>
                              @endif
                              
                              <div class="message clearfix">
                            </div>
                            
                          @endif
                          @if(isset($message['egnineers_internal_notes']))
                            @if($message['engineer'])
                            <div class="message clearfix">
                              <div class="chat-bubble bg-primary from-me text-white">
                                {{ $message['egnineers_internal_notes'] }}<br>
                              </div>
                            </div>
          
                            @else
                              <div class="message clearfix">
                                <div class="chat-bubble from-them bg-success">
                                    {{ $message['egnineers_internal_notes'] }}<br>
                                    @if(isset($message['engineers_attachement']))
                                      <div class="text-center m-t-10">
                                        <a href="{{route('download', $message['engineers_attachement'])}}" class="text-danger">Download</a>
                                      </div>
                                    @endif
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
                                        <a href="{{route('download', $message['file_url_attachement'])}}" class="text-danger">Download</a>
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
                      <div class="b-t b-grey bg-white clearfix p-l-10 p-r-10">
                        <form method="POST" action="{{ route('file-engineers-notes') }}">
                          @csrf
                          <input type="hidden" value="{{$file->id}}" name="file_id">
                        <div class="row">
                          
                            <div class="col-10 no-padding">
                              <input type="text" name="egnineers_internal_notes" class="form-control chat-input" data-chat-input="" data-chat-conversation="#my-conversation" placeholder="Reply to cusotmer." required>
                              @error('egnineers_internal_notes')
                                      <p class="text-danger" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </p>
                              @enderror
                            </div>
                            <div class="col-1 link text-master m-l-10 m-t-15 p-l-10 b-l b-grey col-top">
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
          <div class="tab-pane slide-left" id="slide3">
            <div class="row">
              <div class="col-lg-12">
                <h3>Follow us &amp; get updated!</h3>
                <p>Instantly connect to what's most important to you. Follow your friends, experts, favorite celebrities, and breaking news.</p>
                <br>
              </div>
            </div>
          </div>
        </div>
      </div>

      

    </div>
  </div>
</div>
    
@endsection

@section('pagespecificscripts')
  <script type="text/javascript">
  $(document).ready(function(){
    
      let myDropzone = Dropzone(".dropzone");
        myDropzone.on("addedfile", function(file) {
         
          location.reload();
        });
     
    });
  </script>
@endsection