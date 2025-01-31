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
                    <button data-redirect="{{ route('changes', $user->id) }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Changes</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div>Created By: {{\App\Models\User::findOrFail($user->changed_by)->name}}</div>
                <div>Created at: {{date('d/m/Y h:i:sa',strtotime($user->created_at))}}</div>
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Column</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">From</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">To</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Name</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->name}}</p>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Phone</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->phone}}</p>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Language</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->language}}</p>
                                        </td>
                                        

                                    </tr>
                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Address</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->address}}</p>
                                        </td>
                                        

                                    </tr>
                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>zip</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->zip}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>City</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->city}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>country</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->country}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Company Name</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->company_name}}</p>
                                        </td>
                                       
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Company ID</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->company_id}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        @php
                                        $changeGroup = \App\Models\Group::findOrFail($change->group_id)->name;
                                        $userGroup = \App\Models\Group::findOrFail($user->group_id)->name;
                                        @endphp
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>VAT Group</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$userGroup}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        @php
                                        $changeFrontend = \App\Models\FrontEnd::findOrFail($change->front_end_id)->name;
                                        $userFrontend = \App\Models\FrontEnd::findOrFail($user->front_end_id)->name;
                                        @endphp
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Frontend</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$userFrontend}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Elorus ID</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->elorus_id}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Exclude vat check</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->exclude_vat_check}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>EVC Customer ID</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->evc_customer_id}}</p>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Magic SN</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->sn}}</p>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Test</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$user->test}}</p>
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