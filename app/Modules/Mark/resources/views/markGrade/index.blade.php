@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Mark::label.MARKS')</li>
            <li class="breadcrumb-item active">@lang('Mark::label.MARK') @lang('Mark::label.GRADE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Mark::label.MARK') @lang('Mark::label.GRADE') @lang('Academic::label.LIST')
            </h1>
            <div class="col-xl-10">
                <a href="{{ route('mark.grade.create') }}"
                    class="btn btn-primary btn-sm pull-right m-l-10 float-right">@lang('Academic::label.ADD') @lang('Mark::label.MARK') @lang('Mark::label.GRADE')</a>
            </div>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Mark::label.MARK') @lang('Mark::label.GRADE') <span class="fw-300"><i>@lang('Academic::label.LIST')</i></span>
                    </h2>
                </div>
                {{-- <div class="col-8">
                    <a href="{{ route('class.trash') }}" class="btn btn-danger btn-sm waves-effect float-right"
                        data-toggle="tooltip" data-placement="top" title="Trash"><i class="fas fa-trash-restore"></i></a>
                </div> --}}
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ytable" id="yajraTable">
                                <thead class="thead-themed">
                                    <tr>
                                        <th> No</th>
                                        <th> Mark Range</th>
                                        <th> Grades</th>
                                        {{-- <th> points</th> --}}
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
        <!-- AJAX -->

        <script type="text/javascript">
            // Class table index
            $(function() {
                $('table').on('draw.dt', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                var table = $('#yajraTable').DataTable({
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    searchDelay: 1000,
                    ajax: "{{ route('mark.grade.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.start_mark + '-' + row.end_mark;
                            },
                            name: 'mark_range'
                        },
                        {
                            data: 'grade',
                            name: 'grade'
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
                            searchable: false
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

                // $("input[type=search]").keyup(function() {
                //     table.search($(this).val()).draw();
                // });
            });
        </script>
    @endsection
