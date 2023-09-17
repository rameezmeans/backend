@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">
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

<ul class="nav nav-tabs nav-tabs-fillup m-t-40" data-init-reponsive-tabs="dropdownfx">
             
    <li class="nav-item">
      <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>All Services</span></a>
    </li>
    <li class="nav-item">
      <a href="#" data-toggle="tab" data-target="#slide2"><span>Services Shared With Subdealers</span></a>
    </li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane slide-left active" id="slide1">

            <!-- START card -->
            <div class="card card-transparent m-t-40">
                <div class="card-header ">
                    <div class="card-title"><h3>Servies</h3>
                    </div>
                    <div class="pull-right">
                    <div class="col-xs-12">
                        <button data-redirect="{{ route('create-service') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Service</span>
                        </button>
                        
                        {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                    </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                        <div>
                            <table class="table service-table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                <thead>
                                    <tr role="row">
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                        <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 42px;">Type</th>
                                        <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 342px;">Description</th>
                                        <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 42px;">ECU Tech Credits</th>
                                        <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 42px;">Tuning-X Credits</th>
                                        <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 42px;">Vehicle Type</th>
                                        <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 100px;">Date Created</th>
                                        <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 100px;">ECU Tech Active</th>
                                        <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 100px;">TuningX Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr role="row" class="redirect-click" data-redirect="{{ route('edit-service', $service->id) }}">
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$service->name}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{ucfirst($service->type)}}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>{{$service->description}}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p><label class="label bg-primary text-white">{{$service->credits}}</label></p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p><label class="label bg-warning ">{{$service->tuningx_credits}}</label></p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>{{$service->vehicle_type}}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>{{$service->created_at->diffForHumans()}}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p><input data-service_id={{$service->id}} class="active" type="checkbox" data-init-plugin="switchery" @if($service->active) checked="checked" @endif onclick="status_change()"/></p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p><input data-service_id={{$service->id}} class="tuningx_active" type="checkbox" data-init-plugin="switchery" @if($service->tuningx_active) checked="checked" @endif onclick="status_tuningx_change()"/></p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END card -->
    </div>

    <div class="tab-pane slide-left" id="slide2">

        <!-- START card -->
        <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Shared Servies</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('create-service') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Service</span>
                    </button>
                    
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table evc-table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 42px;">Type</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 342px;">Description</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 42px;">Credits</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 42px;">Vehicle Type</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 100px;">Date Created</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 100px;">Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sharedServices as $service)
                                    <tr role="row" class="redirect-click" data-redirect="{{ route('edit-service', $service->id) }}">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$service->name}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{ucfirst($service->type)}}</p>
                                        </td>
                                        <td class="v-align-middle">
                                            <p>{{$service->description}}</p>
                                        </td>
                                        <td class="v-align-middle">
                                            <p>{{$service->credits}}</p>
                                        </td>
                                        <td class="v-align-middle">
                                            <p>{{$service->vehicle_type}}</p>
                                        </td>
                                        <td class="v-align-middle">
                                            <p>{{$service->created_at->diffForHumans()}}</p>
                                        </td>
                                        <td class="v-align-middle">
                                            <p><input data-service_id={{$service->id}} class="active" type="checkbox" data-init-plugin="switchery" @if($service->active) checked="checked" @endif onclick="status_change()"/></p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END card -->

    </div>


</div>
        </div>
    </div>
</div>
@endsection

@section('pagespecificscripts')

<script type="text/javascript">
    $( document ).ready(function(event) {

        let table = $('.service-table').DataTable({
          "aaSorting": [],

        });

        let table2 = $('.evc-table').DataTable({
          "aaSorting": [],

        });
        
        let switchStatus = true;
        $(document).on('change', '.active', function(e) {
            let service_id = $(this).data('service_id');
            console.log(service_id);
            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }
            else {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }

            change_status(service_id, switchStatus);
        });

        function change_status(service_id, status){
            $.ajax({
                url: "/change_status",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "service_id": service_id,
                    "status": status,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    
                }
            });  
        }
   

    // let switchTuningxStatus = true;
    //     $(document).on('change', '.tuningx_active', function(e) {
    //         let service_id = $(this).data('service_id');
    //         console.log(service_id);
    //         if ($(this).is(':checked')) {
    //             switchTuningxStatus = $(this).is(':checked');
    //             console.log(switchTuningxStatus);
    //         }
    //         else {
    //             switchTuningxStatus = $(this).is(':checked');
    //             console.log(switchTuningxStatus);
    //         }

    //         change_tuningx_status(service_id, switchTuningxStatus);
    //     });
    

    //     function change_tuningx_status(service_id, status){
    //         $.ajax({
    //             url: "/change_tuningx_status",
    //             type: "POST",
    //             data: {
    //                 "_token": "{{ csrf_token() }}",
    //                 "service_id": service_id,
    //                 "status": status,
    //             },
    //             headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
    //             success: function(response) {
                    
    //             }
    //         });  
    //     }

    });
    

</script>

@endsection