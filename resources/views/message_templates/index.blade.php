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
                    <h3>Message Templates</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    {{-- <button data-redirect="{{ route('add-message-template') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Create Message Template</span>
                    </button> --}}
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                <ul class="nav nav-tabs nav-tabs-fillup m-t-0" data-init-reponsive-tabs="dropdownfx">
             
                    <li class="nav-item">
                      <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>ECU Tech</span></a>
                    </li>
                    <li class="nav-item">
                      <a href="#" data-toggle="tab" data-target="#slide2"><span>TuningX</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" data-target="#slide3"><span>E-files</span></a>
                      </li>
                  </ul>

                  <div class="tab-content">
                    <div class="tab-pane slide-left active" id="slide1">


                        <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                            <div>
                                <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ecutechTemplates as $template1)
                                            <tr role="row" class="redirect-click" data-redirect="{{ route('edit-message-template', $template1->id) }}">
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$template1->name}}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane slide-left" id="slide2">

                        <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                            <div>
                                <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tuningxTemplates as $template2)
                                            <tr role="row" class="redirect-click" data-redirect="{{ route('edit-message-template', $template2->id) }}">
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$template2->name}}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane slide-left" id="slide3">

                        <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                            <div>
                                <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($efilesTemplates as $template3)
                                            <tr role="row" class="redirect-click" data-redirect="{{ route('edit-message-template', $template3->id) }}">
                                                <td class="v-align-middle semi-bold sorting_1">
                                                    <p>{{$template3->name}}</p>
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
        
       
    });

</script>

@endsection