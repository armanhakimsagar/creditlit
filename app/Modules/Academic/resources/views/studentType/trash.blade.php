@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Academic::label.ACADEMICS')</li>
            <li class="breadcrumb-item active">@lang('Academic::label.DELETED') @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE') @lang('Academic::label.TRASH')
            </h1>
            <a href="{{ route('student.type.index') }}" class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">@lang('Academic::label.ALL')
                @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Academic::label.DELETED') <span class="fw-300"><i>@lang('Academic::label.STUDENT') @lang('Academic::label.TYPE') @lang('Academic::label.LIST')</i></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="panel-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="yajraTable">
                            <thead class="thead-themed">
                                <th> No</th>
                                <th> Name</th>
                                {{-- <th> Slug</th> --}}
                                <th class="status-column"> Status</th>
                                <th class="action-column"> Action</th>
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
                    "url": "{{ route('student.type.trash') }}",
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
                        name: 'status',
                        className: 'status-column'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'status-column',
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
