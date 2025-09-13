@extends('Admin::layouts.master')
@section('body')
    <style>
        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        .student-label-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
            border: 1px solid #ddd;
            padding: 10px 0px;
            margin-bottom: 20px;
            background-color: #497174;
        }

        .student-label-item {
            padding: 0px 2px;
            color: #fff;
        }

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

        .show-table {
            display: none;
        }

        .select2 {
            height: 33px;
        }
    </style>
    <div class="col-md-3l-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Payment::label.PAYMENTLIST')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i> @lang('Payment::label.PAYMENTLIST')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Payment::label.PAYMENT') <span class="fw-300"><i>@lang('Student::label.LIST')</i></span>
                    </h2>
                </div>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row pb-5">

                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="col-form-label" for="academicYearId">@lang('Student::label.YEAR')</label>
                                    {!! Form::Select('academic_year_id', $academic_year, !empty($currentYear) ? $currentYear->year : null, [
                                        'id' => 'academicYearId',
                                        'class' => 'form-control select2',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('first_name') !!}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('type', 'Type', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select('type', ['' => 'All', '1' => 'Student', '6' => 'Customer'], '', [
                                        'id' => 'type',
                                        'class' => 'form-control select2',
                                    ]) !!}

                                    <span class="error"> {!! $errors->first('status') !!}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="col-form-label" for="studentId">@lang('Payment::label.STUDENT-CUSTOMER-ID')</label>
                                    <select name="student_id" id="studentId" class="form-control select2">
                                        <option value='0'>@lang('Student::label.ALL')</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="col-form-label" for="itemId">@lang('Payment::label.ITEM_SEARCH')</label>
                                    {!! Form::Select('item_id', $itemList, !empty($request->itemList) ? $request->itemList : null, [
                                        'id' => 'itemId',
                                        'class' => 'form-control select2',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="col-form-label" for="monthId">@lang('Payment::label.MONTH')</label>
                                    {!! Form::Select('monthId', $monthList, null, [
                                        'id' => 'monthId',
                                        'class' => 'form-control select2',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="col-form-label">From Date</label>
                                    {!! Form::text('from_date', old('from_date'), [
                                        'id' => 'from_date',
                                        'required' => 'required',
                                        'class' => 'form-control from_date',
                                        'placeholder' => 'dd-mm-yyyy',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="col-form-label">To Date</label>
                                    {!! Form::text('to_date', old('to_date'), [
                                        'id' => 'to_date',
                                        'class' => 'form-control to_date',
                                        'placeholder' => 'dd-mm-yyyy',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="col-form-label" for="invoiceId">@lang('Payment::label.INVOICE')</label>
                                    {!! Form::Select('invoice_id', $invoiceList, null, [
                                        'id' => 'invoiceId',
                                        'class' => 'form-control select2',
                                    ]) !!}
                                </div>
                            </div>
                        </div>



                        <div class="form-group col-lg-3 col-md-4 col-form-label">
                            <button class="btn btn-primary btn-sm ml-auto mt-4 waves-effect waves-themed" type="submit"
                                id="searchBtn" onclick="selectLabel()"><i
                                    class="fas fa-search pr-1"></i>@lang('Student::label.SEARCH')</a></button>
                        </div>
                    </div>

                    <div class="student-label-container">
                        <div class="student-label-item"><span>Academic Year: </span><span id="academicLabel">All</span>
                        </div>
                        <div class="student-label-item"><span>Type: </span><span id="typeLabel">All</span></div>
                        <div class="student-label-item"><span>Student/Customer ID : </span><span id="studentLabel">All</span></div>
                        <div class="student-label-item"><span>Item Search : </span><span id="itemLabel">All</span></div>
                        <div class="student-label-item"><span>Month: </span><span id="monthLabel">All</span></div>
                        <div class="student-label-item"><span>From Date: </span><span id="fromDateLabel"></span></div>
                        <div class="student-label-item"><span>To Date: </span><span id="toDateLabel"></span></div>
                        <div class="student-label-item"><span>Invoice: </span><span id="invoiceLabel">All</span></div>
                        <div class="student-label-item"><span>Total: </span><span id="totalLabel"></span></div>
                    </div>

                    <div class="panel-container show-table">
                        <div class="panel-content">
                            <div class="frame-wrap">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="yajraTable">
                                        <thead class="thead-themed">
                                            <tr>
                                                <th> Sl</th>
                                                <th> SID</th>
                                                <th> Student Name</th>
                                                <th> Amount</th>
                                                <th> Date</th>
                                                <th> Invoice No</th>
                                                <th> Updated By</th>
                                                <th> Updated Time</th>
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
            // Select2 use
            $(function() {
                $(document).on('change', '#academicYearId', function() {
                    getStudent();
                    getInvoice();
                });
                $(document).on('change', '#type', function() {
                    getStudent();
                    getInvoice();
                });

                $(".select2").select2();
                getStudent();
                getInvoice();

                $('#itemId').select2({
                    containerCssClass: "productInput",
                    width: '100%',
                    minimumInputLength: 1,
                    maximumSelectionLength: 3,
                });
                $('#invoiceId').select2({
                    containerCssClass: "productInput",
                    width: '100%',
                    minimumInputLength: 1,
                });
            });

            // for date
            $(document).ready(function() {
                jQuery('.from_date').datepicker({
                    language: 'en',
                    dateFormat: 'dd-mm-yyyy',
                    autoClose: true
                });
                jQuery('.to_date').datepicker({
                    language: 'en',
                    dateFormat: 'dd-mm-yyyy',
                    autoClose: true
                });

            });

            // Product table index
            $(function product() {
                $('table').on('draw.dt', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                $(document).on('click', '#searchBtn', function() {
                    $('#yearError').html('');
                    $(".show-table").css("display", "block");
                    $("#generateButton").css("display", "block");
                    var table = $('#yajraTable').DataTable({
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        paging: true,
                        dom: 'lBfrtip',
                        "ajax": {
                            "url": "{{ route('payment.list') }}",
                            "data": function(e) {
                                e.academicYearId = $("#academicYearId").val();
                                e.studentId = $("#studentId").val();
                                e.itemId = $("#itemId").val();
                                e.monthId = $("#monthId").val();
                                e.invoiceId = $("#invoiceId").val();
                                e.fromDate = $("#from_date").val();
                                e.toDate = $("#to_date").val();
                                e.typeId = $("#type").val();

                            }
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
                                data: 'contact_id',
                                name: 'contact_id'
                            },
                            {
                                data: 'full_name',
                                name: 'full_name'
                            },
                            {
                                data: 'total',
                                name: 'total'
                            },
                            {
                                data: 'sales_invoice_date',
                                name: 'sales_invoice_date'
                            },
                            {
                                data: 'sales_invoice_no',
                                name: 'sales_invoice_no'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'updated_time',
                                name: 'updated_time'
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                        ],
                        "bDestroy": true,
                        select: {
                            style: 'single'
                        },
                        "responsive": true,
                        "autoWidth": false,
                        "buttons": [{
                                extend: 'csvHtml5',
                                text: 'CSV',
                                filename: 'Payment_List',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                                titleAttr: 'Generate CSV',
                                className: 'btn-outline-info btn-sm mr-1'
                            },
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                filename: 'Payment_List',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                                titleAttr: 'Generate Excel',
                                className: 'btn-outline-success btn-sm mr-1'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                filename: 'Payment_List',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                                titleAttr: 'Generate PDF',
                                className: 'btn-outline-danger btn-sm mr-1'
                            },
                            {
                                extend: "print",
                                filename: 'Payment_List',
                                title: "Payment List",
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
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
            });


            // Section Change on select Class
            function getStudent() {
                $('#studentId').select2({
                    containerCssClass: "productInput",
                    width: '100%',
                    minimumInputLength: 1,
                });
                var yearId = $('#academicYearId').val();
                var typeId = $('#type').val();
                // alert(typeId)
                var html = '';
                    $.ajax({
                        url: "{{ url('get-studentscontact') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            yearId: yearId,
                            typeId: typeId
                        },
                        beforeSend: function() {
                            $('select[name="student_id"]').empty();
                        },
                        success: function(response) {
                            $('select[name="student_id"]').append(
                                '<option value="">Select One</option>');
                            $.each(response, function(key, data) {
                                $('select[name="student_id"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.full_name + '</option>');
                            });
                        }
                    });
            }


            // Invoice Change on select Year
            function getInvoice() {
                var yearId = $('#academicYearId').val();
                // alert(yearId)
                var html = '';
                    $.ajax({
                        url: "{{ url('get-invoice') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            yearId: yearId
                        },
                        beforeSend: function() {
                            $('select[name="invoice_id"]').empty();
                        },
                        success: function(response) {
                            $('select[name="invoice_id"]').append(
                                '<option value="">Select Invoice</option>');
                            $.each(response, function(key, data) {
                                $('select[name="invoice_id"]').append(
                                    '<option value="' + data
                                    .sales_invoice_no + '">' + data.sales_invoice_no + '</option>');
                            });
                        }
                    });
            }
            function selectLabel() {
                $('#academicLabel').empty();
                $('#typeLabel').empty();
                $('#studentLabel').empty();
                $('#itemLabel').empty();
                $('#monthLabel').empty();
                $('#fromDateLabel').empty();
                $('#toDateLabel').empty();
                $('#invoiceLabel').empty();
                $('#totalLabel').empty();
                $('#academicLabel').append($("#academicYearId :selected").text());
                $('#typeLabel').append($("#type :selected").text());
                $('#studentLabel').append($("#studentId :selected").text());
                $('#itemLabel').append($("#itemId :selected").text());
                $('#monthLabel').append($("#monthId :selected").text());
                $('#fromDateLabel').append($("#from_date").val());
                $('#toDateLabel').append($("#to_date").val());
                $('#invoiceLabel').append($("#invoiceId :selected").text());
            }
        </script>
    @endsection
