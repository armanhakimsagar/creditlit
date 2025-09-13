@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            .generate-button {
                display: block;
                position: fixed;
                bottom: 30px;
                right: 40px;
            }
        </style>
    @endpush
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Examination::label.EXAM_RESULT')</li>
            <li class="breadcrumb-item active">@lang('Examination::label.STUDENTS_MARK_GENERATE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Examination::label.STUDENTS_MARKS') @lang('Examination::label.LIST')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Examination::label.STUDENTS_MARKS') <span class="fw-300"><i>List</i></span>
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
                                                    'onchange' => 'getSection();getExam();getSubject();',
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
                                                <label for="subjectId" class='col-form-label'>Select Subject</label><span
                                                    class="required"> *</span>
                                                {!! Form::Select('subject_id', ['' => 'At first select class'], null, [
                                                    'id' => 'subjectId',
                                                    'class' => 'form-control class-id select2 search-section',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="subjectError"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <div
                                                    class="panel-content float-left border-left-0 border-right-0 border-bottom-0 align-items-center">
                                                    <button
                                                        class="btn btn-primary btn-sm ml-auto mt-3 waves-effect waves-themed"onclick="assignValues()"
                                                        type="submit" id="searchBtn"><i
                                                            class="fas fa-search pr-1"></i>@lang('Certificate::label.SEARCH')
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="error" id="subjectError"></span>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <br>
                                <div class="row clearfix">
                                    <div class="block-header block-header-2">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <center>
                                            <div class="row">
                                                <div class="col-md-12">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                </div>
                                            </div>
                                        </center>
                                        <div class="table-responsive">
                                            <div class="show-table">
                                                <form action="{{ route('student.mark.generate.store') }}" method="POST"
                                                    id="saveMarks">
                                                    @csrf
                                                    <table class="table table-bordered table-striped" id="yajraTable">
                                                        <thead class="thead-themed">
                                                            <th> SL</th>
                                                            <th style="width: 10%;" class="table-checkbox-header-center">
                                                                <span>
                                                                    Check All</span>
                                                                <input type="checkbox" class="all-check-box" id="chkbxAll"
                                                                    onclick="return checkAll()">
                                                            </th>
                                                            <th>
                                                                SID
                                                            </th>
                                                            <th> Student Name</th>
                                                            <th> Roll</th>
                                                            <th> Written</th>
                                                            <th> MCQ</th>
                                                            <th> Lab/Total</th>
                                                            <th> Total</th>
                                                            <th class="action" style="width: 5%;"> Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="class_name" id="className">
                                                    <input type="hidden" name="academic_year" id="yearId">
                                                    <input type="hidden" name="exam_name" id="examName">
                                                    <input type="hidden" name="section_name" id="sectionName">
                                                    <input type="hidden" name="subject_name" id="subjectName">

                                                    <td><button
                                                            class="btn btn-primary btn-sm generate-button float-right mb-3 mr-3 save-btn"
                                                            type="submit">save</button></td>
                                                </form>
                                            </div>
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
                    $("#chkbxAll").prop("checked", false);
                    if ($("#class_id").val() != 0 && $("#academicYearId").val() != 0 && $("#examId").val() !=
                        0 && $("#subjectId").val() != 0) {
                        $('#yearError').html('');
                        $('#classError').html('');
                        $('#examError').html('');
                        $('#subjectError').html('');
                        $(".show-table").css("display", "block");
                        var myTable = $('#yajraTable').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "paging": false,
                            iDisplayLength: 50,
                            "ajax": {
                                "url": "{{ route('student.mark.generate.create') }}",
                                "data": function(e) {
                                    e.class_id = $("#class_id").val();
                                    e.academicYearId = $("#academicYearId").val();
                                    e.examId = $("#examId").val();
                                    e.subjectId = $("#subjectId").val();
                                    e.sectionID = $("#sectionID").val();
                                }
                            },

                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    "className": "table-serial-column-center",
                                },
                                {
                                    data: 'checkbox',
                                    name: 'checkbox',
                                    orderable: false,
                                    searchable: false,
                                    "className": "table-checkbox-column",
                                },
                                {
                                    data: 'contact_id',
                                    name: 'contact_id'
                                },
                                {
                                    data: 'full_name',
                                    name: 'full_name'
                                },
                                {
                                    data: 'class_roll',
                                    name: 'class_roll',
                                    "className": "table-column-center",
                                },
                                {
                                    data: 'written',
                                    name: 'written'
                                },
                                {
                                    data: 'mcq',
                                    name: 'mcq'
                                },
                                {
                                    data: 'lab',
                                    name: 'lab'
                                },
                                {
                                    data: 'total',
                                    name: 'total'
                                },
                                {
                                    data: 'action',
                                    name: 'action',
                                    "className": "action",
                                },
                            ],

                            "bDestroy": true

                        });
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
                        if ($("#subjectId").val() == 0) {
                            $('#subjectError').html('Please Select Subject');
                            $(".show-table").css("display", "none");
                        }
                    }
                    setTimeout(() => {
                        isChecked();
                    }, 250);
                });

                $(".select2").select2();
                <?php if(!empty($request->academic_year_id) && !empty($request->class_id)){ ?>
                $(".search-section").trigger('change');
                <?php
            }
            ?>
            });


            $(document).ready(function() {
                // Delete Student Marks
                $('body').on('click', '.delete-mark', function(data) {
                    let studentMarkId = $(this).data('id');
                    var url = "{{ url('mark-destroy') }}/" + studentMarkId;
                    // alert(url);
                    $.ajax({
                        url: url,
                        type: 'get',
                        success: function(data) {
                            toastr.success(data);
                            $('#searchBtn').trigger('click');
                        }
                    });
                });
            });


            function unCheck(id) {
                if ($('#' + id).is(':not(:checked)')) {
                    $("#chkbxAll").prop("checked", false);
                }
            }


            function Check(id) {
                if (($("#writtenMark" + id).val() == '') && ($("#mcqMark" + id).val() == '') && ($("#labMark" + id).val() ==
                        '')) {
                    $("#checkStudent_" + id).prop('checked', false);
                } else {
                    $("#checkStudent_" + id).prop('checked', true);
                }
            }

            function checkAll() {
                if ($('#chkbxAll').is(':checked')) {
                    $(".allCheck").each(function(index) {
                        // alert();
                        var key = $(this).attr('keyvalue');

                        if ($("#checkStudent_" + key)[0].checked == false) {
                            $("#checkStudent_" + key).prop('checked', true);
                        }
                    });
                } else {
                    $(".allCheck").each(function(index) {
                        var key = $(this).attr('keyvalue');
                        if ($("#checkStudent_" + key)[0].checked == true) {
                            $("#checkStudent_" + key).prop('checked', false);
                        }
                    });
                }
            }

            function isChecked() {
                var countNotChecked = 0;
                var countChecked = 0;
                $(".allCheck").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                    } else {
                        countNotChecked++;
                    }
                });
                if (countNotChecked == 0 && countChecked > 0) {
                    $("#chkbxAll").prop("checked", true);
                }

            }

            $(document).on('click', '.save-btn', function(e) {
                e.preventDefault();

                var countChecked = 0;

                $(".allCheck").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                    }

                });
                if (countChecked > 0) {
                    $("#saveMarks").submit();
                } else {
                    alert("please select student")
                }

            });





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


            // subject change on select class
            function getSubject() {
                var classId = $('#class_id').val()
                var html = '';
                if (classId != 0) {
                    $.ajax({
                        url: "{{ url('get-subject') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            classId: classId
                        },
                        beforeSend: function() {
                            $('select[name="subject_id"]').empty();
                        },
                        success: function(response) {
                            $('select[name="subject_id"]').append(
                                '<option value="">Select Subject</option>');
                            $.each(response, function(key, data) {
                                $('select[name="subject_id"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.name + '</option>');
                            });
                        }
                    });
                }
            }


            function assignValues() {
                var getClassId = $("#class_id").val();
                $("#className").val(getClassId);
                var getAcademicId = $("#academicYearId").val();
                $("#yearId").val(getAcademicId);
                var getExamName = $("#examId").val();
                $("#examName").val(getExamName);
                var getSectionName = $("#sectionID").val();
                $("#sectionName").val(getSectionName);
                var getSubjectName = $("#subjectId").val();
                $("#subjectName").val(getSubjectName);
            }

            function calculatePayable(id) {
                var writtenMark = $('#writtenMark' + id).val() || 0;
                writtenMark = parseFloat(writtenMark);
                var mcqMark = $('#mcqMark' + id).val() || 0;
                mcqMark = parseFloat(mcqMark);
                var labMark = $('#labMark' + id).val() || 0;
                labMark = parseFloat(labMark);
                var totalMark = writtenMark + mcqMark + labMark;
                $('#totalMark' + id).val(totalMark);
            }
        </script>
    @endsection
