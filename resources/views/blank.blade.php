@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Blank</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Blank</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {
        
      $.ajax({
                        url: "/delete_comment",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your Record has been deleted.",
                                type: "success",
                                timer: 5000
                            });

                            location.reload();
                        }
                    });
    });

</script>

@endsection