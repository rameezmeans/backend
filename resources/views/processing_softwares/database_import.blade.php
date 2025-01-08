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
                    <h3>Database Import</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('processing-softwares') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Processing Softwares</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group form-group-default">
                            <form method="POST" action="{{route('database-import')}}">
                                @csrf

                                <select name="selected_records" class=" full-width" data-init-plugin="select2">
                                    <option @if($selected == 'all') selected @endif value="all">All</option>
                                    <option  @if($selected == 'added_to_database') selected @endif value="added_to_database">Added To database</option>
                                    <option  @if($selected == 'not_added_to_database') selected @endif value="not_added_to_database">Not added To database</option>
                                  </select>
                                 
                                <button class="btn btn-success m-t-20" type="submit">Get External Sourced Softwares</button>
                            </form>
                        </div>
                    </div>
                </div>
                

                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Task</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Brand</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Model</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Version</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Engine</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">ECU</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Service ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Software ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Added In Database</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($softwaresAndBrandsRecords as $record)
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p><a target="_blank" href="{{route('file', $record->file_id)}}">{{'Task'.$record->file_id}}</a></p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$record->brand}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$record->model}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$record->version}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$record->engine}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$record->ecu}}</p>
                                        </td>
                                       
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{\App\Models\Service::findOrFail($record->service_id)->name}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{\App\Models\ProcessingSoftware::findOrFail($record->software_id)->name}}</p>
                                        </td>
                                        <td class="v-align-middle">
                                            <p><input data-brand="{{$record->brand}}" data-model="{{$record->model}}" data-version="{{$record->version}}" data-engine="{{$record->engine}}" data-ecu="{{$record->ecu}}" data-service_id={{$record->service_id}} data-software_id={{$record->software_id}} data-file_id={{$record->file_id}} class="ps_active" type="checkbox" data-init-plugin="switchery"  @if(added_to_db($record)) checked="checked" @endif onclick="status_change()"/></p>
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

        let switchStatus = true;

        $(document).on('change', '.ps_active', function(e) {
            
            let file_id = $(this).data('file_id');
            let brand = $(this).data('brand');
            let version = $(this).data('version');
            let model = $(this).data('model');
            let engine = $(this).data('engine');
            let ecu = $(this).data('ecu');
            let software_id = $(this).data('software_id');
            let service_id = $(this).data('service_id');

            console.log(file_id);

            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }
            else {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }

            change_status(file_id, brand, model, version, engine, ecu, software_id, service_id, switchStatus);
        });

    });

    function change_status(file_id, brand, model, version, engine, ecu, software_id, service_id, added_in_database){
            $.ajax({
                url: "/change_ps_external_source",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "file_id": file_id,
                    "brand": brand,
                    "version": version,
                    "model": model,
                    "version": version,
                    "engine": engine,
                    "ecu": ecu,
                    "software_id": software_id,
                    "service_id": service_id,
                    "added_in_database": added_in_database,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    
                }
            });  
       
        }

</script>

@endsection