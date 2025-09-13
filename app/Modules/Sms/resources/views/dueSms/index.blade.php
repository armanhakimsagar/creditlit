@extends('Admin::layouts.master')

@section('body')
    <style>
        a[target]:not(.btn) {
            text-decoration: none !important;
        }

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
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
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
        <li class="breadcrumb-item active">@lang('Sms::label.DUE') @lang('Sms::label.SMS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Sms::label.DUE') @lang('Sms::label.SMS')
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
                        <div class="col-lg-3 col-md-4 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.SESSION')</label>
                                    {!! Form::Select(
                                        'academic_year_id',
                                        $academicYearList,
                                        !empty($request->academic_year_id) ? $request->academic_year_id : null,
                                        [
                                            'id' => 'academicYearId',
                                            'class' => 'form-control select2 academic-year-id',
                                            'onchange' => 'getSection()',
                                        ],
                                    ) !!}
                                    <span class="error" id="yearError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.CLASS')</label>
                                    {!! Form::Select('class_id', $classList, !empty($request->class_id) ? $request->class_id : null, [
                                        'id' => 'class_id',
                                        'class' => 'form-control class-id select2',
                                        'onchange' => 'getSection()',
                                    ]) !!}
                                    <span class="error" id="classError"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 form-data">
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


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class="col-form-label">@lang('Certificate::label.SELECT')
                                        @lang('Academic::label.SHIFT')</label>
                                    {!! Form::Select('shift_id[]', $shift_list, null, [
                                        'id' => 'shiftId',
                                        'class' => 'form-control select2',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                    <span class="error" id="classError"></span>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="genderId">@lang('Student::label.GENDER')</label>
                                    <select name="gender_id[]" id="genderId" class="form-control select2" multiple>
                                        <option value='male'>@lang('Student::label.BOYS')</option>
                                        <option value='female'>@lang('Student::label.GIRLS')</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="versionId">@lang('Student::label.VERSION')</label>
                                    {!! Form::Select('version_id[]', $versionList, !empty($request->version_id) ? $request->version_id : null, [
                                        'id' => 'versionId',
                                        'class' => 'form-control select2',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="groupId">@lang('Student::label.GROUP')</label>
                                    {!! Form::Select('group_id[]', $groupList, !empty($request->group_id) ? $request->group_id : null, [
                                        'id' => 'groupId',
                                        'class' => 'form-control select2',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="statusId" class='col-form-label'>@lang('Student::label.STATUS')</label>
                                    {!! Form::Select(
                                        'status[]',
                                        [
                                            'active' => 'Active',
                                            'cancel' => 'Cancel',
                                            'inactive' => 'Inactive',
                                        ],
                                        !empty($request->status) ? $request->status : 'active',
                                        [
                                            'id' => 'statusId',
                                            'class' => 'form-control select2',
                                            'multiple' => 'multiple',
                                        ],
                                    ) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 form-data">
                            <div class="panel-content align-items-center">

                                <button class="btn btn-primary ml-auto mt-5 waves-effect waves-themed btn-sm" type="submit"
                                    id="searchBtn" onclick="selectLabel()"><i
                                        class="fas fa-search pr-1"></i>@lang('Certificate::label.SEARCH')</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="student-label-container">
                        <div class="student-label-item"><span>Academic Year: </span><span id="academicLabel">All</span>
                        </div>
                        <div class="student-label-item"><span>Class: </span><span id="classLabel">All</span></div>
                        <div class="student-label-item"><span>Section: </span><span id="sectionLabel">All</span></div>
                        <div class="student-label-item"><span>Shift: </span><span id="shiftLabel">All</span></div>
                        <div class="student-label-item"><span>Gender: </span><span id="genderLabel">All</span></div>
                        <div class="student-label-item"><span>Version: </span><span id="versionLabel">All</span></div>
                        <div class="student-label-item"><span>Group: </span><span id="groupLabel">All</span></div>
                        <div class="student-label-item"><span>status: </span><span id="statusLabel">All</span></div>
                        <div class="student-label-item"><span>Total: </span><span id="totalLabel"></span></div>
                    </div>
                    <br>
                    {!! Form::open([
                        'id' => 'dueMessage',
                        'route' => 'due.send.sms',
                        'class' => 'form-horizontal',
                    ]) !!}
                    <div class="body">
                        <div class="">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped myTable show-table" id="yajraTable">
                                    <thead class="">
                                        <tr>
                                            <th width="2%">Sl</th>
                                            <th width="8%">Check All<input type="checkbox" id="chkbxAll"
                                                    class="all-check-box" onclick="return checkAll();"
                                                    style="margin-left: 5px;">
                                            </th>
                                            <th width="12%" class="">Student Name</th>
                                            <th width="8%" class="">Roll No</th>
                                            <th width="12%">Number</th>
                                            <th width="5%" class="">Class
                                            </th>
                                            <th width="5%" class="">DUE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <input type="hidden" name="session_id" id="session_id">
                                <input type="hidden" name="student_class_id" id="student_class_id">
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
            $("#dueMessage").validate({
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
                $('#session_id').val('');
                $('#student_class_id').val('');
                $('#chkbxAll').prop('checked', false);
                if ($("#academicYearId").val() != 0) {
                    $('#session_id').val($("#academicYearId").val());
                    $('#student_class_id').val($("#class_id").val());
                    $('#yearError').html('');
                    $('#classError').html('');
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
                            "url": "{{ route('due.sms.index') }}",
                            "data": function(e) {
                                e.class_id = $("#class_id").val();
                                e.section_id = $("#section_id").val();
                                e.academicYearId = $("#academicYearId").val();
                                e.genderId = $("#genderId").val();
                                e.versionId = $("#versionId").val();
                                e.groupId = $("#groupId").val();
                                e.shiftId = $("#shiftId").val();
                                e.status = $("#statusId").val();

                            }
                        },
                        "drawCallback": function(settings, start, end, max, total, pre) {
                            $('#totalLabel').text(this.fnSettings()
                                .fnRecordsTotal()); // total number of rows
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
                                name: 'full_name',
                                "className": "table-fullname-column",

                            },
                            {
                                data: 'class_roll',
                                name: 'class_roll'
                            },
                            {
                                data: 'phone',
                                name: 'phone'
                            },
                            {
                                data: 'class_name',
                                name: 'class_name'
                            },
                            {
                                data: 'student_due',
                                name: 'student_due'
                            }
                        ],
                        "bDestroy": true,
                        select: {
                            style: 'single'
                        }
                    });
                } else {
                    if ($("#academicYearId").val() == 0) {
                        $('#yearError').html('Please Select a Session');
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
                $(".allCheck").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                    }
                });
                if (countChecked > 0) {
                    $("#dueMessage").submit();
                    return (true);
                } else {
                    return (false);
                }
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
            $('#academicLabel').empty();
            $('#classLabel').empty();
            $('#sectionLabel').empty();
            $('#genderLabel').empty();
            $('#groupLabel').empty();
            $('#versionLabel').empty();
            $('#shiftLabel').empty();
            $('#statusLabel').empty();
            $('#totalLabel').empty();
            $('#academicLabel').append($("#academicYearId :selected").text());
            $('#classLabel').append($("#class_id :selected").text());
            $('#sectionLabel').append($("#section_id :selected").text());
            $('#statusLabel').append($("#statusId :selected").text());
            var selectedshifts = [];
            $("#shiftId option:selected").each(function() {
                selectedshifts.push($(this).text());
            });
            $('#shiftLabel').text(selectedshifts.join(', '));
            var selectedgenders = [];
            $("#genderId option:selected").each(function() {
                selectedgenders.push($(this).text());
            });
            $('#genderLabel').text(selectedgenders.join(', '));
            var selectedversions = [];
            $("#versionId option:selected").each(function() {
                selectedversions.push($(this).text());
            });
            $('#versionLabel').text(selectedversions.join(', '));
            var selectedgroups = [];
            $("#groupId option:selected").each(function() {
                selectedgroups.push($(this).text());
            });
            $('#groupLabel').text(selectedgroups.join(', '));
        }
    </script>
@endsection
