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
                <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
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
    </div>
  </div>
</div>
@endsection

@section('pagespecificscripts')
<script type="text/javascript">
    $(document).ready(function(){

      // let table = $('.table').dataTable();

      let end = $('#end_files').val();
      let start = $('#start_files').val();
      let engineer_files = $('#engineer_files').val();


      get_files_chart( engineer_files, start, end );

      $(document).on('change', '#engineer_files', function(e){
        
        let end = $('#end_files').val();
        let start = $('#start_files').val();
        let engineer_files = $('#engineer_files').val();

        get_files_chart( engineer_files, start, end );

      });

      $(document).on('change', '#end_files', function(e){
        
        let end = $(this).val();
        let start = $('#start_files').val();
        let engineer_files = $('#engineer_files').val();

        get_files_chart( engineer_files, start, end );

      });

      $(document).on('change', '#start_files', function(e){
        
        let start = $(this).val();
        let end = $('#end_files').val();
        let engineer_files = $('#engineer_files').val();

        get_files_chart( engineer_files, start, end );

      });

      function get_files_chart( engineer_files, start, end ){

          // console.log(engineer_files+' '+time_files);

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
                }
                else{
                  $('#table-area').addClass('hide');
                }

                $('#table').html(response.graph.files);

                $('.table').dataTable();
                // table.ajax.reload(null, false );


                new Chart("files-charts", {
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
