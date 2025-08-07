@extends('layouts.app')

@section('pagespecificstyles')
<style>
    .table tbody tr td .fg label::after{
        left: 3px !important;
    }
</style>
@endsection

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
                    <div class="card-title">
                        <h3>Vehicles</h3>
                        
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{ route('create-vehicle') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Vehicle</span>
                            </button>
                            <button data-redirect="{{ route('import-vehicles') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Import Vehicles</span>
                            </button>
                            {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                        </div>
                        </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <button class="btn btn-danger hide" id="delete">Delete</button><div>
                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                        
                            
                            <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                <thead>
                                    <tr role="row" >
                                        <th style="width: 5%;">
                                            <div class="checkbox checkbox-circle check-success  ">
                                                <input type="checkbox" value="1" id="select_all">
                                                <label for="select_all"></label>
                                            </div>
                                        </th>
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
                                            <td>
                                                <div class="checkbox checkbox-circle check-info">
                                                    <input type="checkbox" value="{{$vehicle->id}}" id="checkbox2-{{$vehicle->id}}">
                                                    <label for="checkbox2-{{$vehicle->id}}"></label>
                                                </div>
                                            </td>
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

@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {
        
        $(document).on('click', '#delete' ,function(){
            var searchIDs = $("tbody input:checkbox:checked").map(function(){
                    return $(this).val();
            }).toArray();
            console.log(searchIDs);

            $.ajax({
                url: "/mass_delete",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "searchIDs": searchIDs
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    location.reload();
                }
            }); 
        });

        $(document).on('click', '#select_all' ,function(){

            if($(this).is(":checked")){
                console.log(this);
                $('#delete').removeClass('hide');
                $('input:checkbox').attr('checked',true);
            }
            else{
                $('#delete').addClass('hide');
                $('input:checkbox').attr('checked',false);
            }
        });
    });
</script>

@endsection