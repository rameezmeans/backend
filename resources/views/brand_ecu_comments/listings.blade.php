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
                        <h3>Brands ECU Comments</h3>
                    </div>
                    <div class="pull-right">
                        <div class="col-xs-12">
                            <button data-redirect="{{ route('create-brand-ecu-comment') }}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button">
                                <i class="pg-plus_circle"></i> <span class="bold">Create Comment</span>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper no-footer m-t-40">
                        <div>
                            <table class="table table-hover demo-table-search table-responsive-block dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                                <thead>
                                    <tr role="row">
                                        <th>Brand</th>
                                        <th>ECU</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brandEcuComments as $comment)
                                        <tr role="row" class="redirect-click" data-redirect="{{ route('edit-brand-ecu-comment', $comment->id) }}">
                                            <td class="v-align-middle semi-bold">
                                                <p>{{ $comment->brand }}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>{{ $comment->ecu }}</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>{{ $comment->type }}</p>
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