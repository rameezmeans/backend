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
                <div>Changed By: {{\App\Models\User::findOrFail($change->changed_by)->name}}</div>
                <div>Changed at: {{date('d/m/Y h:i:sa',strtotime($change->created_at))}}</div>
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
                                            <p>{{$change->name}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->name != $user->name)
                                                <p class="label label-danger">{{$user->name}}</p>  
                                            @else
                                                <p>{{$user->name}}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Phone</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->phone}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->phone != $user->phone)
                                            <p class="label label-danger">{{$user->phone}}</p>  
                                        @else
                                            <p>{{$user->phone}}</p>
                                        @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Language</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->language}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->language != $user->language)
                                            <p class="label label-danger">{{$user->language}}</p>  
                                        @else
                                            <p>{{$user->language}}</p>
                                        @endif
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Address</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->address}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->address != $user->address)
                                            <p class="label label-danger">{{$user->address}}</p>  
                                        @else
                                            <p>{{$user->address}}</p>
                                        @endif
                                        </td>

                                    </tr>
                                    <tr>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>zip</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->zip}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->zip != $user->zip)
                                            <p class="label label-danger">{{$user->zip}}</p>  
                                        @else
                                            <p>{{$user->zip}}</p>
                                        @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>City</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->city}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->city != $user->city)
                                            <p class="label label-danger">{{$user->city}}</p>  
                                        @else
                                            <p>{{$user->city}}</p>
                                        @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>country</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->country}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->country != $user->country)
                                            <p class="label label-danger">{{$user->country}}</p>  
                                        @else
                                            <p>{{$user->country}}</p>
                                        @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Company Name</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->company_name}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->company_name != $user->company_name)
                                            <p class="label label-danger">{{$user->company_name}}</p>  
                                        @else
                                            <p>{{$user->company_name}}</p>
                                        @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Company ID</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->company_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->company_id != $user->company_id)
                                            <p class="label label-danger">{{$user->company_id}}</p>  
                                        @else
                                            <p>{{$user->company_id}}</p>
                                        @endif
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
                                            <p>{{$changeGroup}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($changeGroup != $userGroup)
                                            <p class="label label-danger">{{$userGroup}}</p>  
                                        @else
                                            <p>{{$userGroup}}</p>
                                        @endif
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
                                            <p>{{$changeFrontend}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($changeFrontend != $userFrontend)
                                            <p class="label label-danger">{{$userFrontend}}</p>  
                                        @else
                                            <p>{{$userFrontend}}</p>
                                        @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Elorus ID</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->elorus_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->elorus_id != $user->elorus_id)
                                            <p class="label label-danger">{{$user->elorus_id}}</p>  
                                        @else
                                            <p>{{$user->elorus_id}}</p>
                                        @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Exclude vat check</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->exclude_vat_check}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->exclude_vat_check != $user->exclude_vat_check)
                                            <p class="label label-danger">{{$user->exclude_vat_check}}</p>  
                                        @else
                                            <p>{{$user->exclude_vat_check}}</p>
                                        @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Zohobooks id</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->zohobooks_id}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->zohobooks_id != $user->zohobooks_id)
                                            <p class="label label-danger">{{$user->zohobooks_id}}</p>  
                                        @else
                                            <p>{{$user->zohobooks_id}}</p>
                                        @endif
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>Magic SN</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$change->sn}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            @if($change->sn != $user->sn)
                                            <p class="label label-danger">{{$user->sn}}</p>  
                                        @else
                                            <p>{{$user->sn}}</p>
                                        @endif
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