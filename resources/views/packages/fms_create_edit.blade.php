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
                  @if(isset($package))
                  <h5>
                    Edit Package
                  </h5>
                @else
                  <h5>
                    Add Service/EVC Package
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('packages')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Packages</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              @if(isset($package))

              <form class="" role="form" method="POST" action="{{route('update-package')}}" enctype="multipart/form-data">
                @csrf
                <input name="from_master_subdealer" type="hidden" value="1">
                @if(isset($package))
                  <input name="id" type="hidden" value="{{ $package->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($package)){{$package->name}}@else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                    <label>Credits</label>
                    <input value="@if(isset($package)){{$package->credits}}@else{{old('credits') }}@endif"  name="credits" type="number" class="form-control" required>
                  </div>
                  @error('credits')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default required ">
                    <label>Actual Price</label>
                    <input value="@if(isset($package)){{$package->actual_price}}@else{{old('actual_price') }}@endif"  name="actual_price" type="number" class="form-control" required>
                  </div>
                  @error('actual_price')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default required ">
                    <label>Discounted Price</label>
                    <input value="@if(isset($package)){{$package->discounted_price}}@else{{old('discounted_price') }}@endif"  name="discounted_price" type="number" class="form-control" required>
                  </div>
                  @error('discounted_price')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default required ">
                    <label>Description</label>
                    <textarea name="desc" class="form-control">@if(isset($package)){{$package->desc}}@endif</textarea>
                  </div>
                  @error('desc')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($package)) Update @else Add @endif</span></button>
                  @if(isset($package))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$package->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                  @endif
                </div>
              </form>

              @else

              <ul class="nav nav-tabs nav-tabs-fillup m-t-0" data-init-reponsive-tabs="dropdownfx">
             
                <li class="nav-item">
                  <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>Create Service Package</span></a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" data-target="#slide2"><span>Create EVC Package</span></a>
                </li>
              </ul>

              <div class="tab-content">
                <div class="tab-pane slide-left active" id="slide1">
                  <div class="card card-transparent m-t-20">
                    <div class="card-header ">
                        <div class="card-title">
                         
                          <h5>
                            Create Service Package
                          </h5>
                        
                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body">

                      <form class="" role="form" method="POST" action="{{route('store-package')}}" enctype="multipart/form-data">
                        @csrf
                        <input name="from_master_subdealer" type="hidden" value="1">
                        <input name="type" type="hidden" value="service">
                        <div class="form-group form-group-default required ">
                          <label>Name</label>
                          <input value="@if(isset($package)){{$package->name}}@else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                        </div>
                        @error('name')
                          <span class="text-danger" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                        <div class="form-group form-group-default required ">
                            <label>Credits</label>
                            <input value="@if(isset($package)){{$package->credits}}@else{{old('credits') }}@endif"  name="credits" type="number" class="form-control" required>
                          </div>
                          @error('credits')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                          <div class="form-group form-group-default required ">
                            <label>Actual Price</label>
                            <input value="@if(isset($package)){{$package->actual_price}}@else{{old('actual_price') }}@endif"  name="actual_price" type="number" class="form-control" required>
                          </div>
                          @error('actual_price')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
        
                          <div class="form-group form-group-default required ">
                            <label>Discounted Price</label>
                            <input value="@if(isset($package)){{$package->discounted_price}}@else{{old('discounted_price') }}@endif"  name="discounted_price" type="number" class="form-control" required>
                          </div>
                          @error('discounted_price')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default required ">
                            <label>Description</label>
                            <textarea name="desc" class="form-control">@if(isset($package)){{$package->desc}}@endif</textarea>
                          </div>
                          @error('desc')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
        
                        <div class="text-center m-t-40">                    
                          <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($package)) Update @else Add @endif</span></button>
                          @if(isset($package))
                            <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$package->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                          @endif
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
                <div class="tab-pane slide-left" id="slide2">
                  <div class="card card-transparent m-t-20">
                    <div class="card-header ">
                        <div class="card-title">
                         
                          <h5>
                            Create EVC Package
                          </h5>
                        
                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body">
                      
                      <form class="" role="form" method="POST" action="{{route('store-package')}}" enctype="multipart/form-data">
                        @csrf
                        <input name="from_master_subdealer" type="hidden" value="1">
                        <input name="type" type="hidden" value="evc">
                        <div class="form-group form-group-default required ">
                          <label>Name</label>
                          <input value="@if(isset($package)){{$package->name}}@else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                        </div>
                        @error('name')
                          <span class="text-danger" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                        <div class="form-group form-group-default required ">
                            <label>Credits</label>
                            <input value="@if(isset($package)){{$package->credits}}@else{{old('credits') }}@endif"  name="credits" type="number" class="form-control" required>
                          </div>
                          @error('credits')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                          <div class="form-group form-group-default required ">
                            <label>Actual Price</label>
                            <input value="@if(isset($package)){{$package->actual_price}}@else{{old('actual_price') }}@endif"  name="actual_price" type="number" class="form-control" required>
                          </div>
                          @error('actual_price')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
        
                          <div class="form-group form-group-default required ">
                            <label>Discounted Price</label>
                            <input value="@if(isset($package)){{$package->discounted_price}}@else{{old('discounted_price') }}@endif"  name="discounted_price" type="number" class="form-control" required>
                          </div>
                          @error('discounted_price')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default required ">
                            <label>Description</label>
                            <textarea name="desc" class="form-control">@if(isset($package)){{$package->desc}}@endif</textarea>
                          </div>
                          @error('desc')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
        
                        <div class="text-center m-t-40">                    
                          <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($package)) Update @else Add @endif</span></button>
                          @if(isset($package))
                            <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$package->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                          @endif
                        </div>
                      </form>

                    </div>
                  </div>
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
                        url: "/delete_package",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Package has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/packages';
                        }
                    });            
                }

            });
        });
    });

</script>

@endsection