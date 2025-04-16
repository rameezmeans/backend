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
            <!-- START card -->

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Customers</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                  @if(Auth::user()->is_admin())
                    <button data-redirect="{{route('create-customer')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Customer</span>
                    </button>
                    @endif
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>

                <div class="row m-t-20 m-b-20">
                  <div class="col-md-6">

                <div class="form-group" style="display: inline-flex;margin-top:20px;">

                  <label>Payment Date Filter:</label>
          
                  <input class="form-control" type="text" name="daterange" value="" />
          
                  <button class="btn btn-success filter m-l-5">Filter</button>
          
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-group-default-select2">

                  <label>Frontend Filter:</label>
              
                      <select class="form-control" id="frontend">
                        <option value="all">ALL</option>
                        <option value="1">ECUTech</option>
                        <option value="2">TuningX</option>
                        <option value="3">Efiles</option>
                      </select>

                </div>
              </div>

              </div>

            </div>
            <div class="card-body">
              <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                <div>
                    <table class="table table-hover demo-table-search table-responsive-block data-table no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                        <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Portal</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Group</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Email</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Phone</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Country</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Created At</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Elorus Account</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                          {{-- @foreach ($customers as $customer)
                            <tr role="row" class="@if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-customers')) redirect-click @endif" @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-customers')) data-redirect="{{ route('edit-customer', $customer->id) }}" @endif>
                                <td class="v-align-middle semi-bold sorting_1">
                                    <p>{{$customer->name}}</p>
                                </td>

                                <td class="v-align-middle semi-bold sorting_1">
                                  <p>@if($customer->elorus_id)<a href="{{'https://ecutech.elorus.com/contacts/'.$customer->elorus_id}}" target="_blank">Go To Elorus Account</a>@else No Elorus @endif</p>
                                  
                                </td>
                                
                                <td class="v-align-middle semi-bold sorting_1">
                                  <p><label class="label @if($customer->frontend->id == 1) text-white bg-primary @elseif($customer->frontend->id == 3) text-white bg-info @else text-black bg-warning @endif">{{$customer->frontend->name}}</label></p>
                                </td>
                                
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
                                  <p>{{ date('d/m/Y', strtotime($customer->created_at))}}</p>
                              </td>
                            </tr>
                          @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection

@section('pagespecificscripts')

<script type="text/javascript">

    $(function () {

      $('input[name="daterange"]').daterangepicker({
        startDate: moment().subtract(36, 'M'),
        endDate: moment()
      });

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });


      var table = $('.data-table').DataTable({

          processing: true,
          serverSide: true,
          order: [[0,'desc']],
          ajax: {
              url: "{{ route('customers-table') }}",
              type: 'POST',
              data:function (d) {

                d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
                d.frontend = $('#frontend').val();

              }
          },
          columns: [
              {data: 'id', name: 'id'},
              {data: 'frontend', name: 'frontend', orderable: false, searchable: false},
              {data: 'group', name: 'group'},
              {data: 'email', name: 'email'},
              {data: 'phone', name: 'phone'},
              {data: 'country', name: 'country'},
              
              {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }
              },
              {data: 'created_time', name: 'created_time', orderable: false, searchable: false},
              {data: 'elorus', name: 'elorus'},
              {data: 'edit', name: 'edit'},
              
              
          ]

      });

      $(".filter").click(function(){
        table.draw();
      });

      $('#frontend').change(function(){
        table.draw();
      });

    });

</script>

@endsection