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
                
                  <h5>
                    Engineer Assignment
                  </h5>
                
                </div>
                
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              
                <form class="" role="form" method="POST" action="{{route('tasks-rules-set')}}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($engineer))
                      
                    @endif
                    <div class="form-group form-group-default required ">
                      <label>Stage Tasks Assigned to</label>
                      <select class="form-control" id="stage_engineer" name="stage_engineer">
                          
                        @foreach ($allEngineers as $engineer)
                          <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                        @endforeach

                    </select>
                    </div>

                    <div class="form-group form-group-default required ">
                        <label>Options Tasks Assigned to</label>
                        <select class="form-control" id="options_engineer" name="options_engineer">
                            
                          @foreach ($allEngineers as $engineer)
                            <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                          @endforeach
  
                      </select>
                      </div>

                      <div class="form-group form-group-default required ">
                        <label>Stages and Options Tasks Assigned to</label>
                        <select class="form-control" id="stages_options_engineer" name="stages_options_engineer">
                            
                          @foreach ($allEngineers as $engineer)
                            <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                          @endforeach
  
                      </select>
                      </div>
                    
                    <div class="text-center m-t-40">                    
                      <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($engineer)) Update @else Add @endif</span></button>
                      
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