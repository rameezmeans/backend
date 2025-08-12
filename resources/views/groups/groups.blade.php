@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid bg-white">

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
                    <div class="card-title"><h3>VAT Groups</h3>
                    </div>
                    <div class="pull-right">
                    <div class="col-xs-12">
                        @if(Auth::user()->is_admin())
                            <button data-redirect="{{ route('create-group') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add VAT Group</span>
                            </button>
                        @endif
                        {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                    </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">


                    {{-- <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                        <li class="nav-item">
                          <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>EcuTech</span></a>
                        </li>
                        <li class="nav-item">
                          <a href="#" data-toggle="tab" data-target="#slide2"><span>TuningX</span></a>
                        </li>
                        <li class="nav-item">
                          <a href="#" data-toggle="tab" data-target="#slide3"><span>ETF</span></a>
                        </li>
                      </ul>

                      <div class="tab-content">
                        <div class="tab-pane slide-left active" id="slide1"> --}}

                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                        <div>
                            <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                <thead>
                                    <tr role="row">
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Tax percentage</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Frontend</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ecuTechGroups as $group)
                                        <tr role="row" @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-groups')) class="redirect-click" data-redirect="{{ route('edit-group', $group->id) }}" @endif>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$group->name}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$group->tax}}%</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p><label class="label @if($group->front_end_id == 1) text-white bg-primary @elseif($group->front_end_id == 3) text-white bg-info  @elseif($group->front_end_id == 4) text-white bg-success @else text-black bg-warning @endif">{{$group->frontend->name}}</label></p>
                                              </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$group->created_at->diffForHumans()}}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                        {{-- </div> --}}

                        {{-- <div class="tab-pane slide-left" id="slide2">
                            <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                                <div>
                                    <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Frontend</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tuningXGroups as $group)
                                                <tr role="row" @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-groups')) class="redirect-click" data-redirect="{{ route('edit-group', $group->id) }}" @endif>
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p>{{$group->name}}</p>
                                                    </td>
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p><label class="label @if($group->front_end_id == 1) text-white bg-primary @elseif($group->front_end_id == 3) text-white bg-info @else text-black bg-warning @endif">{{$group->frontend->name}}</label></p>
                                                      </td>
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p>{{$group->created_at->diffForHumans()}}</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane slide-left" id="slide3">
                            <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                                <div>
                                    <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Frontend</th>
                                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($etfGroups as $group)
                                                <tr role="row" @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-groups')) class="redirect-click" data-redirect="{{ route('edit-group', $group->id) }}" @endif>
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p>{{$group->name}}</p>
                                                    </td>
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p><label class="label @if($group->front_end_id == 1) text-white bg-primary @elseif($group->front_end_id == 3) text-white bg-info @else text-black bg-warning @endif">{{$group->frontend->name}}</label></p>
                                                      </td>
                                                    <td class="v-align-middle semi-bold sorting_1">
                                                        <p>{{$group->created_at->diffForHumans()}}</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div> --}}

                </div>
            </div>     
        </div>
    </div>
</div>

@endsection