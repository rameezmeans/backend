@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                    <h3>All Logs</h3>
                </div>
                <div class="pull-right">
                    <div class="col-xs-12">
                        <button data-redirect="{{ route('payment-and-customers') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Payments And Customers</span>
                        </button>
                        {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                    </div>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        {{-- <table class="table table-hover demo-table-search table-responsive-block data-table no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr>
                                    

                                    <th>DB ID</th>
                                    {{-- <th>Invoice ID</th> --}}
                                    {{-- <th>Date</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Customer Group</th>
                                    <th>Elorus Invoice Number</th>
                                    <th>Credits</th>
                                    <th>Price Payed (€)</th>
                                    <th>Elorus</th>
                                    <th>Zohobooks</th>
                                    <th>Trouble Email Sent</th> 
                                    
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table> --}}

                        <table class="table table-hover demo-table-search table-responsive-block data-table no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info" >

                            <thead>
                    
                                <tr>
                    
                                    <th>DB ID</th>
                                    {{-- <th>Invoice ID</th> --}}
                                    {{-- <th>Date</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Customer Group</th>
                                    <th>Elorus Invoice Number</th>
                                    <th>Credits</th>
                                    <th>Price Payed (€)</th>
                                    <th>Elorus</th>
                                    <th>Zohobooks</th>
                                    <th>Trouble Email Sent</th> --}}
            
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
        
        var table = $('.data-table').DataTable({

            processing: true,
            serverSide: true,
            
            ajax: {
                url: "{{ route('payment-logs-table') }}",
                type: 'POST',
                data:function (d) {

                }
            },
            columns: [
                {data: 'id', name: 'id'},
                // {data: 'invoice_id', name: 'invoice_id'},
                // {
                //     data: 'created_at',
                //     type: 'num',
                //     render: {
                //         _: 'display',
                //         sort: 'timestamp'
                //     }
                // },
                // {data: 'customer', name: 'customer'},
                // {data: 'email', name: 'email'},
                // {data: 'group', name: 'group'},
                // {data: 'elorus_invoice_id', name: 'elorus_invoice_id'},
                // {data: 'credits', name: 'credits'},
                // {data: 'price_payed', name: 'price_payed'},
                // {data: 'elorus', name: 'elorus'},
                // {data: 'zohobooks', name: 'zohobooks'},
                // {data: 'email_sent', name: 'email_sent'},
                
                
            ]

        });
    });

</script>

@endsection