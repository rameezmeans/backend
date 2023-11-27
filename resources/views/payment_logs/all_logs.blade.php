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
                    <h3>All Logs</h3>
                    
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
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">DB ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Invoice ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Date</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Customer</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Customer Group</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Invoice Number</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus Invoice Number</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Credits</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Price</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Zohobooks</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Trouble Email Sent</th>
                                    {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Actions</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if($allPaymentLogs)
                                    @foreach ($allPaymentLogs as $l)
                                        <tr role="row" class="">

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p class="label bg-warning">{{\App\Models\Credit::findOrFail($l->payment_id)->id}}</p>
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p class="label bg-warning">{{\App\Models\Credit::findOrFail($l->payment_id)->invoice_id}}</p>
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{date('d/m/Y',strtotime($l->created_at))}}</p>
                                                
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{\App\Models\User::findOrFail($l->user_id)->name}}</p>
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{\App\Models\User::findOrFail($l->user_id)->group->name}}</p>
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{\App\Models\Credit::findOrFail($l->payment_id)->invoice_id}}</p>
                                            </td>
                                            
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$l->elorus_invoice_id}}</p>
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{\App\Models\Credit::findOrFail($l->payment_id)->credits}}</p>
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>${{\App\Models\Credit::findOrFail($l->payment_id)->price_payed}}</p>
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>@if($l->elorus_id)<a class="btn btn-warning text-black" target="_blank" href="{{\App\Models\Credit::findOrFail($l->payment_id)->elorus_permalink}}">To Elorus</a>@else No Elorus @endif</p>
                                                
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>@if($l->zohobooks_id)<a class="btn btn-warning text-black" target="_blank" href="{{'https://books.zoho.com/app/8745725#/invoices/'.$l->zohobooks_id}}">To Zohobooks</a>@else No Zohobooks @endif</p>
                                                
                                            </td>

                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>@if($l->email_sent) Yes @else No @endif</p>
                                                
                                            </td>

                                            {{-- <td class="v-align-middle semi-bold sorting_1">
                                                
                                            </td> --}}
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