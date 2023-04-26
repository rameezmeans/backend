@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Permissions</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('edit-subdealer-group', ['id' => $subdealerID])}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Subdealer Group</span>
                    </button>
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
                                        <p>Customers</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-subdealer_group_id={{$subdealerID}} data-permission="customers" class="active" type="checkbox" data-init-plugin="switchery" @if(get_permission($subdealerID, 'customers')) checked="checked" @endif /></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Engineers</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-subdealer_group_id={{$subdealerID}} data-permission="engineers" class="active" type="checkbox" data-init-plugin="switchery" @if(get_permission($subdealerID, 'engineers')) checked="checked" @endif /></p>
                                    </td>
                                </tr>
                                <tr>

                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Services</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-subdealer_group_id={{$subdealerID}} data-permission="services" class="active" type="checkbox" data-init-plugin="switchery" @if(get_permission($subdealerID, 'services')) checked="checked" @endif /></p>
                                    </td>
                                </tr>
                                <tr>

                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Unit Price</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-subdealer_group_id={{$subdealerID}} data-permission="unit_price" class="active" type="checkbox" data-init-plugin="switchery" @if(get_permission($subdealerID, 'unit_price')) checked="checked" @endif /></p>
                                    </td>
                                </tr>
                                <tr>

                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Vehciles</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-subdealer_group_id={{$subdealerID}} data-permission="vehicles" class="active" type="checkbox" data-init-plugin="switchery" @if(get_permission($subdealerID, 'vehicles')) checked="checked" @endif /></p>
                                    </td>
                                </tr>
                                <tr>

                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Transactions</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-subdealer_group_id={{$subdealerID}} data-permission="transactions" class="active" type="checkbox" data-init-plugin="switchery" @if(get_permission($subdealerID, 'transactions')) checked="checked" @endif /></p>
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
        let switchStatus = true;
        $(document).on('change', '.active', function(e) {
            let subdealer_group_id = $(this).data('subdealer_group_id');
            let permission = $(this).data('permission');

            console.log(subdealer_group_id);
            console.log(permission);

            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }
            else {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }

            change_permission(subdealer_group_id, permission, switchStatus);
        });

        function change_permission(subdealer_group_id, permission, switchStatus){

            $.ajax({
                url: "/change_permission",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "subdealer_group_id": subdealer_group_id,
                    "permission": permission,
                    "switchStatus": switchStatus,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    console.log(response);
                }
            });  
        }

    });

</script>

@endsection