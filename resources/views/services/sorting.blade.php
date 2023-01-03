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
                    <h3>Sorting Services</h3>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="row border b-grey-10 m-b-10">
                    <div class="col-lg-12">
                        <h5>Stages</h5>
                            <div class="cf nestable-lists">
                                <div class="dd" id="stages-nestable">
                                <ol class="dd-list">
                                    @foreach ($stages as $item)
                                        <li class="dd-item" data-id="{{$item->id}}">
                                            <div class="dd-handle">
                                                <img style="width: 3%;" src="{{'https://backend.ecutech.gr/icons/'.$item->icon}}" alt="{{$item->name}}">
                                                {{$item->name}}
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="row  border b-grey-10">
                    <div class="col-lg-12">
                        <h5>Options</h5>
                        <div class="cf nestable-lists">
                            <div class="dd" id="options-nestable">
                                <ol class="dd-list">
                                    @foreach ($options as $item)
                                    <li class="dd-item" data-id="{{$item->id}}">
                                        <div class="dd-handle">
                                            <img style="width: 3%;" src="{{'https://backend.ecutech.gr/icons/'.$item->icon}}" alt="{{$item->name}}">
                                            {{$item->name}}
                                        </div>
                                    </li>
                                @endforeach
                                </ol>
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
    (function($) {

'use strict';

$(document).ready(function() {

    toastr.options.closeButton = true;
    
    var updateStages = function(e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {

            $.ajax({
                url: "/sort_services",
                type: "POST",
                data: {
                    sorting: window.JSON.stringify(list.nestable('serialize')),
                    type: 'tunning',
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    toastr.success('Stages are Sorted!', 'Sorting Saved');
                }
            });

        } else {
            console.log('JSON browser support required for this demo.');
        }
    };

    var updateOptions = function(ee) {
        var list = ee.length ? ee : $(ee.target),
        output = list.data('output');
        if (window.JSON) {

            $.ajax({
                url: "/sort_services",
                type: "POST",
                data: {
                    sorting: window.JSON.stringify(list.nestable('serialize')),
                    type: 'option',
                },
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    toastr.success('Options are Sorted!', 'Sorting Saved');
                }
            });

        } else {
            console.log('JSON browser support required for this demo.');
        }
    };
    
    $('#options-nestable').nestable({
            group: 1,
            maxDepth: 1,
        })
    .on('change', updateOptions);

    $('#stages-nestable').nestable({
        group: 1,
        maxDepth: 1,
    })
    .on('change', updateStages);

    // updateOptions($('#options-nestable').data('output', $('#nestable-output')));
    // updateStages($('#stages-nestable').data('output', $('#nestable-output')));

});

})(window.jQuery);
</script>

@endsection