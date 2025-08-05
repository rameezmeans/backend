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
                        <h3>Sample Messages</h3>
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{ route('sample-messages.create') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button">
                                <i class="pg-plus_circle"></i> <span class="bold">Create Message</span>
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
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sampleMessages as $message)
                                        <tr role="row" class="redirect-click" data-redirect="{{ route('sample-messages.edit', $message->id) }}">
                                            <td class="v-align-middle semi-bold">
                                                <p>{{ $message->title }}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>{{ \Carbon\Carbon::parse($message->created_at)->format('m/d/Y') }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    $(document).ready(function() {
        // Optional: Handle row redirection
    });
</script>
@endsection