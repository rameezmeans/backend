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
                  
                  <h2>
                    Add Brand ECU Options Comments
                  </h2>
                
                </div>
                
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                {{-- @foreach($brands as $brand)
                            @php 
                                dd($brand->brand);
                            @endphp
                            @endforeach --}}
              <form class="" role="form" method="POST" action="{{route('set-options-comments')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                <div class="col-lg-3">
                    <div class="form-group form-group-default">
                        <label>Select Brand</label>
                        <select class="full-width" id="brand" data-init-plugin="select2" name="brand">
                        @foreach($brands as $brand)
                            
                            <option value="{{$brand->brand}}">{{$brand->brand}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-group-default">
                        <label>Select ECU</label>
                        <select class="full-width" id="ecu" data-init-plugin="select2" name="ecu">
                        
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-group-default">
                        <label>Select Service</label>
                        <select class="full-width" id="frontend" data-init-plugin="select2" name="service">
                        @foreach($services as $service)
                            <option value="{{$service->label}}">{{$service->label}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-group-default">
                        <label>Select Software</label>
                        <select class="full-width" id="frontend" data-init-plugin="select2" name="software">
                        @foreach($softwares as $software)
                            <option value="{{$software->name}}">{{$software->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group form-group-default">
                        <label>Comment</label>
                        <textarea class="full-width" name="comment"></textarea>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group form-group-default">
                        <label>Result</label>
                        <textarea class="full-width" name="result"></textarea>
                    </div>
                </div>

                <div class="col-lg-2">
                    <input class="btn btn-success" type="submit" value="Add">
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

$(document).ready(function(e){

    console.log('here we are');

$(document).on('change', '#brand', function(e){

    console.log(e);

    let brand = $(this).val();
    
    $.ajax({
                url:'{{route('get-comments-ecus')}}',
                type: "POST",
                data: {
                    brand: brand
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {

                    
                    $('#ecu').html(res.html);
                    
                

                }
            });

    

    });
});

</script>

@endsection