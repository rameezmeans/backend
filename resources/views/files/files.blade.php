@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">
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
                                <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" style="width: 250px;">Name</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 42px;">Status</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 42px;">Credits</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 150px;">Customer</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 100px;">Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($files as $file)
                              <tr class="redirect-click" data-redirect="{{ route('file', $file->id) }}">
                                <td>{{$file->brand}} {{$file->vehicle()->Name}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</td>
                                <td><span class="label label-success">{{$file->status}}</span></td>
                                <td><span class="badge badge-important">{{$file->credits}}</span></td>
                                <td>{{$file->name}}</td>
                                <td>{{$file->created_at->diffForHumans();}}</td>
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