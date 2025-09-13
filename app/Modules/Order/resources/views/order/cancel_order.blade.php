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
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
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

        .table {
            font-size: 14px;
        }

        .font-increase {
            font-size: {{ $companyDetails->ReportFontSize }}px;
        }
    </style>
    <div class="col-md-3l-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Contact::label.ORDER') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Contact::label.CANCELED') @lang('Contact::label.ORDER')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i> @lang('Contact::label.CANCELED') @lang('Contact::label.ORDER') @lang('Student::label.DETAILS')
            </h1>
            <a style="margin-left: 10px;" href="{{ route('order.create') }}"
                class="btn btn-primary btn-sm waves-effect pull-right">@lang('Stuff::label.ADD') @lang('Contact::label.ORDER')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Contact::label.CANCELED') @lang('Contact::label.ORDER') <span class="fw-300"><i>@lang('Student::label.LIST')</i></span>
                    </h2>
                </div>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row pb-5">
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('customer_type', 'Customer Type') !!}

                                    {!! Form::Select(
                                        'customer_type',
                                        ['' => 'All', 'bank' => 'Bank', 'branch' => 'Branch', 'company' => 'Company'],
                                        $bank->status ?? 'all',
                                        [
                                            'id' => 'customer_type',
                                            'class' => 'form-control selectheighttype',
                                            'onchange' => 'getCustomer();',
                                        ],
                                    ) !!}
                                    <span class="error"> {!! $errors->first('first_name') !!}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('customer_id', 'Customer Name') !!} <span class="required"> *</span>

                                    {!! Form::Select('customer_id', $bankId, isset($branch) ? $branch->customer_id : null, [
                                        'id' => 'customer_id',
                                        'class' => 'form-control selectheighttype',
                                        'onchange' => '',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('customer_id') !!}</span>
                                    <label id="customer_id-error" class="error" for="customer_id"></label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('country', 'Country Name') !!} <span class="required"> *</span>

                                    {!! Form::Select('country', $country, isset($branch) ? $branch->key_personnel_id : null, [
                                        'id' => 'country',
                                        'class' => 'form-control selectheighttype',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('country') !!}</span>
                                    <label id="country-error" class="error" for="country"></label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('supplier_id', 'Supplier Name') !!} <span class="required"> *</span>

                                    {!! Form::Select('supplier_id', $supplierId, isset($branch) ? $branch->bank_id : null, [
                                        'id' => 'supplier_id',
                                        'class' => 'form-control selectheighttype',
                                        'onchange' => 'getBuyingPrice()',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('supplier_id') !!}</span>
                                    <label id="supplier_id-error" class="error" for="supplier_id"></label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="col-form-label">From Date</label>
                                    {!! Form::text('from_date', isset($request->from_date) ? $request->from_date : old('from_date'), [
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
                                    {!! Form::text('to_date', isset($request->to_date) ? $request->to_date : old('to_date'), [
                                        'id' => 'to_date',
                                        'class' => 'form-control to_date',
                                        'placeholder' => 'dd-mm-yyyy',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4 mt-1">
                            <div class="align-items-start">
                                <button class="btn btn-primary ml-auto waves-effect waves-themed btn-sm mt-5" name="submit" type="submit" id="searchBtn" onclick="selectLabel();">@lang('Certificate::label.GENERATE')</button>

                            </div>
                        </div>

                    </div>

                    <div class="student-label-container">
                        <div class="student-label-item"><span>Customer Type: </span><span id="customerTypeLabel"></span>
                        </div>
                        <div class="student-label-item"><span>Customer: </span><span id="customerLabel"></span></div>
                        <div class="student-label-item"><span>Country: </span><span id="countryLabel"></span></div>
                        <div class="student-label-item"><span>Supplier: </span><span id="supplierLabel"></span></div>
                        <div class="student-label-item"><span>From Date: </span><span id="formDateLabel"></span></div>
                        <div class="student-label-item"><span>To Date: </span><span id="toDateLabel"></span></div>
                        <div class="student-label-item"><span>Total: </span><span id="totalLabel"></span></div>
                    </div>

                    <div class="panel-container show-table font-increase">
                        <div class="panel-content">
                            <div class="frame-wrap">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped ytable" id="yajraTable">
                                        <thead class="thead-themed">
                                            <tr>
                                                <th> Sl</th>
                                                <th> Order ID</th>
                                                <th> Customer Name</th>
                                                <th> Company Name</th>
                                                <th> Country Name</th>
                                                <th> Order Date</th>
                                                <th> Canceled By</th>
                                                <th> Canceled Note</th>
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
                $("#customer_type").select2();
                $("#customer_id").select2();
                $("#country").select2();
                $("#supplier_id").select2();
            });


            // Customer Change on select Customer Type
            function getCustomer() {
                var customerType = $('#customer_type').val();
                var html = '';
                if (customerType != 0) {
                    $.ajax({
                        url: "{{ url('get-customer-independent') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            customerType: customerType
                        },
                        beforeSend: function() {
                            $('select[name="customer_id"]').empty();
                        },
                        success: function(response) {
                            if (customerType == 'bank') {
                                $('select[name="customer_id"]').append('<option value="0">Select Bank</option>');
                            } else if (customerType == 'branch') {
                                $('select[name="customer_id"]').append('<option value="0">Select Branch</option>');
                            } else if (customerType == 'company') {
                                $('select[name="customer_id"]').append('<option value="0">Select Company</option>');
                            }
                            $.each(response, function(key, data) {
                                $('select[name="customer_id"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.full_name + '</option>');
                            });
                            $("#customer_id").trigger("change");
                        }
                    });
                }
            }

            // Product table index
            $(function product() {
                $('table').on('draw.dt', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                $(document).on('click', '#searchBtn', function() {
                    $(".show-table").css("display", "block");
                    var table = $('#yajraTable').DataTable({
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        paging: true,
                        searchDelay: 1000,
                        dom: 'lBfrtip',
                        "ajax": {
                            "url": "{{ route('cancel.order') }}",
                            "data": function(e) {
                                e.customer_type = $("#customer_type").val();
                                e.customer_id = $("#customer_id").val();
                                e.country = $("#country").val();
                                e.supplier_id = $("#supplier_id").val();
                                e.from_date = $("#from_date").val();
                                e.to_date = $("#to_date").val();

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
                                data: 'order_invoice_no',
                                name: 'order_invoice_no'
                            },
                            {
                                data: 'customer_name',
                                name: 'customer_name'
                            },
                            {
                                data: 'company_name',
                                name: 'company_name'
                            },
                            {
                                data: 'country_name',
                                name: 'country_name'
                            },
                            {
                                data: 'orderDate',
                                name: 'orderDate'
                            },
                            {
                                data: 'cancelBy',
                                name: 'cancelBy'
                            },
                            {
                                data: 'cancel_note',
                                name: 'cancel_note'
                            },
                            {
                                data: 'details',
                                name: 'details',
                                orderable: false,
                                searchable: false

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
                                filename: 'Student_list',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                titleAttr: 'Generate CSV',
                                className: 'btn-outline-info btn-sm mr-1'
                            },
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                filename: 'Student_list',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                titleAttr: 'Generate Excel',
                                className: 'btn-outline-success btn-sm mr-1'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                filename: 'Student_list',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                titleAttr: 'Generate PDF',
                                className: 'btn-outline-danger btn-sm mr-1'
                            },
                            {
                                extend: "print",
                                title: 'Student_list',
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
            });


            // Section Change on select Class
            function getSection() {
                var classId = $('#classId').val()
                var yearId = $('#academicYearId').val();
                // alert(classId)
                var html = '';
                if (classId != 0 && yearId != 0) {
                    $.ajax({
                        url: "{{ url('get-sections') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            classId: classId,
                            yearId: yearId
                        },
                        beforeSend: function() {
                            $('select[name="section_id[]"]').empty();
                        },
                        success: function(response) {
                            $.each(response, function(key, data) {
                                $('select[name="section_id[]"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.name + '</option>');
                            });
                        }
                    });
                }
            }


            // Student details label
            function selectLabel() {
                $('#customerTypeLabel').empty();
                $('#customerLabel').empty();
                $('#countryLabel').empty();
                $('#supplierLabel').empty();
                $('#formDateLabel').empty();
                $('#toDateLabel').empty();
                $('#totalLabel').empty();
                $('#customerTypeLabel').append($("#customer_type :selected").text());
                $('#customerLabel').append($("#customer_id :selected").text());
                $('#countryLabel').append($("#country :selected").text());
                $('#supplierLabel').append($("#supplier_id :selected").text());
                $('#formDateLabel').append($("#from_date").val());
                $('#toDateLabel').append($("#to_date").val());
            }
        </script>
    @endsection
