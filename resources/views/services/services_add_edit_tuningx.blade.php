@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
      <div class="container-fluid padding-25 sm-padding-10">
        <!-- START ROW -->
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
        
        <div class="row">
            <div class="col-lg-9">
              <!-- START card -->
              <div class="card card-default">
                <div class="card-header ">
                  <div class="pull-right">
                      <button data-redirect="{{ route('services') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Servies</span></button>
                      @if(Auth::user()->is_admin())
                        @if(isset($service))
                          <button data-redirect="{{ route('set-group-price', ['id' => $service->id]) }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Set Subdealer Price</span></button>
                        @endif
                      @endif
                  </div>
                  <div class="card-title">
                    @if(isset($service))
                    @if($service->type == 'option')
                    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-right bg-white" id="tab-3">
                      <li class="nav-item">
                        <a href="#" class="active show" data-toggle="tab" data-target="#editServiceTab">Edit Service</a>
                      </li>
                      <li class="nav-item">
                        <a href="#" data-toggle="tab" data-target="#clientCommentsTable" class="">Client's Comments</a>
                      </li>
                    </ul>

                    @else
                      <h5>
                        Edit Service
                      </h5>
                    @endif
                    @else
                      <h5>Add Services</h5>
                    @endif
                  </div>
                </div>

                <div class="tab-content bg-white">
                
                  <div class="tab-pane active show" id="editServiceTab">

                <div class="card-body">

                    @if($service->type == 'option')
                    <ul class="nav nav-tabs nav-tabs-fillup m-t-40" data-init-reponsive-tabs="dropdownfx">
                        
                        <li class="nav-item">
                        <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>Service Information</span></a>
                        </li>
                        @if($service->tuningx_active)
                        <li class="nav-item">
                        <a href="#" data-toggle="tab" data-target="#slide2"><span>Credits Charged</span></a>
                        </li>
                        @endif
                    </ul>
                    @endif

                    <div class="tab-content">
                        <div class="tab-pane slide-left active" id="slide1">
                  
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
                      <label>Label (To show to LUA)</label>
                      <input value="@if(isset($service)) {{ $service->label }} @else{{old('label') }}@endif"  name="label" type="text" class="form-control" required>
                    </div>
                    @error('label')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror

                    @if($service->active)

                      <div class="form-group form-group-default required ">
                        <label>ECU Tech Credits</label>
                        <input value="@if(isset($service)){{$service->credits}}@else{{old('credits') }}@endif" name="credits" min="0" type="number" class="form-control" required>
                      </div>
                    @error('credits')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror

                    @endif

                    @if($service->type == 'tunning')
                      <div class="form-group form-group-default required ">
                        <label>Tuning-X Master Credits</label>
                        <input value="@if(isset($service)){{$service->tuningx_credits}}@else{{old('tuningx_credits') }}@endif" name="tuningx_credits" min="0" type="number" class="form-control" required>
                      </div>
                      @error('tuningx_credits')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                      <div class="form-group form-group-default required ">
                        <label>Tuning-X Slave Credits</label>
                        <input value="@if(isset($service)){{$service->tuningx_slave_credits}}@else{{old('tuningx_slave_credits') }}@endif" name="tuningx_slave_credits" min="0" type="number" class="form-control" required>
                      </div>
                      @error('tuningx_credits')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    @endif
                    <div class="form-group form-group-default form-group-default-select2 required">
                      <label class="">Type</label>
                      <select name="type" class="full-width select2-hidden-accessible" data-placeholder="Select Type" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                          {{-- <option @if(isset($service) && $service->type == 'tunning') {{ 'selected' }} @elseif(old('type') == 'tunning') {{ 'selected' }} @endif value="tunning">Tuning</option> --}}
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

                    <div class="form-group form-group-default">
                      <label>Front End</label>
                      <div class="radio radio-success">
                        <input type="radio" @if($service->active) checked="checked" @endif value="ecutech" name="frontend" id="ecutech">
                        <label for="ecutech">ECU Tech</label>
                        <input type="radio" @if($service->tuningx_active) checked="checked" @endif value="tuningx" name="frontend" id="tuningx">
                        <label for="tuningx">TuningX</label>
                      </div>
                    </div>
                    @error('greek_description')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror

                    <div class="text-center m-t-40">                    
                      <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($service)) Update @else Add @endif</span></button>
                      @if(isset($service))
                       @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-services'))

                        <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$service->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>

                        @endif
                      @endif
                    </div>
                  </form>
                </div>
                @if($service->type == 'option')
                    <div class="tab-pane slide-left" id="slide2">

                        <form class="" role="form" method="POST" action="{{ route('set-credit-prices') }}" enctype="multipart/form-data">
                           
                            @csrf
                            
                            @if(isset($service))
                                <input name="id" type="hidden" value="{{ $service->id }}">
                            @endif

                            <div class="row">

                            <div class="form-group form-group-default required col-md-6" style="padding-left: 7px;">
                                <label>Tuning-X Master Credits</label>
                                <input value="@if(isset($service)){{$service->tuningx_credits}}@endif" name="tuningx_credits" min="0" type="number" class="form-control" required>
                            </div>

                            <div class="form-group form-group-default required col-md-6">
                                <label>Tuning-X Slave Credits</label>
                                <input value="@if(isset($service)){{$service->tuningx_slave_credits}}@endif" name="tuningx_slave_credits" min="0" type="number" class="form-control" required>
                            </div>
                        </div>

                            @foreach($stages as $stage)
                                  
                                <div>{{$stage->name}}</div>

                                <div class="row">
                                <div class="form-group form-group-default required col-md-6" style="padding-left: 7px;">
                                    <label>Tuning-X Master Credits</label>
                                    <input value="{{$stage->stages_option($service->id)->first()->master_credits}}" name="master-{{$service->id}}-{{$stage->id}}" min="0" type="number" class="form-control" required>
                                </div>

                                <div class="form-group form-group-default required col-md-6">
                                    <label>Tuning-X Slave Credits</label>
                                    <input value="{{$stage->stages_option($service->id)->first()->slave_credits}}" name="slave-{{$service->id}}-{{$stage->id}}" min="0" type="number" class="form-control" required>
                                </div>
                                </div>
                            @endforeach
                            
                            <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Set Prices</span></button>

                        </form>


                    </div>
                @endif
                
            </div>
               
                </div>
              </div>
              <!-- END card -->

              <div class="tab-pane active show" id="clientCommentsTable">

              <div class="card-body">
                <form class="" role="form" method="POST" action="{{route('set-customers-comments')}}" enctype="multipart/form-data">
                  @csrf
                  @if(isset($service))
                  <input type="hidden" name="service_id" value="{{$service->id}}">
                    <div class="form-group form-group-default">
                      <div class="checkbox check-success checkbox-circle">
                        <input name="customers_comments_active" @if(isset($service) && $service->customers_comments_active == 1) checked @endif type="checkbox" id="checkbox9">
                        <label for="checkbox9">Active for Customer's Comments</label>
                      </div>
                    </div>

                    <div class="form-group form-group-default required ">
                      <label>Placeholder Text</label>
                      <textarea name="customers_comments_placeholder_text" class="form-control" required>@if(isset($service)){{$service->customers_comments_placeholder_text}}@else{{old('customers_comments_placeholder_text') }}@endif</textarea>
                    </div>
                    @error('customers_comments_placeholder_text')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror

                    <div class="form-group form-group-default">
                      <label>Vehicle Type</label>
                      <select multiple class="full-width" data-init-plugin="select2" name="customers_comments_vehicle_type[]">
                        <option @if(isset($service) && in_array('car', $service->customers_comments_vehicle_type)) selected @endif value="car">Car</option>
                        <option @if(isset($service) && in_array('truck', $service->customers_comments_vehicle_type)) selected @endif value="truck">Truck</option>
                        <option @if(isset($service) && in_array('machine', $service->customers_comments_vehicle_type)) selected @endif value="machine">Machine</option>
                        <option @if(isset($service) && in_array('agri', $service->customers_comments_vehicle_type)) selected @endif value="agri">Agricultural</option>
                      </select>
                    </div>

                    <button class="btn btn-success btn-cons m-b-10 m-t-30" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Set For Customers Comments</span></button>
                  @endif
                </form>
              </div>
              
            </div>

              </div>
            </div>

            </div>
            <div class="col-lg-3">
                @if(isset($service))
                  <div class="card social-card share  col1" >
                    <div class="card-header ">
                      <h5 class="text-black pull-left">Icon Preview</h5>
                    </div>
                    <div class="card-description">
                        <img src="{{ url('icons').'/'.$service->icon }}" alt="{{$service->name}}">
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

