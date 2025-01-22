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
                    <h3>All Payments</h3>
                </div>
                <div class="pull-right">
                    <div class="col-xs-12">
                        <button data-redirect="{{ route('payment-and-customers') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Payments And Customers</span>
                        </button>
                        {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                <livewire:payment-logs 
                    searchable="id"
                />

            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {
        
       
    });

</script>

@endsection