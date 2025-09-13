@extends('Admin::layouts.master')
@section('body')
    <style>
        a[target]:not(.btn) {
            text-decoration: none !important;
        }

        @media print {

            #notPrintDiv,
            .page-breadcrumb,
            .subheader {
                display: none !important;
            }

            @page {
                size: a4 portrait;
            }

            td,
            th {
                font-size: {{ $reportFontSize }}px;
            }
        }

        td,
        th {
            font-size: {{ $reportFontSize }}px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active">@lang('Student::label.ADMISSION') @lang('Student::label.COLLECTION') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Student::label.ADMISSION') @lang('Student::label.COLLECTION') @lang('Student::label.REPORTS')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'admission.collection.filter',
                            'method' => 'post',
                            'id' => 'collectionReportId',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('paymentStatusId', 'Payment Status', ['class' => 'col-form-label']) !!}
                                        {!! Form::Select(
                                            'payment_status_id',
                                            ['1' => 'Paid', '2' => 'Due', '3' => 'Summary'],
                                            !empty($request->payment_status_id) ? $request->payment_status_id : '1',
                                            [
                                                'id' => 'paymentStatusId',
                                                'class' => 'form-control select2',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="date col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label class="col-form-label">From Date</label>
                                                {!! Form::text('from_date', !empty($request->from_date) ? $request->from_date : null, [
                                                    'id' => 'from_date',
                                                    'class' => 'form-control from_date',
                                                    'placeholder' => 'dd-mm-yyyy',
                                                    'onblur' => 'checkPaymentStatus();',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">

                                            <div class="form-line">
                                                <label class="col-form-label">To Date</label>
                                                {!! Form::text('to_date', !empty($request->to_date) ? $request->to_date : null, [
                                                    'id' => 'to_date',
                                                    'class' => 'form-control to_date',
                                                    'placeholder' => 'dd-mm-yyyy',
                                                    'onblur' => 'checkPaymentStatus();',
                                                ]) !!}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('academicYearId', 'Academic Year', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('academic_year_id', $academic_year_list, !empty($request->yearId) ? $request->yearId : null, [
                                            'id' => 'academicYearId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getStudents();',
                                        ]) !!}
                                        <span class="error" id="yearError"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('classId', 'Class Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('class_id', $class_list, !empty($request->class_id) ? $request->class_id : null, [
                                            'id' => 'classId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getStudents();',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('type', 'Select Contact Type', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select(
                                            'type_id',
                                            ['' => 'All', '1' => 'Student', '6' => 'Customer'],
                                            !empty($request->type_id) ? $request->type_id : '',
                                            [
                                                'id' => 'typeId',
                                                'class' => 'form-control select2',
                                                'onchange' => 'getStudents();',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="studentId"
                                            class="col-form-label">@lang('Student::label.STUDENT')/@lang('Item::label.CUSTOMER')</label>
                                        <select name="student_id[]" id="studentId" class="form-control select2" multiple>
                                            <option value='0'></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('studentTypeId', 'Student Type Name', ['class' => 'col-form-label']) !!}
                                        {!! Form::Select(
                                            'student_type_id[]',
                                            $studentTypeList,
                                            !empty($request->student_type_id) ? json_decode($request->student_type_id) : null,
                                            [
                                                'id' => 'studentTypeId',
                                                'class' => 'form-control select2',
                                                'multiple' => 'multiple',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('shiftId', 'Shift Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select(
                                            'shift_id[]',
                                            $shift_list,
                                            !empty($request->shift_id) ? json_decode($request->shift_id) : null,
                                            [
                                                'id' => 'shiftId',
                                                'class' => 'form-control select2',
                                                'multiple' => 'multiple',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('itemId', 'Item Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('item_id[]', $item_list, !empty($request->item_id) ? json_decode($request->item_id) : null, [
                                            'id' => 'itemId',
                                            'class' => 'form-control select2',
                                            'multiple' => 'multiple',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="panel-content d-flex flex-row align-items-center">
                                    <button class="btn btn-primary waves-effect waves-themed btn-sm mt-5" name="submit"
                                        onclick="validate()" type="button" id="saveBtn">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>
                        <br><br>
                        @php
                            $studentArrId = '';
                        @endphp
                        {!! Form::close() !!}
                        @if ($request->search == 'true')
                            @php
                                $studentArrId = implode(',', !empty(json_decode($request->student_id)) ? json_decode($request->student_id) : []);
                            @endphp
                            <div class="subheader">
                                <button style="margin-left: auto;" class="btn btn-info btn-sm print_full_data mt-5"
                                    onclick="window.print();">Print</button>
                            </div>
                            <div id='printMe'>
                                <center>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}"
                                                height="90" class="example-p-5">
                                            <h2>{{ $companyDetails->company_name }}</h2>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span
                                                style="font-size:{{ Session::get('fontsize') }}px;">{{ $companyDetails->address }}</span><br>
                                            <p style="font-size:{{ Session::get('fontsize') }}px;">Tel:
                                                {{ $companyDetails->phone }}, Email: {{ $companyDetails->email }}</p>
                                        </div>
                                    </div>
                                </center>
                                <center>
                                    <h5 style="margin-bottom: 0px;"><strong>@lang('Student::label.COLLECTION') @lang('Report::label.REPORT')</strong>
                                    </h5>
                                    <caption> {{ $pageTitle }}</caption><br>
                                    <caption> Year: {{ $yearName }}, Class : {{ $className }}</caption>
                                    <h4>Total: {{ count($model) }}</h4>
                                </center>
                                <div class="body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="testTable">

                                                @if ($paymentStatus == 1)
                                                    <thead class="thead-themed" style="background: #d1d1d1;">

                                                        <tr>
                                                            <th class="text-left"> No</th>
                                                            <th class="text-center"> Date </th>
                                                            <th class="text-center"> Invoice Number</th>
                                                            <th class="text-center"> Name</th>
                                                            <th class="text-center"> Class Name</th>
                                                            <th class="text-center"> Notes</th>
                                                            <th class="text-center"> Item Name </th>
                                                            <th class="text-center"> Payment Of</th>
                                                            <th class="text-center"> Amount (BDT)</th>

                                                        </tr>

                                                    </thead>
                                                    @if (count($data) > 0)
                                                        <tbody>
                                                            <?php
                                                            $total_rows = 1;
                                                            $sum = 0;
                                                            ?>
                                                            @if (!empty($model))
                                                                @foreach ($model as $values)
                                                                    <tr>
                                                                        <td>
                                                                            <?= $total_rows++ ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $values->sales_invoice_date }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $values->sales_invoice_no }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $values->student_name }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ !empty($values->class_name) ? $values->class_name : '' }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ !empty($values->note) ? $values->note : '' }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $values->product_name }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $values->month_name }}-{{ $values->year_name }}
                                                                        </td>
                                                                        <td class="text-center">{{ $values->price }}</td>
                                                                        @php
                                                                            $sum += $values->price;
                                                                        @endphp
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                        <tfoot style="background: #d1d1d1;">
                                                            <tr>
                                                                <td colspan="8">Total (BDT)</td>
                                                                <td class="text-center">{{ $sum }}</td>
                                                            </tr>
                                                        </tfoot>

                                                    @endif
                                                @endif


                                                @if ($paymentStatus == 3)
                                                    <thead class="thead-themed" style="background: #d1d1d1;">

                                                        <tr>
                                                            <th class="text-left"> No</th>
                                                            <th class="text-center"> Name</th>
                                                            <th class="text-center"> Class</th>
                                                            <th class="text-center"> Guardian Name</th>
                                                            <th class="text-center"> Guardian Phone</th>
                                                            <th class="text-center"> Payment Of</th>
                                                            <th class="text-center"> Paid Amount (BDT)</th>
                                                            <th class="text-center"> Due Amount (BDT)</th>
                                                        </tr>

                                                    </thead>
                                                    @if (count($data) > 0)
                                                        <tbody>
                                                            <?php
                                                            $total_rows = 1;
                                                            $due_sum = 0;
                                                            $paid_sum = 0;
                                                            ?>
                                                            @if (!empty($model))
                                                                @foreach ($model as $values)
                                                                    <tr>
                                                                        <td>
                                                                            <?= $total_rows++ ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $values->student_name }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ !empty($values->class_name) ? $values->class_name : '' }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ !empty($values->guardian_name) ? $values->guardian_name : '' }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ !empty($values->guardian_phone) ? $values->guardian_phone : '' }}
                                                                        </td>
                                                                
                                                                        <td class="text-center">
                                                                            {{ $values->month_name }}-{{ $values->year_name }}
                                                                        </td>
                                                                        <td class="text-center">{{ $values->total_paid }}</td>
                                                                        @php
                                                                            $paid_sum += $values->total_paid;
                                                                        @endphp
                                                                        <td class="text-center">{{ $values->total_due }}</td>
                                                                        @php
                                                                            $due_sum += $values->total_due;
                                                                        @endphp
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                        <tfoot style="background: #d1d1d1;">
                                                            <tr>
                                                                <td colspan="6">Total (BDT)</td>
                                                                <td class="text-center">{{ $due_sum }}</td>
                                                                <td class="text-center">{{ $paid_sum }}</td>
                                                            </tr>
                                                        </tfoot>

                                                    @endif
                                                @endif

                                                @if ($paymentStatus == 2)
                                                    <thead class="thead-themed" style="background: #d1d1d1;">

                                                        <tr>
                                                            <th class="text-left"> No</th>
                                                            <th class="text-center"> Name</th>
                                                            <th class="text-center"> Class Name</th>
                                                            <th class="text-center"> Guardian Name</th>
                                                            <th class="text-center"> Guardian Phone</th>
                                                            <th class="text-center"> Item Name </th>
                                                            <th class="text-center"> Payment Of</th>
                                                            <th class="text-center"> Due Amount (BDT)</th>
                                                        </tr>

                                                    </thead>
                                                    @if (count($data) > 0)
                                                        <tbody>
                                                            <?php
                                                            $total_rows = 1;
                                                            $sum = 0;
                                                            ?>
                                                            @if (!empty($model))
                                                                @foreach ($model as $values)
                                                                    <tr>
                                                                        <td>
                                                                            <?= $total_rows++ ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $values->student_name }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ !empty($values->class_name) ? $values->class_name : '' }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ !empty($values->guardian_name) ? $values->guardian_name : '' }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ !empty($values->guardian_phone) ? $values->guardian_phone : '' }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $values->product_name }} </td>
                                                                        <td class="text-center">
                                                                            {{ $values->month_name }}-{{ $values->year_name }}
                                                                        </td>
                                                                        <td class="text-center">{{ $values->due }}</td>
                                                                        @php
                                                                            $sum += $values->due;
                                                                        @endphp
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                        <tfoot style="background: #d1d1d1;">
                                                            <tr>
                                                                <td colspan="7">Total (BDT)</td>
                                                                <td class="text-center">{{ $sum }}</td>
                                                            </tr>
                                                        </tfoot>

                                                    @endif
                                                @endif

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
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

            /* $('#paymentStatusId').change(function() {
                $formDate = $("#from_date").val();
                $toDate = $("#to_date").val();

                if ($formDate !== '' || $toDate !== '') {
                    $(this).val('1');
                }
            }); */

            if($("#paymentStatusId").val() == 2 || $("#paymentStatusId").val() == 3){
                $("#from_date").prop('disabled', true)
                $("#to_date").prop('disabled', true)
                $('.date').addClass('d-none')
            }
            else{
                $("#from_date").prop('disabled', false)
                $("#to_date").prop('disabled', false)
                $('.date').removeClass('d-none')
            }

            $('#paymentStatusId').on('change', function(){
                if($(this).val() == 2 || $(this).val() == 3){
                    $("#from_date").prop('disabled', true)
                    $("#to_date").prop('disabled', true)
                    $('.date').addClass('d-none')
                }
                else{
                    $("#from_date").prop('disabled', false)
                    $("#to_date").prop('disabled', false)
                    $('.date').removeClass('d-none')
                }
            })

        });

        function checkPaymentStatus() {
            $("#paymentStatusId").trigger("change");
        }


        function getStudents() {
            /* $('#studentId').select2({
                containerCssClass: "productInput",
                width: '100%',
                minimumInputLength: 1,
            }); */
            var yearId = $('#academicYearId').val();
            var classId = $('#classId').val();
            var typeId = $('#typeId').val();
            /* alert(typeId); */
            var html = '';
            if (yearId != 0) {
                $.ajax({
                    url: "{{ url('get-student-details') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        classId: classId,
                        yearId: yearId,
                        typeId: typeId
                    },
                    beforeSend: function() {
                        $('select[name="student_id[]"]').empty();
                    },
                    success: function(response) {
                        $.each(response, function(key, data) {
                            $('select[name="student_id[]"]').append(
                                '<option value="' + data
                                .id + '">' + data.full_name + '</option>');
                        });
                        var searchStudentText = "<?php echo $request->search; ?>";
                        if (searchStudentText == 'true') {
                            var studentArrayId = "<?php echo $studentArrId; ?>";
                            $("#studentId").val(studentArrayId.split(','));
                        }
                    }
                });
            }
        }

        function validate() {
            if ($("#classId").val() != 0 || $("#shiftId").val() != 0 || $("#itemId").val() != 0 || $("#studentId").val() !=
                0) {

                if ($("#academicYearId").val() == 0) {
                    $('#yearError').html('Please Select Year');

                } else {
                    $("#saveBtn").attr('type', 'submit');
                    $("#saveBtn").submit();
                }
            } else {
                $("#saveBtn").attr('type', 'submit');
                $("#saveBtn").submit();
            }
        }
        $(document).ready(function() {

            $('.chart-of-expense-select2').select2({
                width: "100%",
                placeholder: "Select Chart of Expense",
            });
            $('.select2').select2({
                width: "100%",
            });

        });

        function printDiv() {
            var printContents = document.getElementById('printMe').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        $('input[type=text]').attr('autocomplete', 'off');
        $('input[type=number]').attr('autocomplete', 'off');
        $(document).ready(function() {

            // initialize datatable
            $('#testTable').dataTable({

                responsive: true,
                lengthChange: false,
                paging: false,
                searching: false,
                order: false,
                bSort: false,
                bPaginate: false,
                bLengthChange: false,
                bInfo: false,
                bAutoWidth: false,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                dom:
                    /*  --- Layout Structure
                        --- Options
                        l   -   length changing input control
                        f   -   filtering input
                        t   -   The table!
                        i   -   Table information summary
                        p   -   pagination control
                        r   -   processing display element
                        B   -   buttons
                        R   -   ColReorder
                        S   -   Select

                        --- Markup
                        < and >             - div element
                        <"class" and >      - div with a class
                        <"#id" and >        - div with an ID
                        <"#id.class" and >  - div with an ID and a class

                        --- Further reading
                        https://datatables.net/reference/option/dom
                        --------------------------------------
                     */
                    "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: 'Student_Collection_Report',
                    titleAttr: 'Generate Excel',
                    className: 'btn-outline-success btn-sm mr-1'
                }]
            });

            var searchText = "<?php echo $request->search; ?>";
            if (searchText == 'true') {
                getStudents();
            }

        });
    </script>
@endsection
