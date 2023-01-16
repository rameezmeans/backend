@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">
            
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
            <div class="card-header ">
                <div class="card-title">
                    <h3>News Feeds</h3> 
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('add-feed')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Add News Feed</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Title</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 100px;">Date Created</th>
                                    <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 100px;">Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($newsFeeds as $feed)
                                    <tr role="row" class="redirect-click" data-redirect="{{ route('edit-feed', $feed->id) }}">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$feed->title}}</p>
                                        </td>
                                        
                                        <td class="v-align-middle">
                                            <p>{{$feed->created_at->diffForHumans()}}</p>
                                        </td>
                                        <td class="v-align-middle">
                                            <p><input data-feed_id={{$feed->id}} class="active" type="checkbox" data-init-plugin="switchery" @if($feed->active == 1) checked="checked" @endif onclick="status_change()"/></p>
                                        </td>
                                    </tr>
                                @endforeach
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
        let switchStatus = true;
        $(document).on('change', '.active', function(e) {
            let feed_id = $(this).data('feed_id');
            console.log(feed_id);
            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }
            else {
                switchStatus = $(this).is(':checked');
                console.log(switchStatus);
            }

            change_status(feed_id, switchStatus);
        });

        function change_status(feed_id, status){
            $.ajax({
                        url: "/change_status_feeds",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "feed_id": feed_id,
                            "status": status,
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            
                        }
                    });  
        }
    });
   

</script>

@endsection