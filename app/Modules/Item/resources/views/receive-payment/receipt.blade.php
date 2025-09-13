<!DOCTYPE html>
<html>

<head>
    <title>{{ isset($pageTitle) ? $pageTitle : '' }}</title>
    <link rel="shortcut icon" href="{{ URL::to('logo/favicon.ico') }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\DB;
    ?>

    <link href="{{ asset(config('app.asset') . 'css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
    <script src="{{ asset(config('app.asset') . 'js/bootstrap.min.js') }}"></script>
    <script src="{{ asset(config('app.asset') . 'js/jquery.min.js') }}"></script>

    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

        }
    </script>
    <style>
        /*p {
            margin-top: -15px;
        }*/

        /*        table {
            margin-top: -10px;
        }*/

        @media print {
            #printbtn {
                display: none;
            }
            .content p strong.info-strong {
            /* font-family: 'Tangerine', cursive; */
            font-size: {{ Session::get('invoicefontSize') }}px;
            letter-spacing: 1px;
            font-weight: 700;
            border-bottom: 1px dotted #555;
            padding: 0 10px;
        }
        .dash-border {
            border: 1px dashed #555;
            height: 38em;
            padding: 0 1em 0 1em ;
        }
        .darul>p{
            
        }
        }
        .table td,
        .table th,
        .table td {
            border: 1px solid black !important;
            border-collapse: collapse;
            padding: 1px !important;
            font-size: {{ Session::get('invoicefontSize') }}px;
        }
        .mydivider {
    border: none;
    border-bottom: 30px solid grey;
    border-image: linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
    -webkit-border-image: -webkit-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
    -moz-border-image: -moz-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
    border-image-slice: 1;
}
.moneydiv{
    color: white;background-color: #443f3f;width: 200px;height: 50px;border-radius: 5px;margin-top: -40px; text-align: center;justify-content: center;  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
  margin-left: auto;
  margin-right: auto;
  border-image: linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
    -webkit-border-image: -webkit-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
    -moz-border-image: -moz-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
}
.note{
    color: white;background-color: #443f3f;
    width: 400px;
    height: 40px;
    border-radius: 5px;
    margin-top: -40px; 
    text-align: center;
    justify-content: center; 
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-image: linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
    -webkit-border-image: -webkit-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
    -moz-border-image: -moz-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
}

.content p strong.info-strong {
/*            font-family: 'Tangerine', cursive;*/
/*            font-size: 25px;*/
            letter-spacing: 1px;
            font-weight: 700;
            border-bottom: 1px dotted #555;
            padding: 0 10px;
        }

.content p strong.info-strong.student-name {
                display: -webkit-inline-box;
                width: 80%;
            }
.content p strong.info-strong.class {
                display: -webkit-inline-box;
                width: 30%;
            }
.content p strong.info-strong.roll {
                display: -webkit-inline-box;
                width: 27%;
            }
.content p strong.info-strong.section {
                display: -webkit-inline-box;
                width: 30%;
            }

.content p strong.info-strong.amount {
                display: -webkit-inline-box;
                width: 17%;
            }
.content p strong.info-strong.word {
                display: -webkit-inline-box;
                width: 70.6%;
            }
.content p strong.info-strong.monthof {
                display: -webkit-inline-box;
                width: 91.5%;
            }
        .dash-border {
            border: 1px dashed #555;
            height: 35em;
            padding: 0 1em 0 1em ;
        }
        .signature-details {
            overflow: hidden;
            margin-top: 0em;
        }

        .principal-sign>p {
            border-top: 1px solid #555;
            font-size: 12px;
            margin: 0;
        }

    </style>

</head>
<button style=" float: right;" class="btn btn-info" onclick="printDiv('printMe')" id="printbtn">Print</button>
<div id='printMe'>

    <!------ Include the above in your HEAD tag ---------->

    <div class="container dash-border">
        <div class="row " style="margin-top: 10px;">
            <div class="col-md-1">
                <img height="100"  src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}" class="example-p-5">
            </div>
            <div class="col-md-7 darul" style="padding-left: 50px;">
                <h3>DARUL AZHAR MODEL MADRASAH</h3>
                <p><h2>দারুল আজহার মডেল মাদরাসা</h2></p>
            </div>
            <div class="col-md-4" style="border-left: 5px solid grey;">
                <strong><h6>Main Campus, Uttara</h6></strong>
                <hr>
               <h6>House:17,Road:20,Sector:4, Uttara,Dhaka-1230</h6>
                <h6>Cell: +88018233</h6>
            </div>

            <?php $merchants = Session::get('sales'); ?>
            <div class="col-md-12">
            <br>
            <div class="mydivider"></div>
            <div class="moneydiv">
                <center><h6>MONEY RECEIPT</h6></center>
            </div>
          
            </div>
            <div class="col-md-6">
                <p style="float: left;">{{$saleData->sales_invoice_no}}</p>
            </div>
            <div class="col-md-6">
                <p style="float: right;">Date: {{ date('d-m-Y') }}</p>
            </div>
            @php 
            $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            @endphp
            <div class="col-md-12 content" >
                <p>Received with thanks from <strong class="info-strong student-name" style="">{{$saleData->full_name}}</strong></p>
                <p>Phone<strong class="info-strong amount" style="">{{$saleData->cp_phone_no}}</strong> Address: <strong class="info-strong word" style="">{{$saleData->address}}</strong></p>
                <p>TK<strong class="info-strong amount" style="">{{$saleData->paid_amount}}</strong> In Words TK: <strong class="info-strong word" style=""> {{ ucwords($digit->format($saleData->paid_amount)) }} Only</strong></p>
                <p>Month Of <strong class="info-strong monthof" style="">
                    @foreach ($month as $key => $data)
                        @if( count( $month ) != $key + 1 )
                        {{ $data->month_name }}-{{$data->year}},
                        @else
                            {{ $data->month_name }}-{{$data->year}}
                        @endif
                    @endforeach  
                </strong></p>
                @foreach ($item as $data)
                    <td>&#x2611; {{$data->product_name}}</td>
                @endforeach                
            </div>

            <div class="col-lg-12">
                <div class="signature-details float-right">       
                    <div class="principal-sign text-center">
                        {{-- <img src="{{ asset(config('app.asset') . 'image/principalSignature/principal-signature.svg') }}" class="principal-sign" alt="signature"> --}}
                        <br><br>
                        <p><b>For: DARUL AZHAR MODEL MADRASHA</b></p>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="mydivider">
            <div class="note">
                <center><h6>Note: This money receipt is valid subject to realization</h6></center>
            </div>
            
        </div>
    </div>
    

</div>


</body>
@include('Admin::layouts.js')

</html>
