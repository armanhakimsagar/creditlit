@extends('Admin::layouts.master')
@section('body')
    <style>
        th:nth-last-child(2),
        th:last-child {
            text-align: center;
            vertical-align: middle;
        }

        td:nth-last-child(2),
        td:last-child {
            text-align: center;
            vertical-align: middle;
        }
    </style>

    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Examination::label.EXAM_RESULT')</li>
            <li class="breadcrumb-item active">@lang('Examination::label.EXAM')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Examination::label.EXAM_LIST')
            </h1>
            <a href="{{ route('exam.create') }}"
                class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">@lang('Academic::label.ADD') @lang('Examination::label.EXAM')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Examination::label.EXAM') <span class="fw-300"><i>@lang('Academic::label.LIST')</i></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="panel-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="yajraTable">
                            <thead class="thead-themed">
                                <th> No</th>
                                <th> Exam Name</th>
                                {{-- <th> Class Name</th> --}}
                                <th> Exam Type</th>
                                <th> Academic Year</th>
                                {{-- <th>Percentage for final</th> --}}
                                <th> Status</th>
                                <th> Action</th>
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

    <script>
        $(function() {
            $('table').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
            $('#yajraTable').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                "stateSave": true,
                "ajax": {
                    "url": "{{ route('exam.index') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'exam_name',
                        name: 'exam_name'
                    },
                    // {
                    //     data: 'class_name',
                    //     name: 'class_name'
                    // },
                    {
                        data: 'exam_type_name',
                        name: 'exam_type_name'
                    },
                    {
                        data: 'academic_year',
                        name: 'academic_year'
                    },
                    // {
                    //     data: 'percentage_for_final',
                    //     name: 'percentage_for_final'
                    // },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
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
