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
                  @if(isset($number))
                  <h5>
                    Edit ECU Number
                  </h5>
                @else
                  <h5>
                    Add ECU Number
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('numbers')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">numbers</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($number)){{route('update-number')}}@else{{ route('add-number') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($number))
                  <input name="id" type="hidden" value="{{ $number->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Manufacturer Number</label>
                  <input value="@if(isset($number)) {{ $number->manufacturer_number }} @else{{old('manufacturer_number') }}@endif"  name="manufacturer_number" type="text" class="form-control" required>
                </div>
                @error('manufacturer_number')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                    <label>ECU</label>
                    <input value="@if(isset($number)) {{ $number->ecu }} @else{{old('ecu') }}@endif"  name="ecu" type="text" class="form-control" required>
                  </div>
                  @error('ecu')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default required ">
                    <label>ECU Brand</label>
                    <input value="@if(isset($number)) {{ $number->ecu_brand }} @else{{old('ecu_brand') }}@endif"  name="ecu_brand" type="text" class="form-control" required>
                  </div>
                  @error('ecu_brand')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($number)) Update @else Add @endif</span></button>
                  @if(isset($number))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$number->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
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
                        url: "/delete_bosch_number",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "ECU Number has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/bosch_numbers';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection