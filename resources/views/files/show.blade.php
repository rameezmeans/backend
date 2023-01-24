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
      <div class="card card-transparent m-t-40">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
          <li class="nav-item">
            <a href="#"  @if(!Session::has('tab')) class="active" @endif data-toggle="tab" data-target="#slide1"><span>Task</span></a>
          </li>
          <li class="nav-item">
            <a href="#" data-toggle="tab" @if(Session::get('tab') == 'chat') class="active" @endif data-target="#slide2"><span>Chat and Support</span></a>
          </li>
          
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane slide-left  @if(!Session::has('tab')) active @endif" id="slide1">
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

                    

                    <div class="row">

                      <div class="col-lg-6">
                        <h5 class="">General Information</h5>
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Uploaded Time</p>
                          <div class="pull-right">
                            <span class="">{{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i: A')}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
        
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

                        @if($file->additional_comments)

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Additional Comments</p>
                          <br>
                          <div>
                            {{$file->additional_comments}}
                          </div>
                          <div class="clearfix"></div>
                        </div>

                        @endif

                        @if($file->request_type)

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Requste Type</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->request_type}}<span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      
                        @endif

                        @if(Auth::user()->is_admin)

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
                          <p class="pull-left">Status</p>
                          <form action="{{route('change-status')}}" method="POST">
                            @csrf
                            <input type="hidden" name="file_id" value="{{$file->id}}">
                            <div class="">
                              <select class="full-width" data-init-plugin="select2" name="status">
                                  <option @if(isset($file) && $file->status == "rejected") selected @endif value="rejected">Rejected</option>
                              </select>
                              <div class="text-center m-t-20">                    
                                <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Update</span></button>
                              </div>
                            </div>
                            
                          </form>
                          <div class="clearfix"></div>
                        </div>

                        <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Assign This File to An Engineer</p>
                          <form action="{{route('assign-engineer')}}" method="POST">
                            @csrf
                            <input type="hidden" name="file_id" value="{{$file->id}}">
                            <div class="">
                              <select class="full-width" data-init-plugin="select2" name="assigned_to">
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

                      </div>

                      <div class="col-lg-6">
                        <h5 class="">Vehicle Information</h5>
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
                          <p class="pull-left">Vin Number</p>
                          <div class="pull-right">
                            <span class="label label-success">{{$file->vin_number}}<span>
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
                        <div class="text-center m-t-20">                    
                          <a class="btn btn-success btn-cons m-b-10" href="{{route('vehicle', $vehicle->id)}}"><span class="bold">Go To Vehicle</span></a>
                        </div>
        
                      </div>
        
                      <div class="col-lg-6">
                        <h5 class="m-t-40">Reading Tool</h5>
        
                            
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Tool</p>
                          <div class="pull-right">
                              <img alt="{{$file->tool}}" width="50" height="20" data-src-retina="{{ get_dropdown_image($file->tool) }}" data-src="{{ get_dropdown_image($file->tool) }}" src="{{ get_dropdown_image($file->tool) }}">
                              <span class="" style="top: 2px; position:relative;">{{ $file->tool }}</span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                     
        
                      <h5 class="m-t-40">Options And Credits</h5>
        
                      @if($file->stages)
                        <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                          <p class="pull-left">Stage</p>
                          <div class="pull-right">
                              <img alt="{{$file->stages}}" width="33" height="33" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
                              <span class="text-black" style="top: 2px; position:relative;">{{ $file->stages }}</span>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      @endif
                      <div class="p-b-20">
                      @if($file->options)
                      <div class="b b-grey p-l-20 p-r-20 p-t-10">
                        <p class="pull-left">Options</p>
                        <div class="clearfix"></div>
                      </div>
                        @foreach($file->options() as $option) 
                        <div class="p-l-20"> 
                          <img alt="{{$option}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}">
                          {{$option}}  
                        </div>

                          @foreach($comments as $comment)
                              @if($option == $comment->option)
                                <div class="p-l-20 p-b-10"> 
                                  {{$comment->comments}}
                                </div>
                              @endif
                          @endforeach
                        @endforeach
                      @endif

                      </div>
                     
                      <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                        <p class="pull-left">Credits Paid</p>
                        <div class="pull-right">
                          <span class="label label-danger">{{$file->credits}}<span>
                        </div>
                        <div class="clearfix"></div>
                      </div>
        
                      </div>
        
                      <div class="col-lg-6">
                        <h5 class="m-t-40">Uploaded Files</h5>
                            @foreach($messages as $message)
                              @if(isset($message['request_file']))
                                @if($message['engineer'] == 1)
                            <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                <p class="pull-left">{{$message['request_file']}}</p>
                                <div class="pull-right">
                                  @isset($message['type'])
                                 
                                 
                                  <a href="#" class="btn-sm btn-info btn-cons"> <span class="bold">{{$message['type']}}</span>
                                  </a>
                                  @endisset
                                    <a href="{{ route('download', $message['request_file']) }}" class="btn-sm btn-success btn-cons m-b-10"> <span class="bold">Download</span>
                                    </a>
                                    <a href="#" class="btn-sm btn-cons btn-danger delete-uploaded-file" data-request_file_id="{{$message['id']}}"><i class="pg-trash text-white"></i></a>
                                </div>
                                
                                  <div class="clearfix"></div>
                            </div>
        
                        @endif
                        @endif
                      @endforeach
                      </div>
                      <div class="col-lg-12">
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
            </div>
          </div>
          <div class="tab-pane slide-left @if(Session::get('tab') == 'chat') active @endif" id="slide2">
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
                         
                          {{-- @if(isset($message['request_file'])) --}}
                            {{-- @if($message['engineer'] == 0)

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
                              @endif --}}
                              
                              {{-- <div class="message clearfix">
                            </div>
                            
                          @endif --}}
                          @if(isset($message['egnineers_internal_notes']))
                            @if($message['engineer'])
                            <div class="message clearfix">
                              <div class="chat-bubble bg-primary from-me text-white">
                                {{ $message['egnineers_internal_notes'] }} 
                                <i data-note_id="{{$message['id']}}" data-message="{{$message['egnineers_internal_notes']}}" class="fa fa-edit m-l-20"></i> 
                                <i class="pg-trash delete-message" data-note_id="{{$message['id']}}"></i> 
                                <br>
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
    
@endsection

@section('pagespecificscripts')
  <script type="text/javascript">
  $(document).ready(function(){

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
    
    let engineerFileDrop= new Dropzone(".dropzone", {});

    engineerFileDrop.on("complete", function(file) {
      engineerFileDrop.removeFile(file);
      location.reload();
    });

    });
  </script>
@endsection