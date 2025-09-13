@extends('Admin::layouts.master')
@section('body')
    <style>
        a[target]:not(.btn){
            text-decoration: none !important;
        }
        tr:nth-child(even),
        tr:nth-child(even) {
            background-color: #fff;
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

            footer {
                page-break-after: always;
            }
        }

        td,
        th {
            font-size: {{ $reportFontSize }}px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('DataImport::label.Attendance')</li>
        <li class="breadcrumb-item active">@lang('DataImport::label.Students') @lang('DataImport::label.DAILY') @lang('DataImport::label.Attendance')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>

    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('DataImport::label.Students') @lang('DataImport::label.DAILY') @lang('DataImport::label.Attendance')
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
                            'route' => 'student.daily.attendance.filter',
                            'method' => 'post',
                            'id' => 'collectionReportId',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row" id="notPrintDiv">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('academicYearId', 'Academic Year', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('academic_year_id', $academic_year_list, !empty($request->yearId) ? $request->yearId : null, [
                                            'id' => 'academicYearId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getSection();',
                                        ]) !!}
                                        <span class="error" id="yearError"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('classId', 'Class Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('class_id', $class_list, !empty($request->class_id) ? $request->class_id : null, [
                                            'id' => 'classId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getSection();',
                                        ]) !!}
                                        <span class="error" id="classError"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::label('sectionId', 'SECTION', ['class' => 'col-form-label']) !!}


                                        {!! Form::Select(
                                            'section_id',
                                            ['' => 'At first select a Academic Year & Class'],
                                            !empty($request->section_id) ? $request->section_id : null,
                                            [
                                                'id' => 'sectionId',
                                                'class' => 'form-control select2',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('shiftId', 'Shift Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('shift_id', $shift_list, !empty($request->shift_id) ? $request->shift_id : null, [
                                            'id' => 'shiftId',
                                            'class' => 'form-control select2',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        <label class="col-form-label">Attendance Date</label>
                                        {!! Form::text('attendance_date', !empty($request->attendance_date) ? $request->attendance_date : null, [
                                            'id' => 'attendanceDate',
                                            'class' => 'form-control attendance_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}
                                        <span class="error" id="attendanceDateError"></span>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="panel-content d-flex flex-row align-items-center">
                                    <button class="btn btn-primary waves-effect waves-themed btn-sm mt-5" name="submit"
                                        onclick="validate()" type="button" id="saveBtn">@lang('Certificate::label.SEARCH')</button>

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
                                            <p style="font-size:{{ Session::get('fontsize') }}px;">
                                                Tel: {{ $companyDetails->phone }}, Email:{{ $companyDetails->email }}</p>
                                        </div>
                                    </div>
                                </center>
                                <center>
                                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                                        <h5>
                                            <center>{{ $pageTitle }}</center>
                                        </h5>
                                        <h5>
                                            <center>Year: {{ $yearName }}</center>
                                        </h5>
                                        <h5>
                                            <center>Class : {{ $className }}</center>
                                        </h5>
                                        @if ($sectionName)
                                            <h5>
                                                <center>Section : {{ $sectionName }}</center>
                                            </h5>
                                        @endif
                                        @if ($shiftName)
                                            <h5>
                                                <center>Shift : {{ $shiftName }}</center>
                                            </h5>
                                        @endif
                                        <h5>
                                            <center>Day : {{ $day }}</center>
                                        </h5>
                                        <h5>
                                            <center>Date : {{ $date }}</center>
                                        </h5>
                                    </div>
                                </center>
                                <div class="body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="testTable">
                                                <thead class="thead-themed" style="background: #d1d1d1;">
                                                    <tr>
                                                        <th class="text-left"> No</th>
                                                        <th class="text-center"> Photo </th>
                                                        <th class="text-center"> Name</th>
                                                        <th class="text-center"> Phone</th>
                                                        <th class="text-center"> Roll</th>
                                                        <th class="text-center"> Attendance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($data))
                                                        <?php
                                                        $total_rows = 1;
                                                        $sum = 0;
                                                        ?>
                                                        @foreach ($data as $item)
                                                            <tr>
                                                                <td class="text-left"> {{ $total_rows++ }}</td>
                                                                <td class="text-center">
                                                                    @if ($item->photo != null && base_path() . '/public/backend/images/students/' . $item->photo)
                                                                        <img src="{{ asset(config('app.asset') . 'backend/images/students/' . $item->photo) }}"
                                                                            name="student_picture" id="upload-img"
                                                                            height="50" width="50">
                                                                    @else
                                                                        <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}"
                                                                            name="student_picture" id="upload-img"
                                                                            height="50" width="50">
                                                                    @endif
                                                                </td>
                                                                <td class="text-center"> 
                                                                    <a href="{{ route(app('studentName'), $item->id) }}" target="_blank" style="text-decoration: none;">
                                                                        {{ $item->student_name }}
                                                                    </a>
                                                                </td>
                                                                <td class="text-center"> {{ $item->guardian_phone }}</td>
                                                                <td class="text-center"> {{ $item->class_roll }}</td>
                                                                <td class="text-center">
                                                                    @if ($item->attendance_status == 'Present')
                                                                        <label for=""
                                                                            class="badge-success p-1">Present</label>
                                                                    @elseif($item->attendance_status == 'Absent')
                                                                        <label for=""
                                                                            class="badge-warning p-1">Absent</label>
                                                                    @elseif($item->attendance_status == 'Weekend')
                                                                        <label for=""
                                                                            class="badge-secondary p-1">Weekend</label>
                                                                    @elseif($item->attendance_status == 'Holiday')
                                                                        <label for=""
                                                                            class="badge-primary p-1">Holiday</label>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <!-- Table body content -->
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
    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            jQuery('.attendance_date').datepicker({
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                autoClose: true
            });

            $('.select2').select2({
                width: "100%",
            });

            getSection();

        });


        function getSection() {
            var classId = $('#classId').val();
            var yearId = $('#academicYearId').val();
            var sectionId = {!! json_encode($request->section_id) !!};

            if (classId != 0 && yearId != 0) {
                $.ajax({
                    url: "{{ url('get-sections') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        classId: classId,
                        yearId: yearId
                    },
                    beforeSend: function() {
                        $('select[name="section_id"]').empty();
                    },
                    success: function(response) {
                        var selectElement = $('select[name="section_id"]');
                        selectElement.append('<option value="">Select Section</option>');

                        $.each(response, function(key, data) {
                            var option = $('<option>').val(data.id).text(data.name);

                            // Check if the sectionId matches the current iteration option value
                            if (sectionId == data.id) {
                                option.attr('selected', 'selected');
                            }

                            selectElement.append(option);
                        });
                    }
                });
            }
        }


        function validate() {
            if ($("#academicYearId").val() != 0 && $("#classId").val() != 0 && $("#attendanceDate").val() != 0) {
                $("#saveBtn").attr('type', 'submit');
                $("#saveBtn").submit();
            } else {
                if ($("#classId").val() == 0) {
                    $('#classError').html('Please Select Class');
                } else {
                    $('#classError').html('');
                }

                if ($("#academicYearId").val() == 0) {
                    $('#yearError').html('Please Select Session');
                } else {
                    $('#yearError').html('');
                }

                if ($("#attendanceDate").val() == 0) {
                    $('#attendanceDateError').html('Please Select Attendance Date');
                } else {
                    $('#attendanceDateError').html('');
                }
            }
        }
    </script>
@endsection
