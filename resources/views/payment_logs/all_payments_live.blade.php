@extends('layouts.app')

@section('pagespecificstyles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
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

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@livewireScripts
@stack('scripts')

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
            </div>
            <div class="card-body">

              <table class="table table-bordered data-table" >

                <thead>
        
                    <tr>
        
                        <th>Payment ID</th>
        
                        <th>Invoice IC</th>
        
                        
        
        
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

      var table = $('.data-table').DataTable({

          processing: true,

          serverSide: true,

          ajax: "{{ route('payment-table') }}",

          columns: [

              {data: 'id', name: 'Payment ID'},
              {data: 'invoice_id', name: 'Invoice ID'},

          ]

      });

    });

</script>

@endsection