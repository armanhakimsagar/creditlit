@extends('Admin::layouts.master')
@section('body')
    <style>
        tbody>tr:nth-child(even),
        tbody>tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tfoot>tr:last-child {
            background-color: #ffffff;
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
        <li class="breadcrumb-item"> @lang('Stuff::label.EMPLOYEE')</li>
        <li class="breadcrumb-item active">@lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.PAYROLL') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.PAYROLL') @lang('Student::label.REPORTS')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="panel-content">
                        @if ($page == 'Without-type')
                            @php
                                $currentUrl = \Illuminate\Support\Facades\Request::fullUrl();
                                $segments = explode('/', $currentUrl);
                                $yearId = $segments[count($segments) - 2];
                                $monthId = $segments[count($segments) - 1];
                            @endphp
                        @else
                            @php
                                $currentUrl = \Illuminate\Support\Facades\Request::fullUrl();
                                $segments = explode('/', $currentUrl);
                                $yearId = $segments[count($segments) - 3];
                                $monthId = $segments[count($segments) - 2];
                                $paymentType = $segments[count($segments) - 1];
                            @endphp
                        @endif

                        {!! Form::open([
                            'route' => 'employee.payroll.filter',
                            'method' => 'post',
                            'id' => 'collectionReportId',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="academicYearId" class="col-form-label">Academic Year</label>

                                        {!! Form::Select('academic_year_id', $academic_year_list, !empty($yearId) ? $yearId : null, [
                                            'id' => 'academicYearId',
                                            'class' => 'form-control select2',
                                        ]) !!}
                                        <span class="error" id="yearError"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="monthId" class="col-form-label">@lang('Stuff::label.MONTH')</label>
                                        {!! Form::Select('month_id', $months, !empty($monthId) ? $monthId : null, [
                                            'id' => 'monthId',
                                            'class' => 'form-control selectheighttype select2',
                                        ]) !!}
                                        <span class="error" id="monthIdError"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('payment_type', 'Payment Type', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select(
                                            'payment_type',
                                            ['' => 'All', 'cash' => 'Cash', 'bank' => 'Bank'],
                                            !empty($paymentType) ? $paymentType : '',
                                            [
                                                'id' => 'payment_type',
                                                'class' => 'form-control select3',
                                            ],
                                        ) !!}

                                        <span class="error"> {!! $errors->first('payment_type') !!}</span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">

                                <div class="panel-content d-flex flex-row align-items-center">
                                    <button class="btn btn-primary waves-effect waves-themed btn-sm mt-5" name="submit"
                                        type="submit" id="saveBtn">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>
                        <br><br>
                        @php
                            $studentArrId = '';
                        @endphp
                        {!! Form::close() !!}
                        <div class="subheader">
                            <button style="margin-left: auto;" class="btn btn-info btn-sm print_full_data mt-5"
                                onclick="window.print();">Print</button>
                        </div>
                        <div id='printMe'>
                            <center>
                                <div class="row">
                                    <div class="col-md-12">
                                        <img style="height: 50px;"
                                            src="{{ asset(config('app.asset') . 'uploads/company/menu-logo.svg') }}">

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
                                <h5 style="margin-bottom: 0px;"><strong>Salary for {{ $month_name->name }}, {{ $academic_year->year }}</strong></h5>
                                <caption> </caption><br>
                                {{-- <caption> </caption> --}}
                            </center>
                            <div class="body">
                                <div class="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="testTable">
                                            <thead class="thead-themed" style="background: #d1d1d1;">
                                                
                                                <tr>
                                                    <th class="text-left"> SL</th>
                                                    <th class="text-center"> Name </th>
                                                    <th class="text-center"> Designation</th>
                                                    <th class="text-center"> Bank Acc</th>
                                                    <th class="text-center"> Gross Tk.</th>
                                                    <th class="text-center" id="payableAmount"> Payable Tk.</th>
                                                    @foreach ($allowance_list as $item)
                                                        <th class="text-center">(+) {{ $item->name }}</th>
                                                    @endforeach
                                                    @foreach ($deduction_list as $item)
                                                        <th class="text-center">(-) {{ $item->name }}</th>
                                                    @endforeach
                                                    <th class="text-center"> Net Salary</th>

                                                </tr>

                                            </thead>
                                            <tbody>
                                                {{-- <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-center">Salary for {{ $month_name->name }}, {{ $academic_year->year }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>

                                                </tr> --}}
                                                @php
                                                    $total_gross_salary = 0;
                                                    $total_salary = 0;
                                                    $allowance_summation = array_fill(0, count($allowance_list), 0);
                                                    $digit = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                                @endphp
                                                @foreach ($data as $key => $item)
                                                    <tr>
                                                        @php
                                                            $index = $key + 1;
                                                            $total_gross_salary += $item->gross_salary;
                                                            $total_salary += $item->total_salary;
                                                        @endphp
                                                        <td class="text-center">{{ $index }}</td>
                                                        <td class="text-center">{{ $item->full_name }}</td>
                                                        <td class="text-center">{{ $item->designation_name }}</td>
                                                        <td class="text-center">{{ $item->bank_account_number }}</td>
                                                        <td class="text-center">{{ $item->gross_salary }}</td>
                                                        <td class="text-center">{{ $item->gross_salary }}</td>
                                                        @foreach ($allowance_list as $allowance_item)
                                                            @php
                                                                $allowanceItem = DB::table('employee_payroll_item_details')
                                                                    ->where('employee_payroll_item_details.payroll_id', $item->id)
                                                                    ->where('employee_payroll_item_details.item_id', $allowance_item->id)
                                                                    ->first();
                                                                $allowance_summation[$loop->index] += $allowanceItem->total_amount ?? 0;
                                                            @endphp
                                                            <td class="text-center">
                                                                {{ $allowanceItem->total_amount ?? '' }}</td>
                                                        @endforeach

                                                        @foreach ($deduction_list as $deduction_item)
                                                            @php
                                                                $deductionItem = DB::table('employee_payroll_item_details')
                                                                    ->where('employee_payroll_item_details.payroll_id', $item->id)
                                                                    ->where('employee_payroll_item_details.item_id', $deduction_item->id)
                                                                    ->first();
                                                            @endphp
                                                            <td class="text-center">
                                                                {{ $deductionItem ? abs($deductionItem->total_amount) : '' }}
                                                            </td>
                                                        @endforeach
                                                        <td class="text-center">{{ $item->total_salary }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <th>Total (BDT)</th>
                                                    <td></td>
                                                    <td></td>
                                                    <th class="text-center">{{ $total_gross_salary }}</th>
                                                    <th class="text-center">{{ $total_gross_salary }}</th>
                                                    @foreach ($allowance_list as $allowance_item)
                                                        @php
                                                            $total_allowance_item_amount = 0;
                                                        @endphp
                                                        @foreach ($data as $item)
                                                            @php
                                                                $allowanceItem = DB::table('employee_payroll_item_details')
                                                                    ->where('employee_payroll_item_details.payroll_id', $item->id)
                                                                    ->where('employee_payroll_item_details.item_id', $allowance_item->id)
                                                                    ->first();
                                                                $total_allowance_item_amount += $allowanceItem->total_amount ?? 0;
                                                            @endphp
                                                        @endforeach
                                                        <th class="text-center">{{ $total_allowance_item_amount }}</th>
                                                    @endforeach
                                                    @foreach ($deduction_list as $deduction_item)
                                                        @php
                                                            $total_deduction_item_amount = 0;
                                                        @endphp
                                                        @foreach ($data as $item)
                                                            @php
                                                                $deductionItem = DB::table('employee_payroll_item_details')
                                                                    ->where('employee_payroll_item_details.payroll_id', $item->id)
                                                                    ->where('employee_payroll_item_details.item_id', $deduction_item->id)
                                                                    ->first();
                                                                $total_deduction_item_amount += $deductionItem ? abs($deductionItem->total_amount) : 0;
                                                            @endphp
                                                        @endforeach
                                                        <th class="text-center">{{ $total_deduction_item_amount }}</th>
                                                    @endforeach
                                                    <th class="text-center">{{ $total_salary }}</th>
                                                </tr>

                                                </tbody>
                                                <tr>
                                                    <td id="footer-bottom1">
                                                        <br><br><br>
                                                        <h4>Total Payable Amount: <b>{{ $total_gross_salary }}</b></h4>
                                                        <h4>Total Payable Amount In Words: <b>
                                                                {{ ucwords($digit->format($total_gross_salary)) }} Only</b>
                                                        </h4>
                                                    </td>
                                                    <td id="footer-bottom2">
                                                        <br>
                                                        <br>
                                                        <br>
                                                        @php
                                                            $madrasah = DB::table('companies')->first();
                                                        @endphp
                                                        <div class="principal-sign-box pull-right text-center float-right">
                                                            {{-- <img src="{{ asset(config('app.asset') . 'image/principalSignature/principal-signature.svg') }}"
                                                                class="principal-sign" alt="principal-signature"
                                                                height="50"> --}}
                                                            {!! $madrasah->principal_details_in_certificate !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                        </table>

                                    </div>

                                </div>
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


                        // Get the number of columns in the table
                        var numColumns = $('table thead tr th').length;

                        // Set the colspan of the footer element
                        /* $('#totalAmount').attr('colspan', numColumns - 1); */
                        $('#footer-bottom1').attr('colspan', numColumns / 2);
                        $('#footer-bottom2').attr('colspan', numColumns / 2);

                    });


                    $(document).ready(function() {

                        $('.chart-of-expense-select2').select2({
                            width: "100%",
                            placeholder: "Select Chart of Expense",
                        });
                        $('.select2').select2({
                            width: "100%",
                        });
                        $('.select3').select2({
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

                            responsive: false,
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
                                titleAttr: 'Generate Excel',
                                className: 'btn-outline-success btn-sm mr-1',
                                 messageTop: 'Salary for {{ $month_name->name }}, {{ $academic_year->year }}'
                            }]
                        });


                    });
                </script>
            @endsection
