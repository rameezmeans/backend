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
                    <h3>{{$customer->name}}'s Logs</h3>
                </div>
                <div class="pull-right">
                    <div class="col-xs-12">
                        <button data-redirect="{{ route('payment-and-customers') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Payments And Customers</span>
                        </button>
                        {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                    </div>
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
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Payment</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Customer</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Reason To Skip Elorus</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Zohobooks ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Reason To Skip Zohobooks ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Email Sent</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($paymentLogs)
                                    @foreach ($paymentLogs as $l)
                                        <tr role="row" class="">
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$l->payment_id}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$l->user_id}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$l->elorus_id}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$l->reason_to_skip_elorus_id}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$l->zohobooks_id}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$l->reason_to_skip_zohobooks_id}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$l->email_sent}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$p->created_at->diffForHumans()}}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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