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
                    <div style="margin: 20px 0px;">

                      <strong>Submission Date Filter:</strong>
              
                      <input type="text" name="daterange" value="" />
              
                      <button class="btn btn-success filter">Filter</button>
              
                  </div>
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

                

                {{-- <livewire:files-datatable 
                  searchable="id,username,brand,model,ecu"
                /> --}}

                <table class="table table-bordered data-table" >

                  <thead>
          
                      <tr>
          
                          <th>Task ID</th>
                          <th>Submission Countdown / Reply Countdown</th>
                          <th>Frontend</th>
                          <th>Submission Date</th>
                          <th>Submission Time</th>
                          <th>Customer</th>
                          <th>Brand</th>
                          <th>Model</th>
                          <th>ECU</th>
                          <th>Stage</th>
                          <th>Options</th>
                          
                          
                      </tr>
          
                  </thead>
          
                  <tbody>
          
                  </tbody>
          
              </table>
              </div>
            </div>
        </div>
        </div>
    </div>
@endsection
@section('pagespecificscripts')
    <script type="text/javascript">

      $(function () {

      $('input[name="daterange"]').daterangepicker({
        startDate: moment().subtract(36, 'M'),
        endDate: moment()
      });

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });


      var table = $('.data-table').DataTable({

          processing: true,
          serverSide: true,
          order: [[0,'desc']],
          ajax: {
              url: "{{ route('ajax-files') }}",
              type: 'POST',
              data:function (d) {

                d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

              }
          },
          columns: [
              {data: 'id', name: 'id'},
              {data: 'timers', name: 'timers', orderable: false, searchable: false},
              {data: 'frontend', name: 'frontend', orderable: false, searchable: false},
              {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }
              },
              {data: 'created_time', name: 'created_time', orderable: false, searchable: false},
              {data: 'username', name: 'username'},
              {data: 'brand', name: 'brand'},
              {data: 'model', name: 'model'},
              {data: 'ecu', name: 'ecu'},
              {data: 'stage', name: 'stage'},
              {data: 'options', name: 'options'},
              
          ]

      });

      $(".filter").click(function(){
        table.draw();
      });

      });



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
          // console.log(ek);

          var sk=[];
          $('.submission-stoped').each(function() { sk.push($(this).attr('id')); });
          console.log(sk);

          var ik=[];
          $('.open').each(function() { ek.push($(this).attr('id')); });
          // console.log(ik);

          function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10)
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
          }

          function stopTimer(duration, display) {
            var timer = duration, minutes, seconds;

                 minutes = parseInt(timer / 60, 10)
                  seconds = parseInt(timer % 60, 10);

                

                display.textContent = minutes + ":" + seconds;

            
          }

          $.each(ek , function(index, val) { 
            console.log(index, val);
            let display = document.querySelector('#'+val);
            let seconds = $('#'+val).data('seconds');
            startTimer(seconds, display);
          });

          $.each(sk , function(index, val) { 
            console.log(index, val);
            let display = document.querySelector('#'+val);
            let seconds = $('#'+val).data('seconds');
            stopTimer(seconds, display);
          });



          $.each(ik , function(index, val) { 
            console.log(index, val);
            let display = document.querySelector('#'+val);
            let seconds = $('#'+val).data('seconds');
            startTimer(seconds, display);
          });

          

        });
    </script>
@endsection