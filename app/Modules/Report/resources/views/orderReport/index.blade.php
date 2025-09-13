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
        <li class="breadcrumb-item active"> @lang('Report::label.ORDER') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Report::label.ORDER') @lang('Student::label.REPORTS')
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
                            'route' => 'order.report.filter',
                            'method' => 'post',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">Form Date</label>
                                        {!! Form::text('from_date', !empty($request->from_date) ? $request->from_date : date('d-m-Y'), [
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
                                        {!! Form::text('to_date', !empty($request->to_date) ? $request->to_date : date('d-m-Y'), [
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
                                    <h5 style="margin-bottom: 0px;"><strong>Order REPORT</strong></h5>
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
                                                        <th class="text-center" style="width: 80%;">Item Name</th>
                                                        <th class="text-center">Total Order</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Pending Orders</td>
                                                        <td>{{count($pendingOrders)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Processing Orders</td>
                                                        <td>{{count($processingOrders)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Query Orders</td>
                                                        <td>{{count($queryOrders)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cancel Orders</td>
                                                        <td>{{count($cancelOrders)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Completed Orders</td>
                                                        <td>{{count($completedOrders)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Delivered Orders</td>
                                                        <td>{{count($deliveredOrders)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Orders</td>
                                                        <td>{{count($allOrders)}}</td>
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
                        filename: 'Order Report',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }]
                });

            });
        </script>
    @endsection
