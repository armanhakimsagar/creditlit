@extends('Admin::layouts.master')
@section('body')
    <style>
        .marksheet-details-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-auto-flow: column;
            background-color: #EEEEEE;
            padding: 10px;
            /* -webkit-print-color-adjust: exact; */

        }

        .marksheet-details-item {
            background-color: #EEEEEE;
            /* -webkit-print-color-adjust: exact; */
        }

        .marksheet-details-container th,
        .marksheet-details-container td {
            border: 1px solid #ffffff;
            /* -webkit-print-color-adjust: exact; */
        }

        .marksheet-details-item tr:nth-child(even),
        .marksheet-details-item tr:nth-child(odd) {
            background-color: #EEEEEE;
            /* -webkit-print-color-adjust: exact; */
        }

        .monthName {
            position: relative;
            margin-top: 30px;
            margin-bottom: 30px;
            text-align: center;
            width: 150px;
            margin: auto;
            margin-bottom: 10px;

        }

        /* bottom line */
        .monthName::after {
            content: '';
            position: absolute;
            background-color: rgb(0, 0, 0);
            width: 100%;
            height: 3px;
            bottom: 0;
            left: 0;
            margin: auto;
        }

        .currentMonthName {
            position: relative;
            margin-top: 30px;
            margin-bottom: 30px;
            text-align: center;
            width: 300px;
            margin: auto;
            margin-bottom: 10px;

        }

        /* bottom line */
        .currentMonthName::after {
            content: '';
            position: absolute;
            background-color: rgb(0, 0, 0);
            width: 100%;
            height: 3px;
            bottom: 0;
            left: 0;
            margin: auto;
        }

        .table thead th,
        .table tbody td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        tr {
            line-height: 0px;
        }

        td,
        th {
            font-size: {{ $reportFontSize }}px;
        }

        @media print {
            .marksheet-details-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-auto-flow: column;
                background-color: #EEEEEE;
                padding: 0px;
                margin-bottom: 1px;
                /* -webkit-print-color-adjust: exact; */

            }

            .marksheet-details-item {
                background-color: #EEEEEE;
                /* -webkit-print-color-adjust: exact; */
            }

            .marksheet-details-container th,
            .marksheet-details-container td {
                border: 1px solid #ddd;
                /* -webkit-print-color-adjust: exact; */
            }

            .col-md-6 {
                width: 50%;
            }

            tr:last-child,
            tr:nth-last-child(2),
            tr:nth-last-child(3) {
                background-color: #EEEEEE;
            }

            .marksheet-details-item tr:nth-child(even),
            .marksheet-details-item tr:nth-child(odd) {
                background-color: #EEEEEE;
                /* -webkit-print-color-adjust: exact; */
            }

            #notPrintDiv,
            .page-breadcrumb,
            .subheader {
                display: none !important;
            }

            .payment-history-filter {
                display: none;
            }

            td,
            th {
                font-size: {{ $reportFontSize }}px;
            }

            @page {
                size: a4 portrait;
            }

            .marksheet-details-item tr:nth-child(even),
            .marksheet-details-item tr:nth-child(odd) {
                border: 1px solid #000;
                /* -webkit-print-color-adjust: exact; */
            }
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active">@lang('Student::label.STUDENT_ACCOUNT_PROFILE')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i>@lang('Student::label.STUDENT_ACCOUNT_PROFILE')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-body">
                    <div class="panel-content">
                        <div class="payment-history-filter">
                            {!! Form::open(['method' => 'post', 'route' => 'students.wise.payment.history.report.search']) !!}
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="academicYearId">@lang('Student::label.ACADEMIC') @lang('Student::label.YEAR')</label>
                                        {!! Form::Select(
                                            'academic_year_id',
                                            $academic_year,
                                            !empty($selected_student) ? $selected_student->academic_year_id : null,
                                            [
                                                'id' => 'academicYearId',
                                                'class' => 'form-control selectheighttype',
                                                'onchange' => 'getSection();getStudent();',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="classId">@lang('Student::label.CLASS')</label>
                                        {!! Form::Select('class_id', $classList, !empty($selected_student) ? $selected_student->class_id : null, [
                                            'id' => 'classId',
                                            'class' => 'form-control selectheighttype',
                                            'onchange' => 'getSection();getStudent();',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="sectionId">@lang('Student::label.SECTION')</label>
                                        <select name="section_id" id="sectionId" onchange="getStudent()"
                                            class="form-control select2">
                                            <option value='0'>@lang('Student::label.SELECT') @lang('Student::label.SECTION')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="studentID">@lang('Payment::label.SELECT-STUDENT')</label>

                                        {!! Form::Select('student_id', $studentList, !empty($selected_student) ? $selected_student->id : null, [
                                            'id' => 'student_id',
                                            'class' => 'form-control select2',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-1 align-items-end">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <button
                                                class="btn btn-primary btn-sm ml-auto waves-effect waves-themed float-left mt-3"
                                                id="btnsm" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        {{-- @if ($request->search == 'true') --}}
                        <div class="subheader">
                            <button style="margin-left: auto;" class="btn btn-info btn-sm print_full_data mt-5"
                                onclick="window.print();">Print</button>
                        </div>
                        <div id='printMe'>
                            <center>
                                <div class="row">
                                    <div class="col-md-12">

                                        <h2>{{ $companyDetails->company_name }}</h2>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span
                                            style="font-size:{{ Session::get('fontsize') }}px;">{{ $companyDetails->address }}</span><br>
                                        <p style="font-size:{{ Session::get('fontsize') }}px;">Tel:
                                            {{ $companyDetails->phone }}, Email: {{ $companyDetails->email }}
                                        </p>
                                    </div>
                                </div>
                            </center>
                            <center>
                                <h5 style="margin-bottom: 10px;"><strong>@lang('Student::label.STUDENT_ACCOUNT_PROFILE')</strong></h5>
                                {{-- <caption>Year: {{$yearName}}, Class : {{$className}}</caption> --}}
                                {{-- <caption> </caption> --}}
                            </center>

                            <div id="testi-border">
                                <div class="testi-border">
                                    <div class="testimonial">
                                        <div class="col-xs-12">
                                            <div class="testimonial-content">
                                                <div class="row clearfix" id="studentMarksheet">
                                                    <div class="block-header block-header-2">
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="marksheet-details-container">
                                                            <div class="marksheet-details-item">
                                                                <table width="100%" class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Name:</td>
                                                                            <td id="classRoll">{{ $data->full_name }}</td>
                                                                        </tr>
                                                                        <tr id="registrationNo">

                                                                        </tr>
                                                                        <tr>
                                                                            <td>Class :</td>
                                                                            <td id="className">{{ $data->class_name }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Roll :</td>
                                                                            <td id="sectionName">{{ $data->class_roll }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="22%">Guardian Name :</td>
                                                                            <td width="39%" id="studentName">
                                                                                {{ $data->guardian_name }}
                                                                            </td>
                                                                        </tr>
                                                                        @if ($data->section_name)
                                                                            <tr>
                                                                                <td>Section :</td>
                                                                                <td id="studentContactId">
                                                                                    {{ $data->section_name }}</td>
                                                                            </tr>
                                                                        @endif
                                                                        <tr>
                                                                            <td> Shift :</td>
                                                                            <td id="fatherName">{{ $data->shift_name }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="marksheet-details-item">
                                                                <table width="100%" class="table">
                                                                    <tbody>

                                                                        <tr>
                                                                            <td colspan="2">Summary</td>

                                                                        </tr>

                                                                        <tr>
                                                                            <td>Total Due
                                                                                (BDT) :</td>
                                                                            <td>{{ $currentDue }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Note: if
                                                                                any previous due :</td>
                                                                            <td>{{ $oldDue }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Grand
                                                                                Total :</td>
                                                                            <td>{{ $currentDue + $oldDue }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Total Paid Amount:</td>
                                                                            <td>{{ !empty($studentTotalPaid) ? $studentTotalPaid : 0 }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Currency :</td>
                                                                            <td>BDT</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                                <div class="row">
                                    <div class="col-md-6">
                                        @php
                                            $countCurrent = count($currentMonthdetails);
                                        @endphp
                                        @if ($countCurrent > 0)
                                            @foreach ($currentMonth as $item)
                                                <h3 class="currentMonthName">{{ strtoupper($item->name) }} (Current Month)
                                                </h3>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover" id="testTable">
                                                        <thead class="thead-themed" style="background: #d1d1d1;">
                                                            <tr>
                                                                <th>Payment Date</th>
                                                                <th>Source</th>
                                                                <th>Fixed</th>
                                                                <th>Paid</th>
                                                                <th>Due</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $totalFixed = 0;
                                                                $totalPaid = 0;
                                                                $totalDue = 0;
                                                            @endphp
                                                            @foreach ($currentMonthdetails as $item)
                                                                @php
                                                                    $totalFixed += $item->amount;
                                                                    $totalPaid += $item->paid_amount;
                                                                    $totalDue += $item->due;
                                                                @endphp
                                                                <tr>
                                                                    <td>
                                                                        @if ($item->payment_date)
                                                                            {{ date('d-m-Y', strtotime($item->payment_date)) }}
                                                                        @else
                                                                            Not Yet Paid
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $item->name }}</td>
                                                                    <td>{{ $item->amount }}</td>
                                                                    <td>
                                                                        @if ($item->paid_amount > 0)
                                                                            {{ $item->paid_amount }}
                                                                        @else
                                                                            0
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (is_numeric($item->due))
                                                                            {{ $item->due }}
                                                                        @else
                                                                            Due Not Generated
                                                                        @endif

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <th colspan="2">Total</th>
                                                            <th>{{ $totalFixed }}</th>
                                                            <th>{{ $totalPaid }}</th>
                                                            <th>{{ $totalDue }}</th>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    @foreach ($monthList as $month)
                                        @php
                                            $paymentAmount = $details->where('month_id', $month->id);
                                            $countData = count($paymentAmount);
                                        @endphp
                                        @if ($countData > 0)
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover" id="testTable">
                                                        <thead class="thead-themed" style="background: #d1d1d1;">
                                                            <tr>
                                                                <th>Payment Date</th>
                                                                <th>Source</th>
                                                                <th>Fixed</th>
                                                                <th>Paid</th>
                                                                <th>Due</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <h3 class="monthName">{{ strtoupper($month->name) }}
                                                            </h3>
                                                            @php
                                                                $totalFixed = 0;
                                                                $totalPaid = 0;
                                                                $totalDue = 0;
                                                            @endphp
                                                            @foreach ($paymentAmount as $item)
                                                                @php
                                                                    $totalFixed += $item->amount;
                                                                    $totalPaid += $item->paid_amount;
                                                                    $totalDue += $item->due;
                                                                @endphp
                                                                <tr>
                                                                    <td>
                                                                        @if ($item->payment_date)
                                                                            {{ date('d-m-Y', strtotime($item->payment_date)) }}
                                                                        @else
                                                                            Not Yet Paid
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $item->name }}</td>
                                                                    <td>{{ $item->amount }}</td>
                                                                    <td>
                                                                        @if ($item->paid_amount > 0)
                                                                            {{ $item->paid_amount }}
                                                                        @else
                                                                            0
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->due == 0)
                                                                            Paid
                                                                        @else
                                                                            {{ $item->due }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <th colspan="2">Total</th>
                                                            <th>{{ $totalFixed }}</th>
                                                            <th>{{ $totalPaid }}</th>
                                                            <th>{{ $totalDue }}</th>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#academicYearId").select2({
                            width: "100%"
                        });
                        $("#classId").select2({
                            width: "100%"
                        });
                        $("#sectionId").select2({
                            width: "100%"
                        });
                        $("#student_id").select2({
                            width: "100%"
                        });

                    });

                    function getStudent() {
                        var sectionId = $('#sectionId').val();
                        // alert(classId)
                        var html = '';
                        $.ajax({
                            url: "{{ url('get-students') }}",
                            type: "post",
                            dataType: "json",
                            data: {
                                _token: '{!! csrf_token() !!}',
                                sectionId: sectionId,
                            },
                            beforeSend: function() {
                                $('select[name="student_id"]').empty();
                            },
                            success: function(response) {
                                $('select[name="student_id"]').append(
                                    '<option value="0">Select Student</option>');
                                $.each(response, function(key, data) {
                                    $('select[name="student_id"]').append(
                                        '<option value="' + data
                                        .id + '">' + data.full_name + ' [' + data.class_roll +
                                        '] [<span style="background-color:red!important">' + data
                                        .contact_id + '</span>]</option>');

                                });

                            }
                        });
                    }


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
                                    $('select[name="section_id"]').empty();
                                    $('select[name="student_id"]').empty();
                                    // $('.student_details').hide();

                                },
                                success: function(response) {
                                    $('select[name="section_id"]').append(
                                        '<option value="0">Select Section</option>');
                                    $.each(response, function(key, data) {
                                        $('select[name="section_id"]').append(
                                            '<option value="' + data
                                            .id + '">' + data.name + '</option>');
                                    });
                                }
                            });
                        }
                    }
                </script>
            @endsection
