@extends('layouts.app')

@section('pagespecificstyles')

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
<style>
.redirect-click-file{
  cursor: pointer;
}

</style>
@endsection
@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                    <h3>Reports</h3>
                </div>
                {{-- <div class="pull-right" id="download">
                    <div class="col-xs-12">
                        <form method="POST" action="{{route('get-engineers-report')}}">
                            @csrf
                            <input id="engineer_field" name="engineer" value="all_engineers" type="hidden">
                            <input id="start_field" name="start" value="" type="hidden">
                            <input id="end_field" name="end" value="" type="hidden">
                            <button type="submit" class="btn btn-success btn-cons m-b-10"><i class="fa fa-download"></i> <span class="bold">Download PDF</span>
                            </button>
                        </form>
                    </div>
                </div> --}}
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                {{-- <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group form-group-default">
                            <label>Engineers</label>
                            <select class="full-width" id="engineers" data-init-plugin="select2" name="engineers">
                                <option value="all_engineers">All Engineers</option>
                            @foreach($engineers as $engineer)
                                <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-group-default input-group">
                            <div class="form-input-group">
                              <label>Start</label>
                              <input type="input" style="margin-bottom: 17px;" class="form-control datepicker" placeholder="Start Date" id="start">
                            </div>
                            <div class="input-group-append ">
                              <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                          </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-group-default input-group">
                            <div class="form-input-group">
                              <label>End</label>
                              <input type="input" style="margin-bottom: 17px;" class="form-control datepicker" placeholder="End Date" id="end">
                            </div>
                            <div class="input-group-append ">
                              <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                          </div>
                    </div>
                </div> --}}
               
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    {{-- <livewire:file-engineer-table
                        searchable="name"
                    /> --}}

                    <table class="table table-hover demo-table-search table-responsive-block data-table no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info" >

                      <thead>
              
                          <tr>
              
                              <th>Index</th>
                              {{-- <th>Frontend</th> 
                              <th>Vehicle</th>
                              <th>Stage</th>
                              <th>Options</th>
                              <th>Credits</th>
                              <th>Assigned to</th>
                              <th>Upload Date</th>
                              <th>Resposne Time</th> --}}
      
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
@endsection


@section('pagespecificscripts')

<script type="text/javascript">
    $(function () {

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
              url: "{{ route('engineers-reports-table') }}",
              type: 'POST',
              data:function (d) {

                d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
                d.frontend = $('#frontend').val();

              }
          },
          columns: [
            { data: 'DT_Row_Index', name: 'DT_Row_Index' , orderable: false, searchable: false},
              // {data: 'invoice_id', name: 'invoice_id'},
              // {data: 'frontend', name: 'frontend', orderable: false, searchable: false},
              // {data: 'country', name: 'country'},
              // {data: 'type', name: 'type'},
              // {
              //   data: 'created_at',
              //   type: 'num',
              //   render: {
              //       _: 'display',
              //       sort: 'timestamp'
              //   }
              // },
              // {data: 'created_time', name: 'created_time', orderable: false, searchable: false},
              // {data: 'customer', name: 'customer'},
              // {data: 'email', name: 'email'},
              // {data: 'group', name: 'group'},
              // {data: 'credits', name: 'credits'},
              // {data: 'price_payed', name: 'price_payed'},
              // {data: 'details', name: 'details', orderable: false, searchable: false},
              // {data: 'elorus', name: 'elorus', orderable: false, searchable: false},
              // {data: 'zohobooks', name: 'zohobooks', orderable: false, searchable: false},
              
          ]

      });

      $(".filter").click(function(){
        table.draw();
      });

      $('#frontend').change(function(){
        table.draw();
      });

});
</script>

@endsection