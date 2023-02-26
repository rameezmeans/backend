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
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                    <h3>Reports</h3>
                </div>
                {{-- <div class="pull-right" id="download">
                    <div class="col-xs-12">
                        <form method="POST" action="{{route('get-engineers-report')}}">
                            @csrf
                            <input id="engineer_field" name="engineer" value="all_engineers" type="hidden">
                            <input id="start_field" name="start" value="" type="hidden">
                            <input id="end_field" name="end" value="" type="hidden">
                            <button type="submit" class="btn btn-success btn-cons m-b-10"><i class="fa fa-download"></i> <span class="bold">Download PDF</span>
                            </button>
                        </form>
                    </div>
                </div> --}}
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                {{-- <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group form-group-default">
                            <label>Engineers</label>
                            <select class="full-width" id="engineers" data-init-plugin="select2" name="engineers">
                                <option value="all_engineers">All Engineers</option>
                            @foreach($engineers as $engineer)
                                <option value="{{$engineer->id}}">{{$engineer->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-group-default input-group">
                            <div class="form-input-group">
                              <label>Start</label>
                              <input type="input" style="margin-bottom: 17px;" class="form-control datepicker" placeholder="Start Date" id="start">
                            </div>
                            <div class="input-group-append ">
                              <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                          </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-group-default input-group">
                            <div class="form-input-group">
                              <label>End</label>
                              <input type="input" style="margin-bottom: 17px;" class="form-control datepicker" placeholder="End Date" id="end">
                            </div>
                            <div class="input-group-append ">
                              <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                          </div>
                    </div>
                </div> --}}
               
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <livewire:file-engineer-table
                        searchable="name"
                    />
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

    });
</script>

@endsection