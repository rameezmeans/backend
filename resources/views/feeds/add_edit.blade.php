@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                  @if(isset($feed))
                  <h5>
                    Edit News Feed
                  </h5>
                @else
                  <h5>
                    Add News Feed
                  </h5>
                  
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    {{-- <button data-redirect="{{route('feeds')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">News Feeds</span>
                    </button> --}}
                   
                      Current Date Time (Europe/Athens): <h5>{{$date}}</h5>
                    
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($feed)){{route('update-feed')}}@else{{ route('post-feed') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($feed))
                  <input name="id" type="hidden" value="{{ $feed->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Title</label>
                  <input value="@if(isset($feed)) {{ $feed->title }} @else{{old('title') }}@endif"  name="title" type="text" class="form-control" required>
                </div>
                @error('title')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Feed</label>
                  <input value="@if(isset($feed)) {{ $feed->feed }} @else{{old('feed') }}@endif"  name="feed" type="text" class="form-control" required>
                </div>
                @error('feed')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default">
                  <label>Feed In Greek</label>
                  <input value="@if(isset($feed->translation)){{ $feed->translation->greek }}@endif"  name="feed_in_greek" type="text" class="form-control">
                </div>
                @error('feed')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                  <label>Front End</label>
                  <select class="full-width" data-init-plugin="select2" name="front_end_id">
                    <option @if(isset($feed) && $feed->front_end_id == 1) selected @endif value="1">ECUTech</option>
                    <option  @if(isset($feed) && $feed->front_end_id == 2) selected @endif value="2">TuningX</option>
                    <option  @if(isset($feed) && $feed->front_end_id == 3) selected @endif value="3">ETF</option>
                  </select>
                </div>
                @error('front_end_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                  <label>Type</label>
                  <select class="full-width" data-init-plugin="select2" name="type">
                    <option @if(isset($feed) && $feed->type == 'danger') selected @endif value="danger">Danger</option>
                    <option  @if(isset($feed) && $feed->type == 'warning') selected @endif value="warning">Warning</option>
                    <option  @if(isset($feed) && $feed->type == 'good_news') selected @endif value="good_news">Good New</option>
                  </select>
                </div>
                @error('type')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <h5 class="m-t-40 m-b-30">
                  Activation Method
                </h5>
                <p class="text-danger">Whenever you want to set Offline status for any vacations please add new feed and set it by Date and Time range. It will be activated in a minute and It will be preferred over Days of Week.</p>
                <div class="card card-transparent ">
                  <!-- Nav tabs -->
                  @if(!isset($feed))
                  <ul class="nav nav-tabs nav-tabs-fillup d-none d-md-flex d-lg-flex d-xl-flex" data-init-reponsive-tabs="dropdownfx">
                    <li class="nav-item">
                      <a href="#" data-toggle="tab" data-target="#slide2" class="active show"><span>Date and Time</span></a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="" data-toggle="tab" data-target="#slide1"><span>Days of Week</span></a>
                    </li>
                    
                  </ul>
                  <div class="nav-tab-dropdown cs-wrapper full-width d-lg-none d-xl-none d-md-none"><div class="cs-select cs-skin-slide full-width" tabindex="0"><span class="cs-placeholder">Hello World</span><div class="cs-options"><ul><li data-option="" data-value="#slide1"><span>Home</span></li><li data-option="" data-value="#slide2"><span>Profile</span></li><li data-option="" data-value="#slide3"><span>Messages</span></li></ul></div><select class="cs-select cs-skin-slide full-width" data-init-plugin="cs-select"><option value="#slide1" selected="">Home</option><option value="#slide2">Profile</option><option value="#slide3">Messages</option></select><div class="cs-backdrop"></div></div></div>
                 
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div class="tab-pane slide-left" id="slide1">
                      <div class="row column-seperation">
                        <div class="col-lg-6">
                            <div class="form-group form-group-default ">
                              <label>Activation Day of Week</label>
                              <select class="full-width" data-init-plugin="select2" name="activation_weekday">
                                <option value="" disabled selected>Select A Day</option>
                                <option @if(isset($feed) && $feed->activation_weekday == 'Sunday') selected @endif value="Sunday">Sunday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Monday') selected @endif value="Monday">Monday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Tuesday') selected @endif value="Tuesday">Tuesday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Wednesday') selected @endif value="Wednesday">Wednesday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Thursday') selected @endif value="Thursday">Thursday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Friday') selected @endif value="Friday">Friday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Saturday') selected @endif value="Saturday">Saturday</option>
                              </select>
                            </div>
                            @error('activation_weekday')
                              <span class="text-danger" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            
                        </div>
                        <div class="col-lg-6">
                          <label>Activation Time for every day of Week:</label>
                          <div class="input-group bootstrap-timepicker">
                            <input name="daily_activation_time" id="timepicker" type="time" class="form-control" value="@if(isset($feed)){{$feed->daily_activation_time}}@endif">
                            
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group form-group-default ">
                            <label>Deactivation Day of Week</label>
                            <select class="full-width" data-init-plugin="select2" name="deactivation_weekday">
                              <option value="" disabled selected>Select A Day</option>
                              <option @if(isset($feed) && $feed->deactivation_weekday == 'Sunday') selected @endif value="Sunday">Sunday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Monday') selected @endif value="Monday">Monday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Tuesday') selected @endif value="Tuesday">Tuesday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Wednesday') selected @endif value="Wednesday">Wednesday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Thursday') selected @endif value="Thursday">Thursday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Friday') selected @endif value="Friday">Friday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Saturday') selected @endif value="Saturday">Saturday</option>
                            </select>
                          </div>
                      
                          @error('deactivation_weekday')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                          
                        </div>
                        <div class="col-lg-6">
                          <label>Deactivation Until This time everyday for the week:</label>
                          <div class="input-group bootstrap-timepicker">
                            <input name="daily_deactivation_time" id="timepicker" type="time" class="form-control" value="@if(isset($feed)){{$feed->daily_deactivation_time}}@endif">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane slide-left active show" id="slide2">
                      <div class="row">
                        <div class="col-lg-12">
                          <label>Activation Range</label>
                            <div class="input-group m-b-10">
                            
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                              </div>
                              <input type="text" name="dateTimeRange" id="daterangepicker" class="form-control" value="@if(isset($feed)){{ \Carbon\Carbon::parse($feed->activate_at)->format('d/m/Y h:i A') }}@else{{date('d/m/Y h:i A')}}@endif - @if(isset($feed)){{\Carbon\Carbon::parse($feed->deactivate_at)->format('d/m/Y h:i A')}}@else{{date('d/m/Y h:i A')}}@endif">
                            </div>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                  @else
                      @if($feed->activation_weekday)
                      <div class="row column-seperation">
                        <div class="col-lg-6">
                            <div class="form-group form-group-default ">
                              <label>Activation Day of Week</label>
                              <select class="full-width" data-init-plugin="select2" name="activation_weekday">
                                <option value="" disabled selected>Select A Day</option>
                                <option @if(isset($feed) && $feed->activation_weekday == 'Sunday') selected @endif value="Sunday">Sunday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Monday') selected @endif value="Monday">Monday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Tuesday') selected @endif value="Tuesday">Tuesday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Wednesday') selected @endif value="Wednesday">Wednesday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Thursday') selected @endif value="Thursday">Thursday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Friday') selected @endif value="Friday">Friday</option>
                                <option  @if(isset($feed) && $feed->activation_weekday == 'Saturday') selected @endif value="Saturday">Saturday</option>
                              </select>
                            </div>
                            @error('activation_weekday')
                              <span class="text-danger" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            
                        </div>
                        <div class="col-lg-6">
                          <label>Activation Time for every day of Week:</label>
                          <div class="input-group bootstrap-timepicker">
                            <input name="daily_activation_time" id="timepicker" type="time" class="form-control" value="@if(isset($feed)){{$feed->daily_activation_time}}@endif">
                            
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group form-group-default ">
                            <label>Deactivation Day of Week</label>
                            <select class="full-width" data-init-plugin="select2" name="deactivation_weekday">
                              <option value="" disabled selected>Select A Day</option>
                              <option @if(isset($feed) && $feed->deactivation_weekday == 'Sunday') selected @endif value="Sunday">Sunday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Monday') selected @endif value="Monday">Monday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Tuesday') selected @endif value="Tuesday">Tuesday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Wednesday') selected @endif value="Wednesday">Wednesday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Thursday') selected @endif value="Thursday">Thursday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Friday') selected @endif value="Friday">Friday</option>
                              <option  @if(isset($feed) && $feed->deactivation_weekday == 'Saturday') selected @endif value="Saturday">Saturday</option>
                            </select>
                          </div>
                      
                          @error('deactivation_weekday')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                          
                        </div>
                        <div class="col-lg-6">
                          <label>Deactivation Until This time everyday for the week:</label>
                          <div class="input-group bootstrap-timepicker">
                            <input name="daily_deactivation_time" id="timepicker" type="time" class="form-control" value="@if(isset($feed)){{$feed->daily_deactivation_time}}@endif">
                          </div>
                        </div>
                      </div>
                      @endif
                      @if($feed->activate_at)
                        <div class="row">
                          <div class="col-lg-12">
                            <label>Activation Range</label>
                              <div class="input-group m-b-10">
                              
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" name="dateTimeRange" id="daterangepicker" class="form-control" value="@if(isset($feed)){{ \Carbon\Carbon::parse($feed->activate_at)->format('d/m/Y h:i A') }}@else{{date('d/m/Y h:i A')}}@endif - @if(isset($feed)){{\Carbon\Carbon::parse($feed->deactivate_at)->format('d/m/Y h:i A')}}@else{{date('d/m/Y h:i A')}}@endif">
                              </div>
                            </div>
                          </div>
                      @endif
                  @endif
                </div>              
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($feed)) Update @else Add @endif</span></button>
                  @if(isset($feed))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$feed->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                  @endif
                </div>
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

       
        $('#daterangepicker').daterangepicker({
            timePicker: true,
            timePickerIncrement: 5,
            format: 'DD/MM/YYYY h:mm A'
        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        $('.btn-delete').click(function() {
          Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                        url: "/delete_feed",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Feed has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/feeds';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection