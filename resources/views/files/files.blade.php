@extends('layouts.app')

@section('pagespecificstyles')
  <style>
    table.dataTable tbody tr.checked-darkly {
      background-color: #d8dadc !important;
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
              <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                <div>
                    <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                        <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 2%;">Task ID</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 2%;">Customer</th>
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 10%;">Vehicle</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 2%;">Support Status</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 2%;">Status</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 15%;">Stages</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 25%;">Options</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 2%;">Credits</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 5%;">Date Uploaded</th>
                                @if(Auth::user()->is_admin)
                                  <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 20%;">Assigned To</th>
                                  <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 20%;">Response Time</th>
                                @endif
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 5%;">Frontend</th>
                              </tr>
                        </thead>
                        <tbody>
                          @foreach($files as $file)
                              <tr class="redirect-click @if($file->checked_by == 'customer') checked-darkly @endif" data-redirect="{{ route('file', $file->id) }}">
                                <td><span class="label @if($file->frontend->id == 1) text-white bg-primary @else text-black bg-warning @endif">Task{{$file->id}}</span></td>
                                <td>{{$file->user->name}}</td>
                                <td>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</td>
                                <td><span class="label @if($file->support_status == 'closed') label-success @else label-danger @endif">{{$file->support_status}}</span></td>
                                <td><span class="label @if($file->status == 'completed') label-success @elseif($file->status == 'rejected') label-danger @else label-info @endif ">{{$file->status}}</span></td>
                                <td>  
                                  <div class="">
                                    @if($file->stages)
                                      <img alt="{{$file->stages}}" width="33" height="33" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon }}">
                                      <span class="text-black" style="top: 2px; position:relative;">{{ $file->stages }}</span>
                                    @endif
                                  </div>  
                                </td>
                                <td>
                                  <div class="">
                                    @if($file->options)
                      
                                      @foreach($file->options() as $option)
                                        <span class="label label-warning-darker m-l-10" class="text-black" style="top: 2px; position:relative;">
                                          <img alt="{{$option}}" width="20" height="20" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon }}">
                                          {{ $option }}<br>
                                        </span>
                                      @endforeach
                       
                                    @endif
                                  </div>  
                                </td>
                                <td><span class="badge badge-important">{{$file->credits}}</span></td>
                                
                                <td>{{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i A')}}</td>
                                @if(Auth::user()->is_admin)
                                  <td><span class="label label-success">@if($file->assigned){{$file->assigned->name}} @else{{ "No one" }}@endif</span></td>
                                  <td><span class="label label-success">@if( $file->response_time ) {{ \Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans() }} @else {{ "Not Responded" }} @endif</span></td>
                                @endif
                                <td><span class="label @if($file->frontend->id == 1) text-white bg-primary @else text-black bg-warning @endif">{{$file->frontend->name}}</span></td>
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