@extends('layouts.app')

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
                  <button data-redirect="{{route('subdealers-entity')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Subdealers</span>
                  </button>
                  @if(isset($subdealer))
                    <button data-redirect="{{route('create-subdealer-customer', ['id' => $subdealer->id])}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Subdealer Customer</span>
                    </button>
                    <button data-redirect="{{route('create-subdealer-engineer', ['id' => $subdealer->id])}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Subdealer Engineer</span>
                    </button>
                    <button data-redirect="{{route('create-subdealer', ['id' => $subdealer->id])}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Subdealer</span>
                    </button>
                    <button data-redirect="{{route('edit-permissions', ['id' => $subdealer->id])}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Set Permissions</span>
                    </button>
                    <button data-redirect="{{route('edit-tokens', ['id' => $subdealer->id])}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Set Tokens</span>
                    </button>
                  @endif
                  @if(isset($subdealer))
                  <h5>
                    Edit Subdealer
                  </h5>
                @else
                  <h5>
                    Add Subdealer
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-xl-9">
                <form class="" role="form" method="POST" action="@if(isset($subdealer)){{route('update-subdealer-entity')}}@else{{ route('add-subdealer-entity') }}@endif" enctype="multipart/form-data">
                  @csrf
                  @if(isset($subdealer))
                    <input name="id" type="hidden" value="{{ $subdealer->id }}">
                  @endif
                  <div class="form-group form-group-default required ">
                    <label>Name</label>
                    <input value="@if(isset($subdealer)){{$subdealer->name}}@else{{old('name')}}@endif"  name="name" type="text" class="form-control" required>
                  </div>
                  @error('name')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default ">
                    <label>LUA search Charges</label>
                    <input value="@if(isset($subdealer)){{$subdealer->subdealers_data->lua_search_charges}}@else{{old('lua_search_charges')}}@endif"  name="lua_search_charges" type="text" class="form-control">
                  </div>
                  @error('name')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default required ">
                    <label>Subdealer Type</label>
                    <select name="type" class="full-width select2-hidden-accessible" data-placeholder="Select Type" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                      @foreach($subdealerTypes as $key => $type)
                        <option @if(isset($subdealer) && $key == $subdealer->subdealers_data->type) {{ 'selected' }} @endif value="{{$key}}">{{$type}}</option>
                      @endforeach
                    </select>
                  </div>
                  @error('payment_account_id')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default">
                    <label>Frontend URL</label>
                    <input value="@if(isset($subdealer->subdealers_data)){{$subdealer->subdealers_data->frontend_url}}@else{{old('frontend_url')}}@endif"  name="frontend_url" type="text" class="form-control">
                  </div>
                  @error('frontend_url')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default">
                    <label>Backend URL</label>
                    <input value="@if(isset($subdealer->subdealers_data)){{$subdealer->subdealers_data->backend_url}}@else{{old('backend_url')}}@endif"  name="backend_url" type="text" class="form-control">
                  </div>
                  @error('backend_url')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default">
                    <label>Colour Scheme</label>
                    <input value="@if(isset($subdealer->subdealers_data)){{$subdealer->subdealers_data->colour_scheme}}@else{{old('colour_scheme')}}@endif"  name="colour_scheme" type="text" class="form-control">
                  </div>
                  @error('colour_scheme')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="form-group form-group-default">
                    <label>Logo</label>
                    <input name="logo" type="file" class="form-control">
                  </div>
                  <div class="text-center m-t-40">                    
                    <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($subdealer)) Update @else Add @endif</span></button>
                    @if(isset($subdealer))
                      <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$subdealer->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                    @endif
                  </div>
                </div>
                <div class="col-xl-3">
                  @if(isset($subdealer->subdealers_data->logo))
                  <div class="card social-card share  col1" >
                    <div class="card-header ">
                      <h5 class="text-black pull-left">Logo Preview</h5>
                    </div>
                    <div class="card-description">
                        <img src="{{ url('icons').'/'.$subdealer->subdealers_data->logo }}" alt="{{$subdealer->name}}">
                    </div>
                  </div>
                @endif
                </div>
                </form>
              </div>
              @if(isset($subdealer))
              <h3>
                Subdealers
              </h3>
              <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                <div>
                    <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                        <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Email</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Phone</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subdealers as $subdealer)
                                <tr role="row" class="redirect-click" data-redirect="{{ route('edit-subdealer', $subdealer->id) }}">
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>{{$subdealer->name}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>{{$subdealer->email}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>{{$subdealer->phone}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>{{$subdealer->created_at->diffForHumans()}}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
              
              <h3>
                Customers
              </h3>
              <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                <div>
                    <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                        <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Portal</th> --}}
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Email</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Phone</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Country</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($customers as $customer)
                            <tr role="row" class="redirect-click" data-redirect="{{ route('edit-subdealer-customer', $customer->id) }}">
                                <td class="v-align-middle semi-bold sorting_1">
                                    <p>{{$customer->name}}</p>
                                </td>
                                {{-- <td class="v-align-middle semi-bold sorting_1">
                                  <p><label class="label @if($customer->frontend->id == 1) text-white bg-primary @else text-black bg-warning @endif">{{$customer->frontend->name}}</label></p>
                              </td> --}}
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>{{$customer->email}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>{{$customer->phone}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>{{code_to_country($customer->country)}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>{{$customer->created_at->diffForHumans()}}</p>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <h3>
              Enginneers
            </h3>
            <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
              <div>
                  <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                      <thead>
                          <tr role="row">
                              <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                              <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Email</th>
                              <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Phone</th>
                              <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($engineers as $engineer)
                              <tr role="row" class="redirect-click" data-redirect="{{ route('edit-subdealer-engineer', $engineer->id) }}">
                                  <td class="v-align-middle semi-bold sorting_1">
                                      <p>{{$engineer->name}}</p>
                                  </td>
                                  <td class="v-align-middle semi-bold sorting_1">
                                      <p>{{$engineer->email}}</p>
                                  </td>
                                  <td class="v-align-middle semi-bold sorting_1">
                                      <p>{{$engineer->phone}}</p>
                                  </td>
                                  <td class="v-align-middle semi-bold sorting_1">
                                      <p>{{$engineer->created_at->diffForHumans()}}</p>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          </div>

          <h3>
            Head
          </h3>
          <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
            <div>
                <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                    <thead>
                        <tr role="row">
                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Email</th>
                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Phone</th>
                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($head as $engineer)
                            <tr role="row" class="redirect-click" data-redirect="{{ route('edit-subdealer-engineer', $engineer->id) }}">
                                <td class="v-align-middle semi-bold sorting_1">
                                    <p>{{$engineer->name}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                    <p>{{$engineer->email}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                    <p>{{$engineer->phone}}</p>
                                </td>
                                <td class="v-align-middle semi-bold sorting_1">
                                    <p>{{$engineer->created_at->diffForHumans()}}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


                @endif
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

      $( document ).ready(function(event) {

        let url = "{{route('delete-subdealer')}}";
        
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
                        url: url,
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Subdealer has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/subdealer_groups';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection