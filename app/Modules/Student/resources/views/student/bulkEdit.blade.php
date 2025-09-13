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
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr  1fr 1fr;
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

    .dt-buttons{
        float: right;
    }
    .dataTables_filter{
        float: left;
    }
    .show-table{
        display: none;
    }
    .generate-button {
            display: block;
            position: fixed;
            bottom: 30px;
            right: 40px;
        }
    </style>
    <div class="col-md-3l-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Student::label.BULK_PROFILE_UPDATE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i> @lang('Student::label.STUDENT_BULK_PROFILE_UPDATE')
            </h1>

        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Student::label.STUDENT') <span class="fw-300"><i>@lang('Student::label.LIST')</i></span>
                    </h2>
                </div>

            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <form method="POST" action="{{ route('bulk.student.edit.filter') }}" enctype="multipart/form-data">
                        @csrf
                    <div class="row pb-5">
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="academicYearId">@lang('Student::label.ACADEMIC') @lang('Student::label.YEAR')</label>
                                    {!! Form::Select('academic_year_id', $academic_year, Request::get('academic_year_id') ? Request::get('academic_year_id') : (!empty($currentYear) ? $currentYear->id : null), [
                                        'id' => 'academicYearId',
                                        'class' => 'form-control selectheighttype',
                                        'onchange' => 'getSection()',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('academic_year_id') !!}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="classId">@lang('Student::label.CLASS')</label>
                                    {!! Form::Select('class_id', $classList, Request::get('class_id') ? Request::get('class_id') : null, [
                                        'id' => 'classId',
                                        'class' => 'form-control selectheighttype',
                                        'onchange' => 'getSection()',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('class_id') !!}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="sectionId">@lang('Student::label.SECTION')</label>
                                    <select name="section_id" id="sectionId" class="form-control select2">
                                        <option value=''>Select Section</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="updateType">@lang('Student::label.UPDATE_TYPE')</label>
                                    {!! Form::Select('update_type', ['' => 'Select Update Type','1'=> 'Student Academic Info','2'=> 'Student Primary Info','3'=> "Student's Father Info",'4'=> "Student's Mother Info",'5'=> "Student's Guardian Info"], Request::get('update_type') ? Request::get('update_type') : null, [
                                        'id' => 'updateType',
                                        'class' => 'form-control select2'
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('update_type') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 float-right">
                            <button class="btn btn-primary btn-sm mt-4 waves-effect waves-themed" type="submit"
                                id="searchBtn" onclick="selectLabel()"><i
                                    class="fas fa-search pr-1"></i>@lang('Student::label.SEARCH')</a></button>
                        </div>
                    </div>
                    </form>

                    <div class="panel-container">
                        <div class="panel-content">
                            <div class="frame-wrap">
                                <form action="{{ route('bulk.student.update') }}" enctype="multipart/form-data" id="monthlyItem" method="POST">
                                    @csrf
                                <input type="hidden" name="update_type" value="{{Request::get('update_type') ? Request::get('update_type') : null}}">
                                <input type="hidden" name="academic_year_id" value="{{Request::get('academic_year_id') ? Request::get('academic_year_id') : null}}">
                                <input type="hidden" name="class_id" value="{{Request::get('class_id') ? Request::get('class_id') : null}}">
                                {!! $html !!}
                                <td><button id="submitBtn"
                                    class="btn btn-primary btn-sm generate-button float-right mb-3 mr-3" type="submit">save</button></td>
                                </form>
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
                $("#updateType").select2();

                if($('#classId').val() > 0){
                    getSection();
                }
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
                            var sectionSelected = "{{ Request::get('section_id') ? Request::get('section_id') : 0 }}";
                            if(sectionSelected > 0){
                              $('select[name="section_id"]').val(sectionSelected);
                            }
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

        function checkAll() {
            if ($('#chkbxAll').is(':checked')) {
                $(".allCheck").each(function(index) {
                    var key = $(this).attr('keyvalue');

                    if ($("#student_" + key)[0].checked == false) {
                        $("#student_" + key).prop('checked', true);
                    }
                });
            } else {
                $(".allCheck").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#student_" + key)[0].checked == true) {
                        $("#student_" + key).prop('checked', false);
                    }
                });
            }
        }
        $(document).on('click','#submitBtn',function(e){
            e.preventDefault();
            var countNotChecked = 0;
            var countChecked = 0;
            $(".allCheck").each(function(index) {
                if ($(this)[0].checked == true) {
                    countChecked++;
                }
            });
            if(countChecked == 0){
                alert('Please check atleast one student');
                return false;
            }else{
                $('form').submit();
            }
        });
        </script>
    @endsection
