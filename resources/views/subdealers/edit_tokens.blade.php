@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid container-fixed-lg bg-white">

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
            <div class="card-header ">
                <div class="card-title"><h3>Tokens</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('edit-subdealer-group', ['id' => $subdealerID])}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Subdealer Group</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <form class="" role="form" method="POST" action="{{route('update-tokens')}}" enctype="multipart/form-data">
                    @csrf
                   
                    <input name="id" type="hidden" value="{{ $subdealerID }}">
                    
                    <div class="form-group form-group-default required ">
                      <label>Alientech API Key</label>
                      <input value="@if($alienTechKey){{$alienTechKey->value}}@endif"  name="alientech_access_token" type="text" class="form-control" required>
                    </div>
                    @error('name')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                   
                      <div class="form-group form-group-default required ">
                        <label>TWILIO_SID</label>
                        <input value="@if($sid){{$sid->value}}@endif"  name="twilio_sid" type="text" class="form-control" required>
                      </div>
                      @error('twilio_sid')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                      <div class="form-group form-group-default required ">
                        <label>TWILIO_AUTH_TOKEN</label>
                        <input value="@if($twilioToken){{$twilioToken->value}}@endif"  name="twilio_token" type="text" class="form-control" required>
                      </div>
                      @error('twilio_token')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                      <div class="form-group form-group-default required ">
                        <label>TWILIO_NUMBER</label>
                        <input value="@if($twilioNumber){{$twilioNumber->value}}@endif"  name="twilio_number" type="text" class="form-control" required>
                      </div>
                      @error('twilio_number')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                      <div class="form-group form-group-default required ">
                        <label>EVC API Username</label>
                        <input value="@if($evcUsername){{$evcUsername->value}}@endif"  name="evc_username" type="text" class="form-control" required>
                      </div>
                      
                      @error('evc_username')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                      <div class="form-group form-group-default required ">
                        <label>EVC API Password</label>
                        <input value="@if($evcPassword){{$evcPassword->value}}@endif"  name="evc_password" type="text" class="form-control" required>
                      </div>
                      @error('evc_password')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    <div class="text-center m-t-40">                    
                      <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Add</span></button>
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