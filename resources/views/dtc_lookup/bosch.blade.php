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
                    <h3>Bosch Lookup Records</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{ route('create-bosch-numbers') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Bosch Numbers</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                
                <form class="" method="POST" action="{{route('search-bosch-number')}}" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group form-group-default required ">
                      <label>Manufacturer Number</label>
                      <input id="manufacturer_number"  name="manufacturer_number" type="text" class="form-control" required>
                    </div>
                    @error('manufacturer_number')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <div class="text-center m-t-40">                    
                      <button id="get-ecu" class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Get ECU</span></button>
                      
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
                                <h3 class="semi-bold">ECU: {{$record->ecu}}</h3>
                                <p>Manufacturer Number: {{$record->manufacturer_number}}</p>
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
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Manufacturer Number</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">ECU</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($boschNumbers as $bosch)
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$bosch->manufacturer_number}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$bosch->ecu}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <div class="btn-group">
                                                <button type="button" data-redirect="{{ route('edit-bosch-numbers', $bosch->id) }}" class="btn btn-success redirect-click"><i class="fa fa-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-delete" data-id="{{$bosch->id}}"><i class="fa fa-trash-o"></i>
                                                </button>
                                              </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $boschNumbers->links() }}
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
                        url: "/delete_bosch_number",
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

                            window.location.href = '/bosch_lookup';
                        }
                    });            
                }
            });
        });
       
    });

</script>

@endsection