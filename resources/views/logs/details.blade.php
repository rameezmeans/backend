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
                    <button data-redirect="{{route($logsUrl)}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">All Logs</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <div class="card card-transparent flex-row">

                
                

                <div class="row">

                  

                  <div class="col-lg-12">

                    <div class="card-title"><h5>Call</h5>
                    </div>
                    
                    <p>{{$record->call}}</p>

                    <br>

                    <div class="card-title m-t-40"><h5>Response</h5>
                    </div>

                    <p>{{$record->response}}</p>

                   
                    
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