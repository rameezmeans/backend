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
                    <form method="POST" action="{{ $editMode ?? false ? route('update-brand-ecu-comment') : route('add-brand-ecu-comment') }}" enctype="multipart/form-data">
                        @csrf

                        @if($editMode ?? false)
                            <input type="hidden" name="id" value="{{ $commentEntry->id }}">

                            {{-- Brand Label --}}
                            <div class="form-group form-group-default">
                                <label>Brand</label>
                                <p class="form-control-static">{{ $commentEntry->brand }}</p>
                            </div>

                            {{-- ECU Label --}}
                            <div class="form-group form-group-default">
                                <label>ECU</label>
                                <p class="form-control-static">{{ $commentEntry->ecu }}</p>
                            </div>

                            {{-- Type Label --}}
                            <div class="form-group form-group-default">
                                <label>Type</label>
                                <p class="form-control-static text-capitalize">{{ $commentEntry->type }}</p>
                            </div>

                            {{-- Editable Comment Textarea --}}
                            <div class="form-group form-group-default required {{ $errors->has('comment') ? 'has-error' : '' }}">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment" rows="4" required>{{ old('comment', $commentEntry->comment ?? '') }}</textarea>
                                @error('comment')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                        {{-- Brand Select --}}
                            <div class="form-group form-group-default required form-group-default-select2 {{ $errors->has('brand') ? 'has-error' : '' }}">
                                <label>Brand</label>
                                <select class="full-width" data-init-plugin="select2" name="brand" id="brand-select" required>
                                    <option value="">Select a Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->make }}" {{ old('brand') == $brand->make ? 'selected' : '' }}>{{ $brand->make }}</option>
                                    @endforeach
                                </select>
                                @error('brand')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- ECU Select --}}
                            <div class="form-group form-group-default required form-group-default-select2 {{ $errors->has('ecu') ? 'has-error' : '' }}">
                                <label>ECU</label>
                                <select class="full-width" data-init-plugin="select2" name="ecu" id="ecu-select" required>
                                    <option value="">Select a Brand First</option>
                                </select>
                                @error('ecu')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Type Select --}}
                            <div class="form-group form-group-default required {{ $errors->has('type') ? 'has-error' : '' }}">
                                <label>Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="download" {{ old('type') == 'download' ? 'selected' : '' }}>Download</option>
                                    <option value="upload" {{ old('type') == 'upload' ? 'selected' : '' }}>Upload</option>
                                </select>
                                @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Comment Textarea --}}
                            <div class="form-group form-group-default required {{ $errors->has('comment') ? 'has-error' : '' }}">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment" rows="4" required>{{ old('comment') }}</textarea>
                                @error('comment')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="text-center m-t-40">
                            <button class="btn btn-success btn-cons m-b-10" type="submit">
                                <i class="pg-plus_circle"></i> <span class="bold">{{ $editMode ?? false ? 'Update' : 'Add' }}</span>
                            </button>
                            @if($editMode ?? false)
                            <button type="button" class="btn btn-danger btn-cons m-b-10 delete-button"
                                data-id="{{ $commentEntry->id }}"
                                data-url="{{ route('delete-brand-ecu-comment', $commentEntry->id) }}">
                                <i class="pg-trash"></i> <span class="bold">Delete</span>
                            </button>
                            @endif
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

    $(document).on('click', '.delete-button', function () {
        var id = $(this).data('id');
        var url = $(this).data('url');

        swal({
            title: "Are you sure?",
            text: "You won’t be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    swal("Deleted!", "The record has been deleted.", "success");
                    setTimeout(function () {
                        window.location.href = "{{ route('brand-ecu-comments') }}";
                    }, 1000);
                },
                error: function (xhr) {
                    swal("Error!", "Something went wrong.", "error");
                }
            });
        });
    });

</script>
@endsection