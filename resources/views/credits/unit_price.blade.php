@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          @if (Session::get('success'))
                <div class="pgn-wrapper" data-position="top" style="top: 59px;">
                    <div class="pgn push-on-sidebar-open pgn-bar">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{ Session::get('success') }}
                        </div>
                    </div>
                </div>
            @endif
            @php
              Session::forget('success')
            @endphp
            <!-- START card -->

            <ul class="nav nav-tabs nav-tabs-fillup m-t-40" data-init-reponsive-tabs="dropdownfx">
             
              <li class="nav-item">
                <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>Service Credit Price</span></a>
              </li>
              {{-- <li class="nav-item">
                <a href="#" data-toggle="tab" data-target="#slide3"><span>Online File Search Status</span></a>
              </li> --}}
              <li class="nav-item">
                <a href="#" data-toggle="tab" data-target="#slide2"><span>EVC Credit Price</span></a>
              </li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane slide-left active" id="slide1">

                <div class="card card-transparent m-t-20">
                  <div class="card-header ">
                      <div class="card-title">
                       
                        <h5>
                          Unit Credit Price For ECUTech
                        </h5>
                      
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">
                    <form class="" role="form" method="POST" action="{{route('update-price-ecutech')}}" enctype="multipart/form-data">
                      @csrf
                     
                      <div class="form-group form-group-default required ">
                        <label>Price in Euros</label>
                        <input value="@if(isset($creditPriceECUTech)){{ $creditPriceECUTech->value }}@endif"  name="credit_price" type="text" class="form-control" required>
                      </div>
                      @error('credit_price')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror

                      <div class="form-group form-group-default required ">
                        <label>Zoho Item ID For ECU Tech</label>
                        <input value="@if(isset($creditPriceECUTech)){{ $creditPriceECUTech->zoho_item_id }}@endif"  name="zoho_item_id" type="text" class="form-control" required>
                      </div>
                      @error('zoho_item_id')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror

                      <div class="text-center m-t-20">                    
                        <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="card card-transparent m-t-20">
                  <div class="card-header ">
                      <div class="card-title">
                       
                        <h5>
                          Unit Credit Price For TuningX
                        </h5>
                      
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">
                    <form class="" role="form" method="POST" action="{{route('update-price-tuningx')}}" enctype="multipart/form-data">
                      @csrf
                     
                      <div class="form-group form-group-default required ">
                        <label>Price in Euros</label>
                        <input value="@if(isset($creditPriceTuningX)){{ $creditPriceTuningX->value }}@endif"  name="credit_price" type="text" class="form-control" required>
                      </div>
                      @error('credit_price')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror

                      <div class="form-group form-group-default required ">
                        <label>Zoho Item ID For TuningX</label>
                        <input value="@if(isset($creditPriceTuningX)){{ $creditPriceTuningX->zoho_item_id }}@endif"  name="zoho_item_id" type="text" class="form-control" required>
                      </div>
                      @error('zoho_item_id')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror

                      <div class="text-center m-t-20">                    
                        <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="card card-transparent m-t-20">
                  <div class="card-header ">
                      <div class="card-title">
                       
                        <h5>
                          Unit Credit Price For EFT
                        </h5>
                      
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">
                    <form class="" role="form" method="POST" action="{{route('update-price-efiles')}}" enctype="multipart/form-data">
                      @csrf
                     
                      <div class="form-group form-group-default required ">
                        <label>Price in Euros</label>
                        <input value="@if(isset($creditPriceEfiles)){{ $creditPriceEfiles->value }}@endif"  name="credit_price" type="text" class="form-control" required>
                      </div>
                      @error('credit_price')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror

                      <div class="form-group form-group-default required ">
                        <label>Zoho Item ID For EFT</label>
                        <input value="@if(isset($creditPriceEfiles)){{ $creditPriceEfiles->zoho_item_id }}@endif"  name="zoho_item_id" type="text" class="form-control" required>
                      </div>
                      @error('zoho_item_id')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror

                      <div class="text-center m-t-20">                    
                        <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                      </div>
                    </form>
                  </div>
                </div>

              </div>
              {{-- <div class="tab-pane slide-left" id="slide3">
              
                <div class="card card-transparent m-t-20">
                  <div class="card-header ">
                      <div class="card-title">
                       
                        <h5>
                          Online File Search Status
                        </h5>
                      
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">

                    
                      <p>ECUTech Online File Search Status: <input data-frontend_id="1" class="online_search_active" type="checkbox" data-init-plugin="switchery" @if($ecutechOnlineStatus) checked="checked" @endif/></p>
                    
                      <p>TuningX Online File Search Status:<input data-frontend_id="2" class="online_search_active" type="checkbox" data-init-plugin="switchery" @if($tuningXOnlineStatus) checked="checked" @endif/></p>
                    
                      <p>ETF Online File Search Status:<input data-frontend_id="3" class="online_search_active" type="checkbox" data-init-plugin="switchery" @if($etfOnlineStatus) checked="checked" @endif/></p>
                   

                  </div>
                </div>
              </div> --}}

              <div class="tab-pane slide-left" id="slide2">
              
                <div class="card card-transparent m-t-20">
                  <div class="card-header ">
                      <div class="card-title">
                       
                        <h5>
                          EVC Credit Price
                        </h5>
                      
                      </div>
                      
                      <div class="clearfix"></div>
                  </div>
                  <div class="card-body">
                    <form class="" role="form" method="POST" action="{{route('update-price-ecutech')}}" enctype="multipart/form-data">
                      @csrf
                     
                      <div class="form-group form-group-default required ">
                        <label>Price in Euros</label>
                        <input value="@if(isset($evcCreditPrice)){{ $evcCreditPrice->value }}@endif"  name="evc_credit_price" type="text" class="form-control" required>
                      </div>
                      @error('credit_price')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                      <div class="text-center m-t-20">                    
                        <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                        
                      </div>
                    </form>
                      
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

        $(document).on('change', '.online_search_active', function(e) {

            let frontend_id = $(this).data('frontend_id');
            console.log(frontend_id);

            if ($(this).is(':checked')) {
                status = $(this).is(':checked');
                console.log(status);
            }
            else {
                status = $(this).is(':checked');
                console.log(status);
            }

            $.ajax({
                url: "/change_online_search_status",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "frontend_id": frontend_id,
                    "status": status,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    console.log(response);
                }
            });

        });
       
      });

</script>

@endsection