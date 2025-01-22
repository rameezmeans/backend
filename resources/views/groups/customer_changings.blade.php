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
                    <h3>Changings</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('changes', $user->id) }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Create Tool</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div>Changed By: {{$change->changed_by}}</div>
                <div>Changed at: {{$change->created_at}}</div>
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">From</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">To</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->name}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->name}}</p>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->phone}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->phone}}</p>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->language}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->language}}</p>
                                        </td>

                                    </tr>
                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->address}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->address}}</p>
                                        </td>

                                    </tr>
                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->zip}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->zip}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->city}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->city}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->country}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->country}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->company_name}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->company_name}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->company_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->company_id}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->group_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->group_id}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->company_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->company_id}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->front_end_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->front_end_id}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->elorus_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->elorus_id}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->exclude_vat_check}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->exclude_vat_check}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->company_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->company_id}}</p>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->sn}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->sn}}</p>
                                        </td>
                                    </tr>

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