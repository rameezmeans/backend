@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class="container-fluid padding-25 sm-padding-10">
          <div class="row">
            <div class="col-lg-9">
              <div class="card card card-default">
                <div class="card-header ">
                    <div class="card-title">
                      @if(isset($tool))
                      <h5>
                        Edit Tool
                      </h5>
                    @else
                      <h5>
                        Add Tool
                      </h5>
                    @endif
                    </div>
                    <div class="pull-right">
                    <div class="col-xs-12">
                        <button data-redirect="{{route('tools')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Tools</span>
                        </button>
                        {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                    </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                  <form class="" role="form" method="POST" action="@if(isset($tool)){{route('update-tool')}}@else{{ route('add-tool') }}@endif" enctype="multipart/form-data">
                    @csrf
                    @if(isset($tool))
                      <input name="id" type="hidden" value="{{ $tool->id }}">
                    @endif
                    <div class="form-group form-group-default required ">
                      <label>Name</label>
                      <input value="@if(isset($tool)) {{ $tool->name }} @else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                    </div>
                    @error('name')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <div class="form-group form-group-default required ">
                      <label>Label</label>
                      <input value="@if(isset($tool)) {{ $tool->label }} @else{{old('label') }}@endif"  name="label" type="text" class="form-control" required>
                    </div>
                    @error('label')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <div class="form-group form-group-default required ">
                      <label>Type</label>
                      <select class="full-width" data-init-plugin="select2" name="type">
                          <option @if(isset($tool) && $tool->type == "master") selected @endif value="master">Master</option>
                          <option @if(isset($tool) && $tool->type == "slave") selected @endif value="slave">Slave</option>
                      </select>
                    </div>
                    <div class="form-group form-group-default @if(!isset($tool)) required @endif">
                      <label>Icon</label>
                      <input name="icon" type="file" class="form-control" @if(!isset($tool)) required @endif>
                    </div>
                    @error('icon')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <div class="text-center m-t-40">                    
                      <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($tool)) Update @else Add @endif</span></button>
                      @if(isset($tool))
                        <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$tool->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                      @endif
                    </div>
                  </form>
                    
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              @if(isset($tool))
                <div class="card social-card share  col1" >
                  <div class="card-header ">
                    <h5 class="text-black pull-left">Icon Preview</h5>
                  </div>
                  <div class="card-description">
                      <img src="{{ url('icons').'/'.$tool->icon }}" alt="tool-icon">
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

      $( document ).ready(function(event) {
        
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
                        url: "/delete_tool",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Tool has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/tools';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection