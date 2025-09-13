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

    {{-- <link href="{{ asset(config('app.asset') . 'css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css"> --}}
    {{-- <script src="{{ asset(config('app.asset') . 'js/bootstrap.min.js') }}"></script>
    <script src="{{ asset(config('app.asset') . 'js/jquery.min.js') }}"></script> --}}

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
            font-family: 'Tangerine', cursive;
            font-size: 25px;
            letter-spacing: 1px;
            font-weight: 700;
            border-bottom: 1px dotted #555;
            padding: 0 10px;
        }
        .dash-border {
            border: 1px dashed #555;
            height: 50em;
            padding: 0 1em 0 1em ;
        }
        }

        .header {
            padding: 10px 20px;
            color: #fff;
            background-color: #75a0cb;
            margin: 0;
        }
    </style>

</head>
    <div class="modal-body">
        <div id='printMe'>

            <!------ Include the above in your HEAD tag ---------->

            <div class="container dash-border">
                <div class="row " style="margin-top: 10px;">
                    <div class="col-md-1">
                        <img height="100"  src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}" class="example-p-5">
                    </div>
                    <div class="col-md-7" style="padding-left: 50px;">
                        <h3>{{ $companyDetails->company_name }}</h3>
                        <h1>{{ $companyDetails->company_name_bn }}</h1>
                    </div>
                    <div class="col-md-4" style="border-left: 5px solid grey;">
                        <strong><h6>Main Campus, Uttara</h6></strong>
                        <hr>
                       <h6>{{ $companyDetails->address }}</h6>
                        <h6>Cell: {{ $companyDetails->phone }}</h6>
                    </div>
                    <div class="col-md-12">
                    <br>
                    <div class="mydivider"></div>

                    </div>
                    <div class="col-md-12 content text-center  mt-3" >
                        <h3 class="header">Student Details</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-stripped ">
                            <tbody>
                                    <tr>
                                        <td>Student Name:</td>
                                        <td>{{ $data->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Class Name :</td>
                                        <td>{{ $data->class_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Roll Number :</td>
                                        <td>{{ $data->class_roll }}</td>
                                    </tr>
                                    <tr>
                                        <td>Section :</td>
                                        <td>{{ $data->section_name }}</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 content mt-3 text-center" >
                        <h3 class="header">Payment Details</h3>
                    </div>
                    <div class="card-body">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="frame-wrap">
                                <div class="table-responsive">
                                    <table  class="table table-bordered table-striped w-100" id="dt-basic-example">
                                        <thead>
                                            <tr>
                                                <th width="5%"> SL</th>
                                                <th width="10%"> Item Name</th>
                                                <th width="10%"> Item Price</th>
                                                <th width="10%">Month</th>
                                                <th width="10%"> Year</th>
                                                <th width="10%"> Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @foreach ($service as $key => $row)
                                                <tr>
                                                    <td> {{  $key + 1 }} </td>
                                                    <td>{{$row->product_name}}</td>
                                                    <td>{{$row->price}}</td>
                                                    <td>{{$row->month_name}}</td>
                                                    <td>{{$row->year}}</td>
                                                    <td>{{$row->note}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        {{-- <button style=" float: right;" class="btn btn-info" onclick="printDiv('printMe')" id="printbtn">Print</button> --}}
    </div>
{{-- </form> --}}
