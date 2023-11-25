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
                    <h3>Original Files</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Producer</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Series</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Model</th>
                                    {{-- <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">File</th> --}}
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($originalFiles as $file)
                                    <tr role="row" class="">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$file->Producer}}</p>
                                            
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <p>{{$file->Series}}</p>
                                            
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <p>{{$file->Model}}</p>
                                           
                                            
                                        </td>
                                        {{-- <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <p>{{$file->File}}</p>
                                           
                                        </td> --}}
                                        <td class="v-align-middle semi-bold sorting_1">
                                            
                                            <a target="_blank" href="{{route('download-original-file', $file->id)}}" class="btn btn-success">Download</a>
                                           
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <div>{{$originalFiles->render();}}</div> --}}
                    <div>{{$originalFiles->onEachSide(5)->links()}}</div>
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