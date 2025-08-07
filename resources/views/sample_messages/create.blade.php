@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="content sm-gutter">
        <div class="container-fluid container-fixed-lg bg-white">

            <div class="card card-transparent m-t-40">
                <div class="card-header">
                    <div class="card-title">
                        <h5>
                            @if(isset($sampleMessage))
                                Edit Sample Message
                            @else
                                Add Sample Message
                            @endif
                        </h5>
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{ route('sample-messages.index') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button">
                                <i class="pg-plus_circle"></i> <span class="bold">Sample Messages</span>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="card-body">
                    <form method="POST" action="@if(isset($sampleMessage)){{ route('sample-messages.update', $sampleMessage->id) }}@else{{ route('sample-messages.store') }}@endif">
                        @csrf
                        @if(isset($sampleMessage))
                            @method('PUT')
                        @endif

                        <div class="form-group form-group-default required">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title', $sampleMessage->title ?? '') }}">
                        </div>
                        @error('title')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="form-group form-group-default required">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="5" required>{{ old('message', $sampleMessage->message ?? '') }}</textarea>
                        </div>
                        @error('message')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="text-center m-t-40">
                            <button class="btn btn-success btn-cons m-b-10" type="submit">
                                <i class="pg-plus_circle"></i> 
                                <span class="bold">@if(isset($sampleMessage)) Update @else Add @endif</span>
                            </button>

                            @if(isset($sampleMessage))
                                <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{ $sampleMessage->id }}" type="button">
                                    <i class="pg-minus_circle"></i> 
                                    <span class="bold">Delete</span>
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
@if(isset($sampleMessage))
<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-delete').click(function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the message!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('sample-messages.destroy', $sampleMessage->id) }}",
                        type: "POST",
                        data: {
                            _method: 'DELETE'
                        },
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Sample message deleted successfully.",
                                icon: "success",
                                timer: 2000
                            });
                            window.location.href = "{{ route('sample-messages.index') }}";
                        }
                    });
                }
            });
        });
    });
</script>
@endif
@endsection