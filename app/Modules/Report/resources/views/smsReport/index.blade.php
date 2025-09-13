@extends('Admin::layouts.master')
@section('body')
    <style>
        a[target]:not(.btn){
            text-decoration: none !important;
        }
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
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active"> @lang('Sms::label.SMS') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Sms::label.SMS') @lang('Student::label.REPORTS')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'sms.report.filter',
                            'method' => 'post',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-3">
                                <div class="form-group">

                                    <div class="form-line">
                                        <label class="col-form-label">From Date</label>
                                        {!! Form::text('from_date', isset($request->from_date) ? $request->from_date : old('from_date'), [
                                            'id' => 'from_date',
                                            'required' => 'required',
                                            'class' => 'form-control from_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">

                                    <div class="form-line">
                                        <label class="col-form-label">To Date</label>
                                        {!! Form::text('to_date', isset($request->to_date) ? $request->to_date : old('to_date'), [
                                            'id' => 'to_date',
                                            'class' => 'form-control to_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('smsType', 'SMS Type', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                        {!! Form::Select('sms_type', $smsType, !empty($request->sms_type) ? $request->sms_type : null, [
                                            'id' => 'smsType',
                                            'class' => 'form-control select2',
                                        ]) !!}
                                        <span class="error"> {!! $errors->first('sms_type') !!}</span>
                                        <label id="smsType-error" class="error" for="smsType"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="statusId">@lang('Certificate::label.SELECT') @lang('Sms::label.STATUS')</label>
                                        <select name="ErrorCode" id="statusId" class="form-control select2">
                                            <option value=''>@lang('Student::label.ALL')</option>
                                            <option value='Success'>Success</option>
                                            <option value='Inactive Account'>Inactive Account</option>
                                            <option value='insufficient Balance'>insufficient Balance</option>
                                            <option value='Invalid number'>Invalid number</option>
                                            <option value='All Number is Invalid'>All Number is Invalid</option>
                                            <option value='Invalid user or Password'>Invalid user or Password</option>
                                            <option value='Empty Number'>Empty Number</option>
                                            <option value='Max number limit exceeded'>Max number limit exceeded</option>
                                            <option value='Invalid message or empty message'>Invalid message or empty
                                                message</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="align-items-start">
                                    <button class="btn btn-primary float-right waves-effect waves-themed btn-sm mt-2"
                                        name="submit" type="submit">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>
                        <br><br>

                        {!! Form::close() !!}
                        @if ($request->search == 'true')
                            <div class="subheader">
                                <button style="margin-left: auto;" class="btn btn-info btn-sm print_full_data mt-5"
                                    onclick="window.print();">Print</button>
                            </div>
                            <div id='printMe'>
                                <center>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}"
                                                height="90" class="example-p-5">
                                            <h2>{{ $companyDetails->company_name }}</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span
                                                style="font-size:{{ Session::get('fontsize') }}px;">{{ $companyDetails->address }}</span><br>
                                            <p style="font-size:{{ Session::get('fontsize') }}px;">Tel:
                                                {{ $companyDetails->phone }}, Email:
                                                {{ $companyDetails->email }}</p>
                                        </div>
                                    </div>
                                </center>
                                <center>
                                    <h5 style="margin-bottom: 0px;"><strong>@lang('Sms::label.SMS') @lang('Report::label.REPORT')</strong>
                                    </h5>
                                    <caption> {{ $pageTitle }}</caption><br>
                                    <caption> Status: {{ !empty($request->status != null) ? $request->status : 'All' }}
                                    </caption>
                                </center>
                                <div class="body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="testTable">
                                                <thead class="thead-themed" style="background: #d1d1d1;">
                                                    <tr>
                                                        <th class="text-center"> No</th>
                                                        <th class="text-center"> Student ID</th>
                                                        <th class="text-center"> Student Name</th>
                                                        <th class="text-center"> Class Name</th>
                                                        <th class="text-center"> Mobile Number</th>
                                                        <th class="text-center"> Messages</th>
                                                        <th class="text-center"> SMS Count</th>
                                                        <th class="text-center"> Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalSms = 0;
                                                        $totalCount = 0;
                                                    @endphp
                                                    @if (!empty($model))
                                                        @foreach ($model as $key => $value)
                                                            <tr>
                                                                <td class="text-center">{{ $key + 1 }}</td>
                                                                <td class="text-center">
                                                                    <a href="{{ route(app('SID'), $value->student_id) }}" target="_blank" style="text-decoration: none;">
                                                                        {{ $value->sid }}
                                                                    </a>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="{{ route(app('studentName'), $value->student_id) }}" target="_blank" style="text-decoration: none;">
                                                                        {{ $value->student_name }}
                                                                    </a>
                                                                </td>
                                                                <td class="text-center">{{ $value->class_name }}</td>
                                                                <td class="text-center">{{ $value->mobile_number }} </td>
                                                                <td class="text-center">{{ $value->msg }}</td>
                                                                <td class="text-center">{{ $value->sms_count }}</td>
                                                                @if ($value->status == 1 || $value->status == 'Success')
                                                                    <td><span
                                                                            class="badge bg-success text-center">{{ $value->status }}</span>
                                                                    </td>
                                                                @else
                                                                    <td><span
                                                                            class="badge bg-danger text-center">{{ $value->status }}</span>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            @php
                                                                $totalSms = $key + 1;
                                                                $totalCount += $value->sms_count;
                                                            @endphp
                                                        @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <td colspan="5" class="text-center">Total SMS</td>
                                                    <td class="text-center"> {{ $totalSms }} </td>
                                                    <td class="text-center"> {{ $totalCount }} </td>
                                                </tfoot>
                        @endif

                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            jQuery('.from_date').datepicker({
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                autoClose: true
            });
            jQuery('.to_date').datepicker({
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                autoClose: true
            });

        });
        $(document).ready(function() {

            $('.select2').select2({
                width: "100%",
            });
        });

        function printDiv() {
            var printContents = document.getElementById('printMe').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        $('input[type=text]').attr('autocomplete', 'off');
        $('input[type=number]').attr('autocomplete', 'off');
        $(document).ready(function() {

            // initialize datatable
            $('#testTable').dataTable({

                responsive: true,
                lengthChange: false,
                paging: false,
                searching: false,
                order: false,
                bSort: false,
                bPaginate: false,
                bLengthChange: false,
                bInfo: false,
                bAutoWidth: false,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                dom:
                    /*  --- Layout Structure 
                        --- Options
                        l   -   length changing input control
                        f   -   filtering input
                        t   -   The table!
                        i   -   Table information summary
                        p   -   pagination control
                        r   -   processing display element
                        B   -   buttons
                        R   -   ColReorder
                        S   -   Select

                        --- Markup
                        < and >             - div element
                        <"class" and >      - div with a class
                        <"#id" and >        - div with an ID
                        <"#id.class" and >  - div with an ID and a class

                        --- Further reading
                        https://datatables.net/reference/option/dom
                        --------------------------------------
                     */
                    "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Excel',
                    titleAttr: 'Generate Excel',
                    className: 'btn-outline-success btn-sm mr-1'
                }]
            });

        });
    </script>
@endsection
