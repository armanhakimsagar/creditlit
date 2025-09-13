@extends('Admin::layouts.master')
@section('body')
    <style>
        #btnsm {
            display: none;
        }
    </style>
    <div class="col-md-3l-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Stuff::label.EMPLOYEE')</li>
            <li class="breadcrumb-item active">@lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY') @lang('Stuff::label.SETUP')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i>@lang('Stuff::label.ADD') @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY')<span class="fw-300"><i>@lang('Stuff::label.ADD')</i></span>
                </h2>
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
                                        'class' => 'form-control selectheighttype select2',
                                        'onchange' => 'setYear()',
                                    ]) !!}
                                    <span class="error" id="yearError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="departmentId">@lang('Stuff::label.DEPARTMENT')</label>
                                    {!! Form::Select('department_id', $department, null, [
                                        'id' => 'departmentId',
                                        'class' => 'form-control selectheighttype select2',
                                    ]) !!}
                                    <span class="error" id="departmentIdError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="designationId">@lang('Stuff::label.DESIGNATION')</label>
                                    {!! Form::Select('designation_id', $designation, null, [
                                        'id' => 'designationId',
                                        'class' => 'form-control selectheighttype select2',
                                    ]) !!}
                                    <span class="error" id="designationIdError"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-3 col-md-4">
                            <button class="btn btn-success btn-sm ml-auto mt-4 waves-effect waves-themed" type="submit"
                                id="searchBtn"><i class="fas fa-search pr-1"></i>@lang('Student::label.SEARCH')</a></button>
                        </div>
                    </div>
                </div>
                {!! Form::open([
                    'route' => 'employee.salary.store',
                    'files' => true,
                    'id' => 'addEmployee',
                    'class' => 'form-horizontal',
                    'autocomplete' => true,
                ]) !!}
                <div class="panel-container show">
                    <div class="panel-content show-table">
                        <div class="frame-wrap">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped ytable" id="yajraTable">
                                    <thead class="thead-themed">
                                        <tr>
                                            <th> Sl</th>
                                            <th> Eployee Name</th>
                                            <th> Gross Salary</th>
                                            <th> Department</th>
                                            <th> Designation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <input type="hidden" name="academic_year" id="academic_year"
                                    value="{{ !empty($currentYear) ? $currentYear->id : '' }}">
                            </div>
                            <div class="col-md-12">
                                <div class=" float-right mt-5 mb-5">
                                    <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" id="btnsm"
                                        type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <script type="text/javascript">
            $(function() {
                $(".select2").select2();
                $(document).on('click', '#searchBtn', function() {
                    if ($("#academicYearId").val() != 0) {
                        $('#yearError').html('');
                        $(".show-table").css("display", "block");
                        $("#btnsm").css("display", "block");
                        $(".certificate-generate").css("display", "block");
                        var table = $('#yajraTable').DataTable({
                            stateSave: true,
                            processing: true,
                            serverSide: true,
                            iDisplayLength: 50,
                            ajax: {
                                "url": "{{ route('employee.salary.create') }}",
                                "data": function(e) {
                                    e.academicYearId = $("#academicYearId").val();
                                    e.departmentId = $("#departmentId").val();
                                    e.designationId = $("#designationId").val();
                                }
                            },
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex'
                                },
                                {
                                    data: 'full_name',
                                    name: 'full_name'
                                },
                                {
                                    data: 'gross_salary',
                                    name: 'gross_salary'
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
                            "drawCallback": function(settings, start, end, max, total, pre) {
                                if (this.fnSettings().fnRecordsTotal() == 0) {
                                    $("#btnsm").css("display", "none");
                                } else {
                                    $("#btnsm").css("display", "block");
                                }
                            }
                        });
                    } else {
                        if ($("#academicYearId").val() == 0) {
                            $(".show-table").css("display", "none");
                            $('#yearError').html('Please Select a Year');
                        }
                    }
                });
            });

            function setYear() {
                var year = $('#academicYearId').val();
                $('#academic_year').val(year);
            }
        </script>
    @endsection
