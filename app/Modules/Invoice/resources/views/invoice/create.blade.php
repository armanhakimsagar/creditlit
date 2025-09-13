@extends('Admin::layouts.master')
@section('body')
    <style>
        a[target]:not(.btn) {
            text-decoration: none !important;
        }

        tr:nth-child(even) {
            background-color: #fff;
        }


        /* Invoice */
        img {
            width: 100%;
        }

        .full-wrapper {
            width: 100%;
            margin: 0 auto;
            position: relative;
        }

        .footer-area {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            bottom: 0;
        }

        .invoice-main-area {
            padding: 20px 48px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .logo {
            width: 150px;
        }


        .header-right-side h1 {
            font-size: 14px;
            font-weight: 800;
            font-family: Arial;
            color: #052B79;
            text-align: right;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .header-right-side h2 {
            font-size: 10px;
            font-weight: 500;
            font-family: 'Open Sans', sans-serif;
            color: #000000;
            text-align: right;
            margin-bottom: 2px;
        }


        .invoice-header h1 {
            font-size: 16px;
            font-weight: bold;
            font-family: Arial;
            color: #052B79;
            text-align: center;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        hr {
            margin-top: 5px;
            height: 3px;
            background: #052B79;
        }

        .invoice-details-header {
            background: #C8D9FD;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 19px;
            margin-top: 30px;
            padding: 0 10px;

        }

        .invoice-details-header span {
            font-size: 10px;
            font-weight: 800;
            font-family: Arial;
            color: #052B79;
            text-transform: uppercase;
            width: 50%;
            text-align: left;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 50px;
        }

        .buyer-details {
            padding-left: 10px;
            width: 50%;
        }

        .invoice-details {
            margin-top: 7px;
        }

        .buyer-details h1 {
            font-size: 10px;
            font-weight: bold;
            font-family: 'Open Sans', sans-serif;
            color: #000000;
            text-align: left;
            margin-bottom: 0;
        }

        .buyer-details h2 {
            font-size: 10px;
            font-weight: 500;
            font-family: 'Open Sans', sans-serif;
            color: #000000;
            text-align: left;
            margin-bottom: 2px;
        }

        .bill-details {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            column-gap: 20px;
            width: 50%;
        }

        .bill-details h2 {
            font-size: 10px;
            font-weight: 500;
            font-family: 'Open Sans', sans-serif;
            color: #000000;
            text-align: left;
            margin-bottom: 2px;
        }

        .bill-details-title h2 {
            text-align: right;
        }

        /* bill table area start */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #C8D9FD;
        }

        th {
            border: none;
            text-align: center;
            height: 21px;
            font-size: 10px;
            font-weight: 800;
            font-family: Arial;
            color: #052B79;

        }

        td {
            border: 1px solid black;
            font-size: 10px;
            font-weight: 500;
            font-family: 'Open Sans', sans-serif;
            color: #000000;
            padding: 0 5px;
            height: 21px;
            text-align: center;
        }

        tr td:nth-child(2) {
            text-align: left;
        }

        /* Customize column width and height */
        colgroup {
            col:nth-child(1) {
                width: 5%;
            }

            col:nth-child(2) {
                width: 50%;
            }

            col:nth-child(3) {
                width: 15%;
            }

            col:nth-child(4) {
                width: 15%;
            }

            col:nth-child(5) {
                width: 15%;
            }
        }

        /* bill table area end */

        .note {
            font-family: Arial;
            color: #052B79;
            font-size: 8px;
            font-weight: 500;
        }

        .calculation-area {
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            column-gap: 10px;
        }

        .calculation-title {
            background: #C8D9FD;
            padding: 7px 5px 5px 15px;
        }

        .calculation-title h3 {
            font-size: 8px;
            font-weight: 800;
            font-family: Arial;
            color: #052B79;
            text-align: right;
            margin-bottom: 9px;
        }

        .calculation-title h1 {
            font-size: 11px;
            font-weight: 800;
            font-family: Arial;
            color: #052B79;
            text-align: right;
            margin-bottom: 4px;
        }

        .td-color>td {
            color: #052B79;
            text-align: center !important;
            
        }

        .td-bold {
            font-weight: 800;
            font-size: 11px;

        }

        .calculation-table {
            width: 30%;
        }

        .footer-head {
            margin-top: 50px;
        }

        .margin-bottom>h2 {
            margin-bottom: 7px !important;
        }

        .account-details {
            margin-left: 30px;
        }

        .special-note {
            font-size: 10px;
            font-weight: 900;
            font-family: 'Open Sans', sans-serif;
            color: #000000;
        }

        .no-need-signeture {
            font-size: 9px;
            font-weight: 500;
            font-family: Arial;
            color: #052B79;
            text-align: center;
            margin: 10px 0 0px 0;

        }

        .company-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
            margin-bottom: 45px;
        }

        .contact-icon {
            width: 30px;
            height: 30px;
            color: #ffffff;
            font-size: 15px;
            background: #052B79;
            text-align: center;
        }

        .contact-item {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            row-gap: 10px;
        }

        .contact-item p {
            font-size: 12px;
            font-weight: 600;
            font-family: Arial;
            color: #000000;
            margin-bottom: 0;
            text-align: center;
        }





        /* page one end */


        /* page two start */
        .statment-header {
            display: flex;
            justify-content: left;
            align-items: center;
            column-gap: 10px;
            
        }

        .statment-header>span:nth-child(1) {
            background: #C8D9FD;
            font-size: 10px;
            font-weight: 800;
            font-family: Arial;
            color: #052B79;
            width: 15%;
            text-align: right;
            padding: 3px 10px;

        }

        .statment-header>span:nth-child(2) {
            font-size: 10px;
            font-weight: 800;
            font-family: Arial;
            color: #000000;
            padding: 3px 10px;

        }
        .statment th{
            border: 1px solid #000000;
        }
        .statment colgroup {
            col:nth-child(1) {
                width: 5%;
            }

            col:nth-child(2) {
                width: 10%;
            }

            col:nth-child(3) {
                width: 10%;
            }

            col:nth-child(4) {
                width: 20%;
            }

            col:nth-child(5) {
                width: 30%;
            }

            col:nth-child(6) {
                width: 15%;
            }

            col:nth-child(7) {
                width: 10%;
            }
        }

        .statment-total-amount {
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
        }

        .statment-total-amount span {
            font-size: 11px;
            font-weight: 800;
            font-family: Arial;
            color: #000000;
            width: 10%;
            text-align: center;

        }

        .statment-total-amount span:nth-child(1) {
            background: #C8D9FD;
            width: 15%;
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
        <li class="breadcrumb-item"> @lang('Invoice::label.INVOICE') @lang('Item::label.DETAILS')</li>
        <li class="breadcrumb-item active">@lang('Invoice::label.INVOICE') @lang('Invoice::label.CREATE')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Invoice::label.INVOICE') @lang('Invoice::label.CREATE')
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
                            'route' => 'create.invoice.filter',
                            'method' => 'post',
                            'id' => 'collectionReportId',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">

                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">From Date</label>
                                        {!! Form::text('from_date', isset($request->from_date) ? $request->from_date : old('from_date'), [
                                            'id' => 'from_date',
                                            'required' => 'required',
                                            'class' => 'form-control from_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}
                                        <label id="fromDateError" class="error" for="customer_id"></label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">To Date</label>
                                        {!! Form::text('to_date', isset($request->to_date) ? $request->to_date : old('to_date'), [
                                            'id' => 'to_date',
                                            'class' => 'form-control to_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}
                                        <label id="toDateError" class="error" for="customer_id"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('customer_type', 'Customer Type', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select(
                                            'customer_type',
                                            ['' => 'All', 'bank' => 'Bank', 'branch' => 'Branch', 'company' => 'Company'],
                                            $bank->status ?? 'all',
                                            [
                                                'id' => 'customer_type',
                                                'class' => 'form-control select2',
                                                'onchange' => 'getCustomer();',
                                            ],
                                        ) !!}
                                        <label id="customerTypeError" class="error" for="customer_id"></label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('bank_type', 'Bank Type', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select(
                                            'bank_type',
                                            ['' => 'Select Bank Type', 'centralized' => 'Centralized', 'decentralized' => 'Decentralized'],
                                            $bank->status ?? 'all',
                                            [
                                                'id' => 'bank_type',
                                                'class' => 'form-control select2',
                                                'onchange' => 'getCustomer();',
                                            ],
                                        ) !!}
                                        <label id="bankTypeError" class="error" for="customer_id"></label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('customer_id', 'Customer Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                        {!! Form::Select('customer_id', $bankId, isset($branch) ? $branch->customer_id : null, [
                                            'id' => 'customer_id',
                                            'class' => 'form-control select2',
                                            'onchange' => '',
                                        ]) !!}
                                        <label id="customerIdError" class="error" for="customer_id"></label>
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
                        @if(count($orderList)>0)
                            <div class="subheader">
                                <button style="margin-left: auto;" class="btn btn-info btn-sm print_full_data mt-5"
                                    onclick="window.print();">Print</button>
                            </div>
                            <div id='printMe'>
                                <div class="body">
                                    <div class="full-wrapper">
                                        <div class="container-fluid p-0">
                                            <div class="invoice-main-area">
                                                <!-- header area start -->
                                                <div class="header">
                                                    <div class="header-left-side">
                                                        <div class="logo">
                                                            <a href="#">
                                                                <img src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}" alt="logo">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="header-right-side">
                                                        <h1>{{ $companyDetails->company_name }}</h1>
                                                        <h2 style="width: 170px;">{{ $companyDetails->address }}</h2>
                                                    </div>
                                                </div>

                                                <div class="invoice-header">
                                                    <h1>INVOICE SUMMERY</h1>
                                                    <hr>
                                                </div>
                                                <div class="invoice-details-area">
                                                    <div class="invoice-details-header">
                                                        <span>BILL TO </span>
                                                        <span>INVOICE DETAILS </span>
                                                    </div>
                                                    <!-- buyer details area start -->
                                                    <div class="invoice-details">
                                                        <div class="buyer-details">
                                                            <h2>Attn: {{ $data->key_personnel_name }}</h2>
                                                            @if ($data->type == '1')
                                                                <h2>Head Office Branch</h2>
                                                            @endif
                                                            <h1>{{ $data->full_name }}</h1>
                                                            <h2 style="width: 170px;">{{ $data->address }}</h2>
                                                        </div>
                                                        <div class="bill-details">
                                                            <div class="bill-details-title">
                                                                <h2>Product</h2>
                                                                <h2>Invoice Date</h2>
                                                                <h2>Invoice No</h2>
                                                                <h2>Billing Period</h2>

                                                            </div>
                                                            <div class="bill-details-content">
                                                                <h2>Business Credit Risk Report</h2>
                                                                @php
                                                                    $invoiceId = now()->format('Ym') . now()->format('s');
                                                                    $invoiceId .= random_int(10, 99);
                                                                @endphp
                                                                <h2>{{ \Carbon\Carbon::now()->format('d F Y') }}</h2>
                                                                <h2>{{ $invoiceId }}</h2>
                                                                <h2>November 2023</h2>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- buyer details area end -->
                                                </div>
                                                <!-- header area end -->

                                                <div class="main-bill-table">
                                                    <table>
                                                        <colgroup>
                                                            <col style="height: 30px;">
                                                            <col style="height: 30px;">
                                                            <col style="height: 30px;">
                                                            <col style="height: 30px;">
                                                            <col style="height: 30px;">
                                                        </colgroup>
                                                        <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>BRANCH NAME</th>
                                                                <th>NO OF REPORT</th>
                                                                <th>TOTAL (USD)</th>
                                                                <th>TOTAL (BDT)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @php
                                                                $sumUSDTotal = 0;
                                                                $sumBDTTotal = 0;
                                                            @endphp
                                                        @foreach ($orderSummary as $customerName => $summary)
                                                            <tr>
                                                                <td>{{ $summary['serial'] }}</td>
                                                                <td>{{ $customerName }}</td>
                                                                <td>{{ $summary['count'] }}</td>
                                                                <td>{{ $summary['total'] }}</td>
                                                                <td>{{ number_format($summary['total'] * $companyDetails->dollarExhangeRateBDT, 2, '.', ',') }}</td>
                                                            </tr>
                                                            @php
                                                                $sumUSDTotal += $summary['total'];
                                                                $sumBDTTotal += $summary['total'] * $companyDetails->dollarExhangeRateBDT;
                                                            @endphp
                                                        @endforeach   

                                                        </tbody>
                                                    </table>
                                                    <p class="note">USD to BDT Exchange Rate = {{ $companyDetails->dollarExhangeRateBDT }}</p>

                                                    <div class="calculation-area">
                                                        <div class="calculation-title">
                                                            <h3>VAT ({{ $companyDetails->invoiceVatPercent }}%)</h3>
                                                            <h3 style="font-weight: 700;">REBATE</h3>
                                                            <h1>TOTAL PAYABLE</h1>
                                                        </div>
                                                        @php
                                                            $vatUSDTotal = $sumUSDTotal * ($companyDetails->invoiceVatPercent / 100);
                                                        @endphp
                                                        <div class="calculation-table">
                                                            <table>
                                                                <tbody>
                                                                    <tr class="td-color">
                                                                        <td>{{ $vatUSDTotal }}</td>
                                                                        <td>{{ number_format($vatUSDTotal * $companyDetails->dollarExhangeRateBDT, 2, '.', ',') }}</td>
                                                                    </tr>
                                                                    <tr class="td-color">
                                                                        <td></td>
                                                                        <td>200</td>
                                                                    </tr>
                                                                    <tr class="td-color ">
                                                                        <td class="td-bold">{{$sumUSDTotal + $vatUSDTotal}}</td>
                                                                        <td class="td-bold">{{number_format(($sumUSDTotal * $companyDetails->dollarExhangeRateBDT + $vatUSDTotal * $companyDetails->dollarExhangeRateBDT), 2, '.', ',')}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- bill footer area start -->
                                                <div class="footer-head">
                                                    <div class="invoice-details-header">
                                                        <span>PAYMENT INSTRUCTION</span>
                                                    </div>

                                                    <div class="bill-details account-details">
                                                        <div class="bill-details-title margin-bottom">
                                                            <h2>Account title</h2>
                                                            <h2>Account no</h2>
                                                            <h2>Bank</h2>
                                                            <h2>Branch</h2>

                                                        </div>
                                                        <div class="bill-details-content margin-bottom">
                                                            <h2>{{$companyDetails->accountTitle}}</h2>
                                                            <h2>{{$companyDetails->AccountNo}}</h2>
                                                            <h2>{{$companyDetails->bankName}}</h2>
                                                            <h2>{{$companyDetails->branchName}}</h2>

                                                        </div>
                                                    </div>
                                                    <h1 class="special-note">Please write all cheques in favor of Credilit Limited</h1>

                                                    <p class="no-need-signeture">This is a computer-generated invoice and needs no signature.</p>
                                                </div>
                                                <!-- bill footer area end -->
                                            </div>
                                        </div>
                                    </div>


                                    <!-- statment section start -->

                                    <div class="full-wrapper">
                                        <div class="container-fluid p-0">
                                            <div class="invoice-main-area">

                                                <div class="invoice-header">
                                                    <h1>DETAILED STATEMENT</h1>
                                                    <hr>
                                                </div>
                                                <br>
                                                @php
                                                    $key = 1;
                                                    $grandTotal = 0;
                                                @endphp
                                                @foreach ($orderSummary as $customerName => $item)                             
                                                <div class="invoice-details-area">
                                                    <div class="statment-header">
                                                        <span>BRANCH:</span>
                                                        <span>{{$customerName}}</span>
                                                    </div>
                                                    <div class="main-bill-table statment">
                                                        <table>
                                                            <colgroup>
                                                                <col style="height: 30px;">
                                                                <col style="height: 30px;">
                                                                <col style="height: 30px;">
                                                                <col style="height: 30px;">
                                                                <col style="height: 30px;">
                                                                <col style="height: 30px;">
                                                                <col style="height: 30px;">
                                                            </colgroup>
                                                            <thead>
                                                                <tr>
                                                                    <th>NO</th>
                                                                    <th>DATE</th>
                                                                    <th>ORDER ID</th>
                                                                    <th>REFERENCE NO</th>
                                                                    <th>INQUIRY NAME</th>
                                                                    <th>COUNTRY</th>
                                                                    <th>PRICE ($)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $key = 1;
                                                                    $branchTotal = 0;
                                                                @endphp
                                                                @foreach($orderList as $orderKey => $orderItem)
                                                                    @if ($orderItem->customer_id == $item['customer_id'])
                                                                        <tr>
                                                                            <td>{{$key}}</td>
                                                                            <td>{{$orderItem->order_date}}</td>
                                                                            <td>{{$orderItem->order_invoice_no}}</td>
                                                                            <td>{{$orderItem->bank_reference}}</td>
                                                                            <td>{{$orderItem->customer_name}}</td>
                                                                            <td>{{$orderItem->country_name}}</td>
                                                                            <td>{{$orderItem->selling_price}}</td>
                                                                        </tr>
                                                                        @php
                                                                            $key++;
                                                                            $branchTotal += $orderItem->selling_price;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach

                                                            </tbody>
                                                        </table>

                                                        <div class="statment-total-amount">
                                                            <span>Branch Total</span>
                                                            <span>{{$branchTotal}}</span>
                                                        </div>
                                                        @php
                                                            $grandTotal += $branchTotal;
                                                        @endphp
                                                    </div>
                                                </div>
                                                @endforeach
                                                
                                                <div class="statment-total-amount">
                                                    <span>Grand Total$</span>
                                                    <span>{{$grandTotal}}</span>
                                                </div>

                                                {!! Form::open([
                                                    'route' => 'store.invoice',
                                                    'files' => true,
                                                    'name' => 'invoice-add',
                                                    'id' => 'invoiceAdd',
                                                    'class' => 'form-horizontal',
                                                    'autocomplete' => true,
                                                ]) !!}

                                                <input type="hidden" name="invoiceNo" value="{{$invoiceId}}">
                                                <input type="hidden" name="invoiceDate" value="{{ \Carbon\Carbon::now()->format('d F Y') }}">
                                                <input type="hidden" name="orderId" value="{{$orderIdArray}}">
                                                <input type="hidden" name="customerId" value="{{$customerId}}">
                                                <input type="hidden" name="customerType" value="{{$customerType}}">
                                                <input type="hidden" name="usdToBdt" value="{{$companyDetails->dollarExhangeRateBDT}}">
                                                <input type="hidden" name="vat" value="{{$companyDetails->invoiceVatPercent}}">
                                                <input type="hidden" name="total_amount" value="{{$sumUSDTotal + $vatUSDTotal}}">

                                                <div class="row mt-5">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <button class="btn btn-primary ml-auto waves-effect waves-themed float-right" id="btnsm"
                                                                    type="submit">Save Invoice</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {!! Form::close() !!}
                                            </div>
                                        </div>

                                        
                                    </div>


                                </div>
                                
                            </div>
                        @else 
                            <div class="card">
                                <div class="card-body">
                                    <h1 class="text-danger text-center">No data available for selected filter.</h1>
                                </div>
                            </div>
                        @endif
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




        function validate() {
            if ($("#from_date").val() === '' || $("#to_date").val() === '' || $("#customer_type").val() === '' || $(
                    "#customer_id").val() === '') {

                if ($("#customer_id").val() === '') {
                    $('#customerIdError').html('Please Select Customer');
                } else {
                    $('#customerIdError').html('');
                }

                if ($("#from_date").val() === '') {
                    $('#fromDateError').html('Please Select From Date');
                } else {
                    $('#fromDateError').html('');
                }

                if ($("#to_date").val() === '') {
                    $('#toDateError').html('Please Select To Date');
                } else {
                    $('#toDateError').html('');
                }

                if ($("#customer_type").val() === '') {
                    $('#customerTypeError').html('Please Select Customer Type');
                } else {
                    $('#customerTypeError').html('');
                }
            } else {
                $('#customerIdError').html('');
                $('#fromDateError').html('');
                $('#toDateError').html('');
                $('#customerTypeError').html('');
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

        // Customer Change on select Customer Type
        function getCustomer() {
            var customerType = $('#customer_type').val();
            var bankType = $('#bank_type').val();
            var html = '';
            if (customerType != 0) {
                $.ajax({
                    url: "{{ url('get-invoice-customer') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        customerType: customerType,
                        bankType: bankType
                    },
                    beforeSend: function() {
                        $('select[name="customer_id"]').empty();
                    },
                    success: function(response) {
                        if (customerType == 'bank') {
                            $('select[name="customer_id"]').append('<option value="0">Select Bank</option>');
                            $("#bank_type").prop('disabled', false);
                        } else if (customerType == 'branch') {
                            $('select[name="customer_id"]').append('<option value="0">Select Branch</option>');
                            $("#bank_type").prop('disabled', true);
                        } else {
                            $('select[name="customer_id"]').append('<option value="0">Select Company</option>');
                            $("#bank_type").prop('disabled', true);
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
    </script>
@endsection
