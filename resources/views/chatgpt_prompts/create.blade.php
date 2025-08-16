@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="content sm-gutter">
        <div class="container-fluid container-fixed-lg bg-white">

            <div class="card card-transparent m-t-40">
                <div class="card-header">
                    <div class="card-title">
                        <h5>
                            @if(isset($chatgptPrompt))
                                Edit ChatGPT Prompt
                            @else
                                Add ChatGPT Prompt
                            @endif
                        </h5>
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
                    <form method="POST" action="@if(isset($chatgptPrompt)){{ route('chatgpt-prompts.update', $prompt->id) }}@else{{ route('chatgpt-prompts.store') }}@endif">
                        @csrf
                        @if(isset($prompt))
                            @method('PUT')
                        @endif

                        <div class="form-group form-group-default required">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title', $prompt->title ?? '') }}">
                        </div>
                        @error('title')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="form-group form-group-default required">
                            <label>Prompt</label>
                            <textarea name="prompt" class="form-control" rows="5" required>{{ old('prompt', $prompt->prompt ?? '') }}</textarea>
                        </div>
                        @error('prompt')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="text-center m-t-40">
                            <button class="btn btn-success btn-cons m-b-10" type="submit">
                                <i class="pg-plus_circle"></i> 
                                <span class="bold">@if(isset($prompt)) Update @else Add @endif</span>
                            </button>

                            @if(isset($prompt))
                                <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{ $prompt->id }}" type="button">
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
@if(isset($prompt))
<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-delete').click(function() {
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
                        url: "{{ route('chatgpt-prompts.destroy', $prompt->id) }}",
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
                                text: "ChatGPT prompt deleted successfully.",
                                icon: "success",
                                timer: 2000
                            });
                            window.location.href = "{{ route('chatgpt-prompts.index') }}";
                        }
                    });
                }
            });
        });
    });
</script>
@endif
@endsection