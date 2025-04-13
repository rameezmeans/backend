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
        <div class=" container-fluid bg-white" style="width: 200%;">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                    <h3>All Payments</h3>
                    <div style="margin: 20px 0px;">

                      <strong>Payment Date Filter:</strong>
              
                      <input type="text" name="daterange" value="" />
              
                      <button class="btn btn-success filter">Filter</button>
              
                  </div>
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
            </div>
            <div class="card-body">

              <table class="table table-bordered data-table" >

                <thead>
        
                    <tr>
        
                        <th>Payment ID</th>
                        <th>Invoice ID</th>
                        <th>Frontend</th>
                        <th>Country</th>
                        <th>Type</th>
                        <th>Payment Date</th>
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

    });

</script>

@endsection