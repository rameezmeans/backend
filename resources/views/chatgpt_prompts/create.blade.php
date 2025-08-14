@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="content sm-gutter">
        <div class="container-fluid container-fixed-lg bg-white">

            <div class="card card-transparent m-t-40">
                <div class="card-header">
                    <div class="card-title">
                        <h5>Add ChatGPT Prompt</h5>
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
                    <form method="POST" action="{{ route('chatgpt-prompts.store') }}">
                        @csrf

                        <div class="form-group form-group-default required">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title') }}" placeholder="Enter prompt title">
                        </div>
                        @error('title')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="form-group form-group-default required">
                            <label>Prompt</label>
                            <textarea name="prompt" class="form-control" rows="8" required placeholder="Enter your ChatGPT prompt here...">{{ old('prompt') }}</textarea>
                            <small class="form-text text-muted">This is the prompt that will be sent to ChatGPT. You can include placeholders like {text}, {tone}, etc.</small>
                        </div>
                        @error('prompt')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="text-center m-t-40">
                            <button class="btn btn-success btn-cons m-b-10" type="submit">
                                <i class="pg-plus_circle"></i> 
                                <span class="bold">Add Prompt</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
