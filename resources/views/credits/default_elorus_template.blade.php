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
            
            <div class="tab-content">
              <div class="tab-pane slide-left active" id="slide1">
                <div class="card card-transparent m-t-20">
                  <div class="card-header ">
                      <div class="card-title">
                       
                        <h5>
                          Default Elorus Template ID
                        </h5>
                      
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">
                    <form class="" role="form" method="POST" action="{{route('update-default-eloru-template-id')}}" enctype="multipart/form-data">
                      @csrf
                     
                      <div class="form-group form-group-default required ">
                        <label class="text-danger">Please fill this field carefully. If this ID will be wrong then code will break.</label>
                        <input value="@if(isset($defaultTemplateID)){{ $defaultTemplateID->value }}@endif"  name="default_elorus_template_id" type="text" class="form-control" required>
                      </div>
                      @error('credit_price')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                      <div class="text-center m-t-20">                    
                        <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                        
                      </div>
                    </form>
                      
                  </div>
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
        
       
    });

</script>

@endsection