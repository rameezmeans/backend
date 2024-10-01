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
                    <h3>Search Engineer and Client Messages</h3>
                </div>
                <div class="pull-right">
                
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              
                <form method="POST" action="{{route('get-search-results')}}">
                    <div class="row">
                        
                        @csrf
                        <div class="col-lg-10">
                            <div class="form-group form-group-default input-group">
                                <div class="form-input-group">
                                    <label>Keyword</label>
                                    <input type="input" style="margin-bottom: 17px;" class="form-control" placeholder="Put Your Text here" name="keyword" value="@if(isset($request->keyword) && $request->keyword != NULL){{$request->keyword}}@endif">
                                </div>
                                


                                </div>
                            </div>
                        <div class="col-lg-2">
                            <input class="btn btn-success" type="submit" value="Search">
                        </div>
                        
                    </div>
                </form>
                @if(isset($results))
                <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                    <div>
                        <table class="table table-hover demo-table-search table-responsive-block no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Task</th>
                                    <th class="" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending">Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($results as $result)
                                    <tr role="row">
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p><a href="{{route('file',$result->file_id)}}" target="_blank">{{'Task'.$result->file_id}}</a></p>
                                        </td>
                                        <td class="v-align-middle semi-bold sorting_1">
                                            <p>{{$result->egnineers_internal_notes}}</p>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                                
                                @if(sizeof($_POST == 0))
                                    {!! $results->appends($_GET)->links() !!}
                                @else
                                    {!! $results->appends($_POST)->links() !!}
                                @endif
                                
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
        
        
    });

</script>

@endsection