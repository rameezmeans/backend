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
                    <h3>{{$customer->name}}'s Payments</h3>
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
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Date</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Customer</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Group</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Invoice ID</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Credits</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Price</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Admin Entry</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Zohobooks</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($allPayments)
                                        @foreach ($allPayments as $p)
                                            <tr role="row" class="">

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{date('d/m/Y',strtotime($p->created_at))}}</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{\App\Models\User::findOrFail($p->user_id)->name}}</p>
                                                    
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{\App\Models\User::findOrFail($p->user_id)->group->name}}</p>
                                                    
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$p->invoice_id}}</p>
                                                    
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$p->credits}}</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>${{$p->price_payed}}</p>
                                                    
                                                </td>

                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>@if($p->gifted) Yes @else No @endif</p>
                                                    
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