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
                    <h3>Country Report</h3>
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
                    @if(isset($duration))
                        <div class="col-lg-4">
                            <p>Time: <span>{{$duration}}</span></p>
                            <p>Frontend: <span>{{\App\Models\FrontEnd::findOrFail($frontend)->name}}</span></p>
                        </div>
                    @endif

                    @if(isset($start))
                        <div class="col-lg-4">
                            <p>Start Date: <span>{{$start}}</span></p>
                            <p>End Date: <span>{{$end}}</span></p>
                            <p>Frontend: <span>{{\App\Models\FrontEnd::findOrFail($frontend)->name}}</span></p>
                        </div>
                    @endif

                </div>
                @if(isset($duration))
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        {{-- <div id="progress" class="text-center">
                            <div class="progress-circle-indeterminate m-t-45" style="">
                            </div>
                            <br>
                        </div> --}}
                        <button id="export" class="btn btn-success " type="button"><i class="pg-plus_circle"></i> <span class="bold">Export To Excel</span>
                        </button>
                        <table class="table table-hover demo-table-search innner-dataTable table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Country</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Customers Registered</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Files</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Credits</th>
                                  
                                    
                                </tr>
                            </thead>
                            <tbody id="recordsRows">

                                @php
                                    $r1 = 0;
                                    $r2 = 0;
                                    $r3 = 0;
                                    $r4 = 0;
                                    
                                @endphp

                                @foreach ($table1 as $key => $row)
                                    
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{code_to_country($key)}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[0]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[1]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[2]}}</p>
                                        </td>
                                        
                                        

                                    </tr>

                                    @php
                                            $r1++;
                                            $r2 += $row[0];
                                            $r3 += $row[1];
                                            $r4 += $row[2];
                                            
                                            
                                        @endphp

                                @endforeach

                                <tr role="row">
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r1}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r2}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r3}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r4}}</p>
                                    </td>
                                    
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if(isset($start))
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        {{-- <div id="progress" class="text-center">
                            <div class="progress-circle-indeterminate m-t-45" style="">
                            </div>
                            <br>
                        </div> --}}
                        <button id="export" class="btn btn-success" type="button"><i class="pg-plus_circle"></i> <span class="bold">Export To Excel</span>
                        </button>

                        <table class="table table-hover demo-table-search innner-dataTable table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Country</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Customers Registered</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Max Per Day</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Min Per Day</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Avg</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Files (All Users from Country)</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Credits (All Users from Country)</th>
                                  
                                    
                                </tr>
                            </thead>
                            <tbody id="recordsRows">

                                @php
                                    $r1 = 0;
                                    $r2 = 0;
                                    $r3 = 0;
                                    $r4 = 0;
                                    $r5 = 0;
                                    $r6 = 0;
                                    $r7 = 0;
                                    $r8 = 0;
                                    $r9 = 0;
                                @endphp

                                @foreach ($table2 as $key => $row)
                                    
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{code_to_country($key)}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[0]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[3]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[4]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[5]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[1]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[2]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[6]}}</p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$row[7]}}</p>
                                        </td>
                                        
                                        @php
                                            $r1++;
                                            $r2 += $row[0];
                                            $r3 += $row[1];
                                            $r4 += $row[2];
                                            $r5 += $row[3];
                                            $r6 += $row[4];
                                            $r7 += $row[5];
                                            $r8 += $row[6];
                                            $r9 += $row[7];
                                            
                                        @endphp
                                        

                                    </tr>

                                @endforeach

                                <tr role="row">
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r1}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r2}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r5}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r6}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r7}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r3}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r4}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r3}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r4}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r8}}</p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>Total: {{$r9}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif


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
                name:"customer_countries_report",
                filename:"customer_countries_report",//do not include extension
                fileext:".xls" // file extension
            });
        });
    });

</script>

@endsection