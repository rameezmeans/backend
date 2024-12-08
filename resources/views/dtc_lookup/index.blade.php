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
                    <h3>DTC Lookup Records</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('create-dtc-records') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add DTC Records</span>
                    </button>
                    <button data-redirect="{{ route('import-dtc-records') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Import DTC Records</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                <form class="" method="POST" action="{{route('search-dtc-record')}}" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group form-group-default required ">
                      <label>Code</label>
                      <input id="code"  name="code" type="text" class="form-control" required>
                    </div>
                    @error('code')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <div class="text-center m-t-40">                    
                      <button id="get-desc" class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Get Description</span></button>
                      
                    </div>
                </form>

                @if(isset($record))

                    <div class="card card-default">
                        <div class="card-header ">
                        <div class="card-title">Results
                        </div>
                        <div class="card-controls">
                            
                        </div>
                        </div>
                        <div class="card-body">
                            @if(is_object($record))
                                <h3 class="semi-bold">Description: {{$record->desc}}</h3>
                                <p>Code: {{$record->code}}</p>
                            @elseif(is_string($record))
                                <h3 class="semi-bold">{{$record}}</h3>
                            @endif
                        </div>
                    </div>

                @endif

                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Code</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Desc</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dtclookupRecords as $dtc)
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$dtc->code}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$dtc->desc}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                        <div class="btn-group">
                                            <button type="button" data-redirect="{{ route('edit-dtc-records', $dtc->id) }}" class="btn btn-success redirect-click"><i class="fa fa-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-delete" data-id="{{$dtc->id}}"><i class="fa fa-trash-o"></i>
                                            </button>
                                          </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $dtclookupRecords->links() }}
                </div>
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
                        url: "/delete_dtc",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Bosch Number has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/dtc_lookup';
                        }
                    });            
                }
            });
        });
       
    });

</script>

@endsection