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
                    Edit Work Hour
                  </h5>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('work-hours')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Work Hours</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="{{route('update-work-hour')}}" enctype="multipart/form-data">
                @csrf
                
                  <input name="id" type="hidden" value="{{ $workHour->id }}">
                
                <div class="form-group form-group-default">
                  <label>Name</label>
                  <label>{{$workHour->name}}</label>
                </div>
                <div class="col-lg-6">
                    <label>Start Time:</label>
                    <div class="input-group bootstrap-timepicker">
                      <input name="start" id="timepicker" type="time" class="form-control" value="@if(isset($workHour)){{$workHour->start}}@endif">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <label>End Time:</label>
                    <div class="input-group bootstrap-timepicker">
                      <input name="end" id="timepicker" type="time" class="form-control" value="@if(isset($workHour)){{$workHour->end}}@endif">
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
        
        // $('.btn-delete').click(function() {

        //   Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, delete it!'
        //         }).then((result) => {
        //     if (result.isConfirmed) {
        //             $.ajax({
        //                 url: "/delete_engineer",
        //                 type: "POST",
        //                 data: {
        //                     id: $(this).data('id')
        //                 },
        //                 headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        //                 success: function(response) {
        //                     Swal.fire({
        //                         title: "Deleted!",
        //                         text: "Engineer has been deleted.",
        //                         type: "success",
        //                         timer: 3000
        //                     });

        //                     window.location.href = '/engineers';
        //                 }
        //             });            
        //         }
        //     });
        // });

    });

</script>

@endsection