@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class="container-fluid   container-fixed-lg bg-white">

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
          <div class="card-title">
            @if(isset($vehicle))
              <img class="" style="width:40%;" src="{{$vehicle->Brand_image_URL}}">
            @endif
          </div>
          @if(isset($vehicle))
            @if($vehicle->Engine_ECU)
            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'see-comments'))
              <div class="pull-right">
                <a class="btn btn-success" href="{{route('add-comments', $vehicle->id)}}">Add Comments</a>
              </div>
            @endif
            @endif
          @endif
        <div class="clearfix"></div>
      </div>
      <div class="card-body">
        @if(isset($vehicle))
          @if($vehicle->Engine_URL)
              <div class="m-b-20"><a target="_blank" href="{{$vehicle->Engine_URL}}">Engine URL</a></div>
          @endif
          @else
          <h4>Add Vehicle</h4>
          @endif
            <form class="" role="form" method="POST" action="@if(isset($vehicle)) {{route('update-vehicle')}} @else {{route('add-vehicle')}} @endif" enctype="multipart/form-data">
                @csrf
                @if(isset($vehicle))
                  <input value="{{$vehicle->id}}" name="id" type="hidden">
                @endif
                <div class="form-group form-group-default">
                    <label>Name</label>
                    <input value="@if(isset($vehicle)) {{$vehicle->Name}} @else{{old('Name') }}@endif"  name="Name" type="text" class="form-control">
                  </div>
                  @error('Name')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Make</label>
                    <input value="@if(isset($vehicle)) {{$vehicle->Make}} @else{{old('Make') }}@endif"  name="Make" type="text" class="form-control">
                  </div>
                  @error('Make')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Model</label>
                    <input value="@if(isset($vehicle)) {{$vehicle->Model}} @else{{old('Model') }}@endif"  name="Model" type="text" class="form-control">
                  </div>
                  @error('Model')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Generation</label>
                    <input value="@if(isset($vehicle)) {{$vehicle->Generation}} @else{{old('Generation') }}@endif"  name="Generation" type="text" class="form-control">
                  </div>
                  @error('Generation')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Engine</label>
                    <input value="@if(isset($vehicle)) {{$vehicle->Engine}} @else{{old('Engine') }}@endif"  name="Engine" type="text" class="form-control">
                  </div>
                  @error('Engine')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Engine ECU</label>
                    <input value="@if(isset($vehicle)) {{$vehicle->Engine_ECU}} @else{{old('Engine_ECU') }}@endif"  name="Engine_ECU" type="text" class="form-control">
                    <p>Please add multiple ECUs with " / " seprator. Otherwise It will not work. For example, Bosch EDC16C39 / Bosch EDC16C39. (It is space slash and then space).</p>
                  </div>
                  @error('Engine_ECU')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Brand Image URL</label>
                    <input value="@if(isset($vehicle)) {{$vehicle->Brand_image_URL}} @else{{old('Brand_image_URL') }}@endif"  name="Brand_image_URL" type="text" class="form-control">
                  </div>
                  @error('Brand_image_URL')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default">
                    <label>Read options</label>
                    <input value="@if(isset($vehicle)) {{$vehicle->Read_options}} @else{{old('Read_options') }}@endif"  name="Read_options" type="text" class="form-control">
                  </div>
                  @error('Read_options')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                <div class="form-group form-group-default">
                  <label>Additional_options</label>
                  <input value="@if(isset($vehicle)) {{$vehicle->Additional_options}} @else{{old('Additional_options') }}@endif"  name="Additional_options" type="text" class="form-control">
                </div>
                @error('Additional options')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default">
                  <label>Gearbox ECU</label>
                  <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{$vehicle->Gearbox_ECU}} @else{{old('Gearbox_ECU') }}@endif"  name="Gearbox_ECU" type="text" class="form-control">
                </div>
                @error('Gearbox_ECU')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default">
                  <label>Type</label>
                  <select class="full-width" data-init-plugin="select2" name="type">
                    <option @if(isset($vehicle) && $vehicle->type == 'car') selected @endif value="car">Car</option>
                    <option  @if(isset($vehicle) && $vehicle->type == 'truck') selected @endif value="truck">Truck</option>
                    <option  @if(isset($vehicle) && $vehicle->type == 'machine') selected @endif value="machine">Machine</option>
                    <option  @if(isset($vehicle) && $vehicle->type == 'agri') selected @endif value="agri">Agricultural</option>
                  </select>
                </div>
                @error('type')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                
                  @if(isset($vehicle))
                    <div class="text-center m-t-40">                    
                      <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($vehicle)) Update @else Add @endif</span></button>
                      @if(isset($vehicle))

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-vehicles'))

                        <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$vehicle->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>

                        @endif
                      @endif
                    </div>
                  @endif

                  <h4>Extra Fields</h4>

                  <div class="form-group form-group-default">
                    <label>Engine URL</label>
                    <input @if(isset($vehicle)) readonly @endif value="@if(isset($vehicle)) {{ $vehicle->Engine_URL }} @else{{old('Engine_URL') }}@endif"  name="Engine_URL" type="text" class="form-control">
                  </div>
                  @error('Engine_URL')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Chart Image URL</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->Chart_image_URL }} @else{{old('Chart_image_URL') }}@endif"  name="Chart_image_URL" type="text" class="form-control">
                  </div>
                  @error('Chart_image_URL')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>BHP standard</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->BHP_standard }} @else{{old('BHP_standard') }}@endif"  name="BHP_standard" type="text" class="form-control">
                  </div>
                  @error('BHP_standard')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>BHP Tuned</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->BHP_tuned }} @else{{old('BHP_tuned') }}@endif"  name="BHP_tuned" type="text" class="form-control">
                  </div>
                  @error('BHP_tuned')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>BHP Difference</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->BHP_difference }} @else{{old('BHP_difference') }}@endif"  name="BHP_difference" type="text" class="form-control">
                  </div>
                  @error('BHP_difference')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>TORQUE Standard</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->TORQUE_standard }} @else{{old('TORQUE_standard') }}@endif"  name="TORQUE_standard" type="text" class="form-control">
                  </div>
                  @error('TORQUE_standard')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>TORQUE Tuned</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->TORQUE_tuned }} @else{{old('TORQUE_tuned') }}@endif"  name="TORQUE_tuned" type="text" class="form-control">
                  </div>
                  @error('TORQUE_tuned')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>TORQUE Difference</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->TORQUE_difference }} @else{{old('TORQUE_difference') }}@endif"  name="TORQUE_difference" type="text" class="form-control">
                  </div>
                  @error('TORQUE_difference')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Type of fuel</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->Type_of_fuel }} @else{{old('Type_of_fuel') }}@endif"  name="Type_of_fuel" type="text" class="form-control">
                  </div>
                  @error('Type_of_fuel')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Method</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->Method }} @else{{old('Method') }}@endif"  name="Method" type="text" class="form-control">
                  </div>
                  @error('Method')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default date">
                    <label>Tuningtype</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)){{date('d/m/Y', strtotime($vehicle->Tuningtype))}}@else{{old('Tuningtype') }}@endif"  name="Tuningtype" type="text" class="form-control datepicker">
                  </div>
                  @error('Tuningtype')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Cylinder Content</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->Cylinder_content }} @else{{old('Cylinder_content') }}@endif"  name="Cylinder_content" type="text" class="form-control">
                  </div>
                  @error('Cylinder_content')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  
                  

                  <div class="form-group form-group-default">
                    <label>Compression ratio</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->Compression_ratio }} @else{{old('Compression_ratio') }}@endif"  name="Compression_ratio" type="text" class="form-control">
                  </div>
                  @error('Compression_ratio')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Bore X stroke</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->Bore_X_stroke }} @else{{old('Bore_X_stroke') }}@endif"  name="Bore_X_stroke" type="text" class="form-control">
                  </div>
                  @error('Bore_X_stroke')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Type of turbo</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->Type_of_turbo }} @else{{old('Type_of_turbo') }}@endif"  name="Type_of_turbo" type="text" class="form-control">
                  </div>
                  @error('Type_of_turbo')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default">
                    <label>Engine number</label>
                    <input @if(isset($vehicle)) disabled @endif value="@if(isset($vehicle)) {{ $vehicle->Engine_number	}} @else{{old('Engine_number') }}@endif"  name="Engine_number" type="text" class="form-control">
                  </div>
                  @error('Engine_number')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                @if(!isset($vehicle))
                    <div class="text-center m-t-40">                    
                      <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($vehicle)) Update @else Add @endif</span></button>
                      @if(isset($vehicle))

                      @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-vehicle'))

                        <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$vehicle->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                      @endif

                      @endif

                    </div>
                  @endif
            </form>
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
                        url: "/delete_vehicle",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your Vehicle has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/vehicles';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection