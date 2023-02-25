@extends('layouts.app')

@section('pagespecificstyles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
<style>
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
        <div class="container-fluid bg-white">
          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Files</h3>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <livewire:files-datatable 
                searchable="name, vehicle"
              />
            </div>
          </div>
        </div>
        </div>
    </div>
@endsection
@section('pagespecificscripts')
    <script type="text/javascript">
      $( document ).ready(function(event) {
        $(document).on('click','.redirect-click-file',function(e) {
          console.log('clicked');
            var lastClass = $(this).attr('class').split(' ').pop();
            console.log(lastClass);
            // console.log("http://backend.test/file/"+lastClass);

            window.location.href = "/file/"+lastClass;
            
          });
        });
    </script>
@endsection