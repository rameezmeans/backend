@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
      <div class="container-fluid padding-25 sm-padding-10">
        <!-- START ROW -->
        <div class="row">
            <div class="col-lg-9">
              <!-- START card -->
              <div class="card card-default">
                <div class="card-header ">
                  <div class="pull-right">
                      <button data-redirect="{{ route('services') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Servies</span></button>
                      <button data-redirect="{{ route('set-group-price', ['id' => $service->id]) }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Set Subdealer Price</span></button>
                  </div>
                  <div class="card-title">
                    @if(isset($service))
                      <h5>
                        Edit Services
                      </h5>
                    @else
                      <h5>
                        Add Services
                      </h5>
                    @endif
                  </div>
                </div>
                <div class="card-body">
                  
                  <form class="" role="form" method="POST" action="@if(isset($service)){{route('update-service')}}@else{{ route('add-service') }}@endif" enctype="multipart/form-data">
                    @csrf
                    @if(isset($service))
                      <input name="id" type="hidden" value="{{ $service->id }}">
                    @endif
                    <div class="form-group form-group-default required ">
                      <label>Name</label>
                      <input value="@if(isset($service)) {{ $service->name }} @else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                    </div>
                    @error('name')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                      <div class="form-group form-group-default required ">
                        <label>Credits</label>
                        <input value="@if(isset($service)){{$service->credits}}@else{{old('credits') }}@endif" name="credits" min="0" type="number" class="form-control" required>
                      </div>
                    @error('credits')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <div class="form-group form-group-default form-group-default-select2 required">
                      <label class="">Type</label>
                      <select name="type" class="full-width select2-hidden-accessible" data-placeholder="Select Type" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                          <option @if(isset($service) && $service->type == 'tunning') {{ 'selected' }} @elseif(old('type') == 'tunning') {{ 'selected' }} @endif value="tunning">Tuning</option>
                          <option @if(isset($service) && $service->type == 'option') {{ 'selected' }} @elseif(old('type') == 'option') {{ 'selected' }} @endif  value="option">Option</option>
                      </select>
                    </div>
                    @error('type')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <div class="form-group form-group-default @if(!isset($service)) required @endif">
                      <label>Icon</label>
                      <input name="icon" type="file" class="form-control" @if(!isset($service)) required @endif>
                    </div>
                    @error('icon')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror

                    <div class="form-group form-group-default">
                      <label>Vehicle Type</label>
                      <select multiple class="full-width" data-init-plugin="select2" name="vehicle_type[]">
                        <option @if(isset($service) && in_array('car', $vehicleTypes)) selected @endif value="car">Car</option>
                        <option @if(isset($service) && in_array('truck', $vehicleTypes)) selected @endif value="truck">Truck</option>
                        <option @if(isset($service) && in_array('machine', $vehicleTypes)) selected @endif value="machine">Machine</option>
                        <option @if(isset($service) && in_array('agri', $vehicleTypes)) selected @endif value="agri">Agricultural</option>
                      </select>
                    </div>
                    @error('type')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror

                    <div class="form-group form-group-default required ">
                      <label>Description</label>
                      <textarea name="description" class="form-control" required>@if(isset($service)) {{ $service->description }} @else{{old('description') }}@endif</textarea>
                    </div>
                    @error('description')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <div class="form-group form-group-default">
                      <label>Greek Description</label>
                      <textarea name="greek_description" class="form-control">@if(isset($modelInstance)) {{ $modelInstance->greek }} @endif</textarea>
                    </div>
                    @error('greek_description')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                    <div class="text-center m-t-40">                    
                      <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($service)) Update @else Add @endif</span></button>
                      @if(isset($service))
                        <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$service->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                      @endif
                    </div>
                  </form>
                </div>
              </div>
              <!-- END card -->
            </div>
            <div class="col-lg-3">
                @if(isset($service))
                  <div class="card social-card share  col1" >
                    <div class="card-header ">
                      <h5 class="text-black pull-left">Icon Preview</h5>
                    </div>
                    <div class="card-description">
                        <img src="{{ url('icons').'/'.$service->icon }}" alt="Stage 0">
                    </div>
                  </div>
                @endif
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
                        url: "/delete_service",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your row has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/services';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection

