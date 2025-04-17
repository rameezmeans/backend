@extends('layouts.app')

@section('pagespecificstyles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
  
  .flex {
    display: flex !important;
    width: max-content;
  }

  .redirect-click-file{
    cursor: pointer;
  }

  [x-cloak] {
    display: none;
}

.dark\:text-white  {
  color: black !important;
}
  
</style>

{{-- @vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@livewireScripts
@stack('scripts') --}}

@endsection



@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                    <h3>All Payments</h3>
                </div>
                
                <div class="pull-right">
                    <div class="col-xs-12">
                        <button data-redirect="{{ route('payment-and-customers') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Payments And Customers</span>
                        </button>
                        <button data-redirect="{{ route('all-payments-admin') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">All Admin Entries</span>
                        </button>
                        {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="card-group horizontal" id="accordion" role="tablist" aria-multiselectable="true">
                  <div class="card card-default m-b-0">
                    <div class="card-header " role="tab" id="headingOne">
                      <h4 class="card-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           Data Filters
                          </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="collapse show" role="tabcard" aria-labelledby="headingOne">
                      <div class="card-body">
                        <div class="row m-t-20 m-b-20">
                          <div class="col-md-6">
        
                        <div class="form-group" style="display: inline-flex;margin-top:20px;">
        
                          <label>Payment Date Filter:</label>
                  
                          <input class="form-control" type="text" name="daterange" value="" />
                  
                          <button class="btn btn-success filter m-l-5">Filter</button>
                  
                        </div>
                      </div>
        
                      <div class="col-md-6">
                        <div class="form-group form-group-default-select2">
        
                          <label>Frontend Filter:</label>
                      
                              <select class="form-control" id="frontend">
                                <option value="all">ALL</option>
                                <option value="1">ECUTech</option>
                                <option value="2">TuningX</option>
                                <option value="3">Efiles</option>
                              </select>
        
                        </div>
                      </div>
        
                      </div>
                      </div>
                    </div>
                  </div>
                  
                  
                </div>

              

                


            
            <div class="card-body">

              <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer">
                <div>

              <table class="table table-hover demo-table-search table-responsive-block data-table no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info" >

                <thead>
        
                    <tr>
        
                        <th>Payment ID</th>
                        <th>Invoice ID</th>
                        <th>Frontend</th>
                        <th>Country</th>
                        <th>Type</th>
                        <th>Payment Date</th>
                        <th>Payment Time</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Group</th>
                        <th>Credits</th>
                        <th>Price Payed (€)</th>
                        <th>Details</th>
                        <th>Elorus</th>
                        <th>Zohobooks</th>

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
              url: "{{ route('payment-table') }}",
              type: 'POST',
              data:function (d) {

                d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
                d.frontend = $('#frontend').val();

              }
          },
          columns: [
              {data: 'id', name: 'id'},
              {data: 'invoice_id', name: 'invoice_id'},
              {data: 'frontend', name: 'frontend', orderable: false, searchable: false},
              {data: 'country', name: 'country'},
              {data: 'type', name: 'type'},
              {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }
              },
              {data: 'created_time', name: 'created_time', orderable: false, searchable: false},
              {data: 'customer', name: 'customer'},
              {data: 'email', name: 'email'},
              {data: 'group', name: 'group'},
              {data: 'credits', name: 'credits'},
              {data: 'price_payed', name: 'price_payed'},
              {data: 'details', name: 'details', orderable: false, searchable: false},
              {data: 'elorus', name: 'elorus', orderable: false, searchable: false},
              {data: 'zohobooks', name: 'zohobooks', orderable: false, searchable: false},
              
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