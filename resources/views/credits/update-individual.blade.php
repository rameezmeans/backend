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
                  @if(isset($engineer))
                  <h5>
                    Edit Engineer
                  </h5>
                @else
                  <h5>
                    Add Engineer
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('edit-credit', $credit->user_id)}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Customer Page</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="{{route('set-credit-information')}}" enctype="multipart/form-data">
                @csrf
                
                <input name="id" type="hidden" value="{{ $credit->id }}">
                
                <div class="form-group form-group-default required ">
                  <label>Credits</label>
                  <input value="{{$credit->credits}}"  name="credits" type="text" class="form-control" required>
                </div>
                @error('credits')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Price Payed</label>
                  <input value="{{$credit->price_payed}}"  name="price_payed" type="text" class="form-control" required>
                </div>
                @error('price_payed')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                </div>
              </form>
                
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