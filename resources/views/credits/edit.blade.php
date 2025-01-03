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
        @if(Session::has('info'))
          <div class="pgn-wrapper" data-position="top" style="top: 59px;">
            <div class="pgn push-on-sidebar-open pgn-bar">
              <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                  <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                </button>
                {{ Session::get('info') }}
              </div>
            </div>
          </div>
        @endif
        <div class="card card-transparent m-t-40">
            <div class="row p-b-20">
                @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'add-transaction'))
                <div class="col-lg-12 p-b-20">
                    <h3 class="text-center">{{$customer->name}}'s Credit Report</h3>
                    <div class="text-center">
                        <a href="{{route('edit-customer', $customer->id)}}" class="btn btn-success">Go To Customer Page</a>
                    </div>
                    <h5 class="">Total Credits</h5>
                    <form action="{{route('update-credits')}}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$customer->id}}">
                        <div class="form-group form-group-default required ">
                            <label></label>
                            <input value="{{ $customer->sum() }}"  name="total_credits_updated" type="number" class="form-control" required>
                        </div>
                        <div class="form-group form-group-default">
                            <label>Message to Client</label>
                            <input value=""  name="message_to_credit" type="text" class="form-control">
                        </div>
                        <div class="form-group form-group-default price_payed_field">
                            <label></label>
                            <input value=""  name="price_payed" type="text" class="form-control">
                        </div>
                        @error('price_payed')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="checkbox check-success">
                            <input name="gifted" type="checkbox" id="checkbox-gifted">
                            <label for="checkbox-gifted">Check if these credits are a gift to customer.</label>
                        </div>
                          <div class="text-center m-t-20">                    
                            <button class="btn btn-success btn-cons m-b-10" type="submit"> <span class="bold">Update Credits</span></button>
                          </div>
                      </form>
                    </div>
                    @endif
                    <div class="col-lg-12 m-b-20">
                        <div class="border border-1 p-1">
                        <h5 class="text-center">Credits Bought or Added</h5>
                                <p class="text-center">Total Amount Spent: <span class="label label-warning text-black">€{{$customer->amount()}}</span></p>

                                <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 20%">Date</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 5%">Purchase</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 10%">Spent</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 25%">Note To Client</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Invoice Number</th>
                                            
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Download PDF Or Task</th>
                                            
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Actions</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($credits as $credit)

                                        @php 
                                            if($credit->file_id){
                                                $file = \App\Models\File::where('id',$credit->file_id)->first();
                                            }
                                            
                                        @endphp
                                            
                                                <tr role="row">
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p><span class="label  @if($credit->credits > 0) label-success @else label-danger @endif">{{\Carbon\Carbon::parse($credit->created_at)->format('d/m/Y')}}</span></p>
                                                    </td>
                                                    
                                                    @if($credit->credits > 0)
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label @if($credit->gifted) label-info @else label-warning @endif">{{$credit->credits}}</span></p>
                                                        </td>
                                                        <td></td>
                                                    @else
                                                        <td></td>   
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label label-danger">{{-1*(int)$credit->credits}}</span></p>
                                                        </td>
                                                    @endif
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p>{{$credit->message_to_credit}}</p>
                                                    </td>
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p>@if($credit->credits > 0)<span class="label label-">{{$credit->invoice_id}}</span>@endif</p>
                                                    </td>
                                                    <td>
                                                        @if($credit->credits > 0)
                                                            @if(!$credit->gifted)
                                                                @if(!$credit->elorus_permalink)
                                                                <a href="{{ route('pdfview',['id'=>$credit->id]) }}" target="_blank" class="btn btn-sm btn-primary"><i class="pg-printer"></i></a>

                                                                @else
                                                                <a href="{{ $credit->elorus_permalink }}" target="_blank" class="btn btn-sm btn-primary"><i class="pg-printer"></i></a>
                                                                @endif
                                                            @endif
                                                        @else

                                                        @if($credit->file_id)
                                                        @if($file)
                                                            @if($file->subdealer_group_id)
                                                                <span class="label label-danger text-white">LUA Entry</span>
                                                            @else
                                                                @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files'))
                                                                    <a href="{{route('file', $credit->file_id)}}" class="btn btn-sm btn-primary"><i class="fa fa-file"></i></a>
                                                                @endif
                                                            @endif
        
                                                            @else
                                                                <p>Record Deleted: {{$credit->file_id}}</p>
                                                            @endif
                                                        
                                                            @else
                                                                <span class="label label-warning text-black">Manual Entry</span>
            
                                                            @endif
                                                        

                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($credit->credits > 0)
                                                            <a href="{{ route('update-credit',['id'=>$credit->id]) }}" class="btn btn-sm btn-success">Edit</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            
                                        @endforeach
                                    </tbody>
                                </table> 

                                    {{-- <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 20%">Invoice ID</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 5%">Credits</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 10%">Amount</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 25%">Strip ID or Manual Entry</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 25%">Note To Client</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 20%">Date</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Download PDF</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Actions</th>
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
                                                            <p><span class="label @if($credit->gifted) label-info @else label-warning @endif">{{$credit->credits}}</span></p>
                                                        </td>
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label  @if($credit->gifted) label-info @else label-warning @endif text-black">@if($credit->price_payed == 0) {{ 'Admin Entry' }} @else €{{$credit->price_payed}} @endif</span></p>
                                                        </td>
                                                        <td class="v-align-middle semi-bold sorting_1" >
                                                            <p style="width: 200px;"><span class="label  @if($credit->gifted) label-info @else label-warning @endif">@if($credit->stripe_id){{$credit->stripe_id}} @else @if($credit->gifted) {{ 'Gifted' }} @else {{ 'Direct Transaction' }} @endif @endif</span></p>
                                                        </td>
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p>{{$credit->message_to_credit}}</p>
                                                        </td>
                                                        <td class="v-align-middle semi-bold sorting_1">
                                                            <p><span class="label  @if($credit->gifted) label-info @else label-success @endif">{{\Carbon\Carbon::parse($credit->created_at)->format('d/m/Y')}}</span></p>
                                                        </td>
                                                        <td>
                                                            @if(!$credit->gifted)
                                                                @if(!$credit->elorus_permalink)
                                                                <a href="{{ route('pdfview',['id'=>$credit->id]) }}" target="_blank" class="btn btn-sm btn-primary"><i class="pg-printer"></i></a>

                                                                @else
                                                                <a href="{{ $credit->elorus_permalink }}" target="_blank" class="btn btn-sm btn-primary"><i class="pg-printer"></i></a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ route('update-credit',['id'=>$credit->id]) }}" class="btn btn-sm btn-success">Edit</a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table> --}}
                               
                        </div>
                    </div>
                    {{-- <div class="col-lg-12">
                        <div class="border border-1 p-1">
                        <h5 class="text-center">Credits Spent</h5> --}}
                        {{-- <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 15%">Invoice ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 5%">Credits</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 25%">Reason to Decrease credits</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 25%">Date</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Download PDF</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($credits as $credit)
                                    @if($credit->credits < 0)
                                        @php 
                                            if($credit->file_id){
                                                $file = \App\Models\File::where('id',$credit->file_id)->first();
                                            }
                                            
                                        @endphp
                                        <tr role="row">
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p><span class="label ">{{$credit->invoice_id}}</span></p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p><span class="label label-danger">{{-1*(int)$credit->credits}}</span></p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">

                                                @if($credit->file_id)
                                                    @if($file)
                                                        @if($file->subdealer_group_id)
                                                            <p>{{$credit->file->vehicle()->Name}} {{ $credit->file->engine }} {{ $credit->file->vehicle()->TORQUE_standard }}</p>
                                                        @else
                                                            <p>@if($credit->file_id)  @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files')) <a href="{{route('file', $credit->file_id)}}"> @endif {{$credit->file->vehicle()->Name}} {{ $credit->file->engine }} {{ $credit->file->vehicle()->TORQUE_standard }} @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files')) </a> @endif @else<span class="label label-danger">{{$credit->message_to_credit}}</span>@endif</p>
                                                        @endif

                                                    @else
                                                        <p>Record Deleted: {{$credit->file_id}}</p>
                                                    @endif

                                                @endif
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p><span class="label label-success">{{\Carbon\Carbon::parse($credit->created_at)->format('d/m/Y')}}</span></p>
                                            </td>
                                            <td><button class="btn btn-sm btn-primary"><i class="pg-printer"></i></button></td>
                                            <td>
                                                @if($credit->file_id)
                                                @if($file)
                                                    @if($file->subdealer_group_id)
                                                        <span class="label label-danger text-white">LUA Entry</span>
                                                    @else
                                                        @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'show-files'))
                                                            <a href="{{route('file', $credit->file_id)}}" class="btn btn-sm btn-primary"><i class="fa fa-file"></i></a>
                                                        @endif
                                                    @endif

                                                    @else
                                                        <p>Record Deleted: {{$credit->file_id}}</p>
                                                    @endif
                                                    
                                                @else
                                                    <span class="label label-warning text-black">Manual Entry</span>

                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table> --}}
                        {{-- </div> --}}
                    {{-- </div> --}}
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
        
        $('#checkbox-gifted').on('change', function() {

            let ischecked= $(this).is(':checked');
            
            if(ischecked) {
                $('.price_payed_field').addClass('hide');
            }
            else{
                $('.price_payed_field').removeClass('hide');
            }
        }); 

    });

</script>

@endsection