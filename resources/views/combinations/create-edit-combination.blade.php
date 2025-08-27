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
            <!-- START card -->

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                  @if(isset($combination))
                  <h5>
                    Edit Combination
                  </h5>
                @else
                  <h5>
                    Add Combination
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('combinations')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Combinations</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($combination)){{route('update-combination')}}@else{{ route('add-combination') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($combination))
                  <input name="id" type="hidden" value="{{ $combination->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($combination)) {{ $combination->name }} @else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required form-group-default-select2">
                    <label>Services</label>
                    
                        <select id="services" @if(isset($combination)) disabled @endif name="services[]" class=" full-width" data-init-plugin="select2" multiple>
                            @foreach ($services as $service)
                                <option @if(isset($selectedServices)) @if(in_array($service->id, $selectedServices)) selected @endif @endif data-credits="{{$service->credits}}" value="{{$service->id}}">{{$service->name}} - ({{$service->vehicle_type}}) - @if($service->front_end_id) @if($service->active == 1) (ECUTech) @elseif($service->tuningx_active == 1) (TuningX) @elseif($service->efiles_active == 1) (ETF) @endif @endif</option>
                            @endforeach
                        </select>
                    
                  </div>
                  @if(isset($combination))

                  <div class="form-group form-group-default ">
                    <label>Actual Price</label>
                    <input @if($combination) disabled @endif value="@if(isset($combination)){{ $combination->actual_credits }}@else{{old('actual_credits') }}@endif"  name="actual_credits" type="text" class="form-control">
                  </div>
                  @error('actual_credits')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default">
                    <label>Discounted Price</label>
                    <input value="@if(isset($combination)){{$combination->discounted_credits}}@else{{old('discounted_credits') }}@endif"  name="discounted_credits" type="text" class="form-control">
                  </div>
                  @error('discounted_credits')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  @endif
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($combination)) Update @else Add @endif</span></button>
                  @if(isset($combination))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$combination->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
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

        $("#services").on("select2-selecting", function(e) {
            $("#services").select2("data",e.credits);
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
                        url: "/delete_combination",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Combination has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/combinations';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection