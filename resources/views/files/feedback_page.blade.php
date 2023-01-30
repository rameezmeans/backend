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
                    <h3>Feedback Email</h3>
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
                <form method="POST" action="{{route('save-feedback-email-template')}}">
                    @csrf
                    <textarea name="new_template" style="width: 100%; height: 500px;">{{$feebdackTemplate->html}}</textarea>
                    <button type="submit" class="btn btn-success pull-right">Save</button>
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