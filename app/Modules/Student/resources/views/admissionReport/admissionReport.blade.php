@extends('Admin::layouts.master')

@section('body')
    <style>
        /* #printbtn {
                        display: none;
                    }
                    .show-table {
                        display: none;
                    } */
        @media print {

            #notPrintDiv,
            .page-breadcrumb,
            .subheader {
                display: none !important;
            }

            @page {
                size: a4 portrait;
            }

            td,
            th {
                font-size: {{ $reportFontSize }}px;
            }
        }

        td,
        th {
            font-size: {{ $reportFontSize }}px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active"> @lang('Student::label.ADMISSION')
            @lang('Student::label.COUNTING') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Student::label.ADMISSION'), @lang('Student::label.SESSION')
            @lang('Student::label.COUNTING') @lang('Student::label.REPORTS')</li>

        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row clearfix">
        <div class="block-header block-header-2">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['method' => 'post', 'route' => 'admission.report.filter']) !!}
                    @csrf
                    <div class="row" id="notPrintDiv">
                        <div class="col-md-5 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.SESSION')</label>
                                    {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                        'id' => 'academicYearId',
                                        'class' => 'form-control select2 academic-year-id',
                                    ]) !!}
                                    <span class="error" id="yearError">{{ $errors->first('academic_year_id') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.CLASS')</label>
                                    {!! Form::Select('class_id[]', $classList, !empty($request->class_id) ? json_decode($request->class_id) : null, [
                                        'id' => 'class_id',
                                        'class' => 'form-control class-id select2',
                                        'multiple' => 'multiple'
                                    ]) !!}
                                </div>
                                <span class="error" id="classError">{{ $errors->first('class_id') }}</span>
                            </div>
                        </div>
                        
                        <div class="col-md-2 form-data">
                            <div class="panel-content align-items-center">

                                <button class="btn btn-primary btn-sm ml-auto mt-5 waves-effect waves-themed" type="submit"
                                    id="">
                                    @lang('Certificate::label.GENERATE')</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <br><br>
                    @if ($request->search == 'true')
                        <div class="subheader">
                            {{-- <button style="margin-left: auto;" class="btn btn-info" onclick="printDiv()"
                            id="printbtn">Print</button> --}}
                            <button style="margin-left: auto;" class="btn btn-info btn-sm print_full_data"
                                onclick="window.print();">Print</button>
                        </div>
                        <div id='printMe'>
                            <center>
                                <div class="row">
                                    <div class="col-md-12">
                                        <img src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}" height="90" class="example-p-5">
                                        <h2>{{ $companyDetails->company_name }}</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span
                                            style="font-size:{{ Session::get('fontsize') }}px;">{{ $companyDetails->address }}</span><br>
                                        <p style="font-size:{{ Session::get('fontsize') }}px;">
                                            Tel: {{ $companyDetails->phone }}, Email:{{ $companyDetails->email }}</p>
                                    </div>
                                </div>
                            </center>
                            <center>
                                <h5 style="margin-bottom: 0px;"><strong>Admission and Session For
                                        {{ $academicYear->year }}</strong>
                                </h5>
                                </caption>
                            </center>
                            <div class="row">
                                <div class="col-md-4 mt-5">
                                    <h3>Today Total: </h3>
                                </div>
                                <div class="col-md-4">
                                    <div class="table-responsive ">
                                        <table class="table table-bordered table-striped table-hover" id="testTable">
                                            <thead class="thead-themed" style="background: #d1d1d1;">
                                                <tr>
                                                    <th colspan="2"class="text-center">Old (Session)</th>
                                                    <th colspan="2"class="text-center">New (Admission)</th>
                                                    <th rowspan="2"class="text-center">Total</th>

                                                </tr>
                                                <tr>
                                                    <th class="text-center">Boy</th>
                                                    <th class="text-center">Girl</th>
                                                    <th class="text-center">Boy</th>
                                                    <th class="text-center">Girl</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        {{ !empty($studentArr['today_total_boy_old']) ? $studentArr['today_total_boy_old'] : 0 }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ !empty($studentArr['today_total_girl_old']) ? $studentArr['today_total_girl_old'] : 0 }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ !empty($studentArr['today_total_boy_new']) ? $studentArr['today_total_boy_new'] : 0 }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ !empty($studentArr['today_total_girl_new']) ? $studentArr['today_total_girl_new'] : 0 }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ !empty($studentArr['total_today']) ? $studentArr['total_today'] : 0 }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                                <h3>Admission/Session Total: </h3>
                                <div class="">
                                    <div class="table-responsive ">
                                        <table class="table table-bordered table-striped table-hover" id="testTable">
                                            <thead class="thead-themed" style="background: #d1d1d1;">
                                                <tr>
                                                    <th rowspan="3" width="15%" class="text-center">Class</th>
                                                    <th colspan="4" width="20%"class="text-center">Session/Admission
                                                    </th>
                                                    <th rowspan="2" colspan="4"width="20%"class="text-center">Total
                                                    </th>
                                                    <th rowspan="4" width="10%"class="text-center">Grand Total</th>
                                                    <th rowspan="2" colspan="4" width="20%"class="text-center">
                                                        S.Type</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2"class="text-center">Old</th>
                                                    <th colspan="2"class="text-center">New</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Boy</th>
                                                    <th class="text-center">Girl</th>
                                                    <th class="text-center">Boy</th>
                                                    <th class="text-center">Girl</th>
                                                    <th class="text-center">Old</th>
                                                    <th class="text-center">New</th>
                                                    <th class="text-center">Boy</th>
                                                    <th class="text-center">Girl</th>
                                                    <th class="text-center">RES</th>
                                                    <th class="text-center">AC</th>
                                                    <th class="text-center">DC</th>
                                                    <th class="text-center">DCF</th>

                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $totalBoyOld = 0;
                                                    $totalGirlOld = 0;
                                                    $totalBoyNew = 0;
                                                    $totalGirlNew = 0;
                                                    $totalOld = 0;
                                                    $totalNew = 0;
                                                    $totalBoy = 0;
                                                    $totalGirl = 0;
                                                    $totalGrand = 0;
                                                    $totalRes = 0;
                                                    $totalDc = 0;
                                                    $totalAc = 0;
                                                    $totalDcf = 0;
                                                @endphp
                                                @if (!$className->isEmpty())
                                                    @foreach ($className as $row)
                                                        <tr>
                                                            <td class="text-center">{{ $row->name }}</td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['boy_old']) ? $studentArr[$row->id]['boy_old'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['girl_old']) ? $studentArr[$row->id]['girl_old'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['boy_new']) ? $studentArr[$row->id]['boy_new'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['girl_new']) ? $studentArr[$row->id]['girl_new'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['total_old']) ? $studentArr[$row->id]['total_old'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['total_new']) ? $studentArr[$row->id]['total_new'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['total_boy']) ? $studentArr[$row->id]['total_boy'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['total_girl']) ? $studentArr[$row->id]['total_girl'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['total_grand']) ? $studentArr[$row->id]['total_grand'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['res']) ? $studentArr[$row->id]['res'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['ac']) ? $studentArr[$row->id]['ac'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['dc']) ? $studentArr[$row->id]['dc'] : 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ !empty($studentArr[$row->id]['dcf']) ? $studentArr[$row->id]['dcf'] : 0 }}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $totalBoyOld += !empty($studentArr[$row->id]['boy_old']) ? $studentArr[$row->id]['boy_old'] : 0;
                                                            $totalGirlOld += !empty($studentArr[$row->id]['girl_old']) ? $studentArr[$row->id]['girl_old'] : 0;
                                                            $totalBoyNew += !empty($studentArr[$row->id]['boy_new']) ? $studentArr[$row->id]['boy_new'] : 0;
                                                            $totalGirlNew += !empty($studentArr[$row->id]['girl_new']) ? $studentArr[$row->id]['girl_new'] : 0;
                                                            $totalOld += !empty($studentArr[$row->id]['total_old']) ? $studentArr[$row->id]['total_old'] : 0;
                                                            $totalNew += !empty($studentArr[$row->id]['total_new']) ? $studentArr[$row->id]['total_new'] : 0;
                                                            $totalBoy += !empty($studentArr[$row->id]['total_boy']) ? $studentArr[$row->id]['total_boy'] : 0;
                                                            $totalGirl += !empty($studentArr[$row->id]['total_girl']) ? $studentArr[$row->id]['total_girl'] : 0;
                                                            $totalGrand += !empty($studentArr[$row->id]['total_grand']) ? $studentArr[$row->id]['total_grand'] : 0;
                                                            $totalRes += !empty($studentArr[$row->id]['res']) ? $studentArr[$row->id]['res'] : 0;
                                                            $totalAc += !empty($studentArr[$row->id]['ac']) ? $studentArr[$row->id]['ac'] : 0;
                                                            $totalDc += !empty($studentArr[$row->id]['dc']) ? $studentArr[$row->id]['dc'] : 0;
                                                            $totalDcf += !empty($studentArr[$row->id]['dcf']) ? $studentArr[$row->id]['dcf'] : 0;
                                                            
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="text-center font-weight-bold" colspan="1">Total</td>
                                                    <td class="text-center font-weight-bold">{{ $totalBoyOld }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalGirlOld }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalBoyNew }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalGirlNew }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalOld }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalNew }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalBoy }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalGirl }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalGrand }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalRes }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalAc }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalDc }}</td>
                                                    <td class="text-center font-weight-bold">{{ $totalDcf }}</td>

                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    </body>
    <script>
        $(function() {

            $(".select2").select2();
        });
    </script>
@endsection
