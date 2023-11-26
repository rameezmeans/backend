@extends('layouts.app')

@section('pagespecificstyles')

<style>



</style>
@endsection


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

                <form method="GET" action="{{route('filter-original-files')}}">
                    @csrf

                    
                        <div class="form-group form-group-default required ">
                            <label>Producer</label>
                            <select name="Producer" class="full-width select2-hidden-accessible" data-placeholder="Select Type" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                              @foreach($producerObjects as $p)
                              <option @if(isset($producer) && $producer && $producer==$p->Producer) selected @endif value="{{ $p->Producer }}">{{$p->Producer}}</option>
                              @endforeach
                            </select>
                          </div>
                        <div class="form-group">
                            <label>Series</label>
                            <select name="Series" class="full-width select2-hidden-accessible" data-placeholder="Select Series" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                                @if($seriesObjects)
                                    @foreach ($seriesObjects as $s)
                                    <option @if(isset($series) && $series && $series==$s->Series) selected @endif value="{{ $s->Series }}">{{$s->Series}}</option>
                                    @endforeach
                                @endif
                            </select>
                          
                        </div>
                        <div class="form-group">
                            <label>Model</label>
                            <select name="Series" class="full-width select2-hidden-accessible" data-placeholder="Select Model" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                                @if($modelsObjects)
                                    @foreach ($modelsObjects as $m)
                                    <option @if(isset($model) && $model && $model==$m->Model) selected @endif value="{{ $m->Model }}">{{$m->Model}}</option>
                                    @endforeach
                                @endif
                            </select>
                          
                        </div>
                        
                        <button type="submit" class="btn btn-success">Search</button>
                        <button type="button" class="btn btn-warning" id="reset_filter">Reset</button>

                     

                </form>

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
                    <div class="m-t-20">
                        {!! $originalFiles->links() !!}
                    </div>
                    <div class="m-t-20">Page {{$originalFiles->currentPage()}} out of {{$originalFiles->total()}}</div>
                    {{-- <div>{{$originalFiles->render()}}</div> --}}
                    
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">
    $(document).ready(function(event) {

        $('#reset_filter').click(function(e) {
            $("#Producer").val($("#producer option:first").val());
            $("#Series").val($("#series option:first").val());
            $("#Model").val($("#model option:first").val());

            window.location = '/original_files';
        });

        $(document).on('change', '#Producer', function(e) {
            let producer = $(this).val();
            disable_dropdowns();

            $.ajax({
                url: "/get_series",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'producer': producer
                },
                success: function(items) {
                    console.log(items);

                    $('#series').removeAttr('disabled');
                    $.each(items.series, function(i, item) {
                        console.log(item.series);
                        $('#Series').append($('<option>', {
                            value: item.Series,
                            text: item.Series
                        }));
                    });
                }
            });
        });

    });

    $(document).on('change', '#Series', function(e) {
        let producer = $('#producer').val();
        let series = $(this).val();

        $.ajax({
            url: "/get_models_orignal_files",
            type: "POST",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'producer': producer,
                'series': series
            },
            success: function(items) {
                console.log(items);

                $('#model').removeAttr('disabled');
                $.each(items.models, function(i, item) {
                    console.log(item.models);
                    $('#Model').append($('<option>', {
                        value: item.Model,
                        text: item.Model
                    }));
                });
            }
        });
    });

    function disable_dropdowns() {

        $('#Series').children().remove();
        $('#Series').append('<option selected value="">Series</option>');
        $('#Model').children().remove();
        $('Model').append('<option selected value="">Models</option>');

        $('#Series').attr('disabled', 'disabled');
        $('#Model').attr('disabled', 'disabled');

    }

</script>

@endsection