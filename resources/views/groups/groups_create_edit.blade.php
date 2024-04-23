@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Groups</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('groups') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Groups</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($group)){{route('update-group')}}@else{{ route('add-group') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($group))
                  <input name="id" type="hidden" value="{{ $group->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($group)){{ $group->name }}@else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Tax</label>
                  <input value="@if(isset($group)){{$group->tax}}@else{{old('tax')}}@endif"  name="tax" type="text" min="0" class="form-control" required>
                </div>
                @error('tax')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Discount</label>
                  <input value="@if(isset($group)){{$group->discount}}@else{{old('discount')}}@endif" name="discount" min="0" type="text" class="form-control" required>
                </div>
                @error('credits')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Raise</label>
                  <input value="@if(isset($group)){{$group->raise}}@else{{old('raise')}}@endif" name="raise" min="0" type="text" class="form-control" required>
                </div>
                @error('raise')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Bonus Credits</label>
                  <input value="@if(isset($group)){{$group->bonus_credits}}@else{{old('bonus_credits')}}@endif" name="bonus_credits" min="0" type="number" class="form-control" required>
                </div>
                @error('bonus_credits')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                @if(isset($group))
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
                      @foreach($paypalAccounts as $account)
                        <option @if(isset($paypalPaymentAccount) && $paypalPaymentAccount->id == $account->id) {{ 'selected' }} @endif value="{{$account->id}}">{{$account->name}}</option>
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
                    <select name="viva_payment_account_id" class="full-width select2-hidden-accessible" data-placeholder="Select Type" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                      @foreach($vivaAccounts as $account)
                        <option @if(isset($vivaPaymentAccount) && $vivaPaymentAccount->id == $account->id) {{ 'selected' }} @endif value="{{$account->id}}">{{$account->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  @error('viva_payment_account_id')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror


                  <div class="form-group form-group-default required ">
                    <label>Elorus Template ID</label>
                    <input value="@if(isset($group)){{$group->elorus_template_id}}@else{{old('elorus_template_id')}}@endif" name="elorus_template_id" min="0" type="text" class="form-control">
                  </div>
                  @error('elorus_template_id')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                
                  <span class="text-danger">We must not leave this empty at all. This will mess up financial records on Elorus. </span>
                <div class="form-group form-group-default required ">
                  <label>Elorus Tax ID</label>
                  
                  <input value="@if(isset($group)){{$group->elorus_tax_id}}@else{{old('elorus_tax_id')}}@endif" name="elorus_tax_id" min="0" type="text" class="form-control">
                </div>
                @error('elorus_tax_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                  <label>Zohobooks Tax ID</label>
                  
                  <input value="@if(isset($group)){{$group->zohobooks_tax_id}}@else{{old('zohobooks_tax_id')}}@endif" name="zohobooks_tax_id" min="0" type="text" class="form-control">
                </div>
                @error('zohobooks_tax_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
              @endif

                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($group)) Update @else Add @endif</span></button>
                  @if(isset($group))
                    {{-- <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$group->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button> --}}
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
                        url: "/delete_group",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your row has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/groups';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection
