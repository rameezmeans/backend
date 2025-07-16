@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="content sm-gutter">
        <div class="container-fluid container-fixed-lg bg-white">

            <div class="card card-transparent m-t-40">
                <div class="card-header">
                    <div class="card-title">
                        <h5>Add Brand ECU Comment</h5>
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{route('brand-ecu-comments')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button">
                                <i class="pg-plus_circle"></i> <span class="bold">Back to List</span>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('add-brand-ecu-comment') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Brand Select --}}
                        <div class="form-group form-group-default required form-group-default-select2">
                            <label>Brand</label>
                            <select class="full-width" data-init-plugin="select2" name="brand" id="brand-select" required>
                                <option value="">Select a Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->make }}">{{ $brand->make }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ECU Select --}}
                        <div class="form-group form-group-default required form-group-default-select2">
                            <label>ECU</label>
                            <select class="full-width" data-init-plugin="select2" name="ecu" id="ecu-select" required>
                                <option value="">Select a Brand First</option>
                            </select>
                        </div>

                        {{-- Type Select --}}
                        <div class="form-group form-group-default required">
                            <label>Type</label>
                            <select class="form-control" name="type" required>
                                <option value="">Select Type</option>
                                <option value="download">Download</option>
                                <option value="upload">Upload</option>
                            </select>
                        </div>

                        {{-- Comment Textarea --}}
                        <div class="form-group form-group-default required">
                            <label>Comment</label>
                            <textarea class="form-control" name="comment" rows="4" required></textarea>
                        </div>

                        <div class="text-center m-t-40">
                            <button class="btn btn-success btn-cons m-b-10" type="submit">
                                <i class="pg-plus_circle"></i> <span class="bold">Add</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('pagespecificscripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#brand-select').on('change', function () {
            var brandMake = $(this).val();
            $('#ecu-select').html('<option>Loading...</option>');

            if (brandMake) {
                $.ajax({
                    url: '/get_ecus_by_brand/' + brandMake,
                    type: 'GET',
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        $('#ecu-select').empty().append('<option value="">Select ECU</option>');
                        $.each(data, function (key, value) {
                            $('#ecu-select').append('<option value="' + value + '">' + value + '</option>');
                        });
                    },
                    error: function () {
                        $('#ecu-select').html('<option value="">Error loading ECUs</option>');
                    }
                });
            } else {
                $('#ecu-select').html('<option value="">Select a Brand First</option>');
            }
        });
    });
</script>
@endsection