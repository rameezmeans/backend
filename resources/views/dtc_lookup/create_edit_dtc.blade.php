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
                  @if(isset($dtcRecord))
                  <h5>
                    Edit DTC Record
                  </h5>
                @else
                  <h5>
                    Add DTC Record
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('dtc-lookup')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">DTC Numbers</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($dtcRecord)){{route('update-dtc')}}@else{{ route('add-dtc') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($dtcRecord))
                  <input name="id" type="hidden" value="{{ $dtcRecord->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Code</label>
                  <input value="@if(isset($dtcRecord)){{ $dtcRecord->code }}@else{{old('code') }}@endif"  name="code" type="text" class="form-control" required>
                </div>
                @error('code')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Desc</label>
                  <input value="@if(isset($dtcRecord)){{ $dtcRecord->desc }}@else{{old('desc') }}@endif"  name="desc" type="text" class="form-control" required>
                </div>
                @error('desc')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($dtcRecord)) Update @else Add @endif</span></button>
                  
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