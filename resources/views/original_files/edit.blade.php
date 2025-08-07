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
                    Edit Original File
                  </h5>
                
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('original-files')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Original Files</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($originalFile)){{route('update-original-file')}}@else{{ route('add-original-file') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($originalFile))
                  <input name="id" type="hidden" value="{{ $originalFile->id }}">
                @endif


                <div class="form-group form-group-default required ">
                  <label>Producer</label>
                  <input value="@if(isset($originalFile)){{$originalFile->Producer}}@else{{old('Producer') }}@endif"  name="Producer" type="text" class="form-control" required>
                </div>
                @error('Producer')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>Series</label>
                    <input value="@if(isset($originalFile)){{$originalFile->Series}}@else{{old('Series') }}@endif"  name="Series" type="text" class="form-control" required>
                </div>
                @error('Series')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>Model</label>
                    <input value="@if(isset($originalFile)){{$originalFile->Model}}@else{{old('Model') }}@endif"  name="Model" type="text" class="form-control" required>
                </div>
                @error('Model')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>Displacement</label>
                    <input value="@if(isset($originalFile)){{$originalFile->Displacement}}@else{{old('Displacement')}}@endif"  name="Displacement" type="text" class="form-control">
                </div>
                @error('Displacement')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>Output</label>
                    <input value="@if(isset($originalFile)){{$originalFile->Output}}@else{{old('Output') }}@endif"  name="Output" type="text" class="form-control" required>
                </div>
                @error('Output')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>Gear</label>
                    <input value="@if(isset($originalFile)){{$originalFile->Gear}}@else{{old('Gear') }}@endif"  name="Gear" type="text" class="form-control">
                </div>
                @error('Gear')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>ProducerECU</label>
                    <input value="@if(isset($originalFile)){{$originalFile->ProducerECU}}@else{{old('ProducerECU') }}@endif"  name="ProducerECU" type="text" class="form-control" required>
                </div>
                @error('ProducerECU')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>BuildECU</label>
                    <input value="@if(isset($originalFile)){{$originalFile->BuildECU}}@else{{old('BuildECU') }}@endif"  name="BuildECU" type="text" class="form-control">
                </div>
                @error('BuildECU')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>ECUNrProd</label>
                    <input value="@if(isset($originalFile)){{$originalFile->ECUNrProd}}@else{{old('ECUNrProd') }}@endif"  name="ECUNrProd" type="text" class="form-control" required>
                </div>
                @error('ECUNrProd')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>ECUNrECU</label>
                    <input value="@if(isset($originalFile)){{$originalFile->ECUNrECU}}@else{{old('ECUNrECU') }}@endif"  name="ECUNrECU" type="text" class="form-control" required>
                </div>
                @error('ECUNrECU')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>Software</label>
                    <input value="@if(isset($originalFile)){{$originalFile->Software}}@else{{old('Software') }}@endif"  name="Software" type="text" class="form-control" required>
                </div>
                @error('Software')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>SWVersion</label>
                    <input value="@if(isset($originalFile)){{$originalFile->SWVersion}}@else{{old('SWVersion') }}@endif"  name="SWVersion" type="text" class="form-control" required>
                </div>
                @error('SWVersion')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>ProjectSize</label>
                    <input value="@if(isset($originalFile)){{$originalFile->ProjectSize}}@else{{old('ProjectSize') }}@endif"  name="ProjectSize" type="text" class="form-control" required>
                </div>
                @error('ProjectSize')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group form-group-default required ">
                    <label>File</label>
                    <input value="@if(isset($originalFile)){{$originalFile->File}}@else{{old('File') }}@endif"  name="File" type="text" class="form-control" required>
                </div>
                @error('File')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror


                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($originalFile)) Update @else Add @endif</span></button>
                  @if(isset($originalFile))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$originalFile->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                  @endif
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
        
        $('.btn-delete').click(function() {
          Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                        url: "/delete_original_file",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "File has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/original_files';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection