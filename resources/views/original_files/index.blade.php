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
                    <h3>Original Files</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                <form method="GET" action="{{route('filter-original-files')}}">
                    @csrf

                    
                        <div class="form-group form-group-default required ">
                            <label>Producer</label>
                            <select name="Producer" id="Producer" class="full-width select2-hidden-accessible" data-placeholder="Select Type" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                              @foreach($producerObjects as $p)
                              <option @if(isset($producer) && $producer && $producer==$p->Producer) selected @endif value="{{ $p->Producer }}">{{$p->Producer}}</option>
                              @endforeach
                            </select>
                          </div>
                        <div class="form-group">
                            <label>Series</label>
                            <select name="Series" id="Series" class="full-width select2-hidden-accessible" data-placeholder="Select Series" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                                @if($seriesObjects)
                                    @foreach ($seriesObjects as $s)
                                    <option @if(isset($series) && $series && $series==$s->Series) selected @endif value="{{ $s->Series }}">{{$s->Series}}</option>
                                    @endforeach
                                @endif
                            </select>
                          
                        </div>
                        <div class="form-group">
                            <label>Model</label>
                            <select name="Model" id="Model" class="full-width select2-hidden-accessible" data-placeholder="Select Model" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                                @if($modelsObjects)
                                    @foreach ($modelsObjects as $m)
                                    <option @if(isset($model) && $model && $model==$m->Model) selected @endif value="{{ $m->Model }}">{{$m->Model}}</option>
                                    @endforeach
                                @endif
                            </select>
                          
                        </div>
                        
                        <button type="submit" class="btn btn-success">Search</button>
                        <button type="button" class="btn btn-warning" id="reset_filter">Reset</button>

                     

                </form>

                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div class="">
                        <div class="col-xs-12">
                            <button class="btn btn-danger hide" id="delete-selected"><i class="pg-plus_circle"></i> <span class="bold">Delete Selected</span>
                            </button>
                        </div>
                      </div>
                    <div>
                        <div class="m-t-20 " style="margin-bottom: 20px;">Only {{$originalFiles->count()}} out of {{$originalFiles->total()}} records are on display.</div>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Check</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Producer</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Series</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Model</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">File</th>
                                    {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">File</th> --}}
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Download</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($originalFiles as $file)
                                    <tr role="row" class="">
                                        <td class="">
                                            <div class="checkbox check-success">
                                                <input type="checkbox" value="{{$file->id}}" id="checkbox{{$file->id}}" class="checkbox-c">
                                                <label for="checkbox{{$file->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$file->Producer}}</p>
                                            
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <p>{{$file->Series}}</p>
                                            
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <p>{{$file->Model}}</p>
                                           
                                            
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <p>{{$file->File}}</p>
                                           
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <a href="{{route('download-original-file', $file->id)}}" class="btn btn-success">Download</a>
                                           
                                        </td>

                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <a href="{{route('edit-original-file', $file->id)}}" class="btn btn-warning">Edit</a>
                                           
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="m-t-20">
                        {!! $originalFiles->links() !!}
                    </div>
                    <div class="m-t-20 " style="margin-bottom: 20px;">Page {{$originalFiles->currentPage()}} out of {{$originalFiles->lastPage()}}</div>
                    
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">
    $(document).ready(function(event) {

        function removeItem(array, item){
        for(var i in array){
            if(array[i]==item){
                array.splice(i,1);
                break;
            }
        }
    }

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
                url: "/delete_original_files",
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

        $('#reset_filter').click(function(e) {
            $("#Producer").val($("#Producer option:first").val());
            $("#Series").val($("#Series option:first").val());
            $("#Model").val($("#Model option:first").val());

            window.location = '/original_files';
        });

        $(document).on('change', '#Producer', function(e) {
            let producer = $(this).val();
            disable_dropdowns();

            $.ajax({
                url: "/get_series",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'producer': producer
                },
                success: function(items) {
                    console.log(items);

                    $('#Series').removeAttr('disabled');
                    $.each(items.series, function(i, item) {
                        console.log(item.series);
                        $('#Series').append($('<option>', {
                            value: item.Series,
                            text: item.Series
                        }));
                    });
                }
            });
        });

    });

    $(document).on('change', '#Series', function(e) {
        let producer = $('#Producer').val();
        let series = $(this).val();

        $.ajax({
            url: "/get_models_orignal_files",
            type: "POST",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'producer': producer,
                'series': series
            },
            success: function(items) {
                console.log(items);

                $('#Model').removeAttr('disabled');
                $.each(items.models, function(i, item) {
                    console.log(item.models);
                    $('#Model').append($('<option>', {
                        value: item.Model,
                        text: item.Model
                    }));
                });
            }
        });
    });

    function disable_dropdowns() {

        $('#Series').children().remove();
        $('#Series').append('<option selected value="">Series</option>');
        $('#Model').children().remove();
        $('Model').append('<option selected value="">Models</option>');

        $('#Series').attr('disabled', 'disabled');
        $('#Model').attr('disabled', 'disabled');

    }

</script>

@endsection