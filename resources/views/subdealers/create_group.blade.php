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
                  <button data-redirect="{{route('subdealer-groups')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Subdealers</span>
                  </button>
                  
                  @if(isset($subdealerGroup))
                  <h5>
                    Edit Subdealer Group
                  </h5>
                @else
                  <h5>
                    Add Subdealer
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($subdealerGroup)){{route('update-subdealer-group')}}@else{{ route('add-subdealer-group') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($subdealerGroup))
                  <input name="id" type="hidden" value="{{ $subdealerGroup->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($subdealerGroup)){{$subdealerGroup->name}}@else{{old('name')}}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                @if(isset($subdealerGroup))
                <div class="form-group form-group-default required ">
                  <label>Stripe Payment Account</label>
                  <select name="stripe_payment_account_id" class="full-width select2-hidden-accessible" data-placeholder="Select Type" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                    @foreach($stripeAccounts as $account)
                      <option @if(isset($stripePaymentAccount) && $stripePaymentAccount->id == $account->id) {{ 'selected' }} @endif value="{{$account->id}}">{{$account->name}}</option>
                    @endforeach
                  </select>
                </div>
                @error('stripe_payment_account_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Paypal Payment Account</label>
                  <select name="paypal_payment_account_id" class="full-width select2-hidden-accessible" data-placeholder="Select Type" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                    @foreach($paypalAccounts as $paypalaccount)
                      <option @if(isset($paypalPaymentAccount) && $paypalPaymentAccount->id == $paypalaccount->id) {{ 'selected' }} @endif value="{{$paypalaccount->id}}">{{$paypalaccount->name}}</option>
                    @endforeach
                  </select>
                </div>
                @error('stripe_payment_account_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                  <div class="form-group form-group-default required ">
                    <label>Tax</label>
                    <input value="@if(isset($subdealerGroup)){{$subdealerGroup->tax}}@else{{old('tax')}}@endif"  name="tax" type="text" min="0" class="form-control" required>
                  </div>
                  @error('tax')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                @endif
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($subdealerGroup)) Update @else Add @endif</span></button>
                  @if(isset($subdealerGroup))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$subdealerGroup->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                  @endif
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

        let url = "{{route('delete-subdealer-group')}}";
        
        $('.btn-delete').click(function() {
          Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Subdealer has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/subdealer_groups';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection