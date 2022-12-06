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

              <!-- Nav tabs -->
              <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                <li class="nav-item">
                  <a href="#" class="active" data-toggle="tab" data-target="#tab-fillup1"><span>General Information</span></a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" data-target="#tab-fillup2"><span>Options and Comments</span></a>
                </li>
                
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="tab-fillup1">
                  <div class="card-header">
                    <div class="card-title">
                      @if(isset($vehicle))
                        <img class="" style="width:40%;" src="{{$vehicle->Brand_image_URL}}">
                      @endif
                    </div>
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
                            <input value="@if(isset($vehicle)) {{ $vehicle->Name }} @else{{old('Name') }}@endif"  name="Name" type="text" class="form-control">
                          </div>
                          @error('name')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Make</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Make }} @else{{old('Make') }}@endif"  name="Make" type="text" class="form-control">
                          </div>
                          @error('name')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Generation</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Generation }} @else{{old('Generation') }}@endif"  name="Generation" type="text" class="form-control">
                          </div>
                          @error('Generation')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Engine</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Engine }} @else{{old('Engine') }}@endif"  name="Engine" type="text" class="form-control">
                          </div>
                          @error('Engine')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Brand Image URL</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Brand_image_URL }} @else{{old('Brand_image_URL') }}@endif"  name="Brand_image_URL" type="text" class="form-control">
                          </div>
                          @error('Brand_image_URL')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Engine URL</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Engine_URL }} @else{{old('Engine_URL') }}@endif"  name="Engine_URL" type="text" class="form-control">
                          </div>
                          @error('Engine_URL')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Chart Image URL</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Chart_image_URL }} @else{{old('Chart_image_URL') }}@endif"  name="Chart_image_URL" type="text" class="form-control">
                          </div>
                          @error('Chart_image_URL')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>BHP standard</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->BHP_standard }} @else{{old('BHP_standard') }}@endif"  name="BHP_standard" type="text" class="form-control">
                          </div>
                          @error('BHP_standard')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>BHP Tuned</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->BHP_tuned }} @else{{old('BHP_tuned') }}@endif"  name="BHP_tuned" type="text" class="form-control">
                          </div>
                          @error('BHP_tuned')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>BHP Difference</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->BHP_difference }} @else{{old('BHP_difference') }}@endif"  name="BHP_difference" type="text" class="form-control">
                          </div>
                          @error('BHP_difference')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>TORQUE Standard</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->TORQUE_standard }} @else{{old('TORQUE_standard') }}@endif"  name="TORQUE_standard" type="text" class="form-control">
                          </div>
                          @error('TORQUE_standard')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>TORQUE Tuned</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->TORQUE_tuned }} @else{{old('TORQUE_tuned') }}@endif"  name="TORQUE_tuned" type="text" class="form-control">
                          </div>
                          @error('TORQUE_tuned')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>TORQUE Difference</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->TORQUE_difference }} @else{{old('TORQUE_difference') }}@endif"  name="TORQUE_difference" type="text" class="form-control">
                          </div>
                          @error('TORQUE_difference')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Type of fuel</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Type_of_fuel }} @else{{old('Type_of_fuel') }}@endif"  name="Type_of_fuel" type="text" class="form-control">
                          </div>
                          @error('Type_of_fuel')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Method</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Method }} @else{{old('Method') }}@endif"  name="Method" type="text" class="form-control">
                          </div>
                          @error('Method')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default date">
                            <label>Tuningtype</label>
                            <input value="@if(isset($vehicle)){{date('d/m/Y', strtotime($vehicle->Tuningtype))}}@else{{old('Tuningtype') }}@endif"  name="Tuningtype" type="text" class="form-control datepicker">
                          </div>
                          @error('Tuningtype')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Cylinder Content</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Cylinder_content }} @else{{old('Cylinder_content') }}@endif"  name="Cylinder_content" type="text" class="form-control">
                          </div>
                          @error('Cylinder_content')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Engine ECU</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Engine_ECU }} @else{{old('Engine_ECU') }}@endif"  name="Engine_ECU" type="text" class="form-control">
                          </div>
                          @error('Engine_ECU')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Gearbox ECU</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Gearbox_ECU }} @else{{old('Gearbox_ECU') }}@endif"  name="Gearbox_ECU" type="text" class="form-control">
                          </div>
                          @error('Gearbox_ECU')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Compression ratio</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Compression_ratio }} @else{{old('Compression_ratio') }}@endif"  name="Compression_ratio" type="text" class="form-control">
                          </div>
                          @error('Compression_ratio')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Bore X stroke</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Bore_X_stroke }} @else{{old('Bore_X_stroke') }}@endif"  name="Bore_X_stroke" type="text" class="form-control">
                          </div>
                          @error('Bore_X_stroke')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Type of turbo</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Type_of_turbo }} @else{{old('Type_of_turbo') }}@endif"  name="Type_of_turbo" type="text" class="form-control">
                          </div>
                          @error('Type_of_turbo')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Engine number</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Engine_number	}} @else{{old('Engine_number') }}@endif"  name="Engine_number" type="text" class="form-control">
                          </div>
                          @error('Engine_number')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Read options</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Read_options	}} @else{{old('Read_options') }}@endif"  name="Read_options" type="text" class="form-control">
                          </div>
                          @error('Read_options')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="form-group form-group-default">
                            <label>Additional_options</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Additional_options }} @else{{old('Additional_options') }}@endif"  name="Additional_options" type="text" class="form-control">
                          </div>
                          @error('Additional options')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror

                          <div class="text-center m-t-40">                    
                            <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($vehicle)) Update @else Add @endif</span></button>
                            @if(isset($vehicle))
                              <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$vehicle->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                            @endif
                          </div>
                    </form>
                </div>
                </div>
                <div class="tab-pane" id="tab-fillup2">
                  <div class="card card-transparent">
                    <div class="card-header  separator">
                      <div class="col-xs-12">
                        <div class="pull-right">
                          <button data-toggle="modal" data-target="#modalSlideUp" class="btn btn-success">Add New Comments</button>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="card-title">
                        <h4><span class="semi-bold">Options</span> and Comments</h4>
                      </div>
                      
                    </div>
                    <div class="card-body">
                      <div class="p-t-20">
                        @if(!$comments->isEmpty())
                          @foreach($comments as $comment)
                            <div class="p-t-10">
                                <img alt="{{$comment->option}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $comment->option)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $comment->option)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $comment->option)->first()->icon }}">
                                {{$comment->option}}
                            </div> 
                            <p> {{$comment->comments}}</p>
                          @endforeach
                        @else
                            <p>No Comments added.</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<div class="modal fade slide-up disable-scroll" id="modalSlideUp" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Options <span class="semi-bold"> and Comment</span></h5>
          <p class="p-b-10">Please select option and add comments.</p>
        </div>
        <div class="modal-body">
          <form role="form" action="{{route('add-option-comments')}}" method="POST">
            @csrf
            <input type="hidden" name="engine" value="{{$vehicle->Engine}}">
            <input type="hidden" name="make" value="{{$vehicle->Make}}">
            <input type="hidden" name="ecu" value="{{$vehicle->Engine_ECU}}">
            <input type="hidden" name="generation" value="{{$vehicle->Generation}}">
            <input type="hidden" name="model" value="{{$vehicle->Model}}">
            <input type="hidden" name="id" value="{{$vehicle->id}}">
            <div class="form-group form-group-default required ">
              <label>Option</label>
              <select class="full-width" data-init-plugin="select2" name="option">
                @foreach($options as $option)
                  @if(!in_array($option->name, $includedOptions))
                  <option value="{{$option->name}}">{{$option->name}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="form-group-attached ">
              <div class="row">
                <div class="col-md-12">
                  
                  <div class="form-group form-group-default required">
                    <label>Comment</label>
                    <textarea name="comments" required style="height: 100px;" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
         
          <div class="row">
            <div class="col-md-4 m-t-10 sm-m-t-10 text-center">
              <button type="submit" class="btn btn-success btn-block m-t-5">Add Comment</button>
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