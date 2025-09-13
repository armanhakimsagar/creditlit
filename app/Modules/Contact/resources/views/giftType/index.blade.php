@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Contact::label.GIFT') @lang('Contact::label.DETAILS')</li>
            <li class="breadcrumb-item active">@lang('Contact::label.GIFT') @lang('Contact::label.TYPE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Contact::label.GIFT') @lang('Contact::label.TYPE') @lang('Academic::label.LIST')
            </h1>
            <a href="{{ route('gift.type.create') }}"
                class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">@lang('Academic::label.ADD')
                @lang('Contact::label.GIFT') @lang('Contact::label.TYPE')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Contact::label.GIFT') @lang('Contact::label.TYPE') <span class="fw-300"><i>List</i></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="panel-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="yajraTable">
                            <thead class="thead-themed">
                                <th> No</th>
                                <th> Name</th>
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
                    "url": "{{ route('gift.type.index') }}",
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
