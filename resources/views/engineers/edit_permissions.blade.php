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
                                        <p>View Dashboard</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="view-dashboard" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'view-dashboard')) checked="checked" @endif /></p>
                                    </td>
                                </tr>
                                
                                <tr role="row">

                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Customer Contact Information</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="customer-contact-information" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'customer-contact-information')) checked="checked" @endif /></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Head Of Engineers</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="head" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'head')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Propose Options</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="propose-options" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'propose-options')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Show All Files</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="show-all-files" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'show-all-files')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Submit File</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="submit-file" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'submit-file')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Admin's Tasks</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="admin-tasks" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'admin-tasks')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                
                                {{-- <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Reports</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="reports" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'reports')) checked="checked" @endif /></p>
                                    </td>
                                </tr> --}}

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Engineer's Reports</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="engineers-report" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'engineers-report')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Feedback Reports</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="feedback-report" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'feedback-report')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Credit Reports</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="credit-report" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'credit-report')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Show Transaction</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="show-transaction" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'show-transaction')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Edit Transaction</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="edit-transaction" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'edit-transaction')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Add Transaction</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="add-transaction" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'add-transaction')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>View Customer</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="view-customers" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'view-customers')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Edit Customer</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="edit-customers" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'edit-customers')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Delete Customer</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="delete-customers" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'delete-customers')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>View Vehicles</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="view-vehicles" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'view-vehicles')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Edit Vehicles</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="edit-vehicles" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'edit-vehicles')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Delete Vehicles</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="delete-vehicles" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'delete-vehicles')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>View Services</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="view-services" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'view-services')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Edit Services</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="edit-services" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'edit-services')) checked="checked" @endif /></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Delete Services</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><input data-engineer_id={{$engineer->id}} data-permission="delete-services" class="active" type="checkbox" data-init-plugin="switchery" @if(get_engineers_permission($engineer->id, 'delete-services')) checked="checked" @endif /></p>
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
            let engineer_id = $(this).data('engineer_id');
            let permission = $(this).data('permission');

            console.log(engineer_id);
            console.log(permission);

            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }
            else {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }

            change_permission(engineer_id, permission, switchStatus);
        });

        function change_permission(engineer_id, permission, switchStatus){

            $.ajax({
                url: "/change_engineer_permission",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "engineer_id": engineer_id,
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