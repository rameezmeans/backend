@extends('layouts.app')

@section('pagespecificstyles')

<style>

.table tbody tr td .checkbox label::after {
    left: 4px !important;
}


</style>

@endsection

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Multi Delete Files</h3>
                </div>

                <div class="pull-right">
                    <div class="col-xs-12">
                        <button data-redirect="{{route('files')}}" class="btn btn-success redirect-click"><i class="pg-plus_circle"></i> <span class="bold">Files</span>
                        </button>
                    </div>
                  </div>

                <div class="">
                    <div class="col-xs-12">
                        <button class="btn btn-danger hide" id="delete-selected"><i class="pg-plus_circle"></i> <span class="bold">Delete Selected</span>
                        </button>
                    </div>
                  </div>
                
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="card-body">
                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                        <div>
                            <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                <thead>
                                    <tr role="row">
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 5%;">Select</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 5%;">ID</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Uploaded By</th>
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($files as $file)

                                        @if(!$file->vehicle())
                                            {{$file->id}}
                                        @endif

                                        <tr role="row" class="">
                                            <td class="">
                                                <div class="checkbox check-success">
                                                    <input type="checkbox" value="{{$file->id}}" id="checkbox{{$file->id}}" class="checkbox-c">
                                                    <label for="checkbox{{$file->id}}"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <span class="label @if($file->front_end_id == 1) bg-primary text-white @else label-warning text-black @endif">{{$file->id}}</span>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$file->brand.' '.$file->engine.' '.$file->vehicle()->TORQUE_standard}}</p>
                                            </td>
                                            <td class="">
                                                <p>{{$file->user->name}}</p>
                                            </td>
                                            <td class="v-align-middle semi-bold sorting_1">
                                                <p>{{$file->created_at->diffForHumans()}}</p>
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
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

    function removeItem(array, item){
        for(var i in array){
            if(array[i]==item){
                array.splice(i,1);
                break;
            }
        }
    }

    $( document ).ready(function(event) {
        var ids = [];

        $(document).on('click', '.checkbox-c' ,function() {

            let value = $(this).val();
            
            if (!$(this).is(':checked')) {

                let index = ids.indexOf(value);
                removeItem(ids, value);

            }
            else if ($(this).is(':checked')) {
                
                ids.push(value);
                
            }

            console.log(ids);

            if(ids.length > 0){
                $('#delete-selected').removeClass('hide');
            }
            else if (ids.length == 0){
                $('#delete-selected').addClass('hide');
            }

        });

        $('#delete-selected').click(function(e){

            const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
  })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
      reverseButtons: false
    }).then((result) => {
      if (result.isConfirmed) {

            $.ajax({
                url: "/delete_files",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'ids': ids
                },
                success: function(items) {
                    console.log(items);
                    location.reload();
                }
            });

        } else if ( result.dismiss === Swal.DismissReason.cancel ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Uploaded files are safe :)',
          'error'
        )
      }
    });
});

});

</script>

@endsection