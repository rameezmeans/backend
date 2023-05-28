@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">
          @if (Session::get('success'))
                <div class="pgn-wrapper" data-position="top" style="top: 59px;">
                    <div class="pgn push-on-sidebar-open pgn-bar">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{ Session::get('success') }}
                        </div>
                    </div>
                </div>
            @endif
            @php
              Session::forget('success')
            @endphp
          <div class="card card-transparent m-t-40">
              <div class="card-header">
                 
                <a class="btn btn-success pull-right" href={{route('vehicle', $vehicle->id)}}>View Vehicle</a>
                
                @if(request()->query('file'))
                  <a class="btn btn-success pull-right m-r-10" href={{route('file', request()->query('file'))}}>Go Back to File</a>
                @endif

              </div>
            <div class="card-body">
              @if($hasECU)
                @php $first = 0; $second = 0; $third = 0; @endphp
                <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                    @foreach($ecus as $ecu)
                        <li class="nav-item">
                            <a href="#" class="@if($first == 0) active @endif" data-toggle="tab" data-target="#tab-fillup-{{$first}}"><span>{{$ecu}}</span></a>
                        </li> 
                        @php $first++; @endphp
                    @endforeach 
                </ul>
                <div class="tab-content">
                    @foreach($ecus as $ecu)
                    @php $third = 0; @endphp
                        <div class="tab-pane @if($second == 0) active @endif" id="tab-fillup-{{$second}}">
                            <div class="row">
                                <div class="col-lg-4">
                                  <h5 class="p-b-20">{{$vehicle->Name}}</h5>
                                 
                                  <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Brand</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$vehicle->Make}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Model</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$vehicle->Model}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Generation</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$vehicle->Generation}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Engine</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$vehicle->Engine}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Engine ECU</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$ecu}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>

                                <div class="m-t-20">
                                  <label>Engineer's Note</label>
                                  <form role="form" action="{{route('add-engineer-comment')}}" method="POST">
                                    @if(request()->query('file'))
                                        <input type="hidden" name="file" value="{{request()->query('file')}}">
                                      @endif
                                    @csrf
                                    <input type="hidden" id="vehicle_id" name="vehicle_id" value="{{$vehicle->id}}">
                                    <input type="hidden" name="ecu" id="ecu" value="{{$ecu}}">
                                    
                                    <div class="form-group-attached ">
                                      <div class="row">
                                        <div class="col-md-12">
                                          
                                          <div class="form-group form-group-default required">
                                          
                                            <textarea id="notes" name="notes" required style="height: 100px;" class="form-control" placeholder="Engineer's Notes">@if($vehicle->getComment($ecu)){{ $vehicle->getComment($ecu)->notes }}@endif</textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                 
                                  <div class="row">
                                    <div class="col-md-4 m-t-10 sm-m-t-10 text-center">
                                      <button type="submit" class="btn btn-success btn-block m-t-5">Add Note</button>
                                    </div>
                                  </div>
                                </form>
                                </div>
                                  
                                </div>
                                <div class="col-lg-4" style="height: 2000px;">
                                  <div class="p-l-10">
                                    <h5 class="">Comments On Download</h5>
                                        @foreach($downloadComments as $comment)
                                            @if($ecu == $comment->ecu)
                                                <div class="p-t-10">
                                                    
                                                    @if(\App\Models\Service::findOrFail( $comment->service_id ) )
                                                    <img alt="{{\App\Models\Service::findOrFail( $comment->service_id )->name}}" 
                                                    width="40" height="40" 
                                                    data-src-retina="{{ url('icons').'/'.\App\Models\Service::findOrFail( $comment->service_id )->icon }}" 
                                                    data-src="{{ url('icons').'/'.\App\Models\Service::findOrFail( $comment->service_id )->icon }}" 
                                                    src="{{ url('icons').'/'.\App\Models\Service::findOrFail( $comment->service_id )->icon }}">
                                                    @endif
                                                    {{ \App\Models\Service::findOrFail( $comment->service_id )->name}} <span style="font-size: 8px;">({{\App\Models\Service::findOrFail( $comment->service_id )->vehicle_type}})</span>
                                                    <span class="m-l-20">
                                                      <i class="fa fa-pencil-square text-success btn-edit" data-id={{$comment->id}} data-comment="{{$comment->comments}}" data-greek-comment="@if($comment->translation){{$comment->translation->greek}}@endif"></i>
                                                      <i class="pg-trash text-danger btn-delete" data-id="{{$comment->id}}"></i>
                                                    </span>
                                                </div> 

                                                <p> {{$comment->comments}}</p>
                                                @php $third++; @endphp
                                            @endif
                                        @endforeach
                                        @if($third == 0)
                                            <p>No Comments.</p>
                                        @endif
                                    <form role="form" action="{{route('add-option-comments')}}" method="POST" class="m-t-10">
                                      @csrf

                                      @if(request()->query('file'))
                                        <input type="hidden" name="file" value="{{request()->query('file')}}">
                                      @endif

                                      <input type="hidden" name="engine" value="{{$vehicle->Engine}}">
                                      <input type="hidden" name="make" value="{{$vehicle->Make}}">
                                      <input type="hidden" name="ecu" value="{{$ecu}}">
                                      <input type="hidden" name="generation" value="{{$vehicle->Generation}}">
                                      <input type="hidden" name="model" value="{{$vehicle->Model}}">
                                      <input type="hidden" name="id" value="{{$vehicle->id}}">
                                      <input type="hidden" name="comment_type" value="download">
                                      <div class="form-group form-group-default required ">
                                        
                                        <label>Option</label>
                                        <select class="full-width" data-init-plugin="select2" name="service_id">
                                          
                                          @foreach($options as $option)
                                            @if(!in_array($option->id, $includedOptionsForDownload[$ecu]))
                                            <option value="{{$option->id}}">{{$option->name}} <span style="font-size: 8px;">({{$option->vehicle_type}})</span></option>
                                            @endif
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="form-group-attached ">
                                        <div class="row">
                                          <div class="col-md-12">
                                            
                                            <div class="form-group form-group-default m-t-10 required" style="height: 50%;">
                                              <label>Comment</label>
                                              <textarea name="comments" required style="height: 10px;" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group form-group-default m-t-10 required" style="height: 50%;">
                                              <label>Greek Comment</label>
                                              <textarea name="greek_comments" required style="height: 10px;" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group m-t-10">
                                              <button type="submit" class="btn btn-success btn-block m-t-5">Add Comment</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                  </form>
                                  </div>

                                  

                                </div>
                                <div class="col-lg-4" style="height: 2000px;">
                                  <div class="p-l-40">
                                    <h5 class="">Comments On Upload</h5>
                                        @foreach($uploadComments as $comment)
                                            @if($ecu == $comment->ecu)
                                                <div class="p-t-10">
                                                   
                                                    <img alt="{{\App\Models\Service::findOrFail( $comment->service_id )->name}}" 
                                                    width="40" height="40" 
                                                    data-src-retina="{{ url('icons').'/'.\App\Models\Service::findOrFail( $comment->service_id )->icon }}" 
                                                    data-src="{{ url('icons').'/'.\App\Models\Service::findOrFail( $comment->service_id )->icon }}" 
                                                    src="{{ url('icons').'/'.\App\Models\Service::findOrFail( $comment->service_id )->icon }}">
                                                    {{ \App\Models\Service::findOrFail( $comment->service_id )->name}} <span style="font-size: 8px;">({{\App\Models\Service::findOrFail( $comment->service_id )->vehicle_type}})</span>
                                                    <span class="m-l-20">
                                                      <i class="fa fa-pencil-square text-success btn-edit" data-id={{$comment->id}} data-comment="{{$comment->comments}}" data-greek-comment="@if($comment->translation){{$comment->translation->greek}}@endif"></i>
                                                      <i class="pg-trash text-danger btn-delete" data-id="{{$comment->id}}"></i>
                                                    </span>
                                                </div> 

                                                <p> {{$comment->comments}}</p>
                                                @php $third++; @endphp
                                            @endif
                                        @endforeach
                                        @if($third == 0)
                                            <p>No Comments.</p>
                                        @endif
                                    <form role="form" action="{{route('add-option-comments')}}" method="POST" class="m-t-10">
                                      @csrf
                                      @if(request()->query('file'))
                                        <input type="hidden" name="file" value="{{request()->query('file')}}">
                                      @endif
                                      <input type="hidden" name="engine" value="{{$vehicle->Engine}}">
                                      <input type="hidden" name="make" value="{{$vehicle->Make}}">
                                      <input type="hidden" name="ecu" value="{{$ecu}}">
                                      <input type="hidden" name="generation" value="{{$vehicle->Generation}}">
                                      <input type="hidden" name="model" value="{{$vehicle->Model}}">
                                      <input type="hidden" name="id" value="{{$vehicle->id}}">
                                      <input type="hidden" name="comment_type" value="upload">
                                      <div class="form-group form-group-default required ">
                                        <label>Option</label>
                                        <select class="full-width" data-init-plugin="select2" name="service_id">
                                          @foreach($options as $option)
                                            @if(!in_array($option->id, $includedOptionsForUpload[$ecu]))
                                            <option value="{{$option->id}}">{{$option->name}} <span style="font-size: 8px;">({{$option->vehicle_type}})</span></option>
                                            @endif
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="form-group-attached ">
                                        <div class="row">
                                          <div class="col-md-12">
                                            
                                            <div class="form-group form-group-default required m-t-10" style="height: 50%;">
                                              <label>Comment</label>
                                              <textarea name="comments" required style="height: 10px;" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group form-group-default required m-t-10" style="height: 50%;">
                                              <label>Greek Comment</label>
                                              <textarea name="greek_comments" required style="height: 10px;" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group m-t-10">
                                              <button type="submit" class="btn btn-success btn-block m-t-5">Add Comment</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                  </form>
                                  </div>
                                </div>
                                
                            </div>
                        </div>
                        @php $second++; @endphp
                    @endforeach 
                </div>
              @endif
            </div>
          </div>
        </div>
    </div>
</div>

<div class="modal fade fill-in show" style="z-index: 99999;" id="editModal" tabindex="-1" role="dialog">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    <i class="pg-close"></i>
  </button>
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="text-left p-b-5"><span class="semi-bold">Update Comment</span></h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <form role="form" action="{{route('edit-option-comment')}}" method="POST">
            @csrf
            <input type="hidden" id="edit-modal-id" name="id" value="">
            <input type="hidden" name="vehicle_id" value="{{$vehicle->id}}">
          <div class="col-lg-9 ">
            <label>English Comment</label>
            <textarea id="edit-modal-comments" name="comments" required style="height: 100px; width: 500px;" class="form-control input-lg"></textarea>
            <label>Greek Comment</label>
            <textarea id="edit-modal-greek-comments" name="greek_comments" required style="height: 100px; width: 500px;" class="form-control input-lg"></textarea>
          </div>
          <div class="col-lg-3 no-padding sm-m-t-10 m-t-10 text-center sm-text-center">
            <button type="submit" class="btn btn-primary btn-lg btn-large fs-15">Update</button>
          </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection

@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {

        $( document ).on('click', '.btn-edit', function(e){
            console.log(e);
            let id = $(this).data('id');
            let comment = $(this).data('comment');
            let greek_comment = $(this).data('greek-comment');
            $('#edit-modal-comments').val(comment);
            $('#edit-modal-greek-comments').val(greek_comment);
            $('#edit-modal-id').val(id);
            console.log(comment+' '+id);

            $('#editModal').modal('show');
        });


    });

    $('.btn-delete').click(function() {
          Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                        url: "/delete_comment",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your Record has been deleted.",
                                type: "success",
                                timer: 5000
                            });

                            location.reload();
                        }
                    });            
                }
            });
        });

</script>

@endsection