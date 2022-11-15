@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">
            <div class="card card-transparent m-t-40">
                <div class="card-header">
                    <div class="card-title">
                        <img class="" style="width:40%;" src="{{$vehicle->Brand_image_URL}}">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    @if($vehicle->Engine_URL)
                        <div class="m-b-20"><a href="{{$vehicle->Engine_URL}}">Engine URL</a></div>
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
                            <label>Engine</label>
                            <input value="@if(isset($vehicle)) {{ $vehicle->Engine }} @else{{old('Engine') }}@endif"  name="Engine" type="text" class="form-control">
                          </div>
                          @error('Engine')
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
        </div>
    </div>
</div>
@endsection