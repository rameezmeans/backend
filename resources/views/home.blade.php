@extends('layouts.app')

@section('content')

<!-- START PAGE CONTENT WRAPPER -->
<div class="page-content-wrapper ">
  <!-- START PAGE CONTENT -->
  <div class="content sm-gutter">
    <!-- START CONTAINER FLUID -->
    <div class="container-fluid p-t-20">
      @if(Auth::user()->is_admin || Auth::user()->is_head)
      <div class="row">
        <div class="col-sm-12 col-xl-12">
          <div class="form-group form-group-default input-group">
            <div class="form-input-group m-l-20 m-r-20 p-b-20">
              <label>Frontend</label>
                <select class="full-width" id="frontend" data-init-plugin="select2" name="frontend">
                    @foreach($frontends as $frontend)
                      <option value="{{$frontend->id}}">{{$frontend->name}}</option>
                    @endforeach
                </select>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 col-xl-12">
          <div class="progress-circle-indeterminate hide" style="" id="loading">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4 col-xl-4">
          <div class="ar-2-1">
            <!-- START WIDGET widget_graphTile-->
            <div class="widget-4 card no-border  no-margin widget-loader-bar">
              <div class="container-sm-height full-height d-flex flex-column">
                <div class="card-header  ">
                  <div class="card-title text-black hint-text">
                    <span class="font-montserrat fs-11 all-caps">Total Cusotmers <i class="fa fa-chevron-right"></i>
                    </span>
                  </div>
                </div>
                <div class="p-l-20 p-r-20">
                  
                  <h1 class="text-success semi-bold" id="customerCount"></h1>
                  <div class="clearfix"></div>
                </div>
                
              </div>
            </div>
            <!-- END WIDGET -->
          </div>
          <div class="ar-2-1" style="margin-top: 12px;">
            <!-- START WIDGET widget_graphTile-->
            <div class="widget-4 card no-border  no-margin widget-loader-bar">
              <div class="container-sm-height full-height d-flex flex-column">
                <div class="card-header  ">
                  <div class="card-title text-black hint-text">
                    <span class="font-montserrat fs-11 all-caps">Total Engineers <i class="fa fa-chevron-right"></i>
                    </span>
                  </div>
                </div>
                <div class="p-l-20 p-r-20">
                  
                  <h1 class="text-success semi-bold">{{$engineersCount}}</h1>
                 
                  <div class="clearfix"></div>
                </div>
                
              </div>
            </div>
            <!-- END WIDGET -->
          </div>
        </div>
        <div class="col-lg-6 col-xl-4 m-b-10">
          <!-- START WIDGET widget_tableWidgetBasic-->
          <div class="widget-11-2 card no-border card-condensed no-margin widget-loader-circle full-height d-flex flex-column">
            <div class="card-header  top-right">
              <div class="card-controls">
                <ul>
                  <li><a data-toggle="refresh" class="card-refresh text-black" href="#">
                  </li>
                </ul>
              </div>
            </div>
            <div class="padding-25">
              <div class="pull-left">
                <h2 class="text-success no-margin">Top Countries</h2>
                
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="auto-overflow widget-11-2-table" id="countryTable">
              {{-- <table class="table table-condensed table-hover">
                <tbody>
                  @foreach($topCountries as $c)
                  <tr>
                    <td class="w-10"><span style="font-size: 20px;">{{getFlags($c['country'])}}</span></td>
                    <td class="font-montserrat all-caps fs-12 w-50">{{code_to_country($c['country'])}}</td>
                    <td class="w-25">
                      <span class="font-montserrat fs-18">{{$c['count']}}</span>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table> --}}
            </div>
            
          </div>
          <!-- END WIDGET -->
        </div>
        <div class="col-lg-6 col-xl-4 m-b-10">
          <!-- START WIDGET widget_tableWidgetBasic-->
          <div class="widget-11-2 card no-border card-condensed no-margin widget-loader-circle full-height d-flex flex-column">
            <div class="card-header  top-right">
              <div class="card-controls">
                <ul>
                  <li><a data-toggle="refresh" class="card-refresh text-black" href="#">
                  </li>
                </ul>
              </div>
            </div>
            <div class="padding-25">
              <div class="pull-left">
                <h2 class="text-success no-margin">Top Brands</h2>
                
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="auto-overflow widget-11-2-table" id="brandsTable">
              {{-- <table class="table table-condensed table-hover">
                <tbody>
                  @foreach($topBrands as $brand)
                  <tr>
                    <td class="w-10"><img src="{{get_image_from_brand($brand['brand'])}}" style="width: 60%;"></td>
                    <td class="font-montserrat all-caps fs-12 w-50">{{$brand['brand']}}</td>
                    <td class="w-25">
                      <span class="font-montserrat fs-18">{{$brand['count']}}</span>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table> --}}
            </div>
            
          </div>
          <!-- END WIDGET -->
        </div>

      </div>
      <div class="row">
        <div class="col-sm-4 col-xl-4">
          <div class="ar-2-1">
            <!-- START WIDGET widget_graphTile-->
            <div class="widget-4 card no-border  no-margin widget-loader-bar">
              <div class="container-sm-height full-height d-flex flex-column">
                <div class="card-header  ">
                  <div class="card-title text-black hint-text">
                    <span class="font-montserrat fs-11 all-caps">File Processed (Today) <i class="fa fa-chevron-right"></i>
                    </span>
                  </div>
                </div>
                <div class="p-l-20 p-r-20">
                  
                  <h1 class="text-success semi-bold" id="totalFileCountToday"></h1>
                  <span class="text-success" style="font-size: 20px;" id="autotunedFileCountToday"></span>
                  (Avg. Response Time <span class="text-success" style="font-size: 20px;" id="AvgRTToday"></span>)
                  <div class="clearfix"></div>
                </div>
                
              </div>
            </div>
            <!-- END WIDGET -->
            </div>
          </div>
        <div class="col-sm-4 col-xl-4">
          <div class="ar-2-1">
            <!-- START WIDGET widget_graphTile-->
            <div class="widget-4 card no-border  no-margin widget-loader-bar">
              <div class="container-sm-height full-height d-flex flex-column">
                <div class="card-header  ">
                  <div class="card-title text-black hint-text">
                    <span class="font-montserrat fs-11 all-caps">File Processed (Last 7 days) <i class="fa fa-chevron-right"></i>
                    </span>
                  </div>
                </div>
                <div class="p-l-20 p-r-20">
                  
                  <h1 class="text-success semi-bold" id="totalsevenDaysCount"></h1>
                  <span class="text-success" style="font-size: 20px;" id="autotunedFileCountSevendays"></span>
                  (Avg. Response Time <span class="text-success" style="font-size: 20px;" id="AvgRTSevendays"></span>)
                  <div class="clearfix"></div>
                </div>
                
              </div>
            </div>
            <!-- END WIDGET -->
            </div>
          </div>
          
            <div class="col-sm-4 col-xl-4">
              <div class="ar-2-1">
                <!-- START WIDGET widget_graphTile-->
                <div class="widget-4 card no-border  no-margin widget-loader-bar">
                  <div class="container-sm-height full-height d-flex flex-column">
                    <div class="card-header  ">
                      <div class="card-title text-black hint-text">
                        <span class="font-montserrat fs-11 all-caps">File Processed (30 days) <i class="fa fa-chevron-right"></i>
                        </span>
                      </div>
                    </div>
                    <div class="p-l-20 p-r-20">
                      
                      <h1 class="text-success semi-bold" id="total30DaysCount"></h1>
                      <span class="text-success" style="font-size: 20px;" id="autotunedFileCount30days"></span>
                      (Avg. Response Time <span class="text-success" style="font-size: 20px;" id="AvgRT30days"></span>)
                      <div class="clearfix"></div>
                      
                    </div>
                    
                  </div>
                </div>
                <!-- END WIDGET -->
                </div>
              </div>
              <div class="col-sm-4 col-xl-4 m-t-10">
                <div class="ar-2-1">
                  <!-- START WIDGET widget_graphTile-->
                  <div class="widget-4 card no-border  no-margin widget-loader-bar">
                    <div class="container-sm-height full-height d-flex flex-column">
                      <div class="card-header  ">
                        <div class="card-title text-black hint-text">
                          <span class="font-montserrat fs-11 all-caps">File Processed <i class="fa fa-chevron-right"></i>
                          </span>
                        </div>
                      </div>
                      <div class="p-l-20 p-r-20">
                        
                        <h1 class="text-success semi-bold" id="total365DaysCount"></h1>
                      <span class="text-success" style="font-size: 20px;" id="autotunedFileCount365days"></span>
                      (Avg. Response Time <span class="text-success" style="font-size: 20px;" id="AvgRT365days"></span>)
                      <div class="clearfix"></div>

                      </div>
                      
                    </div>
                  </div>
                  <!-- END WIDGET -->
                  </div>
                </div>
        </div>
    </div>
    <div class="container-fluid p-t-25">
      <div class="row">
        <div class="col-lg-12 col-xlg-12">
          <div class="widget-15 card card-condensed  no-margin no-border widget-loader-circle">
            <div class="card-header">
              <div class="">
                <h2 class="text-black text-center">Auto Tunned Files</h2>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group form-group-default input-group">
                      <div class="form-input-group">
                        <label>Start</label>
                        <input type="input" style="margin-bottom: 13px;" class="form-control datepicker" placeholder="Start Date" id="start_autotunned_files">
                      </div>
                      <div class="input-group-append ">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
              </div>
              <div class="col-lg-6">
                  <div class="form-group form-group-default input-group">
                      <div class="form-input-group">
                        <label>End</label>
                        <input type="input" style="margin-bottom: 13px;" class="form-control datepicker" placeholder="End Date" id="end_autotunned_files">
                      </div>
                      <div class="input-group-append ">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
              </div>
              </div>
                
                  <div class="row p-l-40 p-r-40 m-t-40">
                    
                      <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                        <h4 class="bold no-margin" id="total_files_upper"></h4>
                        <p class="no-margin font-large" >Total Files</p>
                      </div>
                      <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                        <h4 class="bold no-margin" id="total_autotunned_files"></h4>
                        <p class="no-margin font-large" >Total Files Auto Tuned</p>
                      </div>
                      <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                        <h4 class="bold no-margin" id="total_manual_files"></h4>
                        <p class="no-margin font-large" >Total Manual Files</p>
                      </div>
                      
                  </div>

                  
                             
                <div class="col-lg-12">              
                  <canvas id="autotunned-files-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
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
                
                  <div class="row p-l-40 p-r-40 m-t-40">
                    
                      <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                        <h4 class="bold no-margin" id="total_files"></h4>
                        <p class="no-margin font-large" >Total Files</p>
                      </div>
                      <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                        <h4 class="bold no-margin" id="avg_files"></h4>
                        <p class="no-margin ">Avg. Per Engineer</p>
                      </div>
                  </div>
                             
                <div class="col-lg-12">              
                  <canvas id="files-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
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
                @if(!empty($topCredits))
                <h2 class="text-black text-center">Credits Information</h2>
                @endif
              </div>
              <div class="row p-l-20 p-r-20 m-t-40">
                    
                <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                  <h4 class="bold no-margin" id="total_credits"></h4>
                  <p class="no-margin font-large">Total Credits</p>
                </div>
                <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                  <h4 class="bold no-margin" id="avg_credits"></h4>
                  <p class="no-margin">Avg. Per Customer</p>
                </div>
            </div>
              <div class="row p-l-20 p-r-20" id="customerTab">
                {{-- @foreach($topCredits as $t)
                  <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                    <h4 class="bold no-margin">{{$t['credits']}}</h4>
                    <p class="small no-margin">{{$t['user']}}</p>
                  </div>
                @endforeach --}}
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
                        <select class="full-width" id="customer_credits" data-init-plugin="select2" >
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
                <div class="row p-l-40 p-r-40 m-t-40">
                    <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                      <h4 class="bold no-margin" id="total_requests"></h4>
                      <p class="no-margin font-large" >Total Requests</p>
                    </div>
                    <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                      <h4 class="bold no-margin" id="avg_requests"></h4>
                      <p class="no-margin ">Avg. Per Engineer</p>
                    </div>
                </div>
                <div class="col-lg-12">              
                  <canvas id="support-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
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
                  <div class="row p-l-40 p-r-40 m-t-40" id="show_avarage">
                    <div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                      <h4 class="bold no-margin" id="user_average"></h4>
                      <p class="no-margin font-large" >Average Time</p>
                    </div>
                   
                </div>
                  <div class="col-lg-12">              
                    <canvas id="response-charts" height="696" width="1902" class="chartjs-render-monitor" style="display: block; height: 0px; width: 0px;"></canvas>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
          </div>
          @endif
    </div>
  </div>
</div>
{{-- <audio id="submit" src="{{url('sound/hover.wav')}}" type="audio/wav"></audio>   --}}
@endsection

@section('pagespecificscripts')
<script type="text/javascript">
    $(document).ready(function(){

      function set_and_get_response_time(){
        let reponse_engineer = $('#reponse_engineer').val();
        let frontend_id = $('#frontend').val();
        get_response_time_chart( reponse_engineer, frontend_id );
      }

      $(document).on('change', '#reponse_engineer', function(e){
        let reponse_engineer = $(this).val();
        let frontend_id = $('#frontend').val();
        get_response_time_chart( reponse_engineer, frontend_id );
      });

      function set_and_get_support(){
        let ends = $('#end_support').val();
        let starts = $('#start_support').val();
        let support_engineer = $('#support_engineer').val();
        let frontend_id = $('#frontend').val();
        get_support_chart( support_engineer, starts, ends, frontend_id );
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
      
      function set_and_get_credits() {
        let endc = $('#end_credits').val();
        let startc = $('#start_credits').val();
        let customer_credits = $('#customer_credits').val();
        let frontend_id = $('#frontend').val();
        get_credits_chart( customer_credits, startc, endc, frontend_id );
      }

      let frontendURL = "{{route('get-frontend-data')}}";

      $(document).on('change', '#frontend', function(e){

        let frontend_id = $(this).val();
        get_front_end_data(frontend_id, true);

      });

      get_front_end_data(1, false);

      function get_front_end_data(frontend_id, onChange){

        $('#loading').removeClass('hide');

        $.ajax({
            url: frontendURL,
            type: "POST",
            data: {
                'frontend_id': frontend_id,
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {

              console.log(response.customerCount);

              $('#loading').addClass('hide');
              $('#customerCount').html(response.customerCount);
              $('#autotunedFileCountToday').html(response.autotunedFileCountToday);
              $('#totalFileCountToday').html(response.totalFileCountToday);
              $('#AvgRTToday').html(response.AvgRTToday);
              $('#autotunedFileCountSevendays').html(response.autotunedFileCountSevendays);
              $('#totalsevenDaysCount').html(response.totalsevenDaysCount);
              $('#AvgRTSevendays').html(response.AvgRTSevendays);
              $('#autotunedFileCount30days').html(response.autotunedFileCount30days);
              $('#total30DaysCount').html(response.total30DaysCount);
              $('#AvgRT365days').html(response.AvgRT30days);
              $('#autotunedFileCount365days').html(response.autotunedFileCount365days);
              $('#total365DaysCount').html(response.total365DaysCount);
              $('#AvgRT30days').html(response.AvgRT365days);
              $('#brandsTable').html(response.brandsTable);
              $('#countryTable').html(response.countryTable);

              if(onChange){
                console.log(response.customerOptions);
                $('#customer_credits').empty().append(response.customerOptions);
              }
              
              set_and_get_files();
              set_and_get_credits();
              set_and_get_support();
              set_and_get_response_time();

            }
        });
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

      set_and_get_autotunned_files();

      function set_and_get_autotunned_files(){
        let start = $('#start_autotunned_files').val();
        let end = $('#end_autotunned_files').val();
        let frontend_id = $('#frontend').val();
        get_autotunned_files_chart( start, end, frontend_id );
      }

      $(document).on('change', '#start_autotunned_files', function(e){
        set_and_get_autotunned_files();
      });

      $(document).on('change', '#end_autotunned_files', function(e){
        set_and_get_autotunned_files();
      });


      function set_and_get_files(){
        let end = $('#end_files').val();
        let start = $('#start_files').val();
        let engineer_files = $('#engineer_files').val();
        let frontend_id = $('#frontend').val();
        get_files_chart( engineer_files, start, end, frontend_id );
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

      function get_response_time_chart( reponse_engineer, frontend_id  ){

        $.ajax({
            url: "/get_response_time_chart",
            type: "POST",
            data: {
                'reponse_engineer': reponse_engineer,
                'frontend_id': frontend_id,
                
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {

              if(response.graph.show_avarage){
                
                $('#show_avarage').removeClass('hide');
              }
              else{
                
                $('#show_avarage').addClass('hide');
              }

              $('#user_average').html(response.graph.user_average);

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

      function get_support_chart( support_engineer, starts, ends, frontend_id  ){

        $.ajax({
            url: "/get_support_chart",
            type: "POST",
            data: {
                'support_engineer': support_engineer,
                'starts': starts,
                'ends': ends,
                'frontend_id': frontend_id,
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {

              $('#total_requests').html(response.graph.total_requests);
              $('#avg_requests').html(response.graph.avg_requests);

              $('#stable').dataTable({
                retrieve: true,
              });

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

      function get_credits_chart( customer_credits, startc, endc, frontend_id ){

        $.ajax({
            url: "/get_credits_chart",
            type: "POST",
            data: {
                'customer_credits': customer_credits,
                'startc': startc,
                'endc': endc,
                'frontend_id': frontend_id,
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {

              $('#total_credits').html(response.graph.total_credits);
              $('#avg_credits').html(response.graph.avg_credits);
              $('#customerTab').html(response.graph.customerTab);

              $('#table-credits').html(response.graph.credits);

              $('#customerTab').html(response.customerTab);

              $('#ctable').dataTable({
                retrieve: true,
              });

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

      function get_autotunned_files_chart( start, end, frontend_id ){
          
          $.ajax({
              url: "/get_autotunned_files_chart",
              type: "POST",
              data: {

                  'frontend_id': frontend_id,
                  'start': start,
                  'end': end,
              },
              headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
              success: function(response) {

                console.log(response);

                $('#total_autotunned_files').html(response.graph.total_autotuned_files); 
                $('#total_manual_files').html(response.graph.total_manual_files); 
                $('#total_files_upper').html(response.graph.total_files); 
                
                let chartf = new Chart("autotunned-files-charts", {
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

      function get_files_chart( engineer_files, start, end, frontend_id ){
          
          $.ajax({
              url: "/get_files_chart",
              type: "POST",
              data: {
                  'engineer_files': engineer_files,
                  'frontend_id': frontend_id,
                  'start': start,
                  'end': end,
              },
              headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
              success: function(response) {

                console.log(response);

                $('#total_files').html(response.graph.total_files); 
                $('#avg_files').html(response.graph.avg_files); 

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
