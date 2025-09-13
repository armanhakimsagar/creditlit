@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Payment::label.ACCOUNT_SETTING')</li>
            <li class="breadcrumb-item active">@lang('Academic::label.DELETED') @lang('Payment::label.ACCOUNT') @lang('Payment::label.CATEGORY') </li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i>@lang('Payment::label.ACCOUNT') @lang('Payment::label.CATEGORY') @lang('Academic::label.TRASH')
            </h1>
            <a href="{{route('account.category.index')}}"
                class="btn btn-primary btn-sm waves-effect pull-right m-l-10"> @lang('Academic::label.ALL') @lang('Payment::label.ACCOUNT') @lang('Payment::label.CATEGORY')
            </a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Academic::label.DELETED') <span class="fw-300"><i>@lang('Payment::label.ACCOUNT') @lang('Payment::label.CATEGORY') @lang('Payment::label.LIST')</i></span>
                    </h2>
                </div>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table id="yajraTable" class="table table-striped table-bordered" style="width: 100%;">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>No</th>
                                        <th>Account Type</th>
                                        <th>Account Category</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function() {
                $('table').on('draw.dt', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                var table = $('#yajraTable').DataTable({
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('account.category.trash') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'account_type',
                            name: 'account_type'
                        },
                        {
                            data: 'TypeName',
                            name: 'TypeName '
                        },
                        {
                            data: 'status',
                            name: 'status',
                            className: 'status-column'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            className: 'action-column',
                            orderable: false,
                            searchable: true
                        },
                    ],
                    select: {
                        style: 'single'
                    }
                });
            });
        </script>
    @endsection
