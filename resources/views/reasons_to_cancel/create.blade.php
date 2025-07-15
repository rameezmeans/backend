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
                  @if(isset($engineer))
                  <h5>
                    Edit Reason To Reject
                  </h5>
                @else
                  <h5>
                    Add Reason To Reject
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('reasons-to-reject')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Reasons to reject</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($reason)){{route('update-reason-to-reject')}}@else{{ route('add-reason-to-cancel') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($reason))
                  <input name="id" type="hidden" value="{{ $reason->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Reason To Reject</label>
                  <input value="@if(isset($reason)) {{ $reason->reason_to_cancel }} @else{{old('reason_to_cancel') }}@endif"  name="reason_to_cancel" type="text" class="form-control" required>
                </div>
                @error('reason_to_cancel')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($reason)) Update @else Add @endif</span></button>
                  @if(isset($reason))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$reason->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
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
                        url: "/delete_reason_to_reject",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Reason has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/engineers';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection