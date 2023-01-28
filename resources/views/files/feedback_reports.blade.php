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
                    <h3>Feedback Reports</h3>
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
                              <label>Feedback</label>
                              <select class="full-width" id="feedback" data-init-plugin="select2" name="feedback">
                                <option value="all_types">All Types</option>
                                <option value="not_provided">No Feedback</option>
                                <option value="angry">Angry</option>
                                <option value="sad">Sad</option>
                                <option value="ok">OK</option>
                                <option value="good">Good</option>
                                <option value="happy">Happy</option>
                            </select>
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
                                    <th style="width: 8%;">Brand</th>
                                    <th style="width: 8%;">Model</th>
                                    <th style="width: 8%;">ECU</th>
                                    <th style="width: 25%;">Stages and Options</th>
                                    <th style="width: 20%;">Feedback</th>
                                    <th style="width: 10%;">Engineer</th>
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

function getFiles(engineer, feedback){

    $('#progress').show();

    // $('#start_field').val(start);
    // $('#end_field').val(end);
    // $('#engineer_field').val(engineer);

    let feedback_url = '{{route('get-feedback-report')}}';
    
    $.ajax({
            url: "/get_feedback_report",
            type: "POST",
            data: {
                engineer: engineer,
                feedback: feedback,
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
        let feedback = $('#feedback').val();
        
        getFiles(engineer, feedback);

        $(document).on('change', '#engineers', function(e){

            console.log(e);
            let engineer = $('#engineers').val();
            let feedback = $('#feedback').val();
            
            console.log(engineer);
            getFiles(engineer, feedback);
        });

        $(document).on('change', '#feedback', function(e){

            console.log(e);
            let engineer = $('#engineers').val();
            let feedback = $('#feedback').val();
            console.log(engineer);
            getFiles(engineer, feedback);
        });

    });

</script>

@endsection