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
              
              <div class="col-lg-12">              
                <canvas id="files-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
              </div>
              <div class="row">
                <div class="col-lg-6">
                <select class="full-width" id="engineer_files" data-init-plugin="select2" name="engineers">
                
                    <option value="all_engineers">All Engineers</option>
                    @foreach($engineers as $engineer)
                      <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                    @endforeach
                  
                </select>
                </div>
                <div class="col-lg-6">
                  <select class="full-width" id="time_files" data-init-plugin="select2" name="time">
                    
                      <option value="all_times">All Times</option>
                      <option value="this_year">This Year</option>
                      <option value="this_month">This Month</option>
                      <option value="this_week">This Week</option>
                  
                  </select>
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

      let engineer_files = $('#engineer_files').val();
      let time_files = $('#time_files').val();

      get_files_chart( engineer_files, time_files );

      $(document).on('change', '#engineer_files', function(e){
        
        let engineer_files = $(this).val();
        let time_files = $('#time_files').val();

        get_files_chart( engineer_files, time_files );

      });

      $(document).on('change', '#time_files', function(e){
        
        let time_files = $(this).val();
        let engineer_files = $('#engineer_files').val();

        get_files_chart( engineer_files, time_files );

      });

      function get_files_chart( engineer_files, time_files ){

          console.log(engineer_files+' '+time_files);

          $.ajax({
              url: "/get_files_chart",
              type: "POST",
              data: {
                  'engineer_files': engineer_files,
                  'time_files': time_files,
              },
              headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
              success: function(response) {

                console.log(response);

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
