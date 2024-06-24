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
                <div class="col-lg-4">
                    <div class="form-group form-group-default">
                        <label>Select Serivce</label>
                        <select class="full-width" id="service_id" data-init-plugin="select2" name="service_id">  
                        @foreach($services as $service)
                            <option value="{{$service->service_id}}">{{\App\Models\Service::findOrFail($service->service_id)->name.', '}}({{\App\Models\Service::findOrFail($service->service_id)->vehicle_type}}) @if(\App\Models\Service::findOrFail($service->service_id)->active == 1) ECUTech @elseif(\App\Models\Service::findOrFail($service->service_id)->tuningx_active == 1) Tuning-X @endif</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group form-group-default">
                        <label>Select Software</label>
                        <select class="full-width" id="software_id" data-init-plugin="select2" name="software_id">
                        @foreach($softwares as $software)
                            <option value="{{$software->software_id}}">{{\App\Models\ProcessingSoftware::findOrFail($software->software_id)->name}}</option>
                        @endforeach
                        </select>
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
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Task ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Brand</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">ECU</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Service</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Softare</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Number of Engineer Uploads</th>
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


        $(document).on('change', '#service_id', function(e){
        
            $('#progress').show();

            let service_id = $('#service_id').val();
            let software_id = $('#software_id').val();


            getReport(service_id, software_id);

        });

        $(document).on('change', '#software_id', function(e){
        
        $('#progress').show();

        let service_id = $('#service_id').val();
        let software_id = $('#software_id').val();


        getReport(service_id, software_id);

    });


        $('#progress').show();

        let service_id = $('#service_id').val();
        let software_id = $('#software_id').val();
        
        
        getReport(service_id, software_id);

        function getReport(service_id, software_id){

        let credit_url = '{{route('get-software-report')}}';
    
        $.ajax({
                url:credit_url,
                type: "POST",
                data: {
                    service_id: service_id,
                    software_id: software_id,
                    
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {

                    $('#progress').hide();
                    $('#recordsRows').html(res.html);
                

                }
            });
        }


        
    });

</script>

@endsection