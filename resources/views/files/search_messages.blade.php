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
                    <h3>Search Engineer and Client Messages</h3>
                </div>
                <div class="pull-right">
                
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              
                <form method="POST" action="{{route('get-search-results')}}">
                    <div class="row">
                        
                        @csrf
                        <div class="col-lg-10">
                            <div class="form-group form-group-default input-group">
                                <div class="form-input-group">
                                    <label>Keyword</label>
                                    <input type="input" style="margin-bottom: 17px;" class="form-control" placeholder="Put Your Text here" name="keyword">
                                </div>
                                
                                </div>
                            </div>
                        <div class="col-lg-2">
                            <input class="btn btn-success" type="submit" value="Search">
                        </div>
                        
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