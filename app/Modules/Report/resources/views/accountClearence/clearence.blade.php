<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} | {{ isset($pageTitle) ? $pageTitle : '' }}</title>
    <!-- Latest compiled and minified CSS -->
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

        .dash-border {
            border: 1px dashed #555;
            min-height: 12em;
            padding: 2em 0 0 2em;
            margin: 10px;
        }

        .dash-border h4 {
            text-align: center;
        }

        .dash-border h4>span {
            border: 1px solid #555;
            border-radius: 20px;
            padding: 3px 10px;
        }

        .header-section {
            text-align: center;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .header-section h3 {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        p {
            font-size: 16px;
        }

        .bottom-section {
            margin-top: 40px;
            position: relative;
        }

        .bottom-section .class-teacher {
            position: absolute;
            bottom: 2px;
            left: 20px;
            border-top: 2px solid #000;
        }

        .bottom-section .principle {
            position: absolute;
            bottom: 2px;
            right: 50px;
            border-top: 2px solid #000;
        }

        .col-xs-6 {
            padding-right: 0px;
            padding-left: 0px;
        }

        .principal-sign-box {
            margin-right: 35px;
            font-size: 10px;

        }

        img.principal-sign {
            height: 22px;
            width: auto;
        }

        img {
            margin-top: -65px;

        }

        .principal-sign>p {
            margin: 0;
        }

        .principal-sign>p {
            border-top: 1px solid #555;
            font-size: 12px;
        }

        @media print {
            .dash-border {
                border: 1px dashed #555;
                min-height: 220px;
                padding: 1em 0 0 1em;
            }

            .header-section {
                margin-top: 10px;
            }

            .header-section h3 {
                margin-top: 5px;
                margin-bottom: 5px;
            }


            .dash-border p {
                margin-bottom: 10px;
            }

            @page {
                size: a4 portrait;

            }

            h5 {
                font-size: 12px;
                margin-top: 0px;
                margin-bottom: 0px;
            }

            h3 {
                font-size: 18px;
            }

            h3 .small {
                font-size: 65%;
                color: #777;
            }

            .pagebreak {
                page-break-after: always;
            }

            body {
                font-size: 12px;
                line-height: 1.12857143;
            }

            .table>thead>tr>th,
            .table>tbody>tr>th,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>tbody>tr>td,
            .table>tfoot>tr>td {
                padding: 6px;
                line-height: 1.028571;
                font-size: 10px;
            }

            h2 {
                font-size: 22px;
            }

            .row {
                margin-right: -5px;
                margin-left: 0px;
            }

            .col-xs-6 {
                padding-right: 0px;
                padding-left: 0px;
            }
        }
    </style>
</head>

<body data-new-gr-c-s-check-loaded="14.1084.0" data-gr-ext-installed="">
    <div class="text-center print_div">
        <button class="btn btn-info btn-md avoid print" id="print"
            style="margin: 1em 0; float: right;color: #fff;
				background-color: #17a2b8; border-color: #17a2b8;"
            type="button">
            <i class="fas fa-print"></i>&nbsp;Print
        </button>
    </div>
    <div class="container" id="print-div1">
        <div class="col-xs-12">

        </div>

        <div class="row">
            @php
                $count = 0;
            @endphp
            @foreach ($studentList as $key => $student)
                @php
                    $count++;
                    $footer = '';
                    $page_style = '';
                    
                    if ($count > 7) {
                        $footer = 'pagebreak';
                        $count = 0;
                    }
                @endphp
                <div class="col-xs-6">
                    <div class="dash-border">
                        <div class="header-section">
                            <h3>{{ $companyDetails->company_name_bn }}</h3>
                            <h4 id="companyAddress{{ $key }}" class="companyAddress">
                                {{ $companyDetails->address }}</h4>
                            <h3>একাউন্ট ক্লিয়ারেন্স</h3>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <p>নাম : {{ $student->student_name }} </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <p>শ্রেনি: {{ $student->class_name }}</p>
                            </div>
                        </div>

                        <div class="row bottom-section">
                            <div class="col-xs-6">
                                <label class="class-teacher">ক্লাস টিচার</label>
                            </div>

                            <div class="col-xs-6">
                                <div class="principal-sign-box pull-right text-center">
                                    <img src="{{ asset(config('app.asset') . 'image/principalSignature/principal-signature.svg') }}"
                                        class="principal-sign" alt="principal-signature">
                                </div>
                                <label class="principle">অধ্যক্ষ</label>

                            </div>
                        </div>
                    </div>
                </div>

                <footer class="{{ $footer }}"></footer>
            @endforeach
        </div>
</body>
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

    function translateToBengali(text, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=bn&dt=t&q=' +
            encodeURI(text));
        xhr.onload = function() {
            if (xhr.status === 200) {
                var result = JSON.parse(xhr.responseText)[0][0][0];
                callback(result);
            } else {
                callback('Translation failed');
            }
        };
        xhr.onerror = function() {
            callback('Translation failed');
        };
        xhr.send();
    }

    var companyAddresses = document.getElementsByClassName('companyAddress');

    for (var i = 0; i < companyAddresses.length; i++) {
        (function(index) {
            var companyAddress = companyAddresses[index].textContent;

            translateToBengali(companyAddress, function(bengaliText) {
                companyAddresses[index].textContent = bengaliText;
            });
        })(i);
    }

</script>
