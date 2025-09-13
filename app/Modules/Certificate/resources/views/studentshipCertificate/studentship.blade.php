<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} | {{ isset($pageTitle) ? $pageTitle : '' }}</title>
    <link rel="stylesheet" href="{{ asset(config('app.asset') . 'css/bootstrap3.4.1.min.css') }}">
    <link rel="shortcut icon" href="{{ URL::to(config('app.asset') . 'logo/favicon.ico') }}" />
    <style>
        /*Tangerine latin */
        @font-face {
            font-family: Certificate;
            src: url({{ asset(config('app.asset') . '/webfonts/latin-normal.woff2') }}) format('woff2');
        }

        /* Lobster latin */
        @font-face {
            font-family: 'Lobster';
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

        #testi-border {
            border-image: url("{{ asset(config('app.asset') . 'image/border/6th.png') }}") 60;
            border-image-width: 30px;
            border-color: #f4be52;
            border-style: inset;
            border-width: 15px;
            filter: contrast(100%);
        }

        .testi-border {
            overflow: hidden;
            min-height: 75em;
            position: relative;
        }

        .company-logo {
            margin-top: 20px;
            margin-bottom: 2em;
        }

        .company-logo>img {
            width: 92%;
            height: auto;
        }

        h1.testimonial-title {
            text-align: center;
            font-family: 'Lobster', cursive;
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
            padding: 10px;
        }

        /*Watermark Code for PNG*/
        /*.testimonial-content:before{
   position: absolute;
   content: url('images/watermark.png');
   top: 15%;
   left: 37%;
  }*/
        /*Watermark Code for SVG*/
        .testimonial-content:before {
            position: absolute;
            content: url("{{ asset(config('app.asset') . 'backend/images/watermark/' . $companyDetails->water_pressure_photo) }}");
            top: {{$companyDetails->water_pressure_margin_top}}%;
            left: {{$companyDetails->water_pressure_margin_left}}%;
            height: {{$companyDetails->water_pressure_height}}px;
            width:30%;
            opacity: .5;
            z-index: -1;
        }

        .testi-footer {
            position: absolute;
            width: 100%;
            bottom: 0em;
            bottom: 20px;
            padding: 10px;
        }

        /*student name field width*/
        .testimonial-content p strong.info-strong.student-name {
            display: -webkit-inline-box;
            width: 81%;
            text-align: -webkit-center;
        }

        /*student father name field width*/
        .testimonial-content p strong.info-strong.father-name {
            display: -webkit-inline-box;
            width: 95%;
            text-align: -webkit-center;
        }

        /*student mother name field width*/
        .testimonial-content p strong.info-strong.mother-name {
            display: -webkit-inline-box;
            width: 94%;
            text-align: -webkit-center;
        }

        /*dynamic address field width*/
        .testimonial-content span.address.input-bold {
            display: -webkit-inline-box;
            width: 95%;
            text-align: -webkit-center;
        }

        .testimonial-content p strong.info-strong {
            font-family: 'Certificate', cursive;
            font-size: 25px;
            letter-spacing: 1px;
            font-weight: 700;
            border-bottom: 1px dotted #555;
            padding: 0 10px;
        }

        .testimonial-content span.input-bold {
            font-size: 25px;
            font-family: Certificate;
            border-bottom: 1px dotted #555;
            padding: 0 15px;
        }

        .testimonial-content input.custom-input {
            border: none;
            border-bottom: 1px dotted #555;
            outline: none;
            padding-left: 10px;
            font-family: 'Certificate', cursive;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 1px;
            text-align: center;
            line-height: 30px;
            width: 20%;
            background: transparent;
        }

        .testimonial-content input.address.custom-input {
            width: 95%;
        }

        ul>li {
            list-style: none;
        }

        .testi-border {
            position: relative;
        }

        .testi-date {
            position: absolute;
            bottom: 20px;
        }

        .principal-sign-box {
            margin-right: 50px;
        }


        img.principal-sign {
            height: {{$companyDetails->principle_signature_height}}px;
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

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 16px !important;
                line-height: 1.7 !important;
            }

            .company-logo {
                margin-top: 20px;
                margin-bottom: 1em;
            }

            .company-logo>img {
                width: 90%;
                height: auto;
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

            .testimonial-content p strong.info-strong {
                font-family: 'Certificate', cursive;
                font-size: 25px;
                font-weight: 700;
                padding: 0 20px;
            }

            /*student name field width*/
            .testimonial-content p strong.info-strong.student-name {
                display: -webkit-inline-box;
                width: 60%;
            }

            /*student father name field width*/
            .testimonial-content p strong.info-strong.father-name {
                display: -webkit-inline-box;
                width: 90%;
                text-align: left;
            }

            /*student mother name field width*/
            .testimonial-content p strong.info-strong.mother-name {
                display: -webkit-inline-box;
                width: 90%;
                text-align: left;
            }

            /*dynamic address field width*/
            .testimonial-content span.address.input-bold {
                display: -webkit-inline-box;
                width: 87%;
            }

            /*manual address field width*/
            .testimonial-content input.address.custom-input {
                width: 90%;
            }

            .testimonial-content input.custom-input {
                font-family: 'Certificate', cursive;
                font-size: 25px;
                font-weight: 700;
                line-height: 0;
                padding-bottom: 0;
                width: 40%;
            }

            /*Watermark Code for PNG*/
            /*.testimonial-content:before{
    top: 25%;
    left: 28%;
   }*/
            /*Watermark Code for SVG*/
            .testimonial-content:before {
                top: 18%;
                left: 27%;
                width: 50%;
                height: 50%;
                opacity: .5;
            }

            .tc-footer {
                margin-top: 50px;
            }

            .testi-footer {
                position: absolute;
                width: 100%;
                bottom: 0em;
                bottom: 20px;
            }

            .testi-border {
                position: relative;
            }

            .testi-date {
                position: absolute;
                bottom: 20px;
            }

            ::-webkit-input-placeholder {
                color: transparent;
            }

            @page {
                size: a4 portrait;
                margin: 1cm 0 0 0;
            }

            footer {
                page-break-before: always;
            }


        }
    </style>
</head>

<body class="print_div" id="print-div1" data-new-gr-c-s-check-loaded="14.1084.0" data-gr-ext-installed="">
    <div class="text-center">
        <button class="btn btn-success btn-md avoid print" id="print"
            style="margin: 1em 0; float: right;color: #fff;
        background-color: #17a2b8; border-color: #17a2b8;"
            type="button">
            <i class="fa fa-print"></i>&nbsp;Print
        </button>
    </div>
    @foreach ($certificates as $certificate)
        <footer></footer>
        <div class="container">
            <div id="border-out">
                <div id="testi-border">
                    <div class="testi-border">
                        <!----- Institute Info Logo Starts ----->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="img-responsive company-logo text-center">
                                    <img src="{{ asset(config('app.asset') . 'image/studentshipCertificate/smallStudentship.svg') }}"
                                        alt="studentship-certificate-header">
                                </div>
                            </div>
                        </div>
                        <!----- Institute Info Logo Ends ----->
                        <div class="testimonial">
                            <!----- Testimonial Content Starts  ----->
                            <div class="col-xs-12">
                                <div class="testimonial-content">
                                    <!--<h1 class="testimonial-title"><span>Studentship Certificate</span></h1>-->
                                    <p>This is to certify that <strong
                                            class="info-strong student-name"><em>{{ $certificate->student_name }}</em></strong>
                                        is @if ($certificate->student_gender == 'male')
                                            son
                                        @else
                                            daughter
                                        @endif of <br>(Father) <strong
                                            class="info-strong father-name"><em>{{ $certificate->father_name }}
                                            </em></strong> (Mother) <strong
                                            class="info-strong mother-name"><em>{{ $certificate->mother_name }}</em></strong>

                                        <br>address <strong><span class="address input-bold">{{ $certificate->area }},
                                                P.S.: {{ $certificate->upazila_name }}, Dist.:
                                                {{ $certificate->district_name }}</span></strong><br>

                                        is a student of <strong>{{$companyDetails->company_name}}</strong>

                                        bearing Roll No <strong
                                            class="info-strong">{{ $certificate->class_roll }}</strong>
                                        Section <strong class="info-strong">{{ $certificate->section_name }}</strong>
                                        Class <strong class="info-strong">{{ $certificate->class_name }}</strong>
                                        and Session <strong class="info-strong">{{ $certificate->year }}</strong>.
                                    </p>
                                    <p>
                                        According to our register @if ($certificate->student_gender == 'male')
                                        his
                                    @else
                                        her
                                    @endif date of birth is <strong class="info-strong">
                                            {{ $certificate->date_of_birth }}.</strong> </p>
                                    <p>@if ($certificate->student_gender == 'male')
                                        He
                                    @else
                                        She
                                    @endif bears a good moral character during @if ($certificate->student_gender == 'male')
                                    his
                                @else
                                    her
                                @endif studentship. I wish @if ($certificate->student_gender == 'male')
                                his
                            @else
                                her
                            @endif success in
                                        life.</p>
                                    <!--<p> sat for the <input type="text" class='custom-input' placeholder="example 1st term exam 2018 and success / failed " /> to be promoted to next class.</p> -->

                                    <p> </p>

                                </div>
                            </div>
                            <!----- Testimonial Content Ends  ----->
                        </div>
                        {{-- Find Princple Certificate Details --}}
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
