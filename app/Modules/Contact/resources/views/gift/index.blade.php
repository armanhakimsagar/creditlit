@extends('Admin::layouts.master')
@section('body')
    <style>
        a[target]:not(.btn) {
            color: #000;
            text-decoration: none !important;
        }

        .dt-buttons {
            float: right;
        }

        .dataTables_filter {
            float: left;
        }
    </style>
    <div class="col-md-3l-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Contact::label.GIFT') @lang('Contact::label.DETAILS')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i> @lang('Contact::label.GIFT') @lang('Student::label.DETAILS')
            </h1>
            <a style="margin-left: 10px;" href="{{ route('gift.create') }}"
                class="btn btn-primary btn-sm waves-effect pull-right">@lang('Student::label.ADD') @lang('Contact::label.GIFT')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Contact::label.GIFT') <span class="fw-300"><i>@lang('Student::label.LIST')</i></span>
                    </h2>
                </div>
            </div>

            <div class="panel-container">
                <div class="panel-content">
                    <div class="panel-container font-increase">
                        <div class="panel-content">
                            <div class="frame-wrap">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped ytable" id="yajraTable">
                                        <thead class="thead-themed">
                                            <tr>
                                                <th> Sl</th>
                                                <th> Gift Name</th>
                                                <th> Gift Type</th>
                                                <th> Customer Name</th>
                                                <th> Key Personnel Name</th>
                                                <th> Cost</th>
                                                <th> Gift Date</th>
                                                <th> Delivered By</th>
                                                <th> Issued By</th>
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
            </div>
        </div>
        <!-- AJAX -->

        <script type="text/javascript">
            // Product table index
            $(function() {
                $('table').on('draw.dt', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                var table = $('#yajraTable').DataTable({
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searchDelay: 1000,
                    dom: 'lBfrtip',
                    "ajax": {
                        "url": "{{ route('gift.index') }}",
                    },
                    "drawCallback": function(settings, start, end, max, total, pre) {
                        $('#totalLabel').text(this.fnSettings()
                            .fnRecordsTotal()); // total number of rows
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'gift_name',
                            name: 'gift_name'
                        },
                        {
                            data: 'gift_type_name',
                            name: 'gift_type_name'
                        },
                        {
                            data: 'customer_name',
                            name: 'customer_name'
                        },
                        {
                            data: 'keyPersonnel_name',
                            name: 'keyPersonnel_name'
                        },
                        {
                            data: 'cost',
                            name: 'cost'
                        },
                        {
                            data: 'gift_date',
                            name: 'gift_date'
                        },
                        {
                            data: 'deliveredMan_name',
                            name: 'deliveredMan_name'
                        },
                        {
                            data: 'issued_by',
                            name: 'issued_by'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false

                        },
                    ],
                    "bDestroy": true,
                    "responsive": true,
                    select: {
                        style: 'single'
                    },
                    "autoWidth": false,
                    "buttons": [{
                            extend: 'csvHtml5',
                            text: 'CSV',
                            filename: 'Gift_List',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            },
                            titleAttr: 'Generate CSV',
                            className: 'btn-outline-info btn-sm mr-1'
                        },
                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            filename: 'Gift_List',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            },
                            titleAttr: 'Generate Excel',
                            className: 'btn-outline-success btn-sm mr-1'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            filename: 'Gift_List',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            },
                            titleAttr: 'Generate PDF',
                            className: 'btn-outline-danger btn-sm mr-1'
                        },
                        {
                            extend: "print",
                            title: 'Gift_List',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            },
                            titleAttr: 'Generate print',
                            className: 'btn-outline-info btn-sm mr-1'
                        },
                    ]
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
