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


                <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                    <li class="nav-item">
                      <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>ECU Tech</span></a>
                    </li>
                    <li class="nav-item">
                      <a href="#" data-toggle="tab" data-target="#slide2"><span>TuningX</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" data-target="#slide3"><span>E-Files</span></a>
                      </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane slide-left active" id="slide1">
                        <p>ECUTech Online File Search Status: <input data-frontend_id="1" class="online_search_active" type="checkbox" data-init-plugin="switchery" @if($ecutechOnlineStatus) checked="checked" @endif/></p>
                
                        <form class="form" role="form" method="POST" action="{{route('add-resellers-text')}}">
                            @csrf
                            
                              <input name="id" type="hidden" value="1">
                           
                            <div>
                              <label>Reseller Text *</label>
                                <div class="form-group">
                                    <input type="text" name="resellers_text" class="form-control" value="{{\App\Models\FrontEnd::findOrFail(1)->resellers_text}}" required>
                                </div>
                                @error('resellers_text')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-center m-t-40">                    
                                <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                            </div>
                        </form>
                
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
                                @foreach ($newsFeedsECUTech as $feed)
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

                    <div class="tab-pane slide-left" id="slide2">

                        <p>TuningX Online File Search Status:<input data-frontend_id="2" class="online_search_active" type="checkbox" data-init-plugin="switchery" @if($tuningXOnlineStatus) checked="checked" @endif/></p>

                        <form class="form" role="form" method="POST" action="{{route('add-resellers-text')}}">
                            @csrf
                            
                              <input name="id" type="hidden" value="2">
                           
                            <div>
                              <label>Reseller Text *</label>
                                <div class="form-group">
                                    <input type="text" name="resellers_text" class="form-control" value="{{\App\Models\FrontEnd::findOrFail(2)->resellers_text}}" required>
                                </div>
                                @error('resellers_text')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-center m-t-40">                    
                                <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                            </div>
                        </form>

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
                                        @foreach ($newsFeedsTuningX as $feed)
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

                    <div class="tab-pane slide-left" id="slide3">

                        <p>ETF Online File Search Status:<input data-frontend_id="3" class="online_search_active" type="checkbox" data-init-plugin="switchery" @if($etfOnlineStatus) checked="checked" @endif/></p>

                        <form class="form" role="form" method="POST" action="{{route('add-resellers-text')}}">
                            @csrf
                            
                              <input name="id" type="hidden" value="3">
                           
                            <div>
                              <label>Reseller Text *</label>
                                <div class="form-group">
                                    <input type="text" name="resellers_text" class="form-control" value="{{\App\Models\FrontEnd::findOrFail(3)->resellers_text}}" required>
                                </div>
                                @error('resellers_text')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-center m-t-40">                    
                                <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">Update</span></button>
                            </div>
                        </form>

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
                                        @foreach ($newsFeedsEfiles as $feed)
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
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {

        $(document).on('change', '.online_search_active', function(e) {

            let frontend_id = $(this).data('frontend_id');
            console.log(frontend_id);

            if ($(this).is(':checked')) {
                status = $(this).is(':checked');
                console.log(status);
            }
            else {
                status = $(this).is(':checked');
                console.log(status);
            }

            $.ajax({
                url: "/change_online_search_status",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "frontend_id": frontend_id,
                    "status": status,
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    console.log(response);
                }
            });

        });


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