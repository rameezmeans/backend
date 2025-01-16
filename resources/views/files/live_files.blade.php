@extends('layouts.app')

@section('pagespecificstyles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
<style>
  
  .flex {
    display: flex !important;
    width: max-content;
  }

  .redirect-click-file{
    cursor: pointer;
  }

</style>
@endsection

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class="container-fluid bg-white">
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
                  <div class="card-title">
                    <h3>Files</h3>
                  </div>
                  <div class="pull-right">
                    <div class="col-xs-12">
                      @if(Auth::user()->is_admin())
                        <button data-redirect="{{route('multi-delete')}}" class="btn btn-success redirect-click"><i class="pg-plus_circle"></i> <span class="bold">Multi Delete</span>
                        </button>
                      @endif
                    </div>
                  </div>
                  <div class="clearfix"></div>
              </div>
              <div class="card-body">
                <livewire:files-datatable 
                  searchable="id,username,brand,model,ecu"
                />
              </div>
            </div>
        </div>
        </div>
    </div>
@endsection
@section('pagespecificscripts')
    <script type="text/javascript">
      $( document ).ready(function(event) {

        $('.parent-adjusted').parent().addClass('flex');

        $(document).on('click','.redirect-click-file',function(e) {
          console.log('clicked');
            var lastClass = $(this).attr('class').split(' ').pop();
            console.log(lastClass);
            // console.log("http://backend.test/file/"+lastClass);

            window.location.href = "/file/"+lastClass;
            
          });
    
          var ek=[];
          $('.submission').each(function() { ek.push($(this).attr('id')); });
          console.log(ek);


          var timeInSecs;
          var ticker;

          function startTimer(secs, id) {
            timeInSecs = parseInt(secs);
            ticker = setInterval("tick()", 1000); 
          }

          function tick() {
            var secs = timeInSecs;
            if (secs > 0) {
            timeInSecs--; 
            }
            else {
              clearInterval(ticker);
              startTimer(5*60); // 4 minutes in seconds
            }

            var mins = Math.floor(secs/60);
            secs %= 60;
            var pretty = ( (mins < 10) ? "0" : "" ) + mins + ":" + ( (secs < 10) ? "0" : "" ) + secs;

            document.getElementById(id).innerHTML = pretty;
          }

          $.each(ek , function(index, val) { 
            console.log(index, val);

            startTimer(5*60, val);

          });

          

        });
    </script>
@endsection