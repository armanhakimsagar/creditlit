<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} | {{ isset($pageTitle) ? $pageTitle : '' }}</title>
    <link rel="stylesheet" href="{{ asset(config('app.asset') . 'css/bootstrap3.4.1.min.css') }}">
    <link rel="stylesheet" href="{{ asset(config('app.asset') . 'css/fa-solid.css') }}">
    <link rel="stylesheet" href="{{ asset(config('app.asset') . 'css/fa-brands.css') }}">
    <link rel="shortcut icon" href="{{ URL::to(config('app.asset') . 'logo/favicon.ico') }}" />
    <style>
        /*Tangerine latin */
        @font-face {
            font-family: 'Tangerine
font-style: normal;
            font-weight: 400;
            src: local('Tangerine Regular'), local('Tangerine-Regular'), url('fonts/Tangerine/latin-normal.woff2') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        /* Lobster latin */
        @font-face {
            font-family: 'Lobster
font-style: normal;
            font-weight: 400;
            src: local('Lobster Regular'), local('Lobster-Regular'), url('fonts/Lobster/latin-normal.woff2') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        /**
       * @import url(http://fonts.googleapis.com/css?family=Bree+Serif);
       */
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
            text-align: justify;
            line-height: 1.7;
        }

        #border-out {
            border: 2px solid #555;
            padding: 0;
        }

        #testi-border {
            border: 12px solid transparent;
            border-image: url({{ asset(config('app.asset') . 'image/border/flower.png') }}) 15% round;
            padding: 0px;
        }

        .testi-border {
            border: 2px solid #555;
            overflow: hidden;
            min-height: 75em;
            position: relative;
        }

        .company-logo {
            margin-top: 20px;
            margin-bottom: 3em;
        }

        h1.testimonial-title {
            text-align: center;
            font-family: 'Lobster', cursive;
            ;
            font-size: 3em;
            margin: 30px 0 25px;
        }

        h1.testimonial-title span {
            border: 1px solid #555;
            padding: 2px 20px;
            border-radius: 30px;
        }

        .testimonial {
            position: relative;
        }

        .testimonial-content {
            position: relative;
        }

        .table>tbody>tr>td {
            border-top: 1px solid #ffffff;
        }

        .marksheet-details-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-auto-flow: column;
            padding: 0 !importantpx;

        }

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 4px !important;
            line-height: 1.1 !important;
        }

        .table2>tbody>tr>td {
            border: 1px solid #000000 !important;
        }

        .table2>thead>tr>th {
            border: 1px solid #000000 !important;
        }

        .table {
            margin-bottom: 0px !important;
        }

        .student-result-sheet {
            padding: 0px !important
        }

        .student-result-sheet {
            padding: 0px !important;
        }



        .h5 {
            margin-bottom: 0px;
        }

        /*Watermark Code for SVG*/
        .testimonial-content:before {
            position: absolute;
            content: url("{{ asset(config('app.asset') . 'backend/images/watermark/' . $companyDetails->water_pressure_photo) }}");
            top: 15%;
            left: 37%;
            width: 30%;
            height: 30%;
            opacity: .5;
            z-index: 99;
        }

        .testi-footer {
            position: absolute;
            width: 100%;
            bottom: 0em;
        }

        ul>li {
            list-style: none;
        }

        img.principal-sign {
            height: 35px;
            width: auto;
        }

        .testi-date>p,
        .principal-sign>p {
            margin: 0;
        }

        .principal-sign>p {
            border-top: 1px solid #555;
            font-size: 12px;
        }

        .company-logo img {
            width: 100%;
        }

        @media print {
            body {
                font-size: 16px !important;
                line-height: 1.7 !important;
            }

            .testimonial-content:before {
                position: absolute;
                content: url("{{ asset(config('app.asset') . 'backend/images/watermark/' . $companyDetails->water_pressure_photo) }}");
                top: 15%;
                left: 37%;
                width: 30%;
                height: 30%;
                opacity: .5;
                z-index: 99;
            }

            .company-logo>img {
                width: 90%;
                height: auto;
            }

            .table2>tbody>tr>td {
                border: 1px solid #000000 !important;
            }

            .table2>thead>tr>th {
                border: 1px solid #000000 !important;
            }

            .table-borderles>tbody>tr>td {
                border: none !important;
            }

            .table-borderles>thead>tr>th {
                border: none !important;
            }

            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                padding: 5px !important;
                line-height: 1.154 !important;
                font-size: 15px !important;
            }

            .testi-border {
                min-height: 63em;
                position: relative;
            }

            .testimonial {
                margin-top: 15px;
            }

            h1.testimonial-title {
                font-size: 2em;
                margin: 20px 0 50px;
            }

            .tc-footer {
                margin-top: 50px;
            }

            .testi-footer {
                position: absolute;
                width: 100%;
                bottom: 0em;
            }

            ::-webkit-input-placeholder {
                color: transparent;
            }

            @page {
                size: a4 portrait;
            }

            footer {
                page-break-after: always;
            }
        }
    </style>
</head>

<body class="print_div" id="print-div1" data-new-gr-c-s-check-loaded="14.1084.0" data-gr-ext-installed="">
    <div class="text-center">
        <button class="btn btn-info btn-md avoid print" id="print"
            style="margin: 1em 0; float: right;color: #fff;
        background-color: #17a2b8; border-color: #17a2b8;"
            type="button">
            <i class="fas fa-print"></i>&nbsp;Print
        </button>
    </div>
    @foreach ($data as $item)
        <div class="container">
            <div id="border-out">
                <div id="testi-border">
                    <div class="testi-border">
                        <!----- Institute Info Logo Starts ----->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="img-responsive company-logo text-center">
                                    <img src="{{ asset(config('app.asset') . 'image/transferCertificate/transfer-certificate-header.svg') }}"
                                        alt="transfer-certificate-header">
                                </div>
                            </div>
                        </div>
                        <!----- Institute Info Logo Ends ----->
                        <div class="testimonial">
                            <!----- Testimonial Content Starts  ----->
                            <div class="col-xs-12">
                                <div class="testimonial-content">
                                    <div class="row clearfix" id="studentMarksheet">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <p class="h3" align="center">{{ $examName->exam_name }} Result :
                                                {{ $yearName->year }}</p>
                                            <div class="marksheet-details-container">
                                                <div class="marksheet-details-item">
                                                    <table width="100%" class="table table-borderles">
                                                        <tbody>
                                                            <tr>
                                                                <td>Roll No :</td>
                                                                <td id="classRoll">{{ $item->class_roll }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td>Class :</td>
                                                                <td id="className">{{ $item->class_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Section :</td>
                                                                <td id="sectionName">{{ $item->section_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Shift :</td>
                                                                <td id="shiftName">{{ $item->shift_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Gender :</td>
                                                                <td id="genderName" class="black12bold" colspan="3">
                                                                    {{ $item->gender }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="marksheet-details-item">
                                                    <table width="100%" class="table table-borderles">
                                                        <tbody>
                                                            <tr>
                                                                <td>Name :</td>
                                                                <td id="studentName">
                                                                    {{ $item->student_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Student ID :</td>
                                                                <td id="studentContactId">
                                                                    {{ $item->student_id }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td> Father's Name :</td>
                                                                <td id="fatherName">{{ $item->father_name }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td> Mother's Name :</td>
                                                                <td id="motherName">{{ $item->mother_name }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td> Date Of Birth :</td>
                                                                <td id="dateOfBirth">{{ $item->date_of_birth }} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @php
                                                $total = 0;
                                            @endphp
                                            <div class="student-result-sheet">
                                                <p class="h5" align="center">Grade Sheet</p>
                                                <table class="table table2" style="table-layout: fixed;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%"> Code</th>
                                                            <th style="width: 30%"> Subject Name</th>
                                                            @foreach ($attributeList as $key => $attribute)
                                                                <th>{{ $attribute->attribute_name }}
                                                                </th>
                                                            @endforeach
                                                            <th>Total</th>
                                                            <th>Grade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="studentResult">
                                                        @foreach ($subArr as $subId => $subInfo)
                                                            <tr>
                                                                <td>{{ $subInfo['sub_code'] }}</td>
                                                                <td>{{ $subInfo['sub_name'] }}</td>
                                                                @foreach ($attributeList as $attributeInfo)
                                                                    <td>{!! !empty($isAbsent[$item->id][$subId][$attributeInfo->id])
                                                                        ? '<span style="color: red">Absent</span>'
                                                                        : (isset($markArr[$item->id][$subId][$attributeInfo->id]) ? $markArr[$item->id][$subId][$attributeInfo->id] : '') !!}</td>
                                                                @endforeach
                                                                {{-- <td>{!! !empty($markArr[$item->id][$subId]['percentage']) ? $markArr[$item->id][$subId]['percentage'] : '' !!}</td> --}}
                                                                <td>{!! !empty($markArr[$item->id][$subId]['total']) ? $markArr[$item->id][$subId]['total'] : '' !!}</td>
                                                                <td>{!! !empty($markArr[$item->id][$subId]['grade']) ? $markArr[$item->id][$subId]['grade'] : '' !!}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="print-btn">

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!----- Testimonial Content Ends  ----->
                        </div>
                        @php
                            $madrasah = DB::table('companies')->first();
                        @endphp
                        @if ($madrasah->principal_details_in_certificate != null)
                            <div class="testi-footer">
                                <div class="col-xs-12">
                                    <div class="testi-date pull-left">
                                        <p>Date: {{ date('d-m-Y') }}</p>
                                    </div>
                                    <div class="principal-sign-box pull-right text-center">
                                        @if ($companyDetails->principle_signature != null)
                                            <img src="{{ asset(config('app.asset') . 'image/principalSignature/' . $companyDetails->principle_signature) }}"
                                                class="principal-sign" alt="principal-signature">
                                        @else
                                            <img src="{{ asset(config('app.asset') . 'image/principalSignature/principal-signature.svg') }}"
                                                class="principal-sign" alt="principal-signature">
                                        @endif
                                        {!! $madrasah->principal_details_in_certificate !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <footer></footer>
    @endforeach
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
<grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>

</html>
