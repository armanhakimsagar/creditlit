@extends('Admin::layouts.master')
@section('body')
    <style>
        .marksheet-details-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-auto-flow: column;
            background-color: #EEEEEE;
            padding: 10px;
            /* -webkit-print-color-adjust: exact; */

        }

        .marksheet-details-item {
            background-color: #EEEEEE;
            /* -webkit-print-color-adjust: exact; */
        }

        .marksheet-details-container th,
        .marksheet-details-container td {
            border: 1px solid #ffffff;
            /* -webkit-print-color-adjust: exact; */
        }

        .student-result-sheet {
            background-color: #EEEEEE;
            /* -webkit-print-color-adjust: exact; */
        }

        .student-result-sheet {
            padding: 10px;
        }

        tr:nth-child(even),
        tr:nth-child(odd) {
            background-color: #EEEEEE;
            /* -webkit-print-color-adjust: exact; */
        }


        .h5 {
            margin-bottom: 0px;
        }


        .table-bordered th,
        .table-bordered td {
            border: 1px solid #8b8b8b;
            /* -webkit-print-color-adjust: exact; */

        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 1px solid #8b8b8b;
            /* -webkit-print-color-adjust: exact; */
        }

        @media print {


            #notPrintDiv,
            .subheader,
            .panel-hdr,
            .breadcrumb {
                display: none;
            }

            tr:nth-child(even),
            tr:nth-child(odd) {
                background-color: #000000;
                -webkit-print-color-adjust: exact !important;
            }

            .marksheet-details-container th,
            .marksheet-details-container td {
                border: 1px solid #EEEEEE;
            }
        }
    </style>
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Examination::label.EXAM_RESULT')</li>
            <li class="breadcrumb-item active"> @lang('Examination::label.STUDENT_WISE_RESULT')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Examination::label.STUDENT_WISE_RESULT')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Examination::label.STUDENT_WISE_RESULT') <span class="fw-300"><i> @lang('Examination::label.LIST')</i></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="row clearfix">
                    <div class="block-header block-header-2">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row" id="notPrintDiv">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="" class='col-form-label'>Select Session</label><span
                                                    class="required"> *</span>
                                                {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                                    'id' => 'academicYearId',
                                                    'class' => 'form-control select2 academic-year-id search-section',
                                                    'onchange' => 'getSection();getExam();',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="yearError"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="" class='col-form-label'>Select Class</label><span
                                                    class="required"> *</span>
                                                {!! Form::Select('class_id', $classList, !empty($request->class_id) ? $request->class_id : null, [
                                                    'id' => 'class_id',
                                                    'class' => 'form-control class-id select2 search-section',
                                                    'onchange' => 'getSection();getExam();getStudent();',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="classError"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="sectionID" class='col-form-label'>Select Section</label>
                                                {!! Form::Select('section_id', ['' => 'At first select session & class'], null, [
                                                    'id' => 'sectionID',
                                                    'class' => 'form-control class-id select2 search-section',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="classError"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="examId" class='col-form-label'>Select Exam</label><span
                                                    class="required"> *</span>
                                                {!! Form::Select('exam_id', ['' => 'At first select session & class'], null, [
                                                    'id' => 'examId',
                                                    'class' => 'form-control class-id select2 search-section',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="examError"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="studentId" class='col-form-label'>Select Student</label><span
                                                    class="required"> *</span>
                                                {!! Form::Select('student_id', ['' => 'At first select class'], null, [
                                                    'id' => 'studentId',
                                                    'class' => 'form-control class-id select2 search-section',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="studentError"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <div
                                                    class="panel-content float-left border-left-0 border-right-0 border-bottom-0 align-items-center">
                                                    <button
                                                        class="btn btn-primary btn-sm ml-auto mt-3 waves-effect waves-themed"
                                                        type="submit" id="searchBtn"><i
                                                            class="fas fa-search pr-1"></i>@lang('Certificate::label.SEARCH')
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="error" id="studentError"></span>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-12">
                                        <center>
                                            <img style="width: 30px; margin-right: 50px; display: none;" class="preloader"
                                                src="{{ asset(config('app.asset') . 'image/my-loading.gif') }}">
                                        </center>
                                    </div>
                                </div>
                                <div class="row clearfix" id="studentMarksheet" style="display: none;">
                                    <div class="block-header block-header-2">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p class="h3" align="center">SSC/Dakhil/Equivalent {{ $yearName->year }}</p>
                                        <div class="marksheet-details-container">
                                            <div class="marksheet-details-item">
                                                <table width="100%" class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td align="left" valign="middle">Roll No :</td>
                                                            <td align="left" valign="middle" id="classRoll">196670</td>
                                                        </tr>
                                                        <tr id="registrationNo">

                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle">Class :</td>
                                                            <td align="left" valign="middle" id="className">COMILLA</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle">Section :</td>
                                                            <td align="left" valign="middle" id="sectionName">A</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle">Version :</td>
                                                            <td align="left" valign="middle" id="versionName">Arabic
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle">Shift :</td>
                                                            <td align="left" valign="middle" id="shiftName">1st</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle">Gender :</td>
                                                            <td align="left" valign="middle" id="genderName"
                                                                class="black12bold" colspan="3">4.82</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="marksheet-details-item">
                                                <table width="100%" class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td width="22%" align="left" valign="middle">Name :</td>
                                                            <td width="39%" align="left" valign="middle"
                                                                id="studentName">NAYEM HOSSAIN</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle">Student ID :</td>
                                                            <td align="left" valign="middle" id="studentContactId">
                                                                S241382</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle"> Father's Name :</td>
                                                            <td align="left" valign="middle" id="fatherName"> ABUL
                                                                KALAM </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle"> Mother's Name :</td>
                                                            <td align="left" valign="middle" id="motherName"> NAZMUN
                                                                NAHAR </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle"> Date Of Birth :</td>
                                                            <td align="left" valign="middle" id="dateOfBirth">
                                                                22-01-2001 </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="middle"> Institute :</td>
                                                            <td align="left" valign="middle" id="dateOfBirth"> K. S.
                                                                Public School </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="student-result-sheet">
                                            <p class="h5" align="center">Grade Sheet</p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <td> Code</td>
                                                        <td>Subject</td>
                                                        <td>Written mark</td>
                                                        <td>MCQ mark</td>
                                                        <td>Lab mark</td>
                                                        <td>Total</td>
                                                        <td>Grade</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="studentResult">


                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="print-btn">

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(function() {
                $(document).on('click', '#searchBtn', function() {
                    if ($("#class_id").val() != 0 && $("#academicYearId").val() != 0 && $("#examId").val() !=
                        0 && $("#studentId").val() != 0) {
                        $('#yearError').html('');
                        $('#classError').html('');
                        $('#examError').html('');
                        $('#studentError').html('');

                        var classId = $('#class_id').val()
                        var yearId = $('#academicYearId').val();
                        var sectionID = $('#sectionID').val();
                        var examId = $('#examId').val();
                        var studentId = $('#studentId').val();
                        var html = '';
                        if (classId != 0 && yearId != 0 && examId != 0 && studentId != 0) {
                            $.ajax({
                                url: "{{ url('get-stuedents-mark') }}",
                                type: "post",
                                dataType: "json",
                                data: {
                                    _token: '{!! csrf_token() !!}',
                                    classId: classId,
                                    yearId: yearId,
                                    sectionID: sectionID,
                                    examId: examId,
                                    studentId: studentId
                                },
                                beforeSend: function() {
                                    $('#classRoll').empty();
                                    $('#registrationNo').empty();
                                    $('#sectionName').empty();
                                    $('#versionName').empty();
                                    $('#shiftName').empty();
                                    $('#studentResult').empty();
                                    $('.preloader').show();
                                    $('.print-btn').empty();
                                },
                                success: function(response) {
                                    $('#studentMarksheet').show();
                                    $('#studentName').text(response[0].full_name);
                                    $('#fatherName').text(response[0].father_name);
                                    $('#motherName').text(response[0].mother_name);
                                    $('#studentContactId').text(response[0].contact_id);
                                    $('#className').text(response[0].class_name);
                                    $('#genderName').text(response[0].gender);
                                    $('#sectionName').text(response[0].section_name);
                                    $('#versionName').text(response[0].version_name);
                                    $('#shiftName').text(response[0].shift_name);
                                    if (response[0].registration_no != null) {
                                        $('#registrationNo').append(
                                            '<td align="left" valign="middle">Registration :</td>' +
                                            '<td align="left" valign="middle">' + response[0]
                                            .registration_no + '</td>'
                                        );
                                    }
                                    $('#classRoll').append(response[0].class_roll);
                                    if (response[1] == '') {
                                        $('#studentResult').append(
                                            '<td align="center" colspan="7" class="dataTables_empty">No records to display</td>'
                                        )
                                    } else {
                                        $.each(response[1], function(key, data) {
                                            var grade = '';
                                            if (response[1][key].total_mark >= 80) {
                                                grade = "A+";
                                            } else if (response[1][key].total_mark >= 70) {
                                                grade = "A";
                                            } else if (response[1][key].total_mark >= 60) {
                                                grade = "A-";
                                            } else if (response[1][key].total_mark >= 50) {
                                                grade = "B";
                                            } else if (response[1][key].total_mark >= 40) {
                                                grade = "C";
                                            } else if (response[1][key].total_mark >= 33) {
                                                grade = "D";
                                            } else {
                                                grade = "F";
                                            }
                                            var tr = '<tr>' +
                                                '<td align="left" valign="middle" bgcolor="#EEEEEE">' +
                                                (response[1][key].sub_code != null ?
                                                    response[1][key].sub_code : '') +
                                                '</td>' +
                                                '<td align="left"valign="middle"bgcolor="#EEEEEE">' +
                                                response[1][key].name + '</td>' +
                                                '<td align="left"valign="middle"bgcolor="#EEEEEE">' +
                                                (response[1][key].written_mark != null ?
                                                    response[1][key].written_mark : ''
                                                ) +
                                                '</td>' +
                                                '<td align="left"valign="middle"bgcolor="#EEEEEE">' +
                                                (response[1][key].mcq_mark != null ?
                                                    response[1][key].mcq_mark : '') +
                                                '</td>' +
                                                '<td align="left"valign="middle"bgcolor="#EEEEEE">' +
                                                (response[1][key].lab_mark != null ?
                                                    response[1][key].lab_mark : '') +
                                                '</td>' +
                                                '<td align="left"valign="middle"bgcolor="#EEEEEE">' +
                                                (response[1][key].lab_mark != null ?
                                                    response[1][key].total_mark : '') +
                                                '</td>' +
                                                '<td align="left"valign="middle"bgcolor="#EEEEEE">' +
                                                (grade != null ?
                                                    grade : '') +
                                                '</td>' +
                                                '</tr>'
                                            $('#studentResult').append(tr);
                                        });
                                        $('.print-btn').append(
                                            '<button class="btn btn-default float-right mt-3" onclick="window.print()"><i class="fa fa-print" aria-hidden="true" style="    font-size: 17px;"> Print</i></button>'
                                        )
                                    }
                                    $('.preloader').hide();
                                }
                            });
                        }

                    } else {
                        if ($("#class_id").val() == 0) {
                            $('#classError').html('Please Select Class');
                            $(".show-table").css("display", "none");
                        }
                        if ($("#academicYearId").val() == 0) {
                            $('#yearError').html('Please Select Session');
                            $(".show-table").css("display", "none");
                        }
                        if ($("#examId").val() == 0) {
                            $('#examError').html('Please Select Exam');
                            $(".show-table").css("display", "none");
                        }
                        if ($("#studentId").val() == 0) {
                            $('#studentError').html('Please Select Subject');
                            $(".show-table").css("display", "none");
                        }
                    }
                    setTimeout(() => {}, 250);
                });

                $(".select2").select2();
                <?php if(!empty($request->academic_year_id) && !empty($request->class_id)){ ?>
                $(".search-section").trigger('change');
                <?php
            }
            ?>
            });


            // function unCheck(id) {
            //     if ($('#' + id).is(':not(:checked)')) {
            //         $("#chkbxAll").prop("checked", false);
            //     }
            // }


            // function Check(id) {
            //     if(($("#writtenMark" + id).val() == '') && ($("#mcqMark" + id).val() == '') && ($("#labMark" + id).val() == '')){
            //         $("#checkStudent_" + id).prop('checked', false);
            //     }else{
            //         $("#checkStudent_" + id).prop('checked', true);
            //     }
            // }

            // function isChecked() {
            //     var countNotChecked = 0;
            //     var countChecked = 0;
            //     $(".allCheck").each(function(index) {
            //         if ($(this)[0].checked == true) {
            //             countChecked++;
            //         } else {
            //             countNotChecked++;
            //         }
            //     });
            //     if (countNotChecked == 0 && countChecked > 0) {
            //         $("#chkbxAll").prop("checked", true);
            //     }

            // }

            // $(document).on('click', '.save-btn', function(e) {
            //     e.preventDefault();

            //     var countChecked = 0;

            //     $(".allCheck").each(function(index) {
            //         if ($(this)[0].checked == true) {
            //             countChecked++;
            //         }

            //     });
            //     if (countChecked > 0) {
            //         $("#saveMarks").submit();
            //     } else {
            //         alert("please select student")
            //     }

            // });






            // Section Change on select Class & Academic Year
            function getSection() {
                var classId = $('#class_id').val()
                var yearId = $('#academicYearId').val();
                var html = '';
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
                            $('select[name="section_id"]').append(
                                '<option value="">Select Section</option>');
                            $.each(response, function(key, data) {
                                $('select[name="section_id"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.name + '</option>');
                            });
                        }
                    });
                }
            }


            // exam change on select class & Academic Year
            function getExam() {
                var classId = $('#class_id').val()
                var yearId = $('#academicYearId').val();
                var html = '';
                if (classId != 0 && yearId != 0) {
                    $.ajax({
                        url: "{{ url('get-exam') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            classId: classId,
                            yearId: yearId
                        },
                        beforeSend: function() {
                            $('select[name="exam_id"]').empty();
                        },
                        success: function(response) {
                            $('select[name="exam_id"]').append(
                                '<option value="">Select Exam</option>');
                            $.each(response, function(key, data) {
                                $('select[name="exam_id"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.exam_name + '</option>');
                            });
                        }
                    });
                }
            }


            // Student change on select class
            function getStudent() {
                var classId = $('#class_id').val();
                var yearId = $('#academicYearId').val();
                var html = '';
                if (classId != 0 && yearId != 0) {
                    $.ajax({
                        url: "{{ url('get-student') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            classId: classId,
                            yearId: yearId
                        },
                        beforeSend: function() {
                            $('select[name="student_id"]').empty();
                        },
                        success: function(response) {
                            $('select[name="student_id"]').append(
                                '<option value="">Select Student</option>');
                            $.each(response, function(key, data) {
                                $('select[name="student_id"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.full_name + '</option>');
                            });
                        }
                    });
                }
            }


            // function assignValues() {
            //     var getClassId = $("#class_id").val();
            //     $("#className").val(getClassId);
            //     var getAcademicId = $("#academicYearId").val();
            //     $("#yearId").val(getAcademicId);
            //     var getExamName = $("#examId").val();
            //     $("#examName").val(getExamName);
            //     var getSectionName = $("#sectionID").val();
            //     $("#sectionName").val(getSectionName);
            //     var getSubjectName = $("#studentId").val();
            //     $("#studentName").val(getSubjectName);
            // }

            // function calculatePayable(id) {
            //     var writtenMark = $('#writtenMark' + id).val() || 0;
            //     writtenMark = parseFloat(writtenMark);
            //     var mcqMark = $('#mcqMark' + id).val() || 0;
            //     mcqMark = parseFloat(mcqMark);
            //     var labMark = $('#labMark' + id).val() || 0;
            //     labMark = parseFloat(labMark);
            //     var totalMark = writtenMark + mcqMark + labMark;
            //     $('#totalMark' + id).val(totalMark);
            // }
        </script>
    @endsection
