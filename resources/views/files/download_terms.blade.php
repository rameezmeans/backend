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
                    <h3>Download Terms Docs</h3>
                </div>
                <div class="clearfix"></div>
                <div class="row m-t-20 m-b-20">
                    <div class="col-md-6">
    
                        <div class="form-group" style="display: inline-flex;margin-top:20px;">
    
                        <label>Creation Date Filter:</label>
                
                        <input class="form-control" type="text" name="daterange" value="" />
                
                        <button class="btn btn-success filter m-l-5">Filter</button>
                
                    </div>
                    </div>
                </div>

                <div class="pull-right">
                <div class="col-xs-12">
                    
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table table-hover demo-table-search data-table table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Select</th> --}}
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Task ID</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created Time</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                
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
        
    $('input[name="daterange"]').daterangepicker({
        startDate: moment().subtract(36, 'M'),
        endDate: moment()
    });

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });


    var table = $('.data-table').DataTable({

        processing: true,
        serverSide: true,
        order: [[0,'desc']],
        ajax: {
            url: "{{ route('download-terms-table') }}",
            type: 'POST',
            data:function (d) {

            d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
            d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
            

            }
        },
        columns: [

            {data: 'task_id', name: 'task_id'},
            {
            data: 'created_at',
            type: 'num',
            render: {
                _: 'display',
                sort: 'timestamp'
            }
            },
            {data: 'created_time', name: 'created_time', orderable: false, searchable: false},
            {data: 'download', name: 'download', orderable: false, searchable: false},
            
        ]

    });

    $(".filter").click(function(){
        table.draw();
    });
       
});

</script>

@endsection