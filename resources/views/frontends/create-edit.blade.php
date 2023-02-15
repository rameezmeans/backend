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
                  @if(isset($frontend))
                        <h5>
                            Edit Frontend
                        </h5>
                    @else
                        <h5>
                            Add Frontend
                        </h5>
                    @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('frontends')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Frontends</span></button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($frontend)){{route('update-frontend')}}@else{{ route('post-frontend') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($frontend))
                  <input name="id" type="hidden" value="{{ $frontend->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($frontend)) {{ $frontend->name }} @else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                    <label>URL</label>
                    <input value="@if(isset($frontend)) {{ $frontend->url }} @else{{old('url') }}@endif"  name="url" type="text" class="form-control" required>
                  </div>
                  @error('url')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                <div class="form-group form-group-default">
                    <label>Description</label>
                    <textarea style="height: 200px;" name="description" class="form-control">@if(isset($frontend)) {{ $frontend->description }} @else{{old('description') }}@endif</textarea>
                  </div>
                  @error('description')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($frontend)) Update @else Add @endif</span></button>
                  @if(isset($frontend))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$frontend->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
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

        let deleteURL = "{{route('delete-frontend')}}";
        
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
                        url: deleteURL,
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Front end has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/frontends';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection