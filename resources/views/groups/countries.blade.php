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
                    <h3>Software Report</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    {{-- <button data-redirect="{{ route('create-tool') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Create Tool</span>
                    </button> --}}
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('get-country-report')}}">
                <div class="row">
                    
                        @csrf
                    <div class="col-lg-5">
                        <div class="form-group form-group-default">
                            <label>Select Duration</label>
                            <select class="full-width" id="duration" data-init-plugin="select2" name="duration">  
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group form-group-default">
                            <label>Select Frontend</label>
                            <select class="full-width" id="frontend" data-init-plugin="select2" name="front_end">
                            @foreach($frontends as $frontend)
                                <option value="{{$frontend->id}}">{{\App\Models\Frontend::findOrFail($frontend->id)->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    
                    <div class="col-lg-2">
                        <input class="btn btn-success" type="submit" value="Filter">
                    </div>
                    
                </div>
            </form>

            <form method="POST" action="{{route('get-country-report')}}">
                <div class="row">
                    
                    @csrf
                    <div class="col-lg-3">
                        <div class="form-group form-group-default input-group">
                            <div class="form-input-group">
                                <label>Start</label>
                                <input autocomplete="false" type="input" style="margin-bottom: 17px;" class="form-control datepicker" placeholder="Start Date" id="start" name="start">
                            </div>
                            <div class="input-group-append ">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group form-group-default input-group">
                            <div class="form-input-group">
                                <label>End</label>
                                <input autocomplete="false" type="input" style="margin-bottom: 17px;" class="form-control datepicker" placeholder="End Date" id="end" name="end">
                            </div>
                            <div class="input-group-append ">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-group-default">
                            <label>Select Frontend</label>
                            <select class="full-width" id="frontend" data-init-plugin="select2" name="front_end">
                            @foreach($frontends as $frontend)
                                <option value="{{$frontend->id}}">{{\App\Models\Frontend::findOrFail($frontend->id)->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    
                    <div class="col-lg-2">
                        <input class="btn btn-success" type="submit" value="Filter">
                    </div>
                    
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

    $(document).ready(function(event){

        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload() 
            }
        };

        // $(document).on('change', '#duration', function(e){
        
        //     $('#progress').show();

        //     let duration = $('#duration').val();
        //     let front_end = $('#frontend').val();
        //     let country = $('#country').val();


        //     getReport(duration, front_end, country);

        // });

        // $(document).on('change', '#frontend', function(e){
        
        //     $('#progress').show();

        //     let duration = $('#duration').val();
        //     let front_end = $('#frontend').val();
        //     let country = $('#country').val();


        //     getReport(duration, front_end, country);

        // });

        // $(document).on('change', '#country', function(e){
        
        //     $('#progress').show();

        //     let duration = $('#duration').val();
        //     let front_end = $('#frontend').val();
        //     let country = $('#country').val();


        //     getReport(duration, front_end, country);

        // });

    


    $('#progress').show();

    let duration = $('#duration').val();
    let front_end = $('#frontend').val();
    let country = $('#country').val();


    getReport(duration, front_end, country);

        function getReport(duration, front_end, country){

        let country_url = '{{route('get-country-report')}}';
    
        $.ajax({
                url:country_url,
                type: "POST",
                data: {
                    duration: duration,
                    front_end: front_end,
                    country: country,
                    
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {

                    $('#progress').hide();
                    $('#recordsRows').html(res.html);
                    $('#customers').html(res.count);
                    // $('#replies').html(res.replies);
                

                }
            });
        }


        
    });

</script>

@endsection