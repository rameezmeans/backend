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
                    <h3>Credits Reports</h3>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group form-group-default">
                            <label>Groups</label>
                            <select class="full-width" id="group" data-init-plugin="select2" name="engineers">
                                <option value="all_groups">All Groups</option>
                                <option value="no_group">No Group</option>
                            @foreach($groups as $group)
                                <option value="{{$group->id}}">{{$group->name}}</option>
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
                    <div id="NoClients">No Clients</div>
                    <div id="progress" class="text-center">
                        <div class="progress-circle-indeterminate m-t-45" style="">
                        </div>
                        <br>
                      </div>
                    <div id='table'>
                        
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

function getCredits( group, start, end ){

    $('#progress').show();
    
    let credit_url = '{{route('get-credits-report')}}';
    
    $.ajax({
            url:credit_url,
            type: "POST",
            data: {
                group: group,
                start: start,
                end: end
            },
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(res) {

                $('#progress').hide();

                $('#NoClients').hide();
                $('#table').html(res.html);
               

            }
        });
    }

    $( document ).ready(function(event) {

        $('#progress').hide();

        let group = $('#group').val();
        let start = $('#start').val();
        let end = $('#end').val();
        
        getCredits(group, start, end);

        $(document).on('change', '#group', function(e){

            console.log(e);
            let group = $('#group').val();
            let start = $('#start').val();
            let end = $('#end').val();
            
            getCredits(group, start, end);

        });

        $(document).on('change', '#start', function(e){

            console.log(e);

            let group = $('#group').val();
            let start = $('#start').val();
            let end = $('#end').val();

            getCredits(group, start, end);
        });

        $(document).on('change', '#end', function(e){

            console.log(e);

            let group = $('#group').val();
            let start = $('#start').val();
            let end = $('#end').val();

            getCredits(group, start, end);

        });

    });

</script>

@endsection