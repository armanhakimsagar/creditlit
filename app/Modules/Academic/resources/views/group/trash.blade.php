@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Academic::label.ACADEMIC')</li>
            <li class="breadcrumb-item active">@lang('Academic::label.DELETED') @lang('Academic::label.GROUP')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Academic::label.GROUP_LIST')
            </h1>
            <a href="{{ route('group.index') }}" class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">@lang('Academic::label.ALL')
                @lang('Academic::label.GROUP')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Academic::label.DELETED') <span class="fw-300"><i>@lang('Academic::label.GROUP') @lang('Academic::label.LIST')</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table class="table m-0 myTable table-bordered table-striped" id="yajraTable">
                                <thead class="thead-themed">
                                    <tr>
                                        <th> No</th>
                                        <th> Name</th>
                                        <th class="status-column"> Status</th>
                                        <th class="action-column"> Action</th>
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
                    ajax: "{{ route('group.trash') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'name',
                            name: 'name'
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
