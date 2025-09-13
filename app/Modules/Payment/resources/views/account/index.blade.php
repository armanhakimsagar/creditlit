@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Payment::label.ACCOUNT_SETTING')</li>
            <li class="breadcrumb-item active">@lang('Payment::label.ACCOUNT')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i>@lang('Payment::label.ACCOUNT') @lang('Payment::label.LIST')
            </h1>
            <a href="{{ route('account.create') }}" class="btn btn-primary btn-sm waves-effect pull-right m-l-10">
                @lang('Payment::label.ADD') @lang('Payment::label.ACCOUNT')
            </a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Payment::label.ACCOUNT')<span class="fw-300"><i>@lang('Payment::label.LIST')</i></span>
                </h2>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table id="yajraTable" class="table table-striped table-bordered" style="width: 100%;">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>No</th>
                                        <th>Account ID</th>
                                        <th>Account Type</th>
                                        <th>Account Category</th>
                                        <th>Short Name</th>
                                        <th>Bank Name</th>
                                        <th>Bank Branch</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
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
                    serverSide: false,
                    ajax: "{{ route('account.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'AccountId',
                            name: 'AccountId'
                        },
                        {
                            data: 'account_type',
                            name: 'account_type'
                        },
                        {
                            data: 'category_name',
                            name: 'category_name '
                        },
                        {
                            data: 'ShortName',
                            name: 'ShortName '
                        },
                        {
                            data: 'BankName',
                            name: 'BankName '
                        },
                        {
                            data: 'BankBranch',
                            name: 'BankBranch '
                        },
                        {
                            data: 'AccountName',
                            name: 'AccountName '
                        },
                        {
                            data: 'AccountNumber',
                            name: 'AccountNumber '
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
