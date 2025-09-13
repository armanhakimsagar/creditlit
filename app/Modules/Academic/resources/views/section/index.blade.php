@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Academic::label.ACADEMICS')</li>
            <li class="breadcrumb-item active">@lang('Academic::label.SECTION')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Academic::label.SECTION_LIST')
            </h1>
            <a href="{{ route('section.create') }}" class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">Add
                Section</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Academic::label.SECTION') <span class="fw-300"><i>@lang('Academic::label.LIST')</i></span>
                    </h2>
                </div>
                <div class="col-8">
                    <a href="{{ route('section.trash') }}" class="btn btn-danger btn-sm waves-effect float-right"
                        data-toggle="tooltip" data-placement="top" title="Trash"><i class="fas fa-trash-restore"></i>
                    </a>
                </div>

            </div>

            <div class="panel-container show">

                <div class="panel-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="yajraTable">
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
            $('#yajraTable').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                "stateSave": true,
                "ajax": {
                    "url": "{{ route('section.index') }}",
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
                        className: 'action-column',
                        orderable: true,
                        searchable: true,

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
