@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            a[target]:not(.btn) {
                text-decoration: none !important;
            }

            .generate-button {
                display: block;
                position: fixed;
                bottom: 30px;
                right: 40px;
            }

            .table-responsive {
                max-height: 600px;
                overflow-y: scroll;
            }

            .table-responsive table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
            }

            .table-responsive thead {
                position: sticky;
                top: -1px;
                z-index: 999;
            }

            thead {
                background: #eee;
            }

            table tr {
                margin: 0;
                padding: 0;
            }

            tr:nth-child(even) {
                background-color: #fcfcfc;
            }
        </style>
    @endpush
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Mark::label.MARKS')</li>
            <li class="breadcrumb-item active">@lang('Academic::label.STUDENT') @lang('Mark::label.MARK') @lang('Mark::label.INPUT')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Academic::label.STUDENT') @lang('Mark::label.MARK') @lang('Mark::label.INPUT')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Academic::label.STUDENT') @lang('Mark::label.MARK') @lang('Mark::label.INPUT') <span class="fw-300"><i>List</i></span>
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
                                                <label for="" class='col-form-label'>Select Academic
                                                    Year</label><span class="required"> *</span>
                                                {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                                    'id' => 'academicYearId',
                                                    'class' => 'form-control select2 academic-year-id search-section',
                                                    'onchange' => 'getSection();',
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
                                                    'onchange' => 'getSection();getSubject();',
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
                                            <span class="error" id="sectionError"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="examId" class='col-form-label'>Select Exam</label><span
                                                    class="required"> *</span>
                                                {!! Form::Select('exam_id', $examList, !empty($request->exam_id) ? $request->exam_id : null, [
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
                                                    'multiple' => 'multiple',
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
                                                        class="btn btn-primary btn-sm ml-auto mt-3 waves-effect waves-themed"
                                                        type="submit" id="searchBtn"
                                                        onclick="getMarkHeader();assignValues();"><i
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
                                {!! Form::open([
                                    'route' => 'student.mark.input.store',
                                    'files' => true,
                                    'name' => 'student_mark_input',
                                    'id' => 'saveMarks',
                                    'class' => 'form-horizontal',
                                ]) !!}
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive" id="markInputTable">
                                        <div class="show-table">
                                            <table class="table table-bordered table-striped">
                                                <thead class="thead-themed">
                                                    <tr id="attributeId">
                                                    </tr>
                                                    <tr id="attributeId1">
                                                    </tr>
                                                </thead>
                                                <tbody id="studenInfo">

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

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $(".select2").select2();
            <?php if(!empty($request->academic_year_id) && !empty($request->class_id)){ ?>
            $(".search-section").trigger('change');
            <?php
            }
            ?>

            $(document).on('keyup', '.mks-input', function() {
                var inputVal = parseFloat($(this).val() || 0);
                var sid = $(this).attr('sid');
                var setupMks = parseFloat($(this).attr('totalMks'));
                var attrId = $(this).attr('attrId');
                var subjectName = $(this).attr('subjectName');
                var totalMark = 0;
                var totalError = 0;

                // Update the error message based on subjectName
                if (inputVal > setupMks) {
                    $('#inputError_' + subjectName + '_' + sid + '_' + attrId).text(
                        'Can not be less than setup number');
                    $('#errorHandleId_' + subjectName + '_' + sid + '_' + attrId).val(1);
                } else {
                    $('#inputError_' + subjectName + '_' + sid + '_' + attrId).text('');
                    $('#errorHandleId_' + subjectName + '_' + sid + '_' + attrId).val(0);
                }

                // Calculate totalMark for each subject
                $('.attribute_mark_' + sid).each(function() {
                    var currentSubjectName = $(this).attr('subjectName');
                    if (currentSubjectName === subjectName) {
                        totalMark += parseFloat($(this).val() || 0);
                    }
                });

                // Update the totalMark input field
                $("#totalMark_" + subjectName + '_' + sid).val(totalMark);

                // Update checkbox state based on totalMark
                if (totalMark > 0) {
                    $("#checkStudent_" + sid).prop('checked', true);
                } else {
                    $("#checkStudent_" + sid).prop('checked', false);
                }

                // Calculate totalError
                $('.error-handle').each(function() {
                    totalError += parseFloat($(this).val());
                });

                // Disable or enable save button based on totalError
                if (totalError > 0) {
                    $(".save-btn").prop('disabled', true);
                } else {
                    $(".save-btn").prop('disabled', false);
                }
                if ($('.save-btn').prop('disabled') === false) {
                    setTimeout(function() {
                        submitInvisibleForm();
                    }, 60000);
                }
            });

            $(document).on('change', '.absent_checkbox', function() {
                var keyValue = $(this).attr('keyvalue');
                $("#checkStudent_" + keyValue).prop('checked', true);
                if ($('.save-btn').prop('disabled') === false) {
                    setTimeout(function() {
                        submitInvisibleForm();
                    }, 60000);
                }
            });


            $(document).on('change', '#subjectId', function() {
                $("#subjectError").text('');
                $('#attributeId').html('');
                $('#studenInfo').html('');
            });
            $(document).on('change', '#examId', function() {
                $("#examError").text('');
                $('#attributeId').html('');
                $('#studenInfo').html('');
            });
            $(document).on('change', '#sectionID', function() {
                $('#attributeId').html('');
                $('#studenInfo').html('');
            });
            $(document).on('change', '#academicYearId', function() {
                $('#attributeId').html('');
                $('#studenInfo').html('');
            });

        });


        function unCheck(id) {
            if ($('#' + id).is(':not(:checked)')) {
                $("#chkbxAll").prop("checked", false);
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

        function checkAll() {
            if ($('#chkbxAll').is(':checked')) {
                $(".allCheck").each(function(index) {
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
        // Section Change on select Class & Academic Year
        function getSection() {
            $("#classError").text('');
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
                        $('#attributeId').html('');
                        $('#studenInfo').html('');
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
                            '<option value="all">All</option>');
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

        function getMarkHeader() {
            var classId = $('#class_id').val();
            var yearId = $('#academicYearId').val();
            var examId = $('#examId').val();
            var sectionId = $('#sectionID').val();
            var subjectId = $('#subjectId').val();
            var html = '';
            if (classId != 0 && yearId != 0 && examId != 0 && subjectId != 0) {
                $(".show-table").css('display', 'block');
                $.ajax({
                    url: "{{ url('get-mark-header') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        classId: classId,
                        yearId: yearId,
                        sectionId: sectionId,
                        subjectId: subjectId,
                        examId: examId,
                    },
                    success: function(response) {
                        $('#attributeId').html(response.attribute);
                        $('#attributeId1').html(response.attribute1);
                        $('#studenInfo').html(response.data);
                    }
                });
            } else {
                if (classId != 0) {
                    $("#classError").text('');
                } else {
                    $("#classError").text('please select class');
                }
                if (examId != 0) {
                    $("#examError").text('');
                } else {
                    $("#examError").text('please select exam')

                }
                if (subjectId != '') {
                    $("#subjectError").text('');
                } else {
                    $("#subjectError").text('please select subject')

                }

            }
        }




        // Function to submit the form using AJAX
        function submitInvisibleForm() {
            $.ajax({
                type: 'POST',
                url: $('#saveMarks').attr('action'),
                data: $('#saveMarks').serialize(),
                success: function(response) {
                    // Handle success response if needed
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Handle error if needed
                }
            });
        }
    </script>
@endsection
