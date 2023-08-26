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
                  <button data-redirect="{{route('edit-service', ['id' => $service->id])}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Back To Service</span>
                  </button>
                  
                  
                  <h5>
                    Add Service Price 
                  </h5>
               
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="{{route('add-subdealer-group-price')}}" enctype="multipart/form-data">
                @csrf
                
                <input id="service_id" name="service_id" type="hidden" value="{{ $service->id }}">
                
                {{-- <div class="form-group form-group-default required ">
                    <label>Subdealer Group</label>
                    <select class="full-width" data-init-plugin="select2" name="subdealer_own_group_id" id="subdealer_own_group_id">
                      @foreach($subdealerGroups as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                      @endforeach
                    </select>
                  </div>
                
                @error('group_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror --}}

                <div class="form-group form-group-default required ">
                  <label>Credits</label>
                  <input value=""  name="credits" id="credits" type="number" class="form-control" required>
                </div>
                @error('credits')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Set Price</span></button>
                  
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

            let subdealer_own_group_id = $("#subdealer_own_group_id").val();
            let service_id = $("#service_id").val();

            get_credits_from_subdealer_group_id_and_service_id(subdealer_own_group_id, service_id);

            function get_credits_from_subdealer_group_id_and_service_id(subdealer_own_group_id, service_id){
                $.ajax({
                        url: "/get_credits_from_service_group",
                        type: "POST",
                        data: {
                            service_id: service_id,
                            subdealer_own_group_id: subdealer_own_group_id,
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(credits) {
                            console.log(credits);
                            $("#credits").val(credits);
                        }
                    });
            }

      });

</script>

@endsection