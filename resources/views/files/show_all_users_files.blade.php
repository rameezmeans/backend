@extends('layouts.app')

@section('pagespecificstyles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
<style>
  
  .flex {
    display: flex !important;
    width: max-content;
  }

  .redirect-click-file{
    cursor: pointer;
  }

</style>
@endsection

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                    <h3>All User's Files</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    {{-- <button data-redirect="{{ route('create-tool') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Create Tool</span>
                    </button> --}}
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                {{-- <livewire:show-all-users-files :params="$id"
                  searchable="id,username,brand,model,ecu"
                /> --}}

                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer">
                  <div>

                <table style="width: 100% !important;" class="table table-hover demo-table-search table-responsive-block data-table no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info" >

                  <thead>
          
                      <tr>
          
                          <th>Task ID</th>
                          <th>Frontend</th>
                          <th>Submission Date</th>
                          <th>Submission Time</th>
                          <th>Customer</th>
                          <th>Brand</th>
                          <th>Model</th>
                          <th>ECU</th>
                          <th>Support Status</th>
                          <th>Status</th>
                          <th>Stage</th>
                          <th>Options</th>
                          <th>Credits</th>
                          <th>Assigned To</th>
                          <th>Response Time</th>
                          
                          
                      </tr>
          
                  </thead>
          
                  <tbody>
          
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

    $( document ).ready(function(event) {

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      var table = $('.data-table').DataTable({

          stripeClasses: [],
          processing: true,
          serverSide: true,
          ajax: {
            url: "{{ route('ajax-all-user-files', "$userID") }}",
            type: 'POST',
            data:function (d) {
              
              

            },

            complete: function (data) {

            },

              
          },

          columns: [
              {data: 'id', name: 'id', orderable: false},
              {data: 'frontend', name: 'frontend', orderable: false, searchable: false},
              {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }, orderable: false
              },
              {data: 'created_time', name: 'created_time', orderable: false},
              {data: 'username', name: 'username', orderable: false},
              {data: 'brand', name: 'brand', orderable: false},
              {data: 'model', name: 'model', orderable: false},
              {data: 'ecu', name: 'ecu', orderable: false},
              {data: 'support_status', name: 'support_status', orderable: false, searchable: false},
              {data: 'status', name: 'status', orderable: false, searchable: false},
              {data: 'stage', name: 'stage', orderable: false, searchable: false},
              {data: 'options', name: 'options', orderable: false, searchable: false},
              {data: 'credits', name: 'credits', orderable: false},
              {data: 'engineer', name: 'engineer', orderable: false},
              {data: 'response_time', name: 'response_time', orderable: false, searchable: false},
              
          ]

      });

      
    // $('.parent-adjusted').parent().addClass('flex');

    // $(document).on('click','.redirect-click-file',function(e) {
    
    // console.log('clicked');
    //     var lastClass = $(this).attr('class').split(' ').pop();
    //     console.log(lastClass);
    //     // console.log("http://backend.test/file/"+lastClass);

    //     window.location.href = "/file/"+lastClass;
        
    // });
       
    });

</script>

@endsection