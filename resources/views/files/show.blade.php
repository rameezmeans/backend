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
      <div class="widget-16 card no-border widget-loader-circle m-t-40">
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
                  @if($message['engineer'])
                    <div class="chat-bubble bg-primary from-me text-white">
                      File Type: {{ str_replace("_"," ", ucfirst($message['file_type'])) }}<br>
                      <div class="text-center  m-t-10">
                        <a href="{{route('download', $message['request_file'])}}" class="text-danger">Download</a>
                      </div>
                    </div>
                  @else
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

            <div class="m-t-20">
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
            <!-- END Chat Input  !-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    
@endsection