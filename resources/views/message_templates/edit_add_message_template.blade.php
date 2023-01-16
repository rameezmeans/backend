@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                  @if(isset($template))
                  <h5>
                    Edit Template
                  </h5>
                @else
                  <h5>
                    Add Template
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('message-templates')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Message Templates</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($template)){{route('update-message-template')}}@else{{ route('post-message-template') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($template))
                  <input name="id" type="hidden" value="{{ $template->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($template)) {{ $template->name }} @else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $template }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>Text</label>
                    <textarea  name="text" type="text" class="form-control" required>@if(isset($template)) {{ $template->text }} @else{{old('text') }}@endif</textarea>
                  </div>
                  @error('text')
                    <span class="text-danger" role="alert">
                        <strong>{{ $template }}</strong>
                    </span>
                  @enderror

                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($template)) Update @else Add @endif</span></button>
                  @if(isset($template))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$template->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                  @endif
                </div>
              </form>
                
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
                        url: "/delete_message_template",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Template has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/message_templates';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection