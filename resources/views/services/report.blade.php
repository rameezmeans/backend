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
                    <h3>Countries and Customers Report</h3>
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
            
            <form method="POST" action="{{route('get-services-report')}}">
                <div class="row">
                    
                    @csrf
                    <div class="col-lg-2">
                        <div class="form-group form-group-default input-group">
                            <div class="form-input-group">
                                <label>Start</label>
                                <input autocomplete="off" type="input" style="margin-bottom: 17px;" class="form-control datepicker" placeholder="Start Date" id="start" name="start">
                            </div>
                            <div class="input-group-append ">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group form-group-default input-group">
                            <div class="form-input-group">
                                <label>End</label>
                                <input autocomplete="off" type="input" style="margin-bottom: 17px;" class="form-control datepicker" placeholder="End Date" id="end" name="end">
                            </div>
                            <div class="input-group-append ">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group form-group-default">
                            <label>Select Frontend</label>
                            <select class="full-width" id="frontend" data-init-plugin="select2" name="front_end">
                            @foreach($frontends as $frontend)
                                <option value="{{$frontend->id}}">{{\App\Models\Frontend::findOrFail($frontend->id)->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-group-default">
                            <label>Select Countries</label>
                            <select class="full-width" id="frontend" data-init-plugin="select2" name="countries[]">
                            @foreach($countries as $country)
                                <option value="{{$country}}">{{code_to_country($country)}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    
                    <div class="col-lg-2">
                        <input class="btn btn-success" type="submit" value="Filter">
                    </div>
                    
                </div>
            </form>

               

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