@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.DETAILS')</li>
            <li class="breadcrumb-item active">@lang('Student::label.STUDENT') @lang('Student::label.READMISSION')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Student::label.STUDENT') List
            </h1>
            <a href="{{ route('student.index') }}"
                class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">@lang('Student::label.ALL') @lang('Student::label.STUDENT')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Student::label.STUDENT') <span class="fw-300"><i>List</i></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="panel-content">

                    <div class="table-responsive">
                        {!! Form::open([
                            'route' => 'student.readmission.create',
                            'id' => 'reAdmission',
                            'class' => 'form-horizontal',
                            'method' => 'POST',
                            'files' => true,
                        ]) !!}
                        <table class="table table-bordered table-striped" id="yajraTable">
                            <thead class="thead-themed">
                                <th> Sl</th>
                                <th> Student Name</th>
                                <th> SID</th>
                                <th> Roll No</th>
                                <th> Session Year</th>
                                <th> Class Name</th>
                                <th> Section</th>
                                <th> Status</th>
                                <th> New Roll</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ $student->SID }}</td>
                                    <td>{{ $student->class_roll }}</td>
                                    <td>{{ $student->year }}</td>
                                    <td>{{ $student->class_name }}</td>
                                    <td>{{ $student->section_name }}</td>
                                    <td>
                                        @if ($student->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif ($student->status == 'inactive')
                                            <span class="badge badge-warning">Inactive</span>
                                        @else
                                            <span class="badge badge-danger">Cancel</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control new_roll"id="newRoll" 
                                                name="new_roll" placeholder="Enter new roll number">
                                            <span class="error" id="error_newRoll"></span>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
       
                        </table>
                        <div id="generateSave">
                            <br><br>
                            <h2 class=" text-center">Readmission/Promotion to new class info</h2>
                            <div class="form-group ">
                                <div class="form-line">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                @lang('Certificate::label.SESSION')</label>
                                            {!! Form::Select('academic_year_id', $academicYearList, null, [
                                                'id' => 'academicYearId',
                                                'class' => 'form-control  select2',
                                                'onchange' => 'getSection2();getItemPrice()',
                                                'style' => 'width: 100%;',
                                            ]) !!}
                                            <span class="error" id="sessionError"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                @lang('Certificate::label.CLASS')</label>
                                            {!! Form::Select('class_id', $classList, null, [
                                                'id' => 'classID',
                                                'class' => 'form-control select2',
                                                'onchange' => 'getSection2();getItemPrice()',
                                                'style' => 'width: 100%;',
                                            ]) !!}
                                            <span class="error" id="newClassError"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                @lang('Certificate::label.SECTION')</label>
                                            <select name="section_id" id="section_id" class="form-control select2">
                                                <option value='0'>@lang('Certificate::label.SELECT') @lang('Certificate::label.SECTION')
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                @lang('Academic::label.GROUP')</label>
                                            {!! Form::Select('group_id', $group_list, null, [
                                                'id' => 'groupId',
                                                'class' => 'form-control select2',
                                                'style' => 'width: 100%;',
                                            ]) !!}
                                            <span class="error" id="groupError"></span>

                                        </div>
                                        <div class="col-md-4">
                                            <label for="studentTypeId" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')</label>
                                            {!! Form::Select('student_type_id', $studentTypeList, null, [
                                                'id' => 'studentTypeId',
                                                'class' => 'form-control select2',
                                                'style' => 'width: 100%;',
                                            ]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                @lang('Academic::label.SHIFT')</label>
                                            {!! Form::Select('shift_id', $shift_list, null, [
                                                'id' => 'shiftId',
                                                'class' => 'form-control select2',
                                                'style' => 'width: 100%;',
                                            ]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                @lang('Academic::label.TRANSPORT')</label>
                                            {!! Form::Select('transport_id', $transport_list, null, [
                                                'id' => 'transportId',
                                                'class' => 'form-control select2',
                                                'style' => 'width: 100%;',
                                            ]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                {!! Form::label('notes', 'Enter Notes', ['class' => 'col-form-label']) !!}
                                                &nbsp;
                                                {!! Form::textarea('notes', null, [
                                                    'class' => 'form-control',
                                                    'style' => 'height:68px;',
                                                    'id' => 'notes',
                                                    'placeholder' => 'Enter Notes',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <input type="hidden" value="{{ $student->id }}" name="contact_id">
                                        <input type="hidden"
                                            value="{{ $student->contact_academic_id }}"name="contact_academic_id">
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                        <table class="table table-bordered table-striped">
                            <div class="row">
                                @include('Student::student.itemPrice')
                            </div>
                            <div class="col-md-12">
                                <div class="float-right"><button type="submit"
                                        class="btn btn-success btn-sm ml-auto mt-5 waves-effect waves-themed generate-button" id="generateButton">@lang('Student::label.SAVE')</button>
                                </div>
                            </div>
                            <br><br>
                        </table> 
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div> 
    </div>
    <script>
        function getSection2() {
            var classId = $('#classID').val()
            var yearId = $('#academicYearId').val();
            var html = '';
            console.log(`${classId} --- ${yearId}`);
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

        $(function() {

            // $("#reAdmission").validate({
            //     rules: {
            //         new_roll: {
            //             required: true,
            //         },

            //     },
            //     messages: {
            //         new_roll: 'Please Enter New roll Number'
            //     }
            // });

            $(".select2").select2();

            $(document).on('click', '.generate-button', function(e) {
                e.preventDefault();
                var total = 0;
                $(".payable").each(function(index) {
                    total += parseInt($(this).val());
                });
                console.log(total);
                if ($("#classID").val() != 0 && $("#academicYearId").val() != 0) {
                    $('#sessionError').html('');
                    $('#newClassError').html('');
                    if (total <= 0 ) {
                            var link = $(this).attr("type");
                            swal({
                                    title: "Are you want to submit without payment?",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((isConfirm) => {
                                    if (isConfirm) {
                                        $("#generateButton").attr("disabled", true);
                                        $("#generateButton").html('Wait..');
                                        $("#reAdmission").submit();
                                    } else {    
                                        swal("Please insert valid payable value!");
                                    }
                                });
                        }else{
                            $("#generateButton").attr("disabled", true);
                            $("#generateButton").html('Wait..');
                            $("#reAdmission").submit();
                        }
                } else {
                    if ($("#classID").val() == 0) {
                        $('#newClassError').html('Please Select Class');
                    }
                    if ($("#academicYearId").val() == 0) {
                        $('#sessionError').html('Please Select Session');
                    }

                }
            });
        });
    </script>
@endsection
