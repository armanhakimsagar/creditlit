<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student ID Card :: Darul Azhar </title>
    <link rel="stylesheet" href="https://ems.darulazharbd.com/css/bootstrap.css">
    <link rel="stylesheet" href="https://ems.darulazharbd.com/includes/font-awesome/css/font-awesome.min.css">
    <style>
        /*Google Fonts*/
        /* Montserrat Fonts */
        /* Font-Weight: 300 */
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 300;
            font-display: swap;
            src: local('Montserrat Light'), local('Montserrat-Light'), url('fonts/Montserrat/Montserrat-Light-300.woff2') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        /* Font-Weight: 400 */
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: local('Montserrat Regular'), local('Montserrat-Regular'), url('fonts/Montserrat/Montserrat-Regular-400.woff2') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        /* Font-Weight: 600 */
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 600;
            font-display: swap;
            src: local('Montserrat SemiBold'), local('Montserrat-SemiBold'), url('fonts/Montserrat/Montserrat-SemiBold-600.woff2') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        /* Font-Weight: 700 */
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: local('Montserrat Bold'), local('Montserrat-Bold'), url('fonts/Montserrat/Montserrat-Bold-700.woff2') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif !important;
        }

        body {
            font-size: 12px;
            font-family: 'Montserrat', sans-serif !important;
            line-height: 1.22857143;
        }

        .s-info p:last-child {
            width: 100%;
        }

        .s-info p {
            margin-bottom: 2px;
        }

        .s-info p>span {
            font-weight: 600;
            display: inline-block;
            min-width: 70px;
        }

        .p-sign img {
            width: 100%;
            height: 30px;
        }

        .custom-grid {
            float: left;
            padding-right: 16.2px;
            padding-left: 16.2px;
        }

        .student-id-card-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0px 1px 10px rgba(0, 0, 0, 0.15);
            width: 201.6px;
            height: 321.6px;
        }

        .top-background {
            position: absolute;
            z-index: -2;
            overflow: hidden;
            background: #0b72ba;
            border-bottom: 5px solid #0b72ba;
            width: 126%;
            height: 25%;
            transform: rotate(-5deg);
            top: -20px;
            left: -10px;
        }

        h3.institute-name {
            color: #fff;
            margin: 10px 0 5px;
            font-size: 13px;
            font-weight: 600;
            height: 30px;
            text-transform: uppercase;
        }

        .institute-info img {
            border: 3px solid #fff;
            border-radius: 10px;
            background: #fff;
            width: 80px;
            height: 80px;
        }

        h3.student-name {
            color: #0b72ba;
            font-size: 13px;
            font-weight: 600;
            margin: 10px 0;
            height: 30px;
            text-transform: capitalize;
        }

        .guardian-info {
            overflow: hidden;
        }

        .p-sign {
            position: absolute;
            bottom: 0px;
            width: 100%;
        }

        .p-sign>img {
            border-bottom: 1px solid #999;
            width: 60%;
        }

        .card-footer {
            background: #eb2227;
            height: 10px;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        @media print {
            h3 {
                font-size: 20px;
            }

            h3 .small {
                font-size: 65%;
                color: #777;
            }

            .student-id-card-wrapper {
                border-radius: 10px;
                box-shadow: 0px 1px 10px rgba(0, 0, 0, 0.15) !important;
            }

            .guardian-info p {
                font-size: 12px;
            }

            guardian-info .p-sign {
                margin-top: 0px;
            }

            footer {
                page-break-after: always;
            }

            @page {
                size: a4 landscape;
                margin: 15pt;
            }
        }
    </style>
</head>

<body data-new-gr-c-s-check-loaded="14.1094.0" data-gr-ext-installed="">

    <div class="container print_div" id="print-div1">
        <div class="text-center">
            <button class="btn btn-info btn-md avoid print" id="print" style="margin: 1em 0;" type="button">
                <i class="fa fa-print"></i>&nbsp;Print
            </button>
        </div>
        <section id="guardian-id-card">
            <br>
            <div class="row">
                @foreach ($data as $item)
                    <!----- STUDENT ID Card Item Starts ----->
                    <div class="custom-grid">
                        <div class="student-id-card-wrapper">
                            <div class="top-background"></div>
                            <div class="institute-info-wrapper">
                                <div class="institute-info">
                                    <div class="text-center">
                                        <h3 class="institute-name">{{$companyDetails->company_name}}</h3>
                                        @if ($item->photo != null)
                                        <img src="{{ asset(config('app.asset') . 'backend/images/students/'.$item->photo) }}">
                                        @else 
                                        <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}">
                                        @endif
                                        <h3 class="student-name">{{$item->student_name}}</h3>
                                    </div>
                                </div>
                            </div>
                            <!----- STUDENT Info Starts ----->
                            <div class="guardian-info">
                                <div class="col-xs-12 s-info">
                                    <p><span>SID No</span>: {{$item->student_id}}</p>
                                    <p><span>Roll</span>: {{$item->class_roll}}</p>
                                    <p><span>Class</span>: {{$item->class_name}}</p>
                                    <p><span>Section</span>: {{$item->section_name}}</p>
                                    <p><span>Contact No</span>: {{$item->contact_no}}</p>
                                </div>
                            </div>
                            <!----- STUDENT Info Ends ----->
                            <div class="text-center p-sign mt-5">
                                <div class="col-xs-12">
                                    <img src="https://ems.darulazharbd.com/images/principal-signature.svg"
                                        alt="principal signature">
                                    <p>Principal</p>
                                    <!----- Head Master for up-to class ten, Authorized for HSC----->
                                </div>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                    <!----- STUDENT ID Card Item Ends ----->
                @endforeach
            </div>
        </section>

    </div>

    <script src="https://ems.darulazharbd.com/js/jquery.min.js"></script>
    <script src="https://ems.darulazharbd.com/includes/jQuery.print.js"></script>
    <link rel="stylesheet" type="text/css" href="https://ems.darulazharbd.com/css/print.css" media="print">
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
