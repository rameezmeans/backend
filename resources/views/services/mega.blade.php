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
                    <h3>Country Service Report</h3>
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
                <div class="row">
                    <div class="col-lg-4">
                        
                    </div>
                    <div class="col-lg-4">
                        
                    </div>
                    

                    
                        <div class="col-lg-4">
                            <p>Start Date: <span>{{$start}}</span></p>
                            <p>End Date: <span>{{$end}}</span></p>
                            <p>Frontend: <span>{{\App\Models\FrontEnd::findOrFail($frontend)->name}}</span></p>
                            <p>Countries: @foreach($countries as $c)<span>{{code_to_country($c).', '}}</span>@endforeach</p>
                        </div>
                    

                </div>
                
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        
                        <button id="export" class="btn btn-success " type="button"><i class="pg-plus_circle"></i> <span class="bold">Export To Excel</span>
                        </button>
                        <table class="table table-hover demo-table-search innner-dataTable table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">

                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Countries</th>
                                    @foreach(reset($megaArr) as $key => $value)
                                        <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">{{\App\Models\Service::findOrFail($key)->name}}</th>
                                    @endforeach

                                </tr>
                            </thead>
                            <tbody id="recordsRows">

                                @php
                                    dd($megaArr);

                                @endphp

                                @foreach ($countries as $value)
                                <tr role="row">
                                    <td><p>{{code_to_country($value)}}</p></td>
                                    @foreach($megaArr as $key => $v)
                                        @foreach($v as $i)
                                            <td><p>{{$i}}</p></td>
                                        @endforeach
                                       
                                    @endforeach
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

        let table = $('.innner-dataTable').DataTable({
            "aaSorting": [],
            "bPaginate": false,
        });

        $("#export").click(function(){
            console.log('export button clicked');
            $("#tableWithSearch").table2excel({
                // exclude CSS class
                exclude:".noExl",
                name:"services_countries_report",
                filename:"services_countries_report",//do not include extension
                fileext:".xls" // file extension
            });
        });
    });

</script>

@endsection