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
                    <h3>Softare Report</h3>
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
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group form-group-default">
                            <label>Select Duration</label>
                            <select class="full-width" id="duration" data-init-plugin="select2" name="duration">  
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="15_days">Last 15 days</option>
                                <option value="month">Last 30 days</option>
                            </select>
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

                    <div class="col-lg-4">
                        <div class="form-group form-group-default">
                            <label>Countries</label>
                            <select class="full-width" id="country" data-init-plugin="select2" name="country">
                                <option value="all">All Countries</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->country}}">{{code_to_country($country->country)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <p>Number of Customers: <span id="customers">0</span></p>
                        {{-- <p>Replies: <span id="replies">0</span></p> --}}
                    </div>
                    
                </div>
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <div id="progress" class="text-center">
                            <div class="progress-circle-indeterminate m-t-45" style="">
                            </div>
                            <br>
                        </div>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Country</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Company</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Tax ID</th>
                                    
                                    {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Tasks</th> --}}
                                    {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">All Files Uploaded</th> --}}
                                </tr>
                            </thead>
                            <tbody id="recordsRows">
                                {{-- @foreach ($softwaresAndBrandsRecords as $row)
                                    
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p><a href="{{route('file', $row->file_id)}}">{{$row->file_id}}</a></p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row->brand}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row->ecu}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{\App\Models\Service::findOrFail($row->service_id)->name}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{\App\Models\ProcessingSoftware::findOrFail($row->software_id)->name}}</p>
                                        </td>
                                        
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{all_files_with_this_ecu_brand_and_service_and_software($row->file_id, $row->service_id, $row->software_id)}}</p>
                                        </td>
                                        

                                    </tr>

                                @endforeach --}}
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

    $(document).ready(function(event){


        $(document).on('change', '#duration', function(e){
        
            $('#progress').show();

            let duration = $('#duration').val();
            let front_end = $('#frontend').val();
            let country = $('#country').val();


            getReport(duration, front_end, country);

        });

        $(document).on('change', '#frontend', function(e){
        
            $('#progress').show();

            let duration = $('#duration').val();
            let front_end = $('#frontend').val();
            let country = $('#country').val();


            getReport(duration, front_end, country);

        });

        $(document).on('change', '#country', function(e){
        
            $('#progress').show();

            let duration = $('#duration').val();
            let front_end = $('#frontend').val();
            let country = $('#country').val();


            getReport(duration, front_end, country);

        });

    


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