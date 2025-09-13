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

            .col-md-4 {
                width: 33.33%;
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
        <li class="breadcrumb-item"> @lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
        <li class="breadcrumb-item"> @lang('Student::label.STUDENT') @lang('Student::label.DETAILS')</li>
        <li class="breadcrumb-item active">@lang('Student::label.STUDENT_WISE_FIXED_PAYMENT_LIST')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i>@lang('Student::label.STUDENT_WISE_FIXED_PAYMENT_LIST')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-body">
                    <div class="panel-content">
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
                                <h5 style="margin-bottom: 10px;"><strong>@lang('Student::label.STUDENT_WISE_FIXED_PAYMENT_LIST')</strong></h5>
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
                                                                            <td>Currency :</td>
                                                                            <td>BDT</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Total :</td>
                                                                            <td id="total">0</td>
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
                                    @php
                                        $grandTotalFixed = 0;
                                    @endphp
                                    @foreach ($monthList as $month)
                                        @php
                                            $setupPaymentList = $details->where('month_id', $month->id);
                                            $countData = count($setupPaymentList);
                                        @endphp
                                        @if ($countData > 0)
                                            <div class="col-md-4">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover" id="testTable">
                                                        <thead class="thead-themed" style="background: #d1d1d1;">
                                                            <tr>
                                                                <th>Source</th>
                                                                <th>Fixed</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <h3 class="monthName">{{ strtoupper($month->name) }}
                                                            </h3>
                                                            @php
                                                                $totalFixed = 0;
                                                            @endphp
                                                            @foreach ($setupPaymentList as $item)
                                                                @php
                                                                    $currentMonthdetails3 = DB::table('contactwise_item_discount_price_list')
                                                                        ->where('contactwise_item_discount_price_list.contact_id', $data->id)
                                                                        ->where('contactwise_item_discount_price_list.academic_year_id', $item->academic_year_id)
                                                                        ->where('contactwise_item_discount_price_list.class_id', $item->class_id)
                                                                        ->where('contactwise_item_discount_price_list.product_id', $item->product_id)
                                                                        ->leftjoin('products', 'contactwise_item_discount_price_list.product_id', 'products.id')
                                                                        ->leftjoin('academic_years', 'contactwise_item_discount_price_list.academic_year_id', 'academic_years.id')
                                                                        ->where('academic_years.is_current', 1)
                                                                        ->select('contactwise_item_discount_price_list.contact_id', 'contactwise_item_discount_price_list.product_id', 'contactwise_item_discount_price_list.academic_year_id', 'contactwise_item_discount_price_list.enum_month_id as month_id', 'products.name', 'contactwise_item_discount_price_list.amount as item_price')
                                                                        ->first();
                                                                    if (isset($currentMonthdetails3)) {
                                                                        $totalFixed += $currentMonthdetails3->item_price;
                                                                    } else {
                                                                        $totalFixed += $item->item_price;
                                                                    }
                                                                @endphp
                                                                <tr>
                                                                    <td width="70%">{{ $item->name }}</td>
                                                                    @if (isset($currentMonthdetails3))
                                                                        <td>{{ $currentMonthdetails3->item_price }}</td>
                                                                    @else
                                                                        <td>{{ $item->item_price }}</td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                            <th>Total</th>
                                                            <th>{{ $totalFixed }}</th>
                                                            @php
                                                                $grandTotalFixed += $totalFixed;
                                                            @endphp
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
                        $("#total").text("{{ $grandTotalFixed }}");

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
