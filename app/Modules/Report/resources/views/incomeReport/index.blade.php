@extends('Admin::layouts.master')
@section('body')
    <style>
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

        tr:nth-child(even),
        tr:nth-child(odd){
            background-color: #ffffff;
        }
        
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active"> @lang('Report::label.INCOME') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Report::label.INCOME') @lang('Student::label.REPORTS')
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
                            'route' => 'income.report.filter',
                            'method' => 'post',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">Form Date</label>
                                        {!! Form::text('from_date', !empty($request->from_date) ? $request->from_date : null, [
                                            'id' => 'from_date',
                                            'required' => 'required',
                                            'class' => 'form-control from_date',
                                            'placeholder' => 'dd-mm-yyyy',
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
                                            'required' => 'required',
                                            'class' => 'form-control to_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <div class="panel-content align-items-center float-right">
                                    <button class="btn btn-primary ml-auto waves-effect waves-themed btn-sm mt-3"
                                        name="submit" type="submit">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>
                        <br>

                        {!! Form::close() !!}
                        @if ($request->search == 'true')
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
                                                {{ $companyDetails->phone }}, Email:
                                                {{ $companyDetails->email }}</p>
                                        </div>
                                    </div>
                                </center>
                                <center>
                                    <h5 style="margin-bottom: 0px;"><strong>INCOME & EXPENSE REPORT</strong></h5>
                                    <caption> {{ $pageTitle }}</caption>
                                </center>
                                <div class="body">
                                    <div class="">
                                        <div class="table-responsive">
                                            @if (Session::get('ReportFontSizeEnable') == 1)
                                                style="font-size: {{ Session::get('ReportfontSize') }}px;"
                                            @endif
                                            <table class="table table-bordered table-hover" id="testTable">
                                                <thead class="thead-themed" style="background: #d1d1d1;">
                                                    <tr style="background-color: #d1d1d1">
                                                        <th class="text-center" style="width: 10%;">Serial No.</th>
                                                        <th class="text-center">Description Of Income</th>
                                                        <th class="text-center">Income Taka</th>
                                                        <th class="text-center">Description Of Expense</th>
                                                        <th class="text-center">Expense Taka</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total_item = 0;
                                                        $total_tution = 0;
                                                        $total_income = 0;
                                                        $total_expense = 0;
                                                        $serial_no = 1;
                                                    @endphp
                                                    @if (!empty($incomeInfo) || !empty($expenseInfo))
                                                        @php
                                                            $incomeCount = max(count($incomeInfo), count($expenseInfo));
                                                        @endphp
                                                        @for ($i = 0; $i < $incomeCount; $i++)
                                                            <tr>
                                                                <td class="text-center">{{ $serial_no++ }}</td>
                                                                <td class="text-center">{{ !empty($incomeInfo[$i]->product_name) ? $incomeInfo[$i]->product_name : '' }}</td>
                                                                <td class="text-center">{{ !empty($incomeInfo[$i]->totalPrice) ? $incomeInfo[$i]->totalPrice : '' }}</td>
                                                                <td class="text-center">{{ !empty($expenseInfo[$i]->sales_chart_name) ? $expenseInfo[$i]->sales_chart_name:'' }}</td>
                                                                <td class="text-center">{{ !empty($expenseInfo[$i]->expense_price) ? $expenseInfo[$i]->expense_price : '' }}</td>
                                                                @php
                                                                    $total_item += !empty($incomeInfo[$i]->totalPrice) ? $incomeInfo[$i]->totalPrice : 0;
                                                                    $total_expense += !empty($expenseInfo[$i]->expense_price) ? $expenseInfo[$i]->expense_price : 0;
                                                                @endphp
                                                            </tr>
                                                        @endfor
                                                    @endif
                                                    @php
                                                        $total_income = (!empty($total_item) ? $total_item : 0) + (!empty($total_tution) ? $total_tution : 0);
                                                    @endphp
                                                    <tr style="background-color: #d1d1d1">
                                                        <td class="text-center" colspan="2">Total Income</td>
                                                        <td class="text-center">{{ !empty($total_income) ? $total_income : 0 }}</td>
                                                        <td class="text-center">Total Expense</td>
                                                        <td class="text-center">{{ $total_expense }}</td>
                                                    </tr>
                                                    <tr style="background-color: #d1d1d1">
                                                        <td class="text-right" colspan="4">Balance TK</td>
                                                        <td class="text-center">{{ $total_income - $total_expense }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

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

            });
            $(document).ready(function() {

                $('.chart-of-expense-select2').select2({
                    width: "100%",
                    placeholder: "Select Chart of Expense",
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
                        filename: 'Income Report',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }]
                });

            });
        </script>
    @endsection
