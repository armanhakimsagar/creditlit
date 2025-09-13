@include('Admin::layouts.css')

<?php

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
use Auth;
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .verticalLine {
            border-left: thick solid #ff0000;
        }

        .cen {
            text-align: center;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            border: none !important;
        }

        .center {
            margin-left: auto;
            margin-right: auto;
        }

        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .tex {
            font-size: 25px;
        }

        .description-tex {
            font-size: 15px;
        }

        .vertical-line {
            border-left: 5px solid rgb(120, 122, 120);
            height: 50px;
            margin-left: 50%;
        }
    </style>
</head>
<body>
    <div>
        <img alt="Paris" style="height: 60px;" src="{{ asset(config('app.asset').'uploads/company/Easca.png') }}">
    </div>
    <div class="col-xl-12">
        <div class="subheader">
            <h2 style="margin-top:20px;" class="subheader-title cen tex">
                Release Notes
            </h2>
          
        </div>
        <hr>
        <div class="panel-container show container">
            <div class="panel-content">
                <div class="frame-wrap">
                    <table class="table table-borderless " width="100%">
                        @if (!$data->isEmpty())
                        @foreach ($data as $value)
                            <tr>
                                <td width="10%"> <span class="label label-default"> Version: {{ $value->version_no }}
                                    </span></td>
                                <td style="vertical-align: middle" width="10%">
                                    {{ date('dS M Y', strtotime($value->release_date)) }}</td>
                                <td width="65%"></td>
                            </tr>
                            @foreach ($version[$value->id] as $version_value)
                                <tr>
                                    <td></td>
                                    <td>
                                        @if ($version_value->type == 1)
                                            <span class="label label-success col-md-12 "> Added </span>
                                        @elseif ($version_value->type == 2)
                                            <span class="label label-primary col-md-12">Fixed</span>
                                        @else
                                            <span class="label label-info col-md-12">Improved</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="description-tex">{{ $version_value->description }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
