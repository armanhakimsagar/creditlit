@extends('Admin::layouts.master')

@section('body')
    <style>
        #generateButton {
            display: none;
        }

        .sms-field {
            display: none;
        }

        .generate-button {
            display: block;
            position: fixed;
            bottom: 30px;
            right: 40px;
        }
        .student-label-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            border: 1px solid #ddd;
            padding: 10px 0px;
            margin-bottom: 20px;
            background-color: #497174;
        }

        .student-label-item {
            padding: 0px 2px;
            color: #fff;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Sms::label.SMS')</li>
        <li class="breadcrumb-item active">@lang('Sms::label.TEACHER') @lang('Sms::label.SMS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Sms::label.TEACHER') @lang('Sms::label.SMS')
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
                        
                        <div class="col-md-4 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Stuff::label.DEPARTMENT')</label>
                                    {!! Form::Select('department_id', $department, null, [
                                        'id' => 'departmentId',
                                        'class' => 'form-control class-id select2'
                                    ]) !!}
                                    <span class="error" id="classError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Stuff::label.DESIGNATION')</label>
                                    {!! Form::Select(
                                        'designation_id',
                                        $designation,
                                        !empty($designation) ? $designation : null,
                                        [
                                            'id' => 'designationId',
                                            'class' => 'form-control select2 academic-year-id'
                                        ],
                                    ) !!}
                                    <span class="error" id="yearError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-data">
                            <div class="panel-content align-items-center">

                                <button class="btn btn-primary ml-auto mt-5 waves-effect waves-themed btn-sm" type="submit"
                                    id="searchBtn" onclick="selectLabel()"><i class="fas fa-search pr-1"></i>@lang('Certificate::label.SEARCH')</button>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="student-label-container">
                        <div class="student-label-item"><span>Department: </span><span id="departmentLabel">All</span>
                        </div>
                        <div class="student-label-item"><span>Designation: </span><span id="designationLabel">All</span></div>
                        <div class="student-label-item"><span>Total: </span><span id="totalLabel"></span></div>
                    </div>
                    <br>
                    {!! Form::open([
                        'id' => 'teacherMessage',
                        'route' => 'teacher.send.sms',
                        'class' => 'form-horizontal',
                    ]) !!}
                    <div class="body">
                        <div class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="smsId" class="sms-field">@lang('Sms::label.MESSAGE')</label>
                                    <textarea name="sms" required style="width:100%" class="sms-field sms-input" id="smsId" cols="20"
                                        placeholder="ইংরেজিতে SMS লেখার সময় ইউনিকোড (বাংলা) এর কোনো চিহ্ন বা বর্ণ ব্যবহার করলে তা বাংলা SMS হিসবে গননা করা হবে। SMS পাঠানোর পূর্বে নিচে উল্লিখিত SMS সংখ্যাটি দেখে নিবেন।"
                                        rows="5"></textarea>
                                    <span class="error"></span>
                                </div>
                                <div class="col-md-6">
                                    <br>
                                    <div class="card card-body sms-field"
                                        style="background: rgba(143, 153, 153, 0.1);line-height: 50%;">
                                        <p>[English] <b>1-160</b>characters = <b>1 SMS</b> | <b>161-300</b> characters =
                                            <b>2 SMS</b>
                                        </p>
                                        <p>[English] <b>301-440</b> characters = <b>3 SMS</b></p>
                                        <p>[Bangla] <b>1-69</b> characters = <b>1 SMS</b> | <b>70-133</b> characters = <b>2
                                                SMS</b></p>
                                        <p>[Bangla] <b>134-200</b> characters = <b>3 SMS</b></p>
                                    </div>
                                </div>
                                <input type="hidden" name ="sms_count" id="smsCount" value="">
                                <div class="sms-field">
                                    <ul>
                                        <li>Characters: <span id="character">0</span></li>
                                        <li>SMS: <span id="smsnums">0</span></li>
                                        <li>Per SMS: <span id="persms">160</span></li>
                                        <li>Remaining: <span id="remaining">160</span></li>
                                    </ul>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped myTable show-table" id="yajraTable">
                                    <thead class="">
                                        <tr>
                                            <th width="2%">Sl</th>
                                            <th width="8%">Check All<input type="checkbox" id="chkbxAll"
                                                    class="all-check-box" onclick="return checkAll();"
                                                    style="margin-left: 5px;">
                                            </th>
                                            <th width="12%" class="">Employee Name</th>
                                            <th width="12%">Mobile Number</th>
                                            <th width="5%" class="">Department</th>
                                            <th width="5%" class="">Designation</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <input type="hidden" name="department_id" id="department_id">
                                <input type="hidden" name="designation_id" id="designation_id">
                                <div class="float-right"><button type="submit"
                                        class="btn btn-success ml-auto mt-5 waves-effect waves-themed generate-button btn-sm"
                                        id="generateButton">@lang('Sms::label.SEND') @lang('Sms::label.MESSAGE')</button>
                                </div>
                                <script></script>
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
    </div>
    </div>
    </body>
    <script>
        $(function() {
            $("#teacherMessage").validate({
                rules: {
                    smsId: {
                        required: true,
                    }
                },
                messages: {
                    smsId: 'Please enter Message'
                }
            });
            $(document).on('click', '#searchBtn', function() {
                $('#chkbxAll').prop('checked', false);
                    $('#department_id').val($("#departmentId").val());
                    $('#designation_id').val($("#designationId").val());
                    $(".show-table").css("display", "block");
                    $(".sms-field").css("display", "block");
                    $("#generateButton").css("display", "block");
                    $(".certificate-generate").css("display", "block");
                    var table = $('#yajraTable').DataTable({
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        iDisplayLength: 50,
                        ajax: {
                            "url": "{{ route('teacher.sms') }}",
                            "data": function(e) {
                                e.department_id = $("#departmentId").val();
                                e.designation_id = $("#designationId").val();
                                
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
                                data: 'cp_phone_no',
                                name: 'cp_phone_no'
                            },
                            {
                                data: 'department_name',
                                name: 'department_name'
                            },
                            {
                                data: 'designation_name',
                                name: 'designation_name'
                            }
                        ],
                        "bDestroy": true,
                        select: {
                            style: 'single'
                        }
                    });
               
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
                $(".allCheck").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                    }
                });
                if (countChecked > 0) {
                    $("#teacherMessage").submit();
                    return (true);
                } else {
                    return (false);
                }
            });
            $(document).on('input', '.sms-input', function() {
                var sms = $(this).val();
                var smsLength = sms.length;
                var banglaLetters = 0;
                for (var i = 0; i < smsLength; i++) {
                    var letterCode = sms.charCodeAt(i);
                    console.log(letterCode);
                    if (letterCode >= 2432 && letterCode <= 2559) {
                        banglaLetters++;
                    }
                }
                $('#character').html('');
                $('#persms').html('');
                $('#remaining').html('');
                $('#smsnums').html('');
                if (banglaLetters > 0) {
                    var perSms = smsLength / 67;
                    var remainingL = smsLength % 67;
                    var remainingLength = 67 - remainingL;
                    perSms = Math.ceil(perSms);
                    $('#persms').html('67');
                    $('#smsnums').html(perSms);
                    $('#smsCount').val(perSms);
                    $('#remaining').html(remainingLength);
                } else {
                    var perSms = smsLength / 153;
                    var remainingL = smsLength % 153;
                    var remainingLength = 153 - remainingL;
                    perSms = Math.ceil(perSms);
                    $('#persms').html('153');
                    $('#smsnums').html(perSms);
                    $('#smsCount').val(perSms);
                    $('#remaining').html(remainingLength);
                }
                $('#character').html(smsLength);
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

        function unCheck(id) {
            if ($('#' + id).is(':not(:checked)')) {
                $("#chkbxAll").prop("checked", false);
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
        function selectLabel() {
                $('#departmentLabel').empty();
                $('#designationLabel').empty();
                $('#totalLabel').empty();
                $('#departmentLabel').append($("#departmentId :selected").text());
                $('#designationLabel').append($("#designationId :selected").text());
            }
    </script>
@endsection
