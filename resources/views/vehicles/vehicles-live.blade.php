@extends('layouts.app')

@section('pagespecificstyles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
<style>
    /* .table tbody tr td .checkbox label::after{
        left: 3px !important;
    } */

    .btn-success {
        background-color: #10cfbd !important;
    }
    .redirect-click-vehicle{
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
                <div class="card-header">
                    <div class="card-title">
                        <h3>Vehicles</h3>
                        
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-vehicles'))
                                <button data-redirect="{{ route('create-vehicle') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add Vehicle</span>
                                </button>
                                <button data-redirect="{{ route('import-vehicles') }}" class="btn btn-green btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Import Vehicles</span>
                                </button>
                            @endif
                            
                            {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                        </div>
                        </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                        <livewire:vehicle-table
                            searchable="Make,Engine,Model,Generation"
                        />
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

        $(document).on('click','.redirect-click-vehicle',function(e) {
          console.log('clicked');
            var lastClass = $(this).attr('class').split(' ').pop();
            console.log(lastClass);
            // console.log("http://backend.test/file/"+lastClass);

            window.location.href = "/vehicle/"+lastClass;
            
          });
        
        $(document).on('click', '#delete' ,function(){
            var searchIDs = $("tbody input:checkbox:checked").map(function(){
                    return $(this).val();
            }).toArray();
            console.log(searchIDs);

            $.ajax({
                url: "/mass_delete",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "searchIDs": searchIDs
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    location.reload();
                }
            }); 
        });

        $(document).on('click', '#select_all' ,function(){

            if($(this).is(":checked")){
                console.log(this);
                $('#delete').removeClass('hide');
                $('input:checkbox').attr('checked',true);
            }
            else{
                $('#delete').addClass('hide');
                $('input:checkbox').attr('checked',false);
            }
        });
    });
</script>

@endsection