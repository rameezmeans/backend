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
                    <h3>Processing Softwares</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('add-processing-softwares') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Processing Software</span>
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
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                    {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">External Source</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($processingSoftwares as $ps)
                                    <tr role="row" class="redirect-click" data-redirect="{{ route('edit-processing-softwares', $ps->id) }}">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$ps->name}}</p>
                                        </td>
                                        <td class="v-align-middle">
                                            <p><input data-ps_id={{$ps->id}} class="ps_active" type="checkbox" data-init-plugin="switchery" @if($ps->external_source) checked="checked" @endif onclick="status_change()"/></p>
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
            let ps_id = $(this).data('ps_id');
            console.log(ps_id);
            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }
            else {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }

            change_status(ps_id, switchStatus);
        });

        function change_status(ps_id, status){
            $.ajax({
                url: "/change_ps_external_source",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "ps_id": ps_id,
                    "external_source": status,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    
                }
            });  
        }
       
    });

</script>

@endsection