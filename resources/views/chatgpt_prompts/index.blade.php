@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid container-fixed-lg bg-white">

            <div class="card card-transparent m-t-40">
                <div class="card-header">
                    <div class="card-title">
                        <h3>ChatGPT Prompts</h3>
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{ route('chatgpt-prompts.create') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button">
                                <i class="pg-plus_circle"></i> <span class="bold">Create Prompt</span>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                        <div>
                            <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th>Title</th>
                                        <th>Prompt Preview</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prompts as $prompt)
                                        <tr role="row">
                                            <td class="v-align-middle semi-bold">
                                                <p>{{ $prompt->title }}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>{{ Str::limit($prompt->prompt, 100) }}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>{{ \Carbon\Carbon::parse($prompt->created_at)->format('m/d/Y') }}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <div class="btn-group">
                                                    <a href="{{ route('chatgpt-prompts.show', $prompt->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('chatgpt-prompts.edit', $prompt->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $prompt->id }}">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($prompts->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $prompts->links() }}
                            </div>
                        @endif
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
        // Handle delete button clicks
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
                                location.reload();
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
