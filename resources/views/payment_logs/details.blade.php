@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Details</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('all-payments')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">All Payments</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <div class="card card-transparent flex-row">

                
                

                <div class="row">

                  <div class="card-title"><h5>Backend</h5>
                  </div>

                  <div class="col-lg-12">
                    <div class="table-responsive table-invoice">
                      <table class="table">

                        <thead>
                          <tr>
                            <th class="">Payment</th>
                            <th class="">Payment Without Tax</th>
                            <th class="text-center">Tax</th>
                            <th class="text-center">User</th>
                            <th class="text-right">Group</th>
                            <th class="text-right">Group Tax (%)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="">
                              €{{$credit->price_payed}}
                            </td>
                            <td class="">
                              €{{$credit->price_without_tax}}
                            </td>
                            <td class="text-center">€{{$credit->tax}}</td>
                            <td class="text-center">{{$user->name}}</td>
                            <td class="text-right">{{$group->name}}</td>
                            <td class="text-right">{{$group->tax}}</td>
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                    
                  </div>

                  
                  @if($credit->payment)

                  <div class="card-title m-t-40"><h5>Payment</h5>
                  </div>

                  <div class="col-lg-12">
                    <div class="table-responsive table-invoice">
                      <table class="table">

                        <thead>
                          <tr>
                            <th class="">Payment Type</th>
                            <th class="">Payment ID</th>
                            <th class="">Payment Amount</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="">
                              {{$credit->type}}
                            </td>
                            <td class="">
                              
                              @if($credit->type == 'paypal')
                                {{$credit->payment->paypal_id}}
                              @else
                                {{$credit->payment->stripe_id}}
                              @endif

                            </td>
                            <td class="">
                              €{{$credit->payment->amount}}
                            </td>
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                    
                  </div>
<<<<<<< HEAD
                  <div class="tab-pane" id="tab3Inspire">
                    
                  </div>
                  <div class="tab-pane" id="tab4Inspire">
                    
                  </div>
=======

                  @endif

                  @if($credit->elorus)

                  <div class="card-title m-t-40"><h5>Elorus</h5>
                  </div>

                  <div class="col-lg-12">
                    <div class="table-responsive table-invoice">
                      <table class="table">
                        <thead>
                          <tr>
                            <th class="">Elorus ID</th>
                            <th class="">Amount</th>
                            <th class="">Tax</th>
                            <th class="">Invoice ID</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="">
                              {{$credit->elorus->elorus_id}}
                            </td>
                            <td class="">
                              
                              
                              €{{$credit->elorus->amount}}
                              

                            </td>
                            <td class="">
                              €{{$credit->elorus->tax}}
                            </td>
                            <td class="">
                              {{$credit->elorus->desc}}
                            </td>
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                    
                  </div>

                  @endif

                  @if($credit->zoho)

                  <div class="card-title m-t-40"><h5>Zoho</h5>
                  </div>

                  <div class="col-lg-12">
                    <div class="table-responsive table-invoice">
                      <table class="table">
                        <thead>
                          <tr>
                            <th class="">Zoho ID</th>
                            <th class="">Amount</th>
                            <th class="">Tax</th>
                            <th class="">Invoice ID</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="">
                              {{$credit->zoho->zoho_id}}
                            </td>
                            <td class="">
                              
                              
                              €{{$credit->zoho->amount+$credit->zoho->tax}}
                              

                            </td>
                            <td class="">
                              €{{$credit->zoho->tax}}
                            </td>
                            <td class="">
                              {{$credit->zoho->desc}}
                            </td>
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                    
                  </div>

                  @endif

                  </div>
                  
>>>>>>> 0c5e701 (new code)
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
        
      
    });

</script>

@endsection