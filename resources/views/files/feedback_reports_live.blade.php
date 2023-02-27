@extends('layouts.app')

@section('pagespecificstyles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
<style>

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
                <div class="card-title"><h3>Feedback Report</h3>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <livewire:feedback-table 
                searchable="request_files.file_id,brand,model,engine"
              />
            </div>
          </div>
        </div>
        </div>
    </div>
@endsection
@section('pagespecificscripts')
    <script type="text/javascript">
      
    </script>
@endsection