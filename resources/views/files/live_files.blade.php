@extends('layouts.app')

@section('pagespecificstyles')
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}
{{-- <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script> --}}
<style>
  
  .flex {
    display: flex !important;
    width: max-content;
  }

  .redirect-click-file{
    cursor: pointer;
  }

  .table tbody tr td{
    background-color: transparent !important;
  }

  .bg-grey{
    --tw-bg-opacity: 1;
    background-color: rgb(107 114 128 / var(--tw-bg-opacity, 1)) !important;
}

.bg-red-200 {
    background-color: rgb(254 202 202) !important;
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
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="pull-right">
                    <div class="col-xs-12">
                      @if(Auth::user()->is_admin())
                        <button data-redirect="{{route('multi-delete')}}" class="btn btn-success redirect-click"><i class="pg-plus_circle"></i> <span class="bold">Multi Delete</span>
                        </button>
                      @endif
                    </div>
                  </div>

                  <div class="card-group horizontal" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="card card-default m-b-0">
                      <div class="card-header " role="tab" id="headingOne">
                        <h4 class="card-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                             Data Filters
                            </a>
                          </h4>
                      </div>
                      <div id="collapseOne" class="collapse" role="tabcard" aria-labelledby="headingOne">
                        <div class="card-body">
                            <div class="row m-t-20 m-b-20">
                      <div class="col-md-6">

                    <div class="form-group" style="display: inline-flex;margin-top:20px;">

                      <label>Submission Date Filter:</label>
              
                      <input class="form-control" type="text" name="daterange" value="" />
              
                      <button class="btn btn-success filter m-l-5">Filter</button>
              
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group form-group-default-select2">

                      <label>Late Filter:</label>
              
                      <select class="form-control" id="late">
                        <option value="all">ALL</option>
                        <option value="late">Late</option>
                      </select>

                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group form-group-default-select2">

                      <label>Frontend Filter:</label>
              
                      <select class="form-control" id="frontend">
                        <option value="all">ALL</option>
                        <option value="1">ECUTech</option>
                        <option value="2">TuningX</option>
                        <option value="3">Efiles</option>
                      </select>

                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group form-group-default-select2">

                      <label>Support Status Filter:</label>
              
                      <select class="form-control" id="support_status">
                        <option value="all">ALL</option>
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                      </select>

                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group form-group-default-select2">

                      <label>Status Filter:</label>
              
                      <select class="form-control full-width" id="status" data-init-plugin="select2" multiple>
                        
                        <option value="completed">Completed</option>
                        <option value="rejected">Canceled</option>
                        <option value="on_hold">On Hold</option>
                        <option value="submitted">Submitted</option>
                        <option value="processing">Processing</option>
                      
                      </select>

                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group form-group-default-select2">

                      <label>Stages Filter:</label>
              
                      <select class="form-control full-width" id="stage" data-init-plugin="select2" multiple>
                          
                          @foreach ($stages as $stage)
                            <option value="{{$stage->name}}">{{$stage->name}}</option>
                          @endforeach
                      
                      </select>

                    </div>
                  </div>

                  {{-- <div class="col-md-6">
                    <div class="form-group form-group-default-select2">

                      <label>Options Filter:</label>
              
                      <select class="form-control" id="options" data-init-plugin="select2" multiple>
                          
                          @foreach ($options as $option)
                            <option value="{{$option->id}}">{{$option->name}}</option>
                          @endforeach
                      
                      </select>

                    </div>
                  </div> --}}

                  <div class="col-md-6">
                    <div class="form-group form-group-default-select2">

                      <label>Assigned To Filter:</label>
              
                      <select class="form-control full-width" id="engineer" data-init-plugin="select2" multiple>
                          
                          @foreach ($engineers as $engineer)
                            <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                          @endforeach

                          <option value="automatic">Automatic</option>
                      
                      </select>

                    </div>
                  </div>

              </div>
                        </div>
                      </div>
                    </div>
                    
                    
                  </div>

                    
              <div class="card-body">

                {{-- <livewire:files-datatable 
                  searchable="id,username,brand,model,ecu"
                /> --}}

                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer">
                  <div>

                <table class="table table-hover demo-table-search table-responsive-block data-table no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info" >

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
                          <th>Support Status</th>
                          <th>Status</th>
                          <th>Stage</th>
                          <th>Options</th>
                          <th>Credits</th>
                          <th>Assigned To</th>
                          <th>Response Time</th>
                          
                          
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
        </div>
    </div>
@endsection
@section('pagespecificscripts')
    <script type="text/javascript">

  $( document ).ready(function(event) {

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

          stripeClasses: [],
          processing: true,
          serverSide: true,
          ajax: {
              url: "{{ route('ajax-files') }}",
              type: 'POST',
              data:function (d) {

                d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
                d.late = $('#late').val();
                d.frontend = $('#frontend').val();
                d.support_status = $('#support_status').val();
                d.status = $('#status').val();
                d.stage = $('#stage').val();
                // d.options = $('#options').val();
                d.engineer = $('#engineer').val();

              },

              complete: function (data) {

                console.log(data['responseJSON']);
                var ek=[];
                $('.submission').each(function() { ek.push($(this).attr('id')); });
                console.log(ek);

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

                    console.log('seconds:'+seconds);

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


              },

              
          },

          columns: [
              {data: 'id', name: 'id', orderable: false},
              {data: 'timers', name: 'timers', orderable: false, searchable: false},
              {data: 'frontend', name: 'frontend', orderable: false, searchable: false},
              {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }, orderable: false
              },
              {data: 'created_time', name: 'created_time', orderable: false},
              {data: 'username', name: 'username', orderable: false},
              {data: 'brand', name: 'brand', orderable: false},
              {data: 'model', name: 'model', orderable: false},
              {data: 'ecu', name: 'ecu', orderable: false},
              {data: 'support_status', name: 'support_status', orderable: false, searchable: false},
              {data: 'status', name: 'status', orderable: false, searchable: false},
              {data: 'stage', name: 'stage', orderable: false, searchable: false},
              {data: 'options', name: 'options', orderable: false, searchable: false},
              {data: 'credits', name: 'credits', orderable: false},
              {data: 'engineer', name: 'engineer', orderable: false},
              {data: 'response_time', name: 'response_time', orderable: false, searchable: false},
              
          ]

      });

      $(".filter").click(function(){
        table.draw();
      });

      $('#late').change(function(){
        table.draw();
      });

      $('#frontend').change(function(){
        table.draw();
      });

      $('#support_status').change(function(){
        table.draw();
      });

      $('#status').change(function(){
        table.draw();
      });

      $('#stage').change(function(){
        table.draw();
      });

      // $('#options').change(function(){
      //   table.draw();
      // });

      $('#engineer').change(function(){
        table.draw();
      });
        
        $('.parent-adjusted').parent().addClass('flex');

        $(document).on('click','.redirect-click-file',function(e) {
          console.log('clicked');
            var lastClass = $(this).attr('class').split(' ').pop();
            console.log(lastClass);
            // console.log("http://backend.test/file/"+lastClass);

            window.location.href = "/file/"+lastClass;
            
          });
    
        });
    </script>
@endsection