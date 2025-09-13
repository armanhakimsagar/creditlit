@extends('Admin::layouts.master')
@section('body')
    <style>
        #btnsm {
            display: none;
        }

        @media (min-width: 980px) {
            .table-responsive {
                overflow-y: auto;
            }

            .dataTables_scrollBody {
                overflow-x: hidden !important;
                overflow-y: hidden !important;
            }

            table {
                table-layout: fixed;
                width: 100% !important;
            }
        }
    </style>
    <div class="col-md-3l-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Stuff::label.EMPLOYEE')</li>
            <li class="breadcrumb-item active">@lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.PAYMENT') @lang('Stuff::label.SETUP')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i>@lang('Stuff::label.PAY') @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>@lang('Stuff::label.PAY') @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY')<span
                        class="fw-300"><i>@lang('Stuff::label.ADD')</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row pb-5">
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="department">@lang('Stuff::label.DEPARTMENT')</label>
                                    {!! Form::Select('department', $department, null, [
                                        'id' => 'departmentId',
                                        'class' => 'form-control selectheighttype select2',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="classId">@lang('Stuff::label.DESIGNATION')</label>
                                    {!! Form::Select('designation', $designation, null, [
                                        'id' => 'designationId',
                                        'class' => 'form-control selectheighttype select2',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-4">
                            <button class="btn btn-success btn-sm ml-auto mt-4 waves-effect waves-themed" type="submit"
                                id="searchBtn"><i class="fas fa-search pr-1"></i>@lang('Student::label.SEARCH')</a></button>
                        </div>
                    </div>
                    {!! Form::open([
                        'route' => 'employee.payroll.update',
                        'files' => true,
                        'id' => 'addEmployeePayroll',
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
                                                <th style="width: 15%;" class="table-checkbox-header-center">
                                                    <span>
                                                        Check All</span>
                                                    <input type="checkbox" class="all-check-box" id="chkbxAll"
                                                        onclick="return checkAll()">
                                                </th>
                                                <th class="payment-type"> Payment Type</th>
                                                <th> Employee Name</th>
                                                <th> Total Salary</th>
                                                <th> Department</th>
                                                <th> Designation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="academic_year_id" value="{{ $academicYearId }}" id="">
                                <input type="hidden" name="month_id" value="{{ $monthId }}" id="">
                                <div class="col-md-12">
                                    <div class=" float-right mt-5 mb-5">
                                        <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed"
                                            id="btnsm" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function() {
                    // Trigger click event of search button
                    $("#searchBtn").click();
                });
                $(function() {
                    $(".select2").select2();
                    $(document).on('click', '#searchBtn', function() {
                        $('#yearError').html('');
                        $(".show-table").css("display", "block");
                        $("#btnsm").css("display", "block");
                        $(".certificate-generate").css("display", "block");
                        var table = $('#yajraTable').DataTable({
                            stateSave: true,
                            processing: true,
                            serverSide: true,
                            paging: false,
                            scrollX: true,
                            scrollY: true,
                            ajax: {
                                "url": "{{ route('pay.employee.salary', ['academicYearId' => $academicYearId, 'monthId' => $monthId]) }}",
                                "data": function(e) {
                                    e.designationId = $("#designationId").val();
                                    e.departmentId = $("#departmentId").val();
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
                                    "className": "table-checkbox-column"
                                },
                                {
                                    data: 'payment_type',
                                    name: 'payment_type',
                                    orderable: false,
                                    searchable: false,
                                    "className": "payment-type"
                                },
                                {
                                    data: 'full_name',
                                    name: 'full_name'
                                },
                                {
                                    data: 'total_salary',
                                    name: 'total_salary'
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
                        table.on('draw', function() {
                            isChecked();
                        });
                        setTimeout(() => {}, 500);
                    });
                    $("#searchBtn").click();
                });

                function checkAll() {
                    if ($('#chkbxAll').is(':checked')) {
                        $(".allCheck").each(function(index) {
                            var key = $(this).attr('keyvalue');

                            if ($("#checkSection_" + key)[0].checked == false) {
                                $("#checkSection_" + key).prop('checked', true);
                            }
                        });
                    } else {
                        $(".allCheck").each(function(index) {
                            var key = $(this).attr('keyvalue');
                            if ($("#checkSection_" + key)[0].checked == true) {
                                $("#checkSection_" + key).prop('checked', false);
                            }
                        });
                    }
                    isChecked();
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
                        if ($(this).prop('checked') && !$(this).prop('disabled')) {
                            countChecked++;
                        } else {
                            countNotChecked++;
                        }
                    });
                    if (countNotChecked == 0 && countChecked > 0) {
                        $("#chkbxAll").prop("checked", true);
                    }
                    if (countChecked < 1) {
                        $('#btnsm').prop('disabled', true);
                    }else{
                        $('#btnsm').prop('disabled', false);
                    }
                }
            </script>
        @endsection
