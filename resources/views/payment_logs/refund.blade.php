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
                    
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                @if(isset($credit))
                        <hr>
                        <h5 class="m-t-30">Refund Credit</h5>

                        <form class="" role="form" method="POST" action="{{route('credit.refund')}}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($credit))
                        <input name="credit_id" type="hidden" value="{{ $credit->id }}">
                        @endif
                        <div class="form-group form-group-default required ">
                        <label>Amount</label>
                        <input value="@if(isset($credit)) {{ $credit->price_payed }} @else{{old('price_payed') }}@endif"  name="amount" type="text" class="form-control" required>
                        </div>
                        @error('price_payed')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="text-center m-t-40">                    
                        <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Refund</span></button>
                        
                        </div>
                    </form>
                    @endif
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