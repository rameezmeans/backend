@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
      <div class=" container-fluid   container-fixed-lg bg-white">
        @if(Session::has('success'))
          <div class="pgn-wrapper" data-position="top" style="top: 59px;">
            <div class="pgn push-on-sidebar-open pgn-bar">
              <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">
                  <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                </button>
                {{ Session::get('success') }}
              </div>
            </div>
          </div>
        @endif
        <div class="card card-transparent m-t-40">
            <div class="row p-b-20">
                <div class="col-lg-12 p-b-20">
                    <h3 class="text-center">{{$customer->name}}'s Credit Report</h3>
                    <h5 class="">Total Credits</h5>
                    <form action="{{route('update-credits')}}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$customer->id}}">
                        <div class="form-group form-group-default required ">
                            <label></label>
                            <input value="{{ $customer->sum() }}"  name="total_credits_updated" type="number" class="form-control" required>
                        </div>
                          <div class="text-center m-t-20">                    
                            <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Update Credits</span></button>
                          </div>
                      </form>
                    </div>
                    <div class="col-lg-12 m-b-20">
                        <div class="border border-1 p-1">
                        <h5 class="text-center">Credits Bought or Added</h5>
                                <p class="text-center">Total Amount Spent: <span class="label label-warning text-black">${{$customer->amount()}}</span></p>
                                    <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 20%">Invoice ID</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 5%">Credits</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 10%">Amount</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 25%">Strip ID</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 20%">Date</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Download PDF</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($credits as $credit)
                                                @if($credit->credits > 0)
                                                    <tr role="row">
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label label-">{{$credit->invoice_id}}</span></p>
                                                        </td>
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label label-success">{{$credit->credits}}</span></p>
                                                        </td>
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label label-warning text-black">@if($credit->price_payed == 0) {{ 'Manual Entry' }} @else ${{$credit->price_payed}} @endif</span></p>
                                                        </td>
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label label-success">@if($credit->stripe_id){{$credit->stripe_id}} @else {{"Manual Entry"}} @endif</span></p>
                                                        </td>
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label label-success">{{\Carbon\Carbon::parse($credit->created_at)->format('d/m/Y')}}</span></p>
                                                        </td>
                                                        <td><a href="{{ route('pdfview',['id'=>$credit->id]) }}" class="btn btn-sm btn-primary"><i class="pg-printer"></i></a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                               
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="border border-1 p-1">
                        <h5 class="text-center">Credits Spent</h5>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 15%">Invoice ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 5%">Credits</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 25%">Date</th>
                                    {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Download PDF</th> --}}
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($credits as $credit)
                                    @if($credit->credits < 0)
                                        <tr role="row">
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p><span class="label label-">{{$credit->invoice_id}}</span></p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p><span class="label label-danger">{{-1*(int)$credit->credits}}</span></p>
                                            </td>
                                            
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p><span class="label label-success">{{\Carbon\Carbon::parse($credit->created_at)->format('d/m/Y')}}</span></p>
                                            </td>
                                            {{-- <td><button class="btn btn-sm btn-primary"><i class="pg-printer"></i></button></td> --}}
                                            <td><a href="{{route('file', $credit->file_id)}}" class="btn btn-sm btn-primary"><i class="fa fa-file"></i></a></td>
                                        </tr>
                                    @endif
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
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

      $( document ).ready(function(event) {
        
       
    });

</script>

@endsection