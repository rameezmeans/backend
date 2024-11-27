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
                <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-white" id="tab-3">
                  <li class="nav-item">
                    <a href="#" class="active" data-toggle="tab" data-target="#tab3hellowWorld">One</a>
                  </li>
                  <li class="nav-item">
                    <a href="#" data-toggle="tab" data-target="#tab3FollowUs">Two</a>
                  </li>
                  <li class="nav-item">
                    <a href="#" data-toggle="tab" data-target="#tab3Inspire">Three</a>
                    <a href="#" data-toggle="tab" data-target="#tab4Inspire">Three</a>
                  </li>
                </ul>
                <div class="tab-content bg-white">
                  <div class="tab-pane" id="tab3hellowWorld">

                    <div class="table-responsive table-invoice">
                      <table class="table m-t-20">
                        <thead>
                          <tr>
                            <th class="">Payment</th>
                            <th class="text-center">Tax</th>
                            <th class="text-center">User</th>
                            <th class="text-right">Group</th>
                            <th class="text-right">Group Tax (%)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="">
                              €{{$credit->price_without_tax}}
                            </td>
                            <td class="text-center">€{{$credit->tax}}</td>
                            <td class="text-center">{{$user}}</td>
                            <td class="text-right">{{$group}}</td>
                            <td class="text-right">{{$group->tax}}</td>
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                    
                  </div>
                  <div class="tab-pane active" id="tab3FollowUs">
                    
                  </div>
                  <div class="tab-pane" id="tab3Inspire">
                    
                  </div>
                  <div class="tab-pane" id="tab4Inspire">
                    
                  </div>
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