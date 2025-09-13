@extends('Admin::layouts.master')

@section('body')
    <style>
          div label {

            size: 60px;
            padding: 20px;
            margin-bottom: 10px;
           
        }
        a[target]:not(.btn) {
            text-decoration: none !important;
        }

        #generateButton {
            display: none;
        }

        .generate-button {
            display: block;
            position: fixed;
            bottom: 30px;
            right: 40px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Mark::label.MARKS')</< /li>
        <li class="breadcrumb-item active">@lang('Mark::label.MARK_SHEET')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Mark::label.MARK_SHEET')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right btn-sm">@lang('Certificate::label.BACK')</a>
    </div>
    <div class="row clearfix">
        <div class="block-header block-header-2">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" id="notPrintDiv">
                        <div class="col-md-5 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        Academic Year</label>
                                    {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                        'id' => 'academicYearId',
                                        'class' => 'form-control select2 academic-year-id',
                                        'onchange' => 'getSection();getExam();',
                                    ]) !!}
                                    <span class="error" id="yearError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.CLASS')</label><span class="required">
                                        *</span>
                                    {!! Form::Select('class_id', $classList, !empty($request->class_id) ? $request->class_id : null, [
                                        'id' => 'class_id',
                                        'class' => 'form-control class-id select2',
                                        'onchange' => 'getSection();',
                                    ]) !!}
                                    <span class="error" id="classError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.SECTION')</label>
                                    <select name="section_id" id="section_id" class="form-control select2">
                                        <option value='0'>@lang('Certificate::label.SELECT') @lang('Certificate::label.SECTION')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="examId" class='col-form-label'>Select Exam</label><span class="required">*</span>
                                    {!! Form::Select('exam_id', $examList, null, [
                                        'id' => 'examId',
                                        'class' => 'form-control class-id select2 search-section',
                                    ]) !!}
                                </div>
                                <span class="error" id="examError"></span>
                            </div>
                        </div>

                        <div class="col-md-1 form-data">
                            <div class="panel-content align-items-center">

                                <button class="btn btn-primary ml-auto mt-5 waves-effect waves-themed btn-sm" type="submit" onclick="getExamList()"
                                    id="searchBtn"><i class="fas fa-search pr-1"></i>@lang('Certificate::label.SEARCH')</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    {!! Form::open([
                        'id' => 'transferCertificate',
                        'route' => 'student.marksheet.generate',
                        'class' => 'form-horizontal',
                        'target' => '_blank',
                        'required' => 'required'
                    ]) !!}
                        <div class="row" id="subjectRow">

                        </div>
                    <div class="body">
                        <div class="">
                            <div class="table-responsive">
                               
                                <table class="table table-bordered table-striped myTable show-table" id="yajraTable">
                                    <thead class="">
                                        <tr>
                                            <th>Sl</th>
                                            <th width="8%">Check All<input type="checkbox" id="chkbxAll"
                                                    class="all-check-box" onclick="return checkAll();"
                                                    style="margin-left: 5px;">
                                            </th>
                                            <th width="20%" class="">Student Name</th>
                                            <th width="20%" class="">Roll No</th>
                                            <th width="20%" class="">Class
                                            </th>
                                            <th width="10%" class="">Exam Name</th>
                                            <th width="20%" class="">Section</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <input type="hidden" name="session_id" id="session_id">
                                <input type="hidden" name="student_class_id" id="student_class_id">
                                <input type="hidden" name="student_exam_id" id="student_exam_id">
                                <div class="float-right">
                                    <button type="submit"
                                        class="btn btn-success ml-auto mt-5 waves-effect waves-themed generate-button btn-sm"
                                        id="generateButton" onclick="validateForm()">@lang('Certificate::label.GENERATE')</button>
                                </div>
                                <script></script>
                                {!! Form::close() !!}
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
    </div>
    </body>
    <script>
        $(function() {
            $(document).on('click', '#searchBtn', function() {
                $('#session_id').val('');
                $('#student_class_id').val('');
                $('#student_exam_id').val('');
                $('#chkbxAll').prop('checked', false);
                if ($("#class_id").val() != 0 && $("#academicYearId").val() != 0 && $("#examId").val() !=
                    0) {
                    $('#session_id').val($("#academicYearId").val());
                    $('#student_class_id').val($("#class_id").val());
                    $('#student_exam_id').val($("#examId").val());
                    $('#yearError').html('');
                    $('#classError').html('');
                    $(".show-table").css("display", "block");
                    $("#generateButton").css("display", "block");
                    $(".certificate-generate").css("display", "block");
                    var table = $('#yajraTable').DataTable({
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        iDisplayLength: 50,
                        ajax: {
                            "url": "{{ route('student.marksheet.index') }}",
                            "data": function(e) {
                                e.class_id = $("#class_id").val();
                                e.section_id = $("#section_id").val();
                                e.academicYearId = $("#academicYearId").val();
                                e.examId = $("#examId").val();
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'checkbox',
                                name: 'checkbox',
                                orderable: false,
                                searchable: false,
                                "className": "table-checkbox-column",
                            },
                            {
                                data: 'full_name',
                                name: 'full_name'
                            },
                            {
                                data: 'class_roll',
                                name: 'class_roll'
                            },
                            {
                                data: 'class_name',
                                name: 'class_name'
                            },
                            {
                                data: 'exam_name',
                                name: 'exam_name'
                            },
                            {
                                data: 'section_name',
                                name: 'section_name'
                            }
                        ],
                        "bDestroy": true,
                        select: {
                            style: 'single'
                        }
                    });
                } else {
                    if ($("#class_id").val() == 0) {
                        $('#classError').html('Please Select a Class');
                    }
                    if ($("#academicYearId").val() == 0) {
                        $('#yearError').html('Please Select a Session');
                    }
                    if ($("#examId").val() == 0) {
                        $('#examError').html('Please Select a Exam');
                    }
                }
            });

              $(".select2").select2();
                var elements = $("input[type!='submit'], textarea, select");
                elements.focus(function() {
                    $(this).parents('li').addClass('highlight');
                });

                elements.blur(function() {
                    $(this).parents('li').removeClass('highlight');
                });

                $(document).on('click', '.generate-button', function(e) {
                    e.preventDefault();
                    var countChecked = 0;
                    var countCheckedtwo = 0;
                    $(".allCheck").each(function(index) {
                        if ($(this).prop('checked')) {
                            countChecked++;
                        }
                    });
                        $(document).find('.allChecktwo').each(function(index) {
                            if ($(this).prop('checked')) {
                                countCheckedtwo++;
                            }
                        });
                    if (countChecked > 0 && countCheckedtwo > 0) {
                        $("#transferCertificate").submit();
                        return true;
                    } else {
                        alert("Please select one student or attribute.");
                        return false;
                    }
                });

            $("#class_id").change(function() {
                $("#classError").html('');
            });
            $("#examId").change(function() {
                $("#examError").html('');
            });
        });

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
                            '<option value="0">Select Section</option>');
                        $.each(response, function(key, data) {
                            $('select[name="section_id"]').append(
                                '<option value="' + data
                                .id + '">' + data.name + '</option>');
                        });
                    }
                });
            }
        }

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
        function checkAlltwo() {
            if ($('#chkbxAlltwo').is(':checked')) {
                $(".allChecktwo").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#examId_" + key)[0].checked == false) {
                        $("#examId_" + key).prop('checked', true);
                    }
                }); 
            } else {
                $(".allChecktwo").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#examId_" + key)[0].checked == true) {
                        $("#examId_" + key).prop('checked', false);
                    }
                });
            }
        }
        function unCheck(id) {
            if ($('#' + id).is(':not(:checked)')) {
                $("#chkbxAll").prop("checked", false);
            }
        }
        function unChecktwo(id) {
            if ($('#' + id).is(':not(:checked)')) {
                $("#chkbxAlltwo").prop("checked", false);
            }
        }

        function isChecked() {
            var countNotChecked = 0;
            $(".allCheck").each(function(index) {
                if ($(this)[0].checked == false) {
                    countNotChecked++;
                }
            });
            if (countNotChecked == 0) {
                $("#chkbxAll").prop("checked", true);
            }
            return countNotChecked;
        }
        function isCheckedtwo() {
            var countNotChecked = 0;
            $(".allChecktwo").each(function(index) {
                if ($(this)[0].checked == false) {
                    countNotChecked++;
                }
            });
            if (countNotChecked == 0) {
                $("#chkbxAlltwo").prop("checked", true);
            }
            return countNotChecked;
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

        
        const searchButton = document.getElementById('searchBtn');
        const resultDiv = document.getElementById('checkbtn');
        searchButton.addEventListener('click', function() {
            resultDiv.style.display = 'block';
        });

        function getExamList() {
            var classId = $('#class_id').val();
            var examId = $('#examId').val();
            if (classId != 0) {
                $.ajax({
                    url: "{{ url('get-exams') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        classId: classId,
                        examId: examId
                    },
                    // beforeSend: function() {
                    //     $('select[name="section_id"]').empty();
                    // },
                    success: function(response) {
                        $('#subjectRow').html(response.data);
                    }
                });
            }
        }

        function validateForm() {
                const checkboxes = document.getElementById('generateButton');
                let isChecked = false;
                checkboxes.forEach((checkbox) => {
                    if (checkbox.checked) {
                        isChecked = true;
                        return;
                    }
                });

                if (!isChecked) {
                    alert("Please select at least one checkbox.");
                    return false;
                }

                return true;
            }
            

    </script>
@endsection
