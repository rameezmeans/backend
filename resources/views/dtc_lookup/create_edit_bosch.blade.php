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
                  @if(isset($boschRecord))
                  <h5>
                    Edit Bosch Record
                  </h5>
                @else
                  <h5>
                    Add Bosch Record
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('bosch-lookup')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Bosch Numbers</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($boschRecord)){{route('update-bosch')}}@else{{ route('add-bosch') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($boschRecord))
                  <input name="id" type="hidden" value="{{ $boschRecord->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Manufacturer Number</label>
                  <input value="@if(isset($boschRecord)){{ $boschRecord->manufacturer_number }}@else{{old('manufacturer_number') }}@endif"  name="manufacturer_number" type="text" class="form-control" required>
                </div>
                @error('manufacturer_number')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>ECU</label>
                  <input value="@if(isset($boschRecord)){{ $boschRecord->ecu }}@else{{old('ecu') }}@endif"  name="ecu" type="text" class="form-control" required>
                </div>
                @error('ecu')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($boschRecord)) Update @else Add @endif</span></button>
                  
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
        

        
    });

</script>

@endsection