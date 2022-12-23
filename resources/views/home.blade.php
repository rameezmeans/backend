@extends('layouts.app')

@section('content')

<!-- START PAGE CONTENT WRAPPER -->
<div class="page-content-wrapper ">
  <!-- START PAGE CONTENT -->
  <div class="content sm-gutter">
    <!-- START CONTAINER FLUID -->
    <div class="container-fluid padding-25 sm-padding-10">  
      <div class="row">
        <div class="col-lg-12 col-xlg-12">
          <div class="widget-15 card card-condensed  no-margin no-border widget-loader-circle">
            <div class="card-header">
              <div class="">
                <h2 class="text-black text-center">Files</h2>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group form-group-default input-group">
                    <div class="form-input-group">
                      <label>Engineer</label>
                        <select class="full-width" id="engineer_files" data-init-plugin="select2" name="engineer">
                        
                            <option value="all_engineers">All Engineers</option>
                            @foreach($engineers as $engineer)
                              <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                            @endforeach
                          
                        </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group form-group-default input-group">
                      <div class="form-input-group">
                        <label>Start</label>
                        <input type="input" style="margin-bottom: 13px;" class="form-control datepicker" placeholder="Start Date" id="start_files">
                      </div>
                      <div class="input-group-append ">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
              </div>
              <div class="col-lg-4">
                  <div class="form-group form-group-default input-group">
                      <div class="form-input-group">
                        <label>End</label>
                        <input type="input" style="margin-bottom: 13px;" class="form-control datepicker" placeholder="End Date" id="end_files">
                      </div>
                      <div class="input-group-append ">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
              </div>
              </div>
              <div class="col-lg-12">              
                <canvas id="files-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
              </div>
              <div id="table-area" class="hide m-t-40">
                <table id="fTable" class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                    <thead>
                        <tr role="row">
                            <th style="width: 2%;">#</th>
                            <th style="width: 15%;">File</th>
                            <th style="width: 8%;">Engineer</th>
                            <th style="width: 25%;">Stages and Options</th>
                            <th style="width: 10%;">Response Time</th>
                            <th style="width: 10%;">Uploaded At</th>
                        </tr>
                    </thead>
                    <tbody id='table'>
                        
                    </tbody>
                </table>
            </div>
            </div>
          </div>
        </div>
      </div>
      <div class="p-t-25">  
      <div class="row">
        <div class="col-lg-12 col-xlg-12">
          
          <div class="widget-15 card card-condensed  no-margin no-border widget-loader-circle">
            <div class="card-header">
              <div class="">
                @if(!empty($topCredits))
                <h2 class="text-black text-center">Top Credits By Customers</h2>
                @endif
              </div>
              <div class="row p-l-20 p-r-20">
                @foreach($topCredits as $t)
                  <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                    <h4 class="bold no-margin">{{$t['credits']}}</h4>
                    <p class="small no-margin">{{$t['user']}}</p>
                  </div>
                @endforeach
              </div>
              <div class="">
                <h2 class="text-black text-center">Credits</h2>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group form-group-default input-group">
                    <div class="form-input-group">
                      <label>Client</label>
                        <select class="full-width" id="customer_credits" data-init-plugin="select2" name="engineer">
                        
                            <option value="all_customers">All Customers</option>
                            @foreach($customers as $customer)
                              <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                          
                        </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group form-group-default input-group">
                      <div class="form-input-group">
                        <label>Start</label>
                        <input type="input" style="margin-bottom: 13px;" class="form-control datepicker" placeholder="Start Date" id="start_credits">
                      </div>
                      <div class="input-group-append ">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
              </div>
              <div class="col-lg-4">
                  <div class="form-group form-group-default input-group">
                      <div class="form-input-group">
                        <label>End</label>
                        <input type="input" style="margin-bottom: 13px;" class="form-control datepicker" placeholder="End Date" id="end_credits">
                      </div>
                      <div class="input-group-append ">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
              </div>
              </div>
              <div class="col-lg-12">              
                <canvas id="credit-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
              </div>
              <div id="table-area-credits" class="hide m-t-40">
                <table id="ctable" class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                    <thead>
                        <tr role="row">
                            <th style="width: 2%;">#</th>
                            <th style="width: 5%;">Credits</th>
                            <th style="width: 8%;">Customer</th>
                            <th style="width: 25%;">Stripe ID</th>
                            <th style="width: 10%;">Paid At</th>
                        </tr>
                    </thead>
                    <tbody id='table-credits'>
                        
                    </tbody>
                </table>
            </div>
            </div>
          </div>
        </div>
      </div>
      </div>
      <div class="p-t-25">  
        <div class="row">
          <div class="col-lg-12 col-xlg-12">
            <div class="widget-15 card card-condensed  no-margin no-border widget-loader-circle">
              <div class="card-header">
                <div class="">
                  <h2 class="text-black text-center">Support Requests</h2>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group form-group-default input-group">
                      <div class="form-input-group">
                        <label>Engineer</label>
                          <select class="full-width" id="support_engineer" data-init-plugin="select2" name="engineer">
                          
                              <option value="all_engineers">All Engineers</option>
                              @foreach($engineers as $engineer)
                                <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                              @endforeach
                            
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group form-group-default input-group">
                        <div class="form-input-group">
                          <label>Start</label>
                          <input type="input" style="margin-bottom: 13px;" class="form-control datepicker" placeholder="Start Date" id="start_support">
                        </div>
                        <div class="input-group-append ">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group form-group-default input-group">
                        <div class="form-input-group">
                          <label>End</label>
                          <input type="input" style="margin-bottom: 13px;" class="form-control datepicker" placeholder="End Date" id="end_support">
                        </div>
                        <div class="input-group-append ">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                </div>
                </div>
                <div class="col-lg-12">              
                  <canvas id="support-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
                </div>
                <div id="table-area-support" class="hide m-t-40">
                  <table id="stable" class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                      <thead>
                          <tr role="row">
                            <th style="width: 2%;">#</th>
                            <th style="width: 15%;">File</th>
                            <th style="width: 8%;">Engineer</th>
                            <th style="width: 8%;">Number of Messages</th>
                            <th style="width: 8%;">Upload Date</th>
                          </tr>
                      </thead>
                      <tbody id='table-support'>
                          
                      </tbody>
                  </table>
              </div>
              </div>
            </div>
          </div>
        </div>
        </div>
        <div class="p-t-25">  
          <div class="row">
            <div class="col-lg-12 col-xlg-12">
              <div class="widget-15 card card-condensed  no-margin no-border widget-loader-circle">
                <div class="card-header">
                  <div class="">
                    <h2 class="text-black text-center">Response Time</h2>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group form-group-default input-group">
                        <div class="form-input-group">
                          <label>Engineer</label>
                            <select class="full-width" id="reponse_engineer" data-init-plugin="select2" name="engineer">
                            
                                <option value="all_engineers">All Engineers</option>
                                @foreach($engineers as $engineer)
                                  <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                                @endforeach
                              
                            </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">              
                    <canvas id="response-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
                  </div>
                  <div id="table-area-response" class="hide m-t-40">
                    <table id="rtable" class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                        <thead>
                            <tr role="row">
                              <th style="width: 2%;">#</th>
                              <th style="width: 15%;">File</th>
                              <th style="width: 8%;">Engineer</th>
                              <th style="width: 8%;">Response Time</th>
                              <th style="width: 8%;">Upload Date</th>
                              
                            </tr>
                        </thead>
                        <tbody id='table-response'>
                            
                        </tbody>
                    </table>
                </div>
                </div>
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
    $(document).ready(function(){

      set_and_get_response_time();

      function set_and_get_response_time(){
        let reponse_engineer = $('#reponse_engineer').val();
        get_response_time_chart( reponse_engineer );
      }

      $(document).on('change', '#reponse_engineer', function(e){
        let reponse_engineer = $(this).val();
        get_response_time_chart( reponse_engineer );
      });

      // get support chart

      set_and_get_support();

      function set_and_get_support(){
        let ends = $('#end_support').val();
        let starts = $('#start_support').val();
        let support_engineer = $('#support_engineer').val();
        get_support_chart( support_engineer, starts, ends );
      }

      $(document).on('change', '#end_support', function(e){
        set_and_get_support();
      });

      $(document).on('change', '#support_engineer', function(e){
        set_and_get_support();
      });

      $(document).on('change', '#start_support', function(e){
        set_and_get_support();
      });
      
      // get credits

      set_and_get_credits();

      function set_and_get_credits() {
        let endc = $('#end_credits').val();
        let startc = $('#start_credits').val();
        let customer_credits = $('#customer_credits').val();
        get_credits_chart( customer_credits, startc, endc );
      }

      $(document).on('change', '#customer_credits', function(e){
        set_and_get_credits();
      });

      $(document).on('change', '#end_credits', function(e){
        set_and_get_credits();
      });

      $(document).on('change', '#start_credits', function(e){
        set_and_get_credits();
      });

      //// files part
      set_and_get_files();

      function set_and_get_files(){
        let end = $('#end_files').val();
        let start = $('#start_files').val();
        let engineer_files = $('#engineer_files').val();
        get_files_chart( engineer_files, start, end );
      }

      $(document).on('change', '#engineer_files', function(e){
        set_and_get_files();
      });

      $(document).on('change', '#end_files', function(e){
        set_and_get_files();
      });

      $(document).on('change', '#start_files', function(e){
        set_and_get_files();
      });

      function get_response_time_chart( reponse_engineer  ){

        $.ajax({
            url: "/get_response_time_chart",
            type: "POST",
            data: {
                'reponse_engineer': reponse_engineer,
                
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {

              console.log(response.graph.has_files);

              if(response.graph.has_files){
                $('#table-area-response').removeClass('hide');
                $('#response-charts').removeClass('hide');
              }
              else{
                $('#table-area-response').addClass('hide');
                $('#response-charts').addClass('hide');
              }

              $('#table-response').html(response.graph.files);

              $('#rtable').dataTable();

              let chartr = new Chart("response-charts", {
                type: "line",
                data: {  
                        labels: response.graph.x_axis,
                        datasets: [{
                        label: response.graph.label,
                        data: response.graph.y_axis,
                        borderColor: "#10cfbd",
                        fill: true,
                        stepSize: 1,
                        backgroundColor: '#10cfbd'
                      }]
                },
                options: {
                    legend: {display: true},
                    animation: false
                }
              });

            }
        });
      } 

      function get_support_chart( support_engineer, starts, ends  ){

        $.ajax({
            url: "/get_support_chart",
            type: "POST",
            data: {
                'support_engineer': support_engineer,
                'starts': starts,
                'ends': ends,
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {

              if(response.graph.has_files){
                $('#table-area-support').removeClass('hide');
                $('#support-charts').removeClass('hide');
              }
              else{
                $('#table-area-support').addClass('hide');
                $('#support-charts').addClass('hide');
              }

              $('#table-support').html(response.graph.files);

              $('#stable').dataTable();

              chartc = new Chart("support-charts", {
                type: "line",
                data: {  
                        labels: response.graph.x_axis,
                        datasets: [{
                        label: response.graph.label,
                        data: response.graph.y_axis,
                        borderColor: "#10cfbd",
                        fill: true,
                        stepSize: 1,
                        backgroundColor: '#10cfbd'
                      }]
                },
                options: {
                    legend: {display: true},
                    animation: false
                }
              });

            }
        });

      } 

      function get_credits_chart( customer_credits, startc, endc ){

        $.ajax({
            url: "/get_credits_chart",
            type: "POST",
            data: {
                'customer_credits': customer_credits,
                'startc': startc,
                'endc': endc,
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {

              console.log(response.graph.has_credits);

              if(response.graph.has_credits){
                $('#table-area-credits').removeClass('hide');
                $('#credit-charts').removeClass('hide');
              }
              else{
                $('#table-area-credits').addClass('hide');
                $('#credit-charts').addClass('hide');
              }

              $('#table-credits').html(response.graph.credits);
              $('#ctable').dataTable();

              chartc = new Chart("credit-charts", {
                type: "line",
                data: {  
                        labels: response.graph.x_axis,
                        datasets: [{
                        label: response.graph.label,
                        data: response.graph.y_axis,
                        borderColor: "#10cfbd",
                        fill: true,
                        stepSize: 1,
                        backgroundColor: '#10cfbd'
                      }]
                },
                options: {
                    legend: {display: true},
                    animation: false
                }
              });

            }
        });

      }

      function get_files_chart( engineer_files, start, end ){
          
          $.ajax({
              url: "/get_files_chart",
              type: "POST",
              data: {
                  'engineer_files': engineer_files,
                  'start': start,
                  'end': end,
              },
              headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
              success: function(response) {

                console.log(response.graph.has_files);

                if(response.graph.has_files){
                  $('#table-area').removeClass('hide');
                  $('#files-charts').removeClass('hide');
                }
                else{
                  $('#table-area').addClass('hide');
                  $('#files-charts').addClass('hide');
                }

                $('#table').html(response.graph.files);

                $('#ftable').dataTable();
                // table.ajax.reload(null, false );

                let chartf = new Chart("files-charts", {
                  type: "line",
                  data: {  
                          labels: response.graph.x_axis,
                          datasets: [{
                          label: response.graph.label,
                          data: response.graph.y_axis,
                          borderColor: "#10cfbd",
                          fill: true,
                          stepSize: 1,
                          backgroundColor: '#10cfbd'
                        }]
                  },
                  options: {
                      legend: {display: true},
                      animation: false
                  }
                });
              }
          });
      
      }

    });
</script>
@endsection
