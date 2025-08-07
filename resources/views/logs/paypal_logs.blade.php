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
                    <h3>Paypal Logs HERE</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    
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
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Type</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Credit ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paypalLogs as $log)
                                
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p><span class="@if($log->type == 'success') label label-success @elseif($log->type == 'error') label label-danger @endif">{{$log->type}}</span></p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$log->message}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($log->credit_id != 0)
                                            <p><a target="_blank" href="{{route('payment-details', $log->credit_id)}}">{{$log->credit_id}}</a></p>
                                            @endif
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$log->created_at}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p><a class="btn btn-warning text-black" target="_blank" href="{{route('paypal-details', $log->id)}}">Details</a></p>
                                        </td>
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
        
       
    });

</script>

@endsection