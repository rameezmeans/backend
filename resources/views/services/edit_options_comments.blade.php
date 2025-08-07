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
                    Edit Option Comment
                  </h5>
                
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    {{-- <button data-redirect="{{route('engineers')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Engineers</span>
                    </button> --}}
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="{{route('update-option-comment')}}" enctype="multipart/form-data">
                @csrf
                @if(isset($record))
                  <input name="id" type="hidden" value="{{ $record->id }}">
                @endif
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group form-group-default">
                            <label>Select Brand</label>
                            <select class="full-width" id="brand" data-init-plugin="select2" name="brand">
                            @foreach($allBrands as $brand)
                                
                                <option @if($brand->brand == $record->brand) selected @endif value="{{$brand->brand}}">{{$brand->brand}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group form-group-default">
                            <label>Select ECU</label>
                            <select class="full-width" id="ecu" data-init-plugin="select2" name="ecu">
                                @foreach($allEcus as $ecu)
                                
                                <option @if($ecu->ecu == $record->ecu) selected @endif value="{{$ecu->ecu}}">{{$ecu->ecu}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group form-group-default">
                            <label>Select Service</label>
                            <select class="full-width" id="frontend" data-init-plugin="select2" name="service">
                            @foreach($services as $service)
                                <option @if($service->label == $record->service_label) selected @endif value="{{$service->label}}">{{$service->label}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group form-group-default">
                            <label>Select Software</label>
                            <select class="full-width" id="frontend" data-init-plugin="select2" name="software">
                            @foreach($softwares as $software)
                                <option  @if($software->name == $record->software) selected @endif  value="{{$software->name}}">{{$software->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
    
                    <div class="col-lg-6">
                        <div class="form-group form-group-default">
                            <label>Comment</label>
                            <textarea class="full-width" name="comment">{{$record->comments}}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group form-group-default">
                            <label>Result</label>
                            <textarea class="full-width" name="result">{{$record->results}}</textarea>
                        </div>
                    </div>
    
                    <div class="col-lg-2">
                        <input class="btn btn-success" type="submit" value="Edit">
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
        
        $(document).on('change', '#brand', function(e){

$('#ecu').html('');

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