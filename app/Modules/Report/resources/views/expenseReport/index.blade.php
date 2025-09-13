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

            td,th {
                font-size: {{ $reportFontSize }}px;
            }
        }

        td,th {
            font-size: {{ $reportFontSize }}px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active"> @lang('Payment::label.EXPENSE') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Payment::label.EXPENSE') @lang('Student::label.REPORTS')
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
                            'route' => 'expense.report.filter',
                            'method' => 'post',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">@lang('Payment::label.EXPENSE') @lang('Payment::label.CHART')</label>
                                        {!! Form::select('expense_chart_id[]', $salesCharts, !empty($request->expense_chart_id) ? json_decode($request->expense_chart_id) : null, [
                                            'class' => 'form-control chart-of-expense-select2',
                                            'multiple' => true,
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <div class="form-line">
                                        <label class="col-form-label">From Date</label>
                                        {!! Form::text('from_date', isset($request->from_date) ? $request->from_date :  old('from_date'), [
                                            'id' => 'from_date',
                                            'required' => 'required',
                                            'class' => 'form-control from_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group">

                                    <div class="form-line">
                                        <label class="col-form-label">To Date</label>
                                        {!! Form::text('to_date',isset($request->to_date) ? $request->to_date :  old('to_date'), [
                                            'id' => 'to_date',
                                            'class' => 'form-control to_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">

                                    <div class="form-line">
                                        <div class="custom-control custom-switch">
                                            <br>
                                            <br>
                                            <input type="checkbox" class="custom-control-input" name="header_wise"
                                                value="1"{{ $request->header_wise ? 'checked' : '' }} id="customSwitch3">
                                            <label class="custom-control-label" for="customSwitch3">Header Wise
                                                Report</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                    <button class="btn btn-primary ml-auto waves-effect waves-themed btn-sm mt-3"
                                        name="submit" type="submit">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>

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
                                            <img src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}" height="90" class="example-p-5">
                                            <h2>{{ $companyDetails->company_name }}</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span style="font-size:{{ Session::get('fontsize') }}px;">{{ $companyDetails->address }}</span><br>
                                            <p style="font-size:{{ Session::get('fontsize') }}px;">Tel:
                                                {{ $companyDetails->phone }}, Email:
                                                {{ $companyDetails->email }}</p>
                                        </div>
                                    </div>
                                </center>
                                <center>
                                    <h5 style="margin-bottom: 0px;"><strong>EXPENSE REPORT</strong></h5>
                                    <caption> {{ $pageTitle }}</caption>
                                </center>
                                <div class="body">
                                    <div class="">
                                        <div class="table-responsive"
                                            @if (Session::get('ReportFontSizeEnable') == 1) style="font-size: {{ Session::get('ReportfontSize') }}px;" @endif>
                                            <table class="table table-bordered table-hover" id="testTable">
                                                <thead class="thead-themed" style="background: #d1d1d1;">
                                                    <tr>
                                                        <th class="text-left"> No</th>
                                                        @if (!$header_wise)
                                                            <th class="text-left"> Date </th>
                                                        @endif
                                                        {{-- <th class="text-center"> Outlet Name </th> --}}
                                                        <th class="text-center"> Expense Chart </th>
                                                        <th class="text-center"> Payment Type </th>
                                                        @if (!$header_wise)
                                                            <th class="text-center"> Notes </th>
                                                        @endif
                                                        <th class="text-right"> Payment Amount (BDT)</th>
                                                        <th class="text-right"> Balance (BDT)</th>
                                                    </tr>

                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $total_paid = 0;
                                                    $total_rows = 1;
                                                    ?>
                                                    @if (count($data) > 0)
                                                        <?php
                                                        $total_paid = 0;
                                                        $total_rows = 1;
                                                        ?>
                                                        @foreach ($data as $values)
                                                            @php
                                                                $notes = DB::table('cash_banks')
                                                                    ->where('invoice_no', $values['invoice'])
                                                                    ->where('status', 'active')
                                                                    ->select('cash_banks.note')
                                                                    ->first();
                                                                $chartName = DB::table('sales_chart')
                                                                    ->where('id', $values['Expense_chart'])
                                                                    ->where('status', 'active')
                                                                    ->select('sales_chart.name')
                                                                    ->first();
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <?= $total_rows ?>
                                                                </td>
                                                                @if (!$header_wise)
                                                                    <td>
                                                                        {{ $values['Date'] }}
                                                                    </td class="text-center">
                                                                @endif
                                                                <td class="text-center">{{ $chartName->name }}</td>

                                                                <td class="text-center">{{ $values['Type'] }}</td>
                                                                @if (!$header_wise)
                                                                    <td class="text-right">{{ !empty($notes->note) ? $notes->note : '' }} </td>
                                                                @endif
                                                                <td class="text-right">{{ $values['payment_amount'] }}
                                                                </td>

                                                                <?php
                                                                $total_rows++;
                                                                $total_paid += $values['payment_amount'];
                                                                ?>
                                                                <td class="text-right">{{ number_format($total_paid, 2) }}
                                                                </td>
                                                        @endforeach
                                                    @endif
                                                </tbody>

                                                <tfoot style="background: #d1d1d1;">
                                                    <tr>
                                                        <td>Total</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        @if (!$header_wise)
                                                            <td></td>
                                                            <td></td>
                                                        @endif
                                                        <td class="text-right">{{ number_format($total_paid, 2) }} </td>
                                                    </tr>
                                                </tfoot>
                                            </table>


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
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }]
                });

            });
        </script>
    @endsection
