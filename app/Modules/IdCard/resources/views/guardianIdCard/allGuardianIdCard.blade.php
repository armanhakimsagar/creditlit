<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Guardian ID Card :: Darul Azhar </title>
    <link rel="stylesheet" href="https://ems.darulazharbd.com/css/bootstrap.css">
    <link rel="stylesheet" href="https://ems.darulazharbd.com/includes/font-awesome/css/font-awesome.min.css">
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
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            line-height: 1.22857143;
        }

        .s-info p:last-child {
            width: 100%;
        }

        .s-info p {
            margin-bottom: 2px;
        }

        .custom-grid {
            float: left;
            padding-right: 16.2px;
            padding-left: 16.2px;
        }

        .student-id-card-wrapper {
            position: relative;
            overflow: hidden;
            width: 201.6px;
            height: 220px;
        }

        h3.student-name {
            color: #0b72ba;
            font-size: 14px;
            margin: 5px 0;
            height: 27px;
            text-transform: capitalize;
            text-align: center;
        }

        .student-info {
            overflow: hidden;
        }

        img.student-photo {
            height: 70px;
            width: 70px;
            border-radius: 5px;
            border: 2px solid #0b72ba;
        }

        .left-div {
            width: 35%;
            float: left;
        }

        .right-div {
            float: left;
            width: 65%;
            margin-top: 10px;
        }

        .right-div img {
            width: auto;
            height: 30px;
        }

        @media print {
            h3 {
                font-size: 20px;
            }

            h3 .small {
                font-size: 65%;
                color: #777;
            }

            h3.student-name {
                color: #0b72ba;
            }

            .student-info p {
                font-size: 12px;
            }

            footer {
                page-break-after: always;
            }

            @page {
                size: a4 portrait;
                margin: 15pt;
            }
        }
    </style>
</head>

<body data-new-gr-c-s-check-loaded="14.1094.0" data-gr-ext-installed="" data-new-gr-c-s-loaded="14.1094.0">

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
                    <!----- student ID Card Item Starts ----->
                    <div class="col-xs-4 custom-grid">
                        <div class="student-id-card-wrapper">
                            <!----- student Info Starts ----->
                            <div class="student-info">
                                <div class="s-info">
                                    <p><b>Guardian <span style="padding-left: 0px;"></span>: </b> {{$item->guardian_name}}</p>
                                    <p><b>SID <span style="padding-left: 32px;"></span>: </b>{{$item->student_id}} </p>
                                    <h3 class="student-name">{{$item->student_name}}</h3>
                                    <div class="">
                                        <div class="left-div text-center">
                                            @if ($item->photo != null)
                                                <img src="{{ asset(config('app.asset') . 'backend/images/students/' . $item->photo) }}" class="student-photo">
                                            @else
                                                <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}" class="student-photo">
                                            @endif
                                        </div>
                                        <div class="right-div text-center">
                                            <img src="https://ems.darulazharbd.com/images/principal-signature.svg"
                                                alt="principal-signature">
                                            <p>Principal</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!----- student ID Card Item Ends ----->
                @endforeach
            </div>
            <br>
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
