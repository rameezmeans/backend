@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">
            @if(Session::has('success'))
                <div class="pgn-wrapper" data-position="top" style="top: 59px;">
                <div class="pgn push-on-sidebar-open pgn-bar">
                    <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                    </button>
                    {{ Session::get('success') }}
                    </div>
                </div>
                </div>
            @endif

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                    <h3>Feedback Reminders</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('feedback-reports') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Feedback Reports</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="">
                    <h5>Email Schedual</h5>
                    <form method="POST" action="{{route('save-feedback-email-schedual')}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <label>Days</label>
                                <input class="form-control" name="days" type="number" min="1" value="@if($schedual){{$schedual->days}}@endif">
                            </div>
                            <div class="bootstrap-timepicker col-lg-4">
                                <label>Time of Day</label>
                                <input class="form-control" name="time_of_day" type="time" value="@if($schedual){{$schedual->time_of_day}}@endif">
                            </div>
                            <div class="bootstrap-timepicker col-lg-4">
                                <label>Number of Cycles</label>
                                <input class="form-control" name="cycle" min="1" type="number" value="@if($schedual){{$schedual->cycle}}@endif">
                            </div>
                            
                        </div>
                        <div class="pull-left">
                            <button type="submit" class="btn btn-success pull-right">Save</button>
                        </div>  
                        
                        
                    </form>
                </div>
                <div class="" style="margin-top: 100px;">
                    <h5>Email Template</h5>
                    <form method="POST" action="{{route('save-feedback-email-template')}}">
                        @csrf
                        <textarea class="form-control" name="new_template" style="width: 100%; height: 500px;">{{$feebdackTemplate->html}}</textarea>
                        <button type="submit" class="btn btn-success m-t-20">Save</button>
                    </form>
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