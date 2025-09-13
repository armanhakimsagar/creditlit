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

        /* .footer-area {
                                                                                        position: absolute;
                                                                                        bottom: 0;
                                                                                        left: 0;
                                                                                        right: 0;
                                                                                        width: 100%;
                                                                                        bottom: 0;
                                                                                    } */

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

        .statment th {
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

        .main-table {
            border-collapse: collapse;
            border: none;
        }

        .main-table td {
            border: 0px;
        }

        .sub-table td {
            border: 1px solid #000000;
        }

        .main-table thead {
            margin: 200px;
        }

        .header {
            background: #C8D9FD;
            padding: 10px 20px;
        }

        @media print {

            html,
            body {
                margin: 0;
                padding: 0;
            }


            #notPrintDiv,
            .page-breadcrumb,
            .subheaders,
            .subheader-top {
                display: none !important;
            }

            td,
            th {
                font-size: {{ $reportFontSize }}px;
            }


            @page {
                size: a4 portrait;
                margin: 0 0 0 0;
            }

            .header {
                position: fixed;
                top: 0;
                width: 100%;
            }

            .content {
                margin-top: 50px;
            }



            .footer-area {
                position: fixed;
                bottom: 0;
                width: 100%;
                background-color: #f5f5f5;
                /* Adjust as needed */
                padding: 10px 0;
                text-align: center;
                font-size: 12px;
                border-top: 1px solid #dddddd;
                /* Adjust as needed */
            }

            .details-statement-page-break,
            .page-break {
                page-break-before: always;
                margin-top: 100px;
            }




            /* table {
                    border: none;
                    page-break-after: always;
                }

                body>table:last-of-type {
                    page-break-after: auto
                } */
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
        <div class="col-md-12 main-section">
            <div class="card">
                <div class="card-body">
                    <div class="panel-content">

                        <br><br>
                        @php
                            $studentArrId = '';
                        @endphp
                        @if (count($orderList) > 0)
                        
                            <div class="subheaders mb-3">
                                @if($invoiceDetails->paymentStatus == 'due')
                                    <div class="alert alert-danger" role="alert">
                                        This invoice is still due. Please, pay now.
                                    </div>
                                @else
                                    <div class="alert alert-success" role="alert">
                                        This invoice payment done.
                                    </div>
                                @endif

                                @if($invoiceDetails->paymentStatus == 'due')
                                <form method="POST" action="{{ route('update.invoice', [$invoiceDetails->id]) }}">
                                    @csrf
                                <div class="row subheader-top">

                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('total_amount', 'Total Amount', ['class' => 'col-form-label']) !!}
                                    
                                                    {!! Form::number('total_amount', $invoiceDetails->total_amount, [
                                                            'id' => 'total_amount',
                                                            'class' => 'form-control select2',
                                                        ]) !!}
                                    
                                                    <span class="error"> {!! $errors->first('status') !!}</span>
                                                </div>
                                            </div>
                                        </div>
                                    

                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            <div class="form-line">
                                                {!! Form::label('paymentStatus', 'Payment Status', ['class' => 'col-form-label']) !!}

                                                {!! Form::Select(
                                                    'paymentStatus',
                                                    ['due' => 'Due', 'paid' => 'Paid'],
                                                    $invoiceDetails->paymentStatus ?? 'due',
                                                    [
                                                        'id' => 'paymentStatus',
                                                        'class' => 'form-control selectheighttype',
                                                    ],
                                                ) !!}

                                                <span class="error"> {!! $errors->first('status') !!}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2 mt-5">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <button class="btn btn-primary ml-auto waves-effect waves-themed float-left" id="btnsm"
                                                    type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                </form>
                                @endif

                                <div class="row subheader-top">
                                <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <button class="btn btn-info btn-sm print_full_data mt-5 float-right"
                                                    onclick="window.print();">Print</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            
                            <div id='printMe'>
                                <div class="body">

                                    <div class="header pt-3">
                                        <div class="header-left-side">
                                            <div class="logo">
                                                <a href="#">
                                                    <img src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}"
                                                        alt="logo">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="header-right-side">
                                            <h1>{{ $companyDetails->company_name }}</h1>
                                            <h2 style="width: 170px;">{{ $companyDetails->address }}</h2>
                                        </div>
                                    </div>



                                    <div class="content main-data">

                                        <div class="full-wrapper">
                                            <div class="container-fluid p-0">
                                                <div class="invoice-main-area">
                                                    <!-- header area start -->


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
                                                                <h2>Attn: {{ $data->key_personnel_name }}
                                                                </h2>
                                                                @if ($data->type == '1')
                                                                    <h2>Head Office Branch</h2>
                                                                @endif
                                                                <h1>{{ $data->full_name }}</h1>
                                                                <h2 style="width: 170px;">
                                                                    {{ $data->address }}</h2>
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
                                                                    <h2>{{ \Carbon\Carbon::now()->format('d F Y') }}
                                                                    </h2>
                                                                    <h2>{{ $invoiceId }}</h2>
                                                                    <h2>November 2023</h2>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- buyer details area end -->
                                                    </div>
                                                    <!-- header area end -->

                                                    <div class="main-bill-table">
                                                        <table class="sub-table">
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
                                                                    $mainTotalTrCount = 0;
                                                                @endphp
                                                                @foreach ($orderSummary as $customerName => $summary)
                                                                    <tr>
                                                                        <td>{{ $summary['serial'] }}</td>
                                                                        <td>{{ $customerName }}</td>
                                                                        <td>{{ $summary['count'] }}</td>
                                                                        <td>{{ $summary['total'] }}</td>
                                                                        <td>{{ number_format($summary['total'] * $companyDetails->dollarExhangeRateBDT, 2, '.', ',') }}
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $sumUSDTotal += $summary['total'];
                                                                        $sumBDTTotal += $summary['total'] * $companyDetails->dollarExhangeRateBDT;
                                                                        $mainTotalTrCount++;
                                                                    @endphp
                                                                    @if ($mainTotalTrCount % 14 == 0)
                                                                        @if ($loop->remaining > 0)
                                                                            </tbody>
                                                                            </table>
                                                                                <div class="page-break"></div>
                                                                                    <table class="sub-table">
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
                                                                                    @endif
                                                                                @endif
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                        <p class="note">USD to BDT Exchange Rate =
                                                            {{ $companyDetails->dollarExhangeRateBDT }}</p>

                                                        <div class="calculation-area">
                                                            <div class="calculation-title">
                                                                <h3>VAT
                                                                    ({{ $companyDetails->invoiceVatPercent }}%)
                                                                </h3>
                                                                <h3 style="font-weight: 700;">REBATE</h3>
                                                                <h1>TOTAL PAYABLE</h1>
                                                            </div>
                                                            @php
                                                                $vatUSDTotal = $sumUSDTotal * ($companyDetails->invoiceVatPercent / 100);
                                                            @endphp
                                                            <div class="calculation-table" class="sub-table">
                                                                <table>
                                                                    <tbody>
                                                                        <tr class="td-color">
                                                                            <td>{{ $vatUSDTotal }}</td>
                                                                            <td>{{ number_format($vatUSDTotal * $companyDetails->dollarExhangeRateBDT, 2, '.', ',') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="td-color">
                                                                            <td></td>
                                                                            <td>200</td>
                                                                        </tr>
                                                                        <tr class="td-color ">
                                                                            <td class="td-bold">
                                                                                {{ $sumUSDTotal + $vatUSDTotal }}
                                                                            </td>
                                                                            <td class="td-bold">
                                                                                {{ number_format($sumUSDTotal * $companyDetails->dollarExhangeRateBDT + $vatUSDTotal * $companyDetails->dollarExhangeRateBDT, 2, '.', ',') }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                                @if($invoiceDetails->paymentStatus == 'due')
                                                                    <h2 class="mt-3"><span class="badge badge-danger">Unpaid</span></h2>
                                                                @else
                                                                    <h2 class="mt-3"><span class="badge badge-success">Paid</span></h2>
                                                                @endif
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
                                                                <h2>{{ $companyDetails->accountTitle }}
                                                                </h2>
                                                                <h2>{{ $companyDetails->AccountNo }}</h2>
                                                                <h2>{{ $companyDetails->bankName }}</h2>
                                                                <h2>{{ $companyDetails->branchName }}</h2>

                                                            </div>
                                                        </div>
                                                        <h1 class="special-note">Please write all cheques in
                                                            favor of Credilit
                                                            Limited</h1>

                                                        <p class="no-need-signeture">This is a
                                                            computer-generated invoice and
                                                            needs no signature.</p>
                                                    </div>
                                                    <!-- bill footer area end -->
                                                </div>
                                            </div>
                                        </div>


                                        <div class="details-statement-page-break"></div>
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
                                                        $totalTrCount = 0;
                                                        $groupedOrders = $orderList->groupBy('customer_id');
                                                    @endphp
                                                    @foreach ($orderSummary as $customerName => $item)
                                                        @php
                                                            $totalTrCount++;
                                                            $customerOrders = $groupedOrders[$item['customer_id']];
                                                        @endphp
                                                        <div class="invoice-details-area">
                                                            <div class="statment-header">
                                                                <span>BRANCH:</span>
                                                                <span>{{ $customerName }}</span>
                                                            </div>
                                                            <div class="main-bill-table statment">
                                                                <table class="sub-table">
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
                                                                        @foreach ($customerOrders as $orderKey => $orderItem)
                                                                                <tr>
                                                                                    <td>{{ $key }}{{$totalTrCount}}
                                                                                    </td>
                                                                                    <td>{{ $orderItem->order_date }}
                                                                                    </td>
                                                                                    <td>{{ $orderItem->order_invoice_no }}
                                                                                    </td>
                                                                                    <td>{{ $orderItem->bank_reference }}
                                                                                    </td>
                                                                                    <td>{{ $orderItem->customer_name }}
                                                                                    </td>
                                                                                    <td>{{ $orderItem->country_name }}
                                                                                    </td>
                                                                                    <td>{{ $orderItem->selling_price }}
                                                                                    </td>
                                                                                </tr>
                                                                                @php
                                                                                    $totalTrCount++;
                                                                                    $key++;
                                                                                    $branchTotal += $orderItem->selling_price;
                                                                                @endphp

                                                                                @if ($totalTrCount % 13 == 0)
                                                                                    @if ($loop->remaining > 0)
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <div class="page-break"></div>
                                                                                    <table class="sub-table">
                                                                                        <colgroup>
                                                                                            
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
                                                                                    @endif
                                                                                @endif
                                                                                
                                                                        @endforeach

                                                                    </tbody>
                                                                </table>

                                                                <div class="statment-total-amount">
                                                                    <span>Branch Total</span>
                                                                    <span>{{ $branchTotal }}</span>
                                                                </div>
                                                                @php
                                                                    $grandTotal += $branchTotal;
                                                                @endphp
                                                            </div>
                                                        </div>

                                                        @if ($totalTrCount >= 14 && ($totalTrCount - 14) % 13 == 0)
                                                            @if ($loop->remaining > 0)
                                                                <div class="page-break"></div>
                                                            @endif
                                                        @endif

                                                        
                                                    @endforeach

                                                    <div class="statment-total-amount">
                                                        <span>Grand Total$</span>
                                                        <span>{{ $grandTotal }}</span>
                                                    </div>


                                                </div>

                                            </div>


                                        </div>

                                    </div>



                                    <div class="footer-area">
                                        <div class="invoice-header">
                                            <hr>
                                        </div>

                                        <div class="company-details">
                                            <div class="contact-item">
                                                <div class="contact-icon">
                                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                                </div>
                                                <div class="contact-way">
                                                    <p>+880 964 990 9990</p>
                                                    <p>+880 132 973 4171</p>
                                                </div>
                                            </div>
                                            <div class="contact-item">
                                                <div class="contact-icon">
                                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                                </div>
                                                <div class="contact-way">
                                                    <p>support@credilit.com</p>
                                                    <p>www.credilit.com</p>
                                                </div>
                                            </div>
                                            <div class="contact-item">
                                                <div class="contact-icon">
                                                    <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                                </div>
                                                <div class="contact-way">
                                                    <p>3rd Floor, 37/A, Central Road</p>
                                                    <p>Dhaka 1205 Bangladesh</p>
                                                </div>
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
                    </div>

                </div>
            </div>
        </div>



    </div>


<script>
    $(function() {
        $('#paymentStatus').select2();

    });
</script>


@endsection
