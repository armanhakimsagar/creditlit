<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admit card print:: Darul Azhar </title>
    <link rel="stylesheet" href="{{ asset(config('app.asset') . 'css/bootstrap3.4.1.min.css') }}">
    <link rel="shortcut icon" href="{{ URL::to(config('app.asset') . 'logo/favicon.ico') }}" />

    <style>
       /* @font-face {
            font-family: 'Bree Serif';
            font-style: normal;
            font-weight: 400;
            src: local('Bree Serif Regular'), local('BreeSerif-Regular'), url('fonts/BreeSerif/BreeSerif-Regular.woff2') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }*/

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

        .card:before {
            position: absolute;
            content: url("{{ asset(config('app.asset') . 'backend/images/watermark/' . $companyDetails->water_pressure_photo) }}");
            top: 45%;
            left: 37%;
            width: 25%;
            height: 25%;
            opacity: .75;
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

            @page {
                size: a4 landscape;
                margin: 0mm 0mm 0mm 0mm !important;
            }

            @if ($companyDetails->rollEnableOnAdmitCard == 1)
                .card {
                    border: 1px solid #555;
                    margin: 2mm;
                    /* padding: 2px 6px; */
                    position: relative;

                }
            @else
                .card {
                    border: 1px solid #555;
                    margin: 2.7mm;
                    /* padding: 2px 6px; */
                    position: relative;

                }
            @endif


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
            <div class="row">
                @php
                    $count = 0;
                @endphp
                @foreach ($studentList as $student)
                    @php
                        $count++;
                        $footer = '';
                        if ($count > 3) {
                            $footer = 'pagebreak';
                            $count = 0;
                        }
                    @endphp
                    <div class="col-xs-6 out-border">
                        <div class="card">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="exam_admit_card_container">
                                        <img class="exam_admit_card"
                                            src="{{ asset(config('app.asset') . 'image/admitCard/exam-admit-card.svg') }}"
                                            alt="exam-admit-card">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"></div>
                                <div class="col-xs-7">
                                    <div class="card-title">
                                        <h4 class="admit-title"><span>@lang('Certificate::label.ADMIT_CARD')</span></h4>
                                        <h5 class="exam-name-year"><b>Exam:</b> {{ $exam_list->exam_name }} | <b>Year
                                                :</b>{{ $student->year }} </h5>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    @if ($student->photo != null)
                                        <img src="{{ asset(config('app.asset') . 'backend/images/students/' . $student->photo) }}"
                                            name="student_picture" id="upload-img">
                                    @else
                                        <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}"
                                            name="student_picture" id="upload-img">
                                    @endif
                                </div>
                                <div class="col-xs-12 student-info">
                                    <div class="col-xs-8">
                                        <p class="student-name"><b>Name: <span>{{ $student->student_name }} </span></b>
                                        </p>
                                    </div>
                                    <div class="col-xs-4">
                                        <p style="text-align: right;">SID: {{ $student->student_id }}
                                        </p>
                                    </div>
                                </div><br>
                                <div class="col-xs-12 student-info">
                                    <div class="col-xs-8">
                                        <p>Class: {{ $student->class_name }}</p>
                                    </div>
                                    @if ($companyDetails->rollEnableOnAdmitCard == 1)
                                        <div class="col-xs-8">
                                            <p>Class Roll: {{ $student->class_roll }}</p>
                                        </div>
                                    @endif
                                    <div class="col-xs-4">
                                        <p style="text-align: right;">
                                            @if ($student->shift_name != '')
                                                {{ $student->shift_name }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-xs-8">
                                        <b>
                                            <p>Section: {{ $student->section_name }}</p>
                                        </b>
                                    </div>
                                    {{-- <div class="col-xs-8">
                                        <p>Roll: {{ $student->class_roll }}</p>
                                    </div> --}}
                                </div>
                                <div class="col-xs-12">
                                    <div class="signature-details" style="margin-top: -10px;">
                                        <div class="col-xs-6">
                                            <br><br><br>
                                            <p style="text-decoration: overline;"><b>Class
                                                    Teacher</b></p>
                                        </div>
                                        <div class="col-xs-6">
                                            @php
                                                $madrasah = DB::table('companies')->first();
                                            @endphp
                                            @if ($madrasah->principal_details_in_certificate != null)
                                                <div class="testi-footer">
                                                    <div class="col-xs-12">
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
                        </div>
                    </div>
                    <footer class="{{ $footer }}"></footer>
                @endforeach

            </div>
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
