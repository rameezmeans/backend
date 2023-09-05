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
        <div class=" container-fluid   container-fixed-lg bg-white">
          <div class="card card-transparent m-t-40">


          <ul class="nav nav-tabs nav-tabs-fillup d-none d-md-flex d-lg-flex d-xl-flex" data-init-reponsive-tabs="dropdownfx">
            <li class="nav-item">
              <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>Support Messages</span></a>
            </li>       
            <li class="nav-item">
              <a href="#" data-toggle="tab" data-target="#slide2"><span>Logs</span></a>
            </li>
          </ul>

         <div class="tab-content">
          <div class="tab-pane slide-left  active" id="slide1">
            <div class="row column-seperation">
              <div class="col-lg-12">
            
                  <div class="widget-16 card no-border widget-loader-circle">
                    <div class=" bg-warning-light">
                      <div class="text-center">
                        <div class="card-title">
                            <img style="width: 15%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                            <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                            <h5>File Name: {{$requestFile->request_file}}</h5>
                          </div>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body">
                      <div class="view chat-view bg-white clearfix">
                        <!-- BEGIN Header  !-->
                        
                        <!-- END Header  !-->
                        <!-- BEGIN Conversation  !-->
                        @if(!empty($requestFile->engineer_file_notes))

                        <h2 class="text-center">Support Messages</h2>
            
                        <div class="chat-inner" id="my-conversation" style="overflow: scroll !important; height:500px;">
                          <!-- END From Me Message  !-->
                          <!-- BEGIN From Them Message  !-->
                          
                          @foreach($requestFile->engineer_file_notes as $message)
                           
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
                            <input type="hidden" value="{{$requestFile->id}}" name="request_file_id">
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

    <div class="tab-pane slide-left" id="slide2">

      <div class="row column-seperation">
        <div class="col-lg-12">
      
            <div class="widget-16 card no-border widget-loader-circle">
              <div class=" bg-warning-light">
                <div class="text-center">
                  <div class="card-title">
                      <img style="width: 15%;" src="{{ $file->vehicle()->Brand_image_URL }}" alt="{{$file->brand}}" class="">
                      <h3>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</h3>
                      <h5>File Name: {{$requestFile->request_file}}</h5>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
              </div>
              <div class="card-body">
                <h2 class="text-center">Logs</h2>
                <div class="view chat-view bg-white clearfix">
                  <!-- END Header  !-->
                        <!-- BEGIN Conversation  !-->
                        <div class="chat-inner" id="my-conversation" style="overflow: scroll !important; height:500px;">

                        @if(!empty($requestFile->file_internel_events))

                        @foreach($requestFile->file_internel_events as $message)
                           
                            <div class="message clearfix">
                              <div class="chat-bubble from-them bg-success">
                                  {{ $message['events_internal_notes'] }}<br>
                                  @if(isset($message['events_attachement']))
                                    <div class="text-center m-t-10">
                                      <a href="{{route('download',[$message['file_id'], $message['events_attachement'], 0])}}" class="text-danger">Download</a>
                                    </div>
                                  @endif
                                  <br>
                                  <br>
                                  <small class="m-t-20" style="font-size: 8px;float:right">{{ date('H:i:s d/m/Y', strtotime( $message['created_at'] ) ) }}</small>
                              </div>
                            </div>
                         
                        
                      @endforeach

                        @endif
                        </div>
                </div>
              </div>
            </div>

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

    $( document ).ready(function(event) {

    });

</script>

@endsection