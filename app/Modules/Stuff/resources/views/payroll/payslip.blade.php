<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Pay Slip Print:: {{$companyDetails->company_name}} </title>
    <link rel="stylesheet" href="{{ asset(config('app.asset') . 'css/bootstrap3.4.1.min.css') }}">
    <link rel="shortcut icon" href="{{ URL::to(config('app.asset') . 'logo/favicon.ico') }}" />

    <style>
        @font-face {
            font-family: 'Bree Serif';
            font-style: normal;
            font-weight: 400;
            src: local('Bree Serif Regular'), local('BreeSerif-Regular'), url('fonts/BreeSerif/BreeSerif-Regular.woff2') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Bree Serif', serif;
        }

        body {
            font-size: 12px;
            line-height: 1.22857143;
        }

        h5 {
            font-size: 14px;
            margin-top: 2px;
            margin-bottom: 2px;
        }

        h4 {
            font-size: 16px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        h4.admit-title {
            text-align: center;
            font-size: 18px;
            margin: 1em 0;
        }

        h4.admit-title>span {
            background: #000 !important;
            color: #fff !important;
            border-radius: 20px;
            padding: 1px 15px;
        }

        .student-info p {
            font-size: 14px;
        }

        p.student-name span {
            text-transform: uppercase;
        }

        h5.exam-name-year {
            font-size: 15px;
            text-align: center;
            margin: 10px 0 20px;
        }

        .signature-details {
            overflow: hidden;
            margin-top: 0em;
        }

        img.principal-sign {
            height: 30px;
            width: auto;
        }

        .principal-sign>p {
            border-top: 1px solid #555;
            font-size: 12px;
            margin: 0;
        }

        .card {
            border: 1px solid #555;
            margin: 5px 0px;
            padding: 2px 6px;
            position: relative;
        }


        .col-xs-6.out-border {
            padding: 5px;
        }

        .print {
            display: block;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }

        #upload-img {
            margin-top: 8px;
            margin-bottom: 4px;
            /* margin-left: 20px; */
            height: 100px;
            width: 100px;
        }

        .exam_admit_card_container {
            text-align: center;
            /* Set width for the container if needed */
        }

        .exam_admit_card_container img {
            max-width: 100%;
            max-height: 100%;
            display: inline-block;
            width: 100%;
            margin-bottom: -10px;
        }

        .table>thead>tr>th {
            border-bottom: 0;
        }

        .table thead tr th,
        .table tbody tr td {
            border: none;
        }


        .table tbody tr td {
            border: 1px solid #000;
        }

        .table tbody td.gaps {
            border: none;
            display: none;
        }

        textarea.form-control {
            height: 150px;
            border: 1px solid black;
            transition: none;
            outline: none;
            box-shadow: none;
        }

        .form-control {
            border: 1px solid black;
            transition: none;
            outline: none;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #000;
            box-shadow: none;
        }

        .payslip {
            margin-bottom: 10px;
        }

        .Remarks {
            font-size: 20px;
            font-weight: 700;
            color: #000;
        }

        .employee-signature {
            font-size: 16px;
            font-style: italic;
            text-align: center;
            resize: none;
        }

        .table thead tr td,
        .table tbody tr td {
            border-width: 1px !important;
            border-style: solid !important;
            border-color: #000 !important;
        }

        .gross-salary,
        .total-salary,
        .net-salary {
            font-weight: 700;
        }

        @media screen and (max-width: 1199px) {
            .col-xs-6 {
                width: 100%;
            }

            .principal-sign {
                margin-top: -40px;
            }


        }


        /* .col-xs-6.out-border:nth-child(even) {
   border-left: 1px dashed #555;
  }
  .col-xs-6.out-border:nth-child(odd), .col-xs-6.out-border:nth-child(even) {
   border-bottom: 1px dashed #555;
  } */
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 12px !important;
            }

            h4.admit-title {
                text-align: center;
                font-size: 18px;
                margin: 1em 0;
            }

            h4.admit-title>span {
                background: #000 !important;
                color: #fff !important;
                border-radius: 20px;
                padding: 1px 15px;
            }

            h5 {
                font-size: 12px;
                margin-top: 0px;
                margin-bottom: 0px;
            }

            h3 {
                font-size: 18px;
            }

            h3.small {
                font-size: 65%;
                color: #777;
            }

            .table tbody td.gaps {
                border: none;
                display: none;
            }

            .payslip {
                margin-bottom: 10px;
            }

            .Remarks {
                font-size: 20px;
                font-weight: 700;
                color: #000;
            }

            .employee-signature {
                font-size: 16px;
                font-style: italic;
                text-align: center;
                resize: none;
            }

            .table>tbody>tr>td {
                color: #000;
            }

            .gross-salary,
            .total-salary,
            .net-salary {
                font-weight: 700;
            }

            .table thead tr td,
            .table tbody tr td {
                border-width: 1px !important;
                border-style: solid !important;
                border-color: #000 !important;
            }

            @page {
                size: a4 landscape;
                margin: 0mm 0mm 0mm 2mm !important;
            }

            .card {
                border: 1px solid #555;
                margin: 6.7mm;
                /* padding: 2px 6px; */
                position: relative;

            }

            #upload-img {
                margin-top: 8px;
                margin-bottom: 4px;
                margin-right: 80px;
            }

            .pagebreak {
                page-break-after: always;
            }

            .page-break {
                page-break-before: always;
            }

            /* Avoid page break after the element with the class 'avoid-page-break' */
            .avoid-page-break {
                page-break-after: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="text-center print_div">
        <button class="btn btn-md avoid print" id="print"
            style="margin: 1em 0; float: right;color: #fff;
			background-color: #17a2b8; border-color: #17a2b8; z-index: 999;"
            type="button">
            <i class="fas fa-print"></i>&nbsp;Print
        </button>
    </div>
    <div class="container print_div" id="print-div1">
        <section id="admit-card">
            @foreach ($employeeList as $item)
                @php
                    $allowance = DB::table('employee_payroll_item_details')
                        ->leftJoin('salary_item', 'employee_payroll_item_details.item_id', 'salary_item.id')
                        ->where('employee_payroll_item_details.payroll_id', $item->id)
                        ->where('salary_item.type', 3)
                        ->select('employee_payroll_item_details.*', 'salary_item.name')
                        ->get();
                    
                    $deduction = DB::table('employee_payroll_item_details')
                        ->leftJoin('salary_item', 'employee_payroll_item_details.item_id', 'salary_item.id')
                        ->where('employee_payroll_item_details.payroll_id', $item->id)
                        ->where('salary_item.type', 2)
                        ->select('employee_payroll_item_details.*', 'salary_item.name', 'salary_item.type')
                        ->get();
                @endphp
                <div class="row" style="page-break-after: always;">
                    <div class="col-xs-6 out-border">
                        @php
                            $totalAllowanceforEmployee = 0;
                        @endphp
                        <div class="card">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="exam_admit_card_container">
                                        <img class="exam_admit_card"
                                            src="{{ asset(config('app.asset') . 'image/paySlip/payslip.png') }}"
                                            alt="Payslip">
                                    </div>
                                </div>
                            </div>
                            <div class="row payslip">
                                <div class="col-xs-2"></div>
                                <div class="col-xs-12">
                                    <div class="card-title">
                                        <h4 class="admit-title"><span>Pay Slip (Office)</span></h4>
                                        <h5 class="exam-name-year"><b> {{ $monthName }}</b>, <b>
                                                {{ $yearName }}</b>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-xs-12 student-info">
                                    <div class="col-xs-12">
                                        <p class="student-name"><b>Name: {{ $item->full_name }}<span> </span></b></p>
                                        <p class="student-name"><b>Designation: {{ $item->designation }}<span>
                                                </span></b></p>
                                        <p class="student-name"><b>Mobile: {{ $item->phone }}<span> </span></b></p>
                                        <p class="student-name"><b>Bank Acc: {{ $item->bank_acc }}<span> </span></b></p>
                                    </div>
                                </div><br>
                                <div class="col-xs-12 student-info">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td class="gross-salary">Gross Salary:</td>
                                                <td class="gross-salary">{{ $item->gross_salary }}</td>
                                                <td rowspan="70" class="gaps"></td>
                                            </tr>
                                            @foreach ($allowance as $allowanceItem)
                                                <tr>
                                                    <td>(+) {{ $allowanceItem->name }}:</td>
                                                    <td>{{ $allowanceItem->total_amount }}</td>
                                                </tr>
                                                @php
                                                    $totalAllowanceforEmployee += $allowanceItem->total_amount;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <td class="total-salary">Total Salary:</td>
                                                <td class="total-salary">
                                                    {{ $item->gross_salary + $totalAllowanceforEmployee }}</td>
                                            </tr>
                                            @foreach ($deduction as $deductionItem)
                                                <tr>
                                                    <td>(-) {{ $deductionItem->name }}</td>
                                                    <td>{{ $deductionItem->total_amount !== null ? $deductionItem->total_amount : 0 }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>Loan Deduct:</td>
                                                <td></td>
                                                <td>Loan Taka:</td>
                                            </tr>
                                            <tr>
                                                <td class="net-salary">Net Salary:</td>
                                                <td class="net-salary">{{ $item->total_salary }}</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" name="" id=""
                                        class="form-control text-center Remarks" value="Remarks"><br>
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="col-xs-6 mb-5">
                                    <textarea name="" id="" cols="30" rows="10" class="form-control employee-signature">Signature of Employee</textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-6 out-border">
                        @php
                            $totalAllowanceforOffice = 0;
                        @endphp
                        <div class="card">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="exam_admit_card_container">
                                        <img class="exam_admit_card"
                                            src="{{ asset(config('app.asset') . 'image/paySlip/payslip.png') }}"
                                            alt="Payslip">
                                    </div>
                                </div>
                            </div>
                            <div class="row payslip">
                                <div class="col-xs-2"></div>
                                <div class="col-xs-12">
                                    <div class="card-title">
                                        <h4 class="admit-title"><span>Pay Slip (Employee)</span></h4>
                                        <h5 class="exam-name-year"><b> {{ $monthName }}</b>, <b>
                                                {{ $yearName }}</b>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-xs-12 student-info">
                                    <div class="col-xs-12">
                                        <p class="student-name"><b>Name: {{ $item->full_name }}<span> </span></b></p>
                                        <p class="student-name"><b>Designation: {{ $item->designation }}<span>
                                                </span></b></p>
                                        <p class="student-name"><b>Mobile: {{ $item->phone }}<span> </span></b></p>
                                        <p class="student-name"><b>Bank Acc: {{ $item->bank_acc }}<span> </span></b>
                                        </p>
                                    </div>
                                </div><br>
                                <div class="col-xs-12 student-info">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td class="gross-salary">Gross Salary:</td>
                                                <td class="gross-salary">{{ $item->gross_salary }}</td>
                                                <td rowspan="70" class="gaps"></td>
                                            </tr>
                                            @foreach ($allowance as $allowanceItem)
                                                <tr>
                                                    <td>(+) {{ $allowanceItem->name }}:</td>
                                                    <td>{{ $allowanceItem->total_amount }}</td>
                                                </tr>
                                                @php
                                                    $totalAllowanceforOffice += $allowanceItem->total_amount;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <td class="total-salary">Total Salary:</td>
                                                <td class="total-salary">
                                                    {{ $item->gross_salary + $totalAllowanceforOffice }}</td>
                                            </tr>
                                            @foreach ($deduction as $deductionItem)
                                                <tr>
                                                    <td>(-) {{ $deductionItem->name }}</td>
                                                    <td>{{ $deductionItem->total_amount !== null ? $deductionItem->total_amount : 0 }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>Loan Deduct:</td>
                                                <td></td>
                                                <td>Loan Taka:</td>
                                            </tr>
                                            <tr>
                                                <td class="net-salary">Net Salary:</td>
                                                <td class="net-salary">{{ $item->total_salary }}</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" name="" id=""
                                        class="form-control text-center Remarks" value="Remarks"><br>
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="col-xs-6 mb-5">
                                    <textarea name="" id="" cols="30" rows="10" class="form-control employee-signature">Signature of Employee</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </section>
    </div>
    <script src="{{ asset(config('app.asset') . 'js/vendors.bundle.js') }}"></script>
    <script src="{{ asset(config('app.asset') . 'js/jquery.print.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset(config('app.asset') . 'css/jquery.print.min.css') }}"
        media="print">

    <script>
        $(document).ready(function() {
            $(".print_div").find("#print").on("click", function() {
                var dv_id = $(this).parents(".print_div").attr("id");
                $("#" + dv_id).print({
                    //Use Global styles
                    globalStyles: true,
                    //Add link with attrbute media=print
                    mediaPrint: false,
                    iframe: true,
                    //Don"t print this
                    noPrintSelector: ".avoid"
                });
            });
        });
    </script>


</body>

</html>
