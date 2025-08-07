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
                  
                  <h2>
                    Add Brand ECU Options Comments
                  </h2>
                
                </div>
                
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                {{-- @foreach($brands as $brand)
                            @php 
                                dd($brand->brand);
                            @endphp
                            @endforeach --}}
              <form class="" role="form" method="POST" action="{{route('set-options-comments')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                <div class="col-lg-3">
                    <div class="form-group form-group-default">
                        <label>Select Brand</label>
                        <select class="full-width" id="brand" data-init-plugin="select2" name="brand">
                        @foreach($brands as $brand)
                            
                            <option value="{{$brand->brand}}">{{$brand->brand}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-group-default">
                        <label>Select ECU</label>
                        <select class="full-width" id="ecu" data-init-plugin="select2" name="ecu">
                        
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-group-default">
                        <label>Select Service</label>
                        <select class="full-width" id="frontend" data-init-plugin="select2" name="service">
                        @foreach($services as $service)
                            <option value="{{$service->label}}">{{$service->label}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-group-default">
                        <label>Select Software</label>
                        <select class="full-width" id="frontend" data-init-plugin="select2" name="software">
                        @foreach($softwares as $software)
                            <option value="{{$software->name}}">{{$software->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group form-group-default">
                        <label>Comment</label>
                        <textarea class="full-width" name="comment"></textarea>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group form-group-default">
                        <label>Result</label>
                        <textarea class="full-width" name="result"></textarea>
                    </div>
                </div>

                <div class="col-lg-2">
                    <input class="btn btn-success" type="submit" value="Add">
                </div>
                </div>
              </form>

              <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                <div>
                    <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                        <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Brand</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">ECU</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Service</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Software</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $c)
                                <tr role="row">
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>{{$c->brand}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>{{$c->ecu}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>{{$c->service_label}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>{{$c->software}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><a class="btn btn-success btn-cons m-b-10" href="{{route('edit-options-comments',$c->id)}}"> <span class="bold">Edit</span></a></p>
                                        <p><button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$c->id}}" type="button"> <span class="bold">Delete</span></button></p>
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
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

$(document).ready(function(e){

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
                        url: "/delete_option_comment",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Package has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/options_comments';
                        }
                    });            
                }

            });
        });

    console.log('here we are');

$(document).on('change', '#brand', function(e){

    $('#ecu').html('');

    console.log(e);

    let brand = $(this).val();
    
    $.ajax({
                url:'{{route('get-comments-ecus')}}',
                type: "POST",
                data: {
                    brand: brand
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {

                    
                    $('#ecu').html(res.html);
                    
                

                }
            });

    

    });
});

</script>

@endsection