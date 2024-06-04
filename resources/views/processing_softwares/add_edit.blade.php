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
                  @if(isset($processingSoftware))
                  <h5>
                    Edit Processing Software
                  </h5>
                @else
                  <h5>
                    Add Processing Software
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('processing-softwares')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Processing Softwres</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($processingSoftware)){{route('update-processing-softwares')}}@else{{ route('create-processing-softwares') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($processingSoftware))
                  <input name="id" type="hidden" value="{{ $processingSoftware->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($processingSoftware)){{$processingSoftware->name}}@else{{old('name') }}@endif" name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($processingSoftware)) Update @else Add @endif</span></button>
                  @if(isset($processingSoftware))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$processingSoftware->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                  @endif
                </div>
              </form>
                
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">

           <table class="table table-hover" id="basicTable">
            <thead>
              <tr>
                
                
                <th style="width:20%">Files</th>
                <th style="width:20%">Revisions</th>
                
              </tr>
            </thead>
            <tbody>
              @foreach($processingSoftware->files as $file)
             

              <tr>
                
                <td class="v-align-middle ">
                  <p>{{  $file->file_id }}</p>
                </td>
                <td class="v-align-middle">
                  <p>{{  }}</p>
                </td>
              
              </tr>

              
              @endforeach
            </tbody>
          </table>

            
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
                        url: "/delete_processing_softwares",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Engineer has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/processing_softwares';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection