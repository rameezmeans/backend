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
                    <h3>All Payments</h3>
                </div>
                <div class="pull-right">
                    <div class="col-xs-12">
                        <button data-redirect="{{ route('payment-and-customers') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Payments And Customers</span>
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
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">DB ID</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Invoice ID</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Frontend</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Country</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Type</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Date</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Customer</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Email</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Group</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Invoice ID</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Credits</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Price</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Admin Entry</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Details</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Zohobooks</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($allPayments)
                                        @foreach ($allPayments as $p)
                                            <tr role="row" class="">

                                                @php
                                                    $customer = \App\Models\User::findOrFail($p->user_id);
                                                @endphp

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p class="label @if($p->front_end_id == 2) bg-warning @elseif($p->front_end_id == 3) text-white bg-info @else bg-primary text-white @endif">{{$p->id}}</p>
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p class="label @if($p->front_end_id == 2) bg-warning @elseif($p->front_end_id == 3) text-white bg-info @else bg-primary text-white @endif">{{$p->invoice_id}}</p>
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p><label class="label @if($customer->front_end_id == 1) text-white bg-primary @elseif($customer->front_end_id == 3) text-white bg-info @else text-black bg-warning @endif">@if($customer->frontend){{$customer->frontend->name}}@else No Front End @endif</label></p>
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p><label class="label @if($customer->front_end_id == 1) text-white bg-primary @elseif($customer->front_end_id == 3) text-white bg-info @else text-black bg-warning @endif">{{code_to_country($customer->country)}}</label></p>
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                        @if($p->type != '')
                                                        <p>{{ ucfirst($p->type) }}</p>
                                                        @else
                                                        <p>Admin</p>
                                                        @endif
                                                    
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{date('d/m/Y',strtotime($p->created_at))}}</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{\App\Models\User::findOrFail($p->user_id)->name}}</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{\App\Models\User::findOrFail($p->user_id)->email}}</p>
                                                    
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>@if(\App\Models\User::findOrFail($p->user_id)->group){{\App\Models\User::findOrFail($p->user_id)->group->name}}@else No Group @endif</p>
                                                    
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$p->invoice_id}}</p>
                                                    
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$p->credits}}</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>€{{$p->price_payed}}</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>@if($p->gifted) Yes @else No @endif</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>@if($p->gifted == 0)<a class="btn btn-warning text-black" target="_blank" href="{{route('payment-details', $p->id)}}">Payment Details</a>@endif</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>@if($p->elorus_id)<a class="btn btn-warning text-black" target="_blank" href="{{$p->elorus_permalink}}">Go To Elorus</a>@else No Elorus @endif</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>@if($p->zohobooks_id)<a class="btn btn-warning text-black" target="_blank" href="{{'https://books.zoho.com/app/8745725#/invoices/'.$p->zohobooks_id}}">Go To Zohobooks</a>@else No Zohobooks @endif</p>
                                                    
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