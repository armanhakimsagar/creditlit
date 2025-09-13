@extends('Admin::layouts.master')
@section('body')
    <style>
        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        .student-label-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
            border: 1px solid #ddd;
            padding: 10px 0px;
            margin-bottom: 20px;
            background-color: #497174;
        }

        .student-label-item {
            padding: 0px 2px;
            color: #fff;
        }

        a[target]:not(.btn) {
            color: #000;
            text-decoration: none !important;
        }

        .dt-buttons {
            float: right;
        }

        .dataTables_filter {
            float: left;
        }

        .show-table {
            display: none;
        }
        .table{
            font-size:14px; 
        }
        .font-increase {
            font-size:{{ $companyDetails->ReportFontSize }}px; 
        }
    </style>
    <div class="col-md-3l-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Student::label.STUDENT') @lang('Student::label.DETAILS')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i> @lang('Student::label.STUDENT') @lang('Student::label.DETAILS')
            </h1>
            <a style="margin-left: 10px;" href="{{ route('student.create') }}"
                class="btn btn-primary btn-sm waves-effect pull-right">@lang('Student::label.ADD') @lang('Student::label.STUDENT')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Student::label.STUDENT') <span class="fw-300"><i>@lang('Student::label.LIST')</i></span>
                    </h2>
                </div>
                <div class="col-8">
                    <a href="{{ route('student.trash') }}" class="btn btn-danger btn-sm waves-effect float-right"
                        data-toggle="tooltip" data-placement="top" title="Trash"><i class="fas fa-trash-restore"></i></a>
                </div>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row pb-5">
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="academicYearId">@lang('Student::label.ACADEMIC') @lang('Student::label.YEAR')</label>
                                    {!! Form::Select('academic_year_id', $academic_year, !empty($currentYear) ? $currentYear->id : null, [
                                        'id' => 'academicYearId',
                                        'class' => 'form-control selectheighttype',
                                        'onchange' => 'getSection()',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('first_name') !!}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="classId">@lang('Student::label.CLASS')</label>
                                    {!! Form::Select('class_id[]', $classList,!empty($request->class_id) ? $request->class_id : null, [
                                        'id' => 'classId',
                                        'class' => 'form-control selectheighttype',
                                        'onchange' => 'getSection()',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="sectionId">@lang('Student::label.SECTION')</label>
                                    <select name="section_id[]" id="sectionId" class="form-control select2" multiple>
                                        <option value='0'></option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="genderId">@lang('Student::label.GENDER')</label>
                                    <select name="gender_id" id="genderId" class="form-control select2">
                                        <option value='0'>@lang('Student::label.ALL')</option>
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
                                    {!! Form::Select('group_id', $groupList, !empty($request->group_id) ? $request->group_id : null, [
                                        'id' => 'groupId',
                                        'class' => 'form-control select2',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="transportId">@lang('Certificate::label.SELECT') @lang('Academic::label.TRANSPORT')</label>
                                    {!! Form::Select(
                                        'transport_id[]',
                                        $transportList,
                                        !empty($request->transport_id) ? $request->transport_id : null,
                                        [
                                            'id' => 'transportId',
                                            'class' => 'form-control select2',
                                            'multiple' => 'multiple',
                                        ],
                                    ) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="">@lang('Certificate::label.SELECT')
                                        @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')</label>
                                    {!! Form::Select('student_type_id[]', $studentTypeList, !empty($request->studentTypeId) ? $request->studentTypeId : null, [
                                        'id' => 'studentTypeId',
                                        'class' => 'form-control select2',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                    <span class="error" id="studentTypeError"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="">@lang('Certificate::label.SELECT')
                                        @lang('Academic::label.SHIFT')</label>
                                    {!! Form::Select('shift_id[]', $shift_list, !empty($request->shift_id) ? $request->shift_id : null, [
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
                                    <label for="admissionTypeId">@lang('Student::label.ADMISSION') @lang('Student::label.TYPE')</label>
                                    <select name="admission_type" id="admissionTypeId" class="form-control admission-type">
                                        <option value='0'>@lang('Student::label.ALL')</option>
                                        <option value='1' {{ !empty($request->admission_type) ? 'selected' : '' }}>
                                            @lang('Student::label.NEW')</option>
                                        <option value='2' {{ !empty($request->admission_type) ? 'selected' : '' }}>
                                            @lang('Student::label.OLD')</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="statusId">@lang('Student::label.STATUS')</label>
                                    {!! Form::Select(
                                        'status',
                                        [
                                            '' => 'All',
                                            'active' => 'Active',
                                            'cancel' => 'Cancel',
                                            'inactive' => 'Inactive',
                                        ],
                                        !empty($request->status) ? 'selected' : 'active',
                                        [
                                            'id' => 'statusId',
                                            'class' => 'form-control select2',
                                        ],
                                    ) !!}
                                </div>
                            </div>
                        </div>



                        <div class="form-group col-lg-4 col-md-4">
                            <button class="btn btn-primary btn-sm mt-4 waves-effect waves-themed" type="submit"
                                id="searchBtn" onclick="selectLabel()"><i
                                    class="fas fa-search pr-1"></i>@lang('Student::label.SEARCH')</a></button>
                        </div>
                    </div>

                    <div class="student-label-container">
                        <div class="student-label-item"><span>Academic Year: </span><span id="academicLabel">All</span>
                        </div>
                        <div class="student-label-item"><span>Class: </span><span id="classLabel">All</span></div>
                        <div class="student-label-item"><span>Section: </span><span id="sectionLabel">All</span></div>
                        <div class="student-label-item"><span>Gender: </span><span id="genderLabel">All</span></div>
                        <div class="student-label-item"><span>Version: </span><span id="versionLabel">All</span></div>
                        <div class="student-label-item"><span>Group: </span><span id="groupLabel">All</span></div>
                        <div class="student-label-item"><span>Transport: </span><span id="transportLabel">All</span></div>
                        <div class="student-label-item"><span>Shift: </span><span id="shiftLabel">All</span></div>
                        <div class="student-label-item"><span>Admission type: </span><span
                                id="admissionTypeLabel">All</span></div>
                        <div class="student-label-item"><span>Total: </span><span id="totalLabel"></span></div>
                    </div>

                    <div class="panel-container show-table font-increase">
                        <div class="panel-content">
                            <div class="frame-wrap">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped ytable" id="yajraTable">
                                        <thead class="thead-themed">
                                            <tr>
                                                <th> Sl</th>
                                                <th> SID</th>
                                                <th> Roll No</th>
                                                <th> Student Name</th>
                                                <th> Class Name</th>
                                                <th> Guardian Name</th>
                                                <th> Guardian Phone Number</th>
                                                <th> Gender</th>
                                                <th> Status</th>
                                                <th> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- AJAX -->

        <script type="text/javascript">
            // Select2 use
            $(function() {
                $("#academicYearId").select2();
                $("#classId").select2();
                $("#sectionId").select2();
                $("#versionId").select2();
                $("#groupId").select2();
                $(".admission-type").select2();
                $("#transportId").select2();
                $("#shiftId").select2();
                $("#studentTypeId").select2();
                $("#statusId").select2();
            });

            // Product table index
            $(function product() {
                $('table').on('draw.dt', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                $(document).on('click', '#searchBtn', function() {
                    $('#yearError').html('');
                    $('#classError').html('');
                    $(".show-table").css("display", "block");
                    $("#generateButton").css("display", "block");
                    var table = $('#yajraTable').DataTable({
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        paging: true,
                        searchDelay: 1000,
                        dom: 'lBfrtip',
                        "ajax": {
                            "url": "{{ route('student.index') }}",
                            "data": function(e) {
                                e.academicYearId = $("#academicYearId").val();
                                e.classId = $("#classId").val();
                                e.sectionId = $("#sectionId").val();
                                e.genderId = $("#genderId").val();
                                e.admissionTypeId = $("#admissionTypeId").val();
                                e.versionId = $("#versionId").val();
                                e.groupId = $("#groupId").val();
                                e.transportId = $("#transportId").val();
                                e.shiftId = $("#shiftId").val();
                                e.studentTypeId = $("#studentTypeId").val();
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
                                data: 'contact_id',
                                name: 'contact_id'
                            },
                            {
                                data: 'class_roll',
                                name: 'class_roll'
                            },
                            {
                                data: 'student_name',
                                name: 'student_name'
                            },
                            {
                                data: 'class_name',
                                name: 'class_name'
                            },
                            {
                                data: 'guardian_name',
                                name: 'guardian_name'
                            },
                            {
                                data: 'guardian_number',
                                name: 'guardian_number'
                            },
                            {
                                data: 'gender',
                                name: 'gender'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false

                            },
                        ],
                        "bDestroy": true,
                        select: {
                            style: 'single'
                        },
                        "responsive": true,
                        "autoWidth": false,
                        "buttons": [{
                                extend: 'csvHtml5',
                                text: 'CSV',
                                filename: 'Student_list',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                titleAttr: 'Generate CSV',
                                className: 'btn-outline-info btn-sm mr-1'
                            },
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                filename: 'Student_list',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                titleAttr: 'Generate Excel',
                                className: 'btn-outline-success btn-sm mr-1'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                filename: 'Student_list',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                titleAttr: 'Generate PDF',
                                className: 'btn-outline-danger btn-sm mr-1'
                            },
                            {
                                extend: "print",
                                title: 'Student_list',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                },
                                titleAttr: 'Generate print',
                                className: 'btn-outline-info btn-sm mr-1'
                            },
                        ]
                    });

                    $('#yajraTable_filter input').unbind();
                    $('#yajraTable_filter input').bind('keyup', function(e) {
                        if (e.keyCode === 13) {
                            $('#yajraTable').DataTable().search(this.value).draw();
                        }
                    });
                });
            });


            // Section Change on select Class
            function getSection() {
                var classId = $('#classId').val()
                var yearId = $('#academicYearId').val();
                // alert(classId)
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
                            $('select[name="section_id[]"]').empty();
                        },
                        success: function(response) {
                            $.each(response, function(key, data) {
                                $('select[name="section_id[]"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.name + '</option>');
                            });
                        }
                    });
                }
            }


            // Student details label
            function selectLabel() {
                $('#academicLabel').empty();
                $('#classLabel').empty();
                $('#sectionLabel').empty();
                $('#genderLabel').empty();
                $('#admissionTypeLabel').empty();
                $('#versionLabel').empty();
                $('#groupLabel').empty();
                $('#transportLabel').empty();
                $('#shiftLabel').empty();
                $('#totalLabel').empty();
                $('#academicLabel').append($("#academicYearId :selected").text());
                $('#classLabel').append($("#classId :selected").text());
                $('#sectionLabel').append($("#sectionId :selected").text());
                $('#genderLabel').append($("#genderId :selected").text());
                $('#admissionTypeLabel').append($("#admissionTypeId :selected").text());
                $('#versionLabel').append($("#versionId :selected").text());
                $('#groupLabel').append($("#groupId :selected").text());
                $('#transportLabel').append($("#transportId :selected").text());
                $('#shiftLabel').append($("#shiftId :selected").text());
            }
        </script>
    @endsection
