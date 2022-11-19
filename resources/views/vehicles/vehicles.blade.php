@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid bg-white">
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
                <div class="card-header">
                    <div class="card-title"><h3>Vehicles</h3>
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{ route('create-vehicle') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Vehicle</span>
                            </button>
                            {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                        </div>
                        </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                        <div>
                            <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                <thead>
                                    <tr role="row">
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="descending" aria-label="Title: activate to sort column descending" style="width: 20px;">Engine</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="descending" aria-label="Title: activate to sort column descending" style="width: 20px;">Name</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="descending" aria-label="Title: activate to sort column descending" style="width: 20px;">Model</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="descending" aria-label="Title: activate to sort column descending" style="width: 20px;">Generation</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="descending" aria-label="Title: activate to sort column descending" style="width: 20px;">Make</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @foreach($vehicles as $vehicle)
                                        <tr class="redirect-click" data-redirect="{{ route('vehicle', $vehicle->id) }}">
                                            <td>{{$vehicle->Engine}}</td>
                                            <td>{{$vehicle->Name}}</td>
                                            <td>{{$vehicle->Model}}</td>
                                            <td>{{$vehicle->Generation}}</td>
                                            <td>{{$vehicle->Make}}</td>
                                        </tr>
                                    @endforeach
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