@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Academic::label.ACADEMIC')</li>
            <li class="breadcrumb-item active">@lang('Academic::label.ACADEMIC_YEAR')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Academic::label.ACADEMIC_YEAR_LIST')
            </h1>
            <a href="{{ route('academic.year.create') }}"
                class="btn btn-sm btn-primary waves-effect pull-right m-l-10">@lang('Academic::label.ADD_ACADEMIC_YEAR')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Academic::label.ACADEMIC_YEAR') <span class="fw-300"><i>@lang('Academic::label.LIST')</i></span>
                    </h2>
                </div>
                <div class="col-8">
                    <a href="{{ route('academic.year.trash') }}"
                        class="btn btn-danger btn-sm waves-effect float-right" data-toggle="tooltip" data-placement="top" title="Trash"><i class="fas fa-trash-restore"></i>
                    </a>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table class="table table-striped myTable" id="yajraTable">
                                <thead class="thead-themed">
                                    <tr>
                                        <th> No</th>
                                        <th> Year</th>
                                        <th> Start Date</th>
                                        <th> End Date</th>
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
                var table = $('#yajraTable').DataTable({
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('academic.year.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'year',
                            name: 'year'
                        },
                        {
                            data: 'start_date',
                            name: 'start_date'
                        },
                        {
                            data: 'end_date',
                            name: 'end_date'
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


                $('#yajraTable_filter input').unbind();
                $('#yajraTable_filter input').bind('keyup', function(e) {
                    if (e.keyCode === 13) {
                        $('#yajraTable').DataTable().search(this.value).draw();
                    }
                });
            });
        </script>
    @endsection
