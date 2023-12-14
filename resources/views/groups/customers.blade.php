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
            <!-- START card -->

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Customers</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                  @if(Auth::user()->is_admin())
                    <button data-redirect="{{route('create-customer')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Customer</span>
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
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Portal</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Email</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Phone</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Country</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($customers as $customer)
                            <tr role="row" class="@if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-customers')) redirect-click @endif" @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-customers')) data-redirect="{{ route('edit-customer', $customer->id) }}" @endif>
                                <td class="v-align-middle semi-bold sorting_1">
                                    <p>{{$customer->name}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p><label class="label @if($customer->frontend->id == 1) text-white bg-primary @else text-black bg-warning @endif">{{$customer->frontend->name}}</label></p>
                              </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>{{$customer->email}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>{{$customer->phone}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>{{code_to_country($customer->country)}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>{{ date('d/m/Y', strtotime($customer->created_at))}}</p>
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
@endsection