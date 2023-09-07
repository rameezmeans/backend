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
                    <h3>Packages</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('fms-create-package') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Create Package</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                <ul class="nav nav-tabs nav-tabs-fillup m-t-0" data-init-reponsive-tabs="dropdownfx">
             
                    <li class="nav-item">
                      <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>Service Packages</span></a>
                    </li>
                    <li class="nav-item">
                      <a href="#" data-toggle="tab" data-target="#slide2"><span>EVC Packages</span></a>
                    </li>
                  </ul>
    
                  <div class="tab-content">
                    <div class="tab-pane slide-left active" id="slide1">
                        <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                            <div>
                                <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 10%;">Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($packages as $package)
                                            <tr role="row" class="redirect-click" data-redirect="{{ route('edit-package', $package->id) }}">
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$package->name}}</p>
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$package->created_at->diffForHumans()}}</p>
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p><input data-package_id={{$package->id}} class="active" type="checkbox" data-init-plugin="switchery" @if($package->active) checked="checked" @endif onclick="status_change()"/></p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane slide-left" id="slide2">
                        <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                            <div>
                                <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 10%;">Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($evcPackages as $package)
                                            <tr role="row" class="redirect-click" data-redirect="{{ route('fms-edit-package', $package->id) }}">
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$package->name}}</p>
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$package->created_at->diffForHumans()}}</p>
                                                </td>
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p><input data-package_id={{$package->id}} class="active" type="checkbox" data-init-plugin="switchery" @if($package->active) checked="checked" @endif onclick="status_change()"/></p>
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
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {
        let switchStatus = true;
        $(document).on('change', '.active', function(e) {
            let package_id = $(this).data('package_id');
            console.log(package_id);
            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }
            else {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }

            change_status(package_id, switchStatus);
        });

        function change_status(package_id, status){
            $.ajax({
                url: "/change_status_package",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "package_id": package_id,
                    "status": status,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    
                }
            });  
        }
    });

</script>

@endsection