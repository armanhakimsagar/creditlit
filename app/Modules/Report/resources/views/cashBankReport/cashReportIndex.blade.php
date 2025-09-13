@extends('Admin::layouts.master')
@section('body')
    <style>
        a[target]:not(.btn){
            text-decoration: none !important;
        }
        td,
        th {
            font-size: {{ $reportFontSize }}px;
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
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active"> @lang('Report::label.CASH') @lang('Report::label.BOOK') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Report::label.CASH') @lang('Report::label.BOOK') @lang('Student::label.REPORTS')
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
                            'route' => 'cash.book.report.filter',
                            'method' => 'post',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-5">
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

                            <div class="col-md-5 ">
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
                            <div class="col-md-2">
                                <div class="align-items-start">
                                    <button class="btn btn-primary ml-auto waves-effect waves-themed btn-sm mt-5"
                                        name="submit" type="submit">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>
                        <br><br>

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
                                    <h5 style="margin-bottom: 0px;"><strong>@lang('Report::label.CASH') @lang('Report::label.BOOK')
                                            @lang('Report::label.REPORT')</strong></h5>
                                    <caption> {{ $pageTitle }}</caption>
                                </center>
                                <div class="body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="testTable">
                                                <thead class="thead-themed" style="background: #d1d1d1;">

                                                    <tr>
                                                        <th class="text-left"> No</th>
                                                        <th class="text-left"> Date </th>
                                                        <th class="text-center"> Invoice Number</th>
                                                        <th class="text-center"> Student Name</th>
                                                        <th class="text-center"> Notes</th>
                                                        <th class="text-right"> Receive (BDT)</th>
                                                        <th class="text-right"> Payment (BDT)</th>
                                                        <th class="text-right"> Balance (BDT)</th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="7">Opening Balance</th>
                                                        <th class="text-right">{{ $opening_balance }}</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    @php
                                                        $remaining_installment = 0;
                                                        $debit = 0;
                                                        $credit = 0;
                                                        $total_down = 0;
                                                        
                                                        $total_rows = 0;
                                                        $balance = $opening_balance;
                                                    @endphp

                                                    @if (count($data) > 0)
                                                        <?php
                                                        $remaining_installment = 0;
                                                        $debit = 0;
                                                        $credit = 0;
                                                        $total_down = 0;
                                                        $total_rows = 1;
                                                        $balance = $opening_balance;
                                                        ?>
                                                        @foreach ($model as $values)
                                                            @php
                                                                $customerName = DB::table('contacts')
                                                                    ->where('id', $values->customer_id)
                                                                    ->first();
                                                            @endphp

                                                            <tr>
                                                                <td>
                                                                    <?= $total_rows++ ?>
                                                                </td>
                                                                <td>
                                                                    {{ $values->invoice_date }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $values->invoice_no }}
                                                                </td>
                                                                <td class="text-center">
                                                                    @if (isset($values->customer_id))
                                                                        @if($customerName->type == 1)
                                                                            <a href="{{ route(app('studentName'), $customerName->id) }}" target="_blank" style="text-decoration: none;">
                                                                                {{ $customerName->full_name }}
                                                                            </a>
                                                                        @else
                                                                            {{ $customerName->full_name }}
                                                                        @endif
                                                                        
                                                                    @endif
                                                                </td>
                                                                <td>{{ $values->note }}</td>
                                                                @if ($values->amount > 0)
                                                                    @php
                                                                        $check_writeoff = DB::table('payment_history')
                                                                            ->join('sales_payment', 'payment_history.id', 'sales_payment.payment_relation_id')
                                                                            ->where('payment_history.payment_invoice', $values->invoice_no)
                                                                            ->where('sales_payment.write_of', 1)
                                                                            ->first();
                                                                    @endphp
                                                                    @if (!$check_writeoff)
                                                                        <td class="text-right">
                                                                            {{ number_format($values->amount, 2) }} </td>
                                                                        <td class="text-right"> 0 </td>
                                                                        @php
                                                                            $debit += $values->amount;
                                                                            $balance += $values->amount;
                                                                        @endphp
                                                                    @endif
                                                                @else
                                                                    <td class="text-right"> 0 </td>
                                                                    <td class="text-right">
                                                                        {{ str_replace('-', '', number_format($values->amount, 2)) }}
                                                                    </td>
                                                                    @php
                                                                        $credit += $values->amount;
                                                                        $balance += $values->amount;
                                                                    @endphp
                                                                @endif
                                                                <td class="text-right">{{ $balance }}</td>
                                                        @endforeach
                                                    @endif
                                                    </tr>
                                                </tbody>
                                                <tfoot style="background: #d1d1d1;">
                                                    <tr>

                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right">{{ number_format($debit, 2) }} </td>
                                                        <td class="text-right">
                                                            {{ str_replace('-', '', number_format($credit, 2)) }} </td>
                                                        <td class="text-right">{{ $balance }}</td>

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
