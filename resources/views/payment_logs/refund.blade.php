@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Refund Payment</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    @if(isset($credit))
                        <hr>
                        <h5 class="m-t-30">Refund Credit</h5>
                        <form method="POST" action="{{ route('credit.refund') }}">
                            @csrf
                            <input type="hidden" name="credit_id" value="{{ $credit->id }}">
                            <div class="form-group form-group-default required">
                                <label>Amount to Refund</label>
                                <input type="number" class="form-control" name="amount" required value="{{ $credit->price_payed }}" min="1">
                            </div>
                            <div class="text-center">
                                <button class="btn btn-warning btn-cons m-b-10" type="submit">
                                    <i class="pg-refresh"></i> <span class="bold">Refund</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

    

</script>

@endsection