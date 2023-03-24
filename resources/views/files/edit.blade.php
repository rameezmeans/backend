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
          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                  <h5>
                    Update File
                  </h5>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <a target="_blank" href="{{route('file', $file->id)}}" class="btn btn-success btn-cons m-b-10" type="button"><i class="pg-plus_circle"></i> <span class="bold">Back to File</span>
                    </a>
                    <a target="_blank" href="{{route('vehicle', $file->vehicle()->id)}}" class="btn btn-success btn-cons m-b-10"><i class="pg-plus_circle"></i> <span class="bold">Go to Vehicle</span>
                    </a>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <p class="text-danger">Note: If you want to add ECU to vehicle and file then please add ECU to vehicle and then move to this page.</p>

              <form class="form" role="form" method="POST" action="{{route('update-file-vehicle')}}">
                @csrf
                @if(isset($file))
                  <input name="id" type="hidden" value="{{ $file->id }}">
                @endif
                <div>
                  <label>Brand</label>
                    <div class="form-group">
                        <select disabled name="brand" id="brand" class="select full-width" data-init-plugin="select2">
                            @foreach ($brands as $brand)
                                <option @if($file->brand==$brand) selected @endif value="{{ $brand }}">{{$brand}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('brand')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                 <label>Model</label>
                    <div class="form-group">
                        <select disabled name="model" id="model" class="select full-width" data-init-plugin="select2">
                            @foreach ($models as $model)
                                <option @if($file->model==$model) selected @endif value="{{ $model }}">{{$model}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('model')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>
                <div>
                    <label>Version</label>
                       <div class="form-group">
                           <select name="version" id="version" class="select full-width" data-init-plugin="select2">
                               @foreach ($versions as $version)
                                   <option @if($file->version==$version) selected @endif value="{{ $version }}">{{$version}}</option>
                               @endforeach
                           </select>
                       </div>
                       @error('version')
                       <span class="text-danger" role="alert">
                           <strong>{{ $message }}</strong>
                       </span>
                   @enderror
                </div>
                <div>
                    <label>Engines</label>
                       <div class="form-group">
                           <select name="engine" id="engine" class="select full-width" data-init-plugin="select2">
                               @foreach ($engines as $engine)
                                   <option @if($file->engine==$engine) selected @endif value="{{ $engine }}">{{$engine}}</option>
                               @endforeach
                           </select>
                       </div>
                       @error('engine')
                       <span class="text-danger" role="alert">
                           <strong>{{ $message }}</strong>
                       </span>
                   @enderror
                </div>
                <div>
                    <label>ECUs</label>
                       <div class="form-group">
                           <select name="ecu" id="ecu" class="select full-width" data-init-plugin="select2">
                               @foreach ($ecus as $ecu)
                                   <option @if($file->ecu==$ecu) selected @endif value="{{ $ecu }}">{{$ecu}}</option>
                               @endforeach
                           </select>
                       </div>
                </div>
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
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

        $(document).on('change', '#brand', function(e) {
            let brand = $(this).val();
            disable_dropdowns();

            $.ajax({
                url: "/get_models",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'brand': brand
                },
                success: function(items) {
                    console.log(items);

                    $('#model').removeAttr('disabled');
                    $('#version').attr('disabled', 'disabled');
                    $('#tools').attr('disabled', 'disabled');
                    $('#engine').attr('disabled', 'disabled');

                    $.each(items.models, function(i, item) {
                        console.log(item.model);
                        $('#model').append($('<option>', {
                            value: item.model,
                            text: item.model
                        }));
                    });
                }
            });
        });

        $(document).on('change', '#model', function(e) {
            // disable_dropdowns();

            $('#version').children().remove();
            $('#version').append('<option selected id="version">Version</option>');
            $('#ecu').children().remove();
            $('#ecu').append('<option selected id="ecu">ECU</option>');
            $('#gear_box').children().remove();

            $('#version').attr('disabled', 'disabled');
            $('#ecu').attr('disabled', 'disabled');
            $('#gear_box').attr('disabled', 'disabled');

            let model = $(this).val();
            let brand = $('#brand').val();
            $.ajax({
                url: "/get_versions",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'model': model,
                    'brand': brand
                },
                success: function(items) {
                    console.log(items);
                    $('#model').removeAttr('disabled');
                    $('#version').removeAttr('disabled');
                    $('#tools').attr('disabled', 'disabled');
                    $('#gear_box').attr('disabled', 'disabled');
                    $.each(items.versions, function(i, item) {
                        console.log(item.generation);
                        $('#version').append($('<option>', {
                            value: item.generation,
                            text: item.generation
                        }));
                    });

                }
            });
        });

        $(document).on('change', '#version', function(e) {
            // disable_dropdowns();
            $('#engine').children().remove();
            $('#engine').append('<option selected value"engine" disabled>Engine</option>');


            // $('#model').attr('disabled', 'disabled');
            // $('#version').attr('disabled', 'disabled');
            $('#engine').attr('disabled', 'disabled');


            let version = $(this).val();
            let brand = $('#brand').val();
            let model = $('#model').val();

            $.ajax({
                url: "/get_engines",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'model': model,
                    'brand': brand,
                    'version': version,
                },
                success: function(items) {
                    $('#engine').removeAttr('disabled');

                    console.log(items.engines);

                    $.each(items.engines, function(i, item) {
                        $('#engine').append($('<option>', {
                            value: item.engine,
                            text: item.engine
                        }));
                    });
                }
            });
        });

        $(document).on('change', '#engine', function(e) {
            // disable_dropdowns();
            $('#ecu').children().remove();
            $('#ecu').append('<option selected value="ecu" disabled>ECU</option>');
            // $('#model').attr('disabled', 'disabled');
            // $('#version').attr('disabled', 'disabled');
            $('#ecu').attr('disabled', 'disabled');
            let engine = $(this).val();
            let brand = $('#brand').val();
            let model = $('#model').val();
            let version = $('#version').val();

            $.ajax({
                url: "/get_ecus",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'model': model,
                    'brand': brand,
                    'version': version,
                    'engine': engine,
                },
                success: function(items) {
                    console.log(items);
                    $('#ecu').removeAttr('disabled');
                    $('#gear_box').removeAttr('disabled');
                    $.each(items.ecus, function(i, item) {
                        $('#ecu').append($('<option>', {
                            value: item,
                            text: item
                        }));
                    });
                }
            });
        });

        function disable_dropdowns() {

            $('#model').children().remove();
            $('#model').append('<option selected id="model" disabled>Model</option>');

            $('#version').children().remove();
            $('#version').append('<option selected id="version" disabled>Version</option>');

            $('#ecu').children().remove();
            $('#ecu').append('<option selected id="ecu" disabled>ECU</option>');

            $('#engine').children().remove();
            $('#engine').append('<option selected id="engine" disabled>Engine</option>');


            $('#model').attr('disabled', 'disabled');
            $('#version').attr('disabled', 'disabled');
            $('#engine').attr('disabled', 'disabled');
            $('#ecu').attr('disabled', 'disabled');
        }
        
    });

</script>

@endsection