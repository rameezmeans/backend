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
                    <h3>Reports</h3>
                </div>
                <div class="pull-right" id="download">
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
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="row">
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
                </div>
               
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div id="NoFile">No Files</div>
                    <div id="progress" class="text-center">
                        <div class="progress-circle-indeterminate m-t-45" style="">
                        </div>
                        <br>
                      </div>
                    <div id='TableArea'>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 2%;">#</th>
                                    <th style="width: 15%;">File</th>
                                    <th style="width: 8%;">Engineer</th>
                                    <th style="width: 25%;">Stages and Options</th>
                                    <th style="width: 10%;">Response Time</th>
                                    <th style="width: 10%;">Uploaded At</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                
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

function getFiles(engineer, start, end){

    $('#progress').show();

    $('#start_field').val(start);
    $('#end_field').val(end);
    $('#engineer_field').val(engineer);
    
    $.ajax({
            url: "/get_engineers_files",
            type: "POST",
            data: {
                engineer: engineer,
                start: start,
                end: end,
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(res) {

                $('#progress').hide();

                if(res.has_files){
                    $('#NoFile').hide();
                    $('#TableArea').show();
                    $('#download').show();
                    $('#table').html(res.html);
                }
                else{
                    $('#download').hide();
                    $('#TableArea').hide();
                    $('#NoFile').show();
                }

            }
        });
    }

    $( document ).ready(function(event) {

        $('#progress').hide();

        let engineer = $('#engineers').val();
        let start = $('#start').val();
        let end = $('#end').val();

        console.log(start);
        console.log(end);
        console.log(engineer);

        getFiles(engineer, start, end);

        $(document).on('change', '#engineers', function(e){

            console.log(e);
            let engineer = $(this).val();
            let start = $('#start').val();
            let end = $('#end').val();

            console.log(engineer);
            getFiles(engineer, start, end);
        });

        $(document).on('change', '#start', function(e){

            console.log(e);
            let start = $(this).val();
            let end = $('#end').val();
            let engineer = $('#engineers').val();

            console.log(engineer);
            getFiles(engineer, start, end);
        });

        $(document).on('change', '#end', function(e){

            console.log(e);
            let end = $(this).val();
            let start = $('#start').val();
            let engineer = $('#engineers').val();

            console.log(engineer);
            getFiles(engineer, start, end);
        });
    });

</script>

@endsection