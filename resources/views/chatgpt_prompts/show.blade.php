@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="content sm-gutter">
        <div class="container-fluid container-fixed-lg bg-white">

            <div class="card card-transparent m-t-40">
                <div class="card-header">
                    <div class="card-title">
                        <h5>ChatGPT Prompt Details</h5>
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{ route('chatgpt-prompts.index') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button">
                                <i class="pg-plus_circle"></i> <span class="bold">ChatGPT Prompts</span>
                            </button>
                            <a href="{{ route('chatgpt-prompts.edit', $chatgptPrompt->id) }}" class="btn btn-warning btn-cons m-b-10">
                                <i class="pg-edit"></i> <span class="bold">Edit</span>
                            </a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>Title:</strong></label>
                                <div class="form-control-static">
                                    {{ $chatgptPrompt->title }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>Prompt:</strong></label>
                                <div class="form-control" style="min-height: 200px; background-color: #f8f9fa; color: #000000; border: 1px solid #ced4da; font-size: 14px; line-height: 1.5; white-space: pre-wrap;" readonly>
                                    {{ $chatgptPrompt->prompt }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Created At:</strong></label>
                                <div class="form-control-static">
                                    {{ \Carbon\Carbon::parse($chatgptPrompt->created_at)->format('F j, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Last Updated:</strong></label>
                                <div class="form-control-static">
                                    {{ \Carbon\Carbon::parse($chatgptPrompt->updated_at)->format('F j, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center m-t-40">
                        <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{ $chatgptPrompt->id }}" type="button">
                            <i class="pg-minus_circle"></i> 
                            <span class="bold">Delete Prompt</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagespecificscripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-delete').click(function() {
            const promptId = $(this).data('id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the prompt!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/chatgpt-prompts/${promptId}`,
                        type: "POST",
                        data: {
                            _method: 'DELETE',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "ChatGPT Prompt deleted successfully.",
                                icon: "success",
                                timer: 2000
                            }).then(() => {
                                window.location.href = "{{ route('chatgpt-prompts.index') }}";
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "Failed to delete the prompt.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
