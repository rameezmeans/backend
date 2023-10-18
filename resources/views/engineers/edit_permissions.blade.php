@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>{{$engineer->name}}'s Permission</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('edit-engineer', $engineer->id)}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Edit Engineer</span>
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
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 80%;">Permission</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" >Active</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr role="row">

                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Customer Contact Information</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="customers" class="active" type="checkbox" data-init-plugin="switchery" @if(get_permission($engineer->id, 'customers')) checked="checked" @endif /></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Head Of Engineers</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-subdealer_group_id={{$engineer->id}} data-permission="engineers" class="active" type="checkbox" data-init-plugin="switchery" @if(get_permission($engineer->id, 'engineers')) checked="checked" @endif /></p>
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