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
                    <h3>Original Files</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    
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
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Customer</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Group</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus Enabled(Stripe)</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus Enabled(Paypal)</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Zohobooks ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr role="row" class="">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$customer->name}}</p>
                                            
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>@if($customer->group){{$customer->group->name}}@else 'No Group'@endif </p>
                                            
                                        </td>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>@if($customer->group) @if($customer->stripe_payment_account()->elorus) <button class="btn btn-success">Enable</button> @else <button class="btn btn-danger">Disable</button> @endif @else 'No Group'@endif</p>
                                            
                                        </td>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>@if($customer->group) @if($customer->paypal_payment_account()->elorus) <button class="btn btn-success">Enable</button> @else <button class="btn btn-danger">Disable</button> @endif @else 'No Group'@endif</p>
                                            
                                        </td>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$customer->elorus_id}}</p>
                                            
                                        </td>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$customer->zohobooks_id}}</p>
                                            
                                        </td>
                                        
                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            {{-- <a target="_blank" href="{{route('download-original-file', $file->id)}}" class="btn btn-success">Download</a> --}}
                                           
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