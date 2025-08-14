@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="content sm-gutter">
        <div class="container-fluid container-fixed-lg bg-white">

            <div class="card card-transparent m-t-40">
                <div class="card-header">
                    <div class="card-title">
                        <h5>Edit ChatGPT Prompt</h5>
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{ route('chatgpt-prompts.index') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button">
                                <i class="pg-plus_circle"></i> <span class="bold">ChatGPT Prompts</span>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('chatgpt-prompts.update', $chatgptPrompt->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group form-group-default required">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title', $chatgptPrompt->title) }}" placeholder="Enter prompt title">
                        </div>
                        @error('title')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="form-group form-group-default required">
                            <label>Prompt</label>
                            <textarea name="prompt" class="form-control" rows="8" required placeholder="Enter your ChatGPT prompt here...">{{ old('prompt', $chatgptPrompt->prompt) }}</textarea>
                            {{-- <small class="form-text text-muted">This is the prompt that will be sent to ChatGPT. You can include placeholders like {text}, {tone}, etc.</small> --}}
                        </div>
                        @error('prompt')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="text-center m-t-40">
                            <button class="btn btn-success btn-cons m-b-10" type="submit">
                                <i class="pg-plus_circle"></i> 
                                <span class="bold">Update Prompt</span>
                            </button>

                            <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{ $chatgptPrompt->id }}" type="button">
                                <i class="pg-minus_circle"></i> 
                                <span class="bold">Delete</span>
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
