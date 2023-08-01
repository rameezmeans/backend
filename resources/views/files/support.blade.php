@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

            <div class="row">
                <div class="col-lg-12">
                  <div class="widget-16 card no-border widget-loader-circle">
                    <div class=" @if($file->frontend->id == 1) bg-primary-light @else bg-warning-light @endif">
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
                        @if(!empty($requestFile->engineer_file_notes))
            
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
                                      <a href="{{route('download',[$message['file_id'], $message['engineers_attachement']])}}" class="text-danger">Download</a>
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
                                          <a href="{{route('download',[$message['file_id'], $message['engineers_attachement']])}}" class="text-danger">Download</a>
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
                                          <a href="{{route('download',[$message['file_id'], $message['file_url_attachement']])}}" class="text-danger">Download</a>
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
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {

    });

</script>

@endsection