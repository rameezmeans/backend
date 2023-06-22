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
                  @if(isset($account))
                  <h5>
                    Edit Payment Account
                  </h5>
                @else
                  <h5>
                    Add Payment Account
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('payment-accounts')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Payment Accounts</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <div class="row">
              <div class="col-xl-9">
              <form class="" role="form" method="POST" action="@if(isset($account)){{route('update-account')}}@else{{ route('add-account') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($account))
                  <input name="id" type="hidden" value="{{ $account->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($account)){{ $account->name }}@else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>Key</label>
                    <input value="@if(isset($account)){{ $account->key }}@else{{old('key')}}@endif"  name="key" type="text" class="form-control" required>
                  </div>
                  @error('key')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default required ">
                    <label>Secret</label>
                    <input value="@if(isset($account)){{ $account->secret }}@else{{old('secret') }}@endif"  name="secret" type="text" class="form-control" required>
                  </div>
                  @error('secret')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default ">
                    <label>Sender's name</label>
                    <input value="@if(isset($account)){{ $account->senders_name }}@else{{old('senders_name') }}@endif"  name="senders_name" type="text" class="form-control">
                  </div>
                  @error('senders_name')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default ">
                    <label>Sender's Phone Number</label>
                    <input value="@if(isset($account)){{ $account->senders_phone_number }}@else{{old('senders_phone_number') }}@endif"  name="senders_phone_number" type="text" class="form-control">
                  </div>
                  @error('senders_phone_number')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default ">
                    <label>Sender's Address</label>
                    <input value="@if(isset($account)){{ $account->senders_address }}@else{{old('senders_address') }}@endif"  name="senders_address" type="text" class="form-control">
                  </div>
                  @error('senders_address')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default ">
                    <label>Invoice Prefix</label>
                    <input value="@if(isset($account)){{ $account->prefix }}@else{{old('prefix') }}@endif"  name="prefix" type="text" class="form-control">
                  </div>
                  @error('prefix')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default ">
                    <label>Note</label>
                    <input value="@if(isset($account)){{ $account->note }}@else{{old('note') }}@endif"  name="note" type="text" class="form-control">
                  </div>
                  @error('note')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                  <div class="form-group form-group-default ">
                    <label>Company's Logo</label>
                    <input value="@if(isset($account)){{ $account->companys_logo }}@else{{old('companys_logo') }}@endif"  name="companys_logo" type="file" class="form-control">
                  </div>

                 
                  <div class="checkbox check-success m-t-20">
                    <input type="checkbox" name="elorus" @if(isset($account) && $account->elorus) checked="checked" @endif @if(isset($account) && $account->elorus) value={{$account->elorus}} @endif id="checkbox2">
                    <label for="checkbox2">Send Invoices to Elorus</label>
                  </div>
                 

                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($account)) Update @else Add @endif</span></button>
                  @if(isset($account))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$account->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                  @endif
                </div>
              </form>
              </div>
              <div class="col-lg-3">
                @if(isset($account))
                  <div class="card social-card share  col1" >
                    <div class="card-header ">
                      <h5 class="text-black pull-left">Logo Preview</h5>
                    </div>
                    <div class="card-description">
                        <img src="{{ url('company_logos').'/'.$account->companys_logo }}" alt="Stage 0">
                    </div>
                  </div>
                @endif
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
                        url: "/delete_payment_account",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Account has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/payment_accounts';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection