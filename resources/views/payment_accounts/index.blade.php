@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

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
            <div class="card-header ">
                <div class="card-title">
                    <h3>Payment Accounts</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    @if(Auth::user()->is_admin())
                        <button data-redirect="{{ route('create-payment-account') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Create Payment Account</span>
                        </button>
                    @endif
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $account)
                                    <tr role="row" @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-payment-accounts')) class="redirect-click" data-redirect="{{ route('edit-payment-account', [$account->id]) }}" @endif>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$account->name}}</p>
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