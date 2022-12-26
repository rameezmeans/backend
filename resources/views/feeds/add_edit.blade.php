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
                    Edit News Feed
                  </h5>
                @else
                  <h5>
                    Add News Feed
                  </h5>
                  
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    {{-- <button data-redirect="{{route('feeds')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">News Feeds</span>
                    </button> --}}
                   
                      Current Time: <h5>{{$date}}</h5>
                    
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($feed)){{route('update-feed')}}@else{{ route('post-feed') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($feed))
                  <input name="id" type="hidden" value="{{ $feed->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Title</label>
                  <input value="@if(isset($feed)) {{ $feed->title }} @else{{old('title') }}@endif"  name="title" type="text" class="form-control" required>
                </div>
                @error('title')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Feed</label>
                  <input value="@if(isset($feed)) {{ $feed->feed }} @else{{old('feed') }}@endif"  name="feed" type="text" class="form-control" required>
                </div>
                @error('feed')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                

                {{-- <div class="form-group form-group-default required ">
                  <label>Activate At</label>
                  <input value="@if(isset($feed)) {{ $feed->activate_at }} @else{{old('activate_at') }}@endif"  name="activate_at" type="text" class="form-control timepicker" required>
                </div>
                @error('feed')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Deactivate At</label>
                  <input value="@if(isset($feed)) {{ $feed->feed }} @else{{old('feed') }}@endif"  name="feed" type="text" class="form-control" required>
                </div>
                @error('feed')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror --}}
                <div class="form-group form-group-default required ">
                  <label>Type</label>
                  <select class="full-width" data-init-plugin="select2" name="type">
                    <option @if(isset($feed) && $feed->type == 'danger') selected @endif value="danger">Danger</option>
                    <option  @if(isset($feed) && $feed->type == 'warning') selected @endif value="warning">Warning</option>
                    <option  @if(isset($feed) && $feed->language == 'good_news') selected @endif value="good_news">Good New</option>
                  </select>
                </div>
              
              @error('type')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
              <label>Activation Range</label>
                <div class="input-group m-b-10">
                 
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" name="dateTimeRange" id="daterangepicker" class="form-control" value="@if(isset($feed)){{ \Carbon\Carbon::parse($feed->activate_at)->format('d/m/Y H:i A') }}@else{{date('d/m/Y h:i A')}}@endif - @if(isset($feed)){{\Carbon\Carbon::parse($feed->deactivate_at)->format('d/m/Y H:i A')}}@else{{date('d/m/Y h:i A')}}@endif">
                </div>
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($feed)) Update @else Add @endif</span></button>
                  @if(isset($feed))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$feed->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
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

        $('#daterangepicker').daterangepicker({
            timePicker: true,
            timePickerIncrement: 5,
            format: 'DD/MM/YYYY h:mm A'
        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

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
                        url: "/delete_feed",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Feed has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/feeds';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection