@extends('Admin::layouts.master')
@section('body')
    <style>
        th:nth-child(3),
        th:nth-child(4) {
            text-align: center;
            vertical-align: middle;
        }

        td:nth-child(3),
        td:nth-child(4) {
            text-align: center;
            vertical-align: middle;
        }
    </style>

    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Examination::label.EXAM_RESULT')</li>
            <li class="breadcrumb-item active">@lang('Academic::label.DELETED') @lang('Examination::label.EXAM_TYPE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Examination::label.EXAM_TYPE') @lang('Academic::label.TRASH')
            </h1>
            <a href="{{ route('exam_type.index') }}" class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">@lang('Academic::label.ALL')
                @lang('Examination::label.EXAM_TYPE')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Academic::label.DELETED') @lang('Examination::label.EXAM_TYPE') <span class="fw-300"><i>@lang('Academic::label.LIST')</i></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="panel-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="yajraTable">
                            <thead class="thead-themed">
                                <th> No</th>
                                <th> Name</th>
                                <th> Status</th>
                                <th> Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <table>

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
            $('#yajraTable').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                "stateSave": true,
                "ajax": {
                    "url": "{{ route('exam_type.trash') }}",
                },
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
        });
    </script>
@endsection
