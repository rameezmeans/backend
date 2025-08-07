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
                  
                  <h5>
                    Timers
                  </h5>
                
                
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="{{route('update-timers')}}" enctype="multipart/form-data">
                @csrf
                
                  
                <div class="form-group form-group-default required ">
                  <label>File submitted Delay time (minutes)</label>
                  <input value="@if(isset($fsdt)){{ $fsdt }}@else{{old('file_submitted_delay_time') }}@endif"  name="file_submitted_delay_time" type="text" class="form-control" required>
                </div>
                @error('file_submitted_delay_time')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                  <label>File submitted Alert time (minutes)</label>
                  <input value="@if(isset($fsat)){{ $fsat }}@else{{old('file_submitted_alert_time') }}@endif"  name="file_submitted_alert_time" type="text" class="form-control" required>
                </div>
                @error('file_submitted_alert_time')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                  <label>File open Alert time (minutes)</label>
                  <input value="@if(isset($foat)){{ $foat }}@else{{old('file_open_alert_time') }}@endif"  name="file_open_alert_time" type="text" class="form-control" required>
                </div>
                @error('file_open_alert_time')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                  <label>File open Delay time (minutes)</label>
                  <input value="@if(isset($fodt)){{ $fodt }}@else{{old('file_open_delay_time') }}@endif"  name="file_open_delay_time" type="text" class="form-control" required>
                </div>
                @error('file_open_delay_time')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold"> Update Timers </span></button>
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