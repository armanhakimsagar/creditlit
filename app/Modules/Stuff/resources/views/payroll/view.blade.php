@extends('Admin::layouts.master')
@section('body')
    <style>
        #btnsm {
            display: none;
        }

        .generate-button {
            display: block;
            position: fixed;
            bottom: 30px;
            right: 40px;
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
            <li class="breadcrumb-item active">@lang('Stuff::label.GENERATED') @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i>@lang('Stuff::label.GENERATED') @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>@lang('Stuff::label.GENERATED') @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY')<span
                        class="fw-300"><i>@lang('Stuff::label.LIST')</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    {!! Form::open([
                        'id' => 'employeePayslip',
                        'route' => 'employee.payslip',
                        'class' => 'form-horizontal',
                        'target' => '_blank',
                    ]) !!}
                    <div class="panel-container show">
                        <div class="panel-content show-table">
                            <div class="frame-wrap">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped ytable" id="yajraTable">
                                        <thead class="thead-themed">
                                            <tr>
                                                <th> Sl</th>
                                                <th width="8%">Check All<input type="checkbox" id="chkbxAll"
                                                        class="all-check-box" onclick="checkAll()"
                                                        style="margin-top: 15px;margin-left: 5px;">
                                                </th>
                                                <th> Employee Name</th>
                                                <th> Department</th>
                                                <th> Designation</th>
                                                <th> Gross Salary</th>
                                                <th> Basic Salary</th>
                                                <th> Deduction Salary</th>
                                                <th> Allowance Salary</th>
                                                <th> Total Salary</th>
                                                <th class="allowance-column"> Add Allowance</th>
                                                <th class="payment-column"> Payment Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="academic_year_id" value="{{ $academicYearId }}"
                                    id="academicYearId">
                                <input type="hidden" name="month_id" value="{{ $monthId }}" id="monthId">
                                <div class="col-md-12">
                                    <div class="float-right mt-5 mb-5">
                                        <button
                                            class="btn btn-primary btn-sm ml-auto waves-effect waves-themed generate-button"
                                            id="btnsm" type="submit">Generate Pay Slip</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                    <!-- Modal -->
                    <div class="modal fade allowance-add-modal" id="exampleModalCenter" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenter" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            {!! Form::open([
                                'route' => 'allowance.update',
                                'files' => true,
                                'id' => 'addEmployeePayroll',
                                'class' => 'form-horizontal',
                                'autocomplete' => true,
                            ]) !!}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Allowance in Employee Salary</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <input type="hidden" name="employee_payroll_id">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <h4>
                                                    <input type="hidden" name="warehouse_id">
                                                    <label>Name :</label>
                                                    <strong id="employeeName">Nayem Hossain</strong>
                                                </h4>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <h4>
                                                    <label>Department :</label>
                                                    <strong id="departmentName">English</strong>
                                                </h4>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <h4>
                                                    <label>Designation :</label>
                                                    <strong id="designationName">Teacher</strong>
                                                </h4>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="basicSalary" class="col-form-label">Basic Salary :</label>

                                                    <input id="basicSalary" class="form-control"
                                                        placeholder="Enter Basic Salary" readonly="" name="basic_salary"
                                                        type="number">
                                                    <span class="error" id="basic-error"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="otherSalary" class="col-form-label">Other Salary :</label>

                                                    <input id="otherSalary" class="form-control"
                                                        placeholder="Enter Other Salary" readonly="" name="other_salary"
                                                        type="number">
                                                    <span class="error"> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="grossSalary" class="col-form-label">Gross Salary :</label>

                                                    <input id="grossSalary" class="form-control"
                                                        placeholder="Enter Gross Salary" readonly=""
                                                        name="gross_salary" type="number">
                                                    <span class="error"> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="deductionSalary" class="col-form-label">Deduction Salary
                                                        :</label>

                                                    <input id="deductionSalary" class="form-control"
                                                        placeholder="Enter Deduction Salary" readonly=""
                                                        name="deduction_salary" type="number">
                                                    <span class="error"> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="allowanceSalary" class="col-form-label">Allowance Salary
                                                        :</label>

                                                    <input id="allowanceSalary" class="form-control"
                                                        placeholder="Enter Deduction Salary" readonly=""
                                                        name="allowance_salary" type="number">
                                                    <span class="error"> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="totalSalary" class="col-form-label">Total Salary :</label>

                                                    <input id="totalSalary" class="form-control"
                                                        placeholder="Enter Total Salary" readonly=""
                                                        name="total_salary" type="number">
                                                    <span class="error" id="total-error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row allowance-add-container"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>



            <script type="text/javascript">
                $(document).ready(function() {
                    // Trigger click event of search button
                    $("#searchBtn").click();
                    $('.select3').select2();

                    // Open Edit Modal
                    $(document).on('click', '.allowance-add-btn', function() {
                        var id = $(this).data('id').toString();
                        // alert(id);
                        var academicYearId = $("#academicYearId").val();
                        var monthId = $("#monthId").val();
                        var url = "{{ route('allowance.add', ['id' => ':id']) }}".replace(':id', id) +
                            "?academicYearId=" + academicYearId + "&monthId=" + monthId;


                        $.getJSON(url, function(data) {
                            $('.allowance-add-container').html(data.data1);
                            $("#employeeName").text(data.data['full_name']);
                            $("#departmentName").text(data.data['department_name']);
                            $("#designationName").text(data.data['designation_name']);
                            $("#exampleModalCenter input[name='employee_payroll_id']").val(data.data['id']);
                            $("#exampleModalCenter input[name='basic_salary']").val(data.data[
                                'basic_salary']);
                            $("#exampleModalCenter input[name='other_salary']").val(data.data[
                                'other_salary']);
                            $("#exampleModalCenter input[name='gross_salary']").val(data.data[
                                'gross_salary']);
                            $("#exampleModalCenter input[name='deduction_salary']").val(data.data[
                                'deduction_salary']);
                            $("#exampleModalCenter input[name='allowance_salary']").val(data.data[
                                'allowance_salary']);
                            $("#exampleModalCenter input[name='total_salary']").val(data.data[
                                'total_salary']);
                            $("#editModal input[name='email']").val(data.data['email']);
                            $("#editModal textarea[name='address']").val(data.data['address']);
                            $("#editModal input[name='warehouse_id']").val(data.data['id']);

                        });
                    });
                });

                // calculateSalary function
                function calculateSalary() {
                    var grossSalary = $('#grossSalary').val();
                    var deductionSalary = $('#deductionSalary').val();
                    var allowanceSalary = $('#allowanceSalary').val();
                    var totalSalary = 0;
                    var totalAllowanceAmount = 0;
                    var totaldeductionAmount = 0;
                    $('.allowance-item-amount').each(function() {
                        id = ($(this).attr('data-key'));
                        // var grossAmountType = $('#grossAmountType_' + id).val();
                        var allowanceItemAmount = $('#allowanceItemAmount_' + id).val() || 0;
                        var allowanceAmount = $('#allowanceAmount_' + id).val();
                        allowanceAmount = allowanceItemAmount;
                        $('#allowanceAmount_' + id).val(allowanceAmount);
                        totalAllowanceAmount += parseFloat(allowanceAmount);
                        $('#allowanceSalary').val(totalAllowanceAmount);
                    });

                    @if ($companyDetails->salary_system == 1)
                        var Baseamount = $('#basicSalary').val() || 0;
                    @else
                        var Baseamount = $('#grossSalary').val() || 0;
                    @endif

                    $('.deduction-item-amount').each(function() {
                        id = ($(this).attr('data-key'));
                        var deductionAmountType = $('#allowanceAmountType_' + id).val();
                        var deductionItemAmount = $('#allowanceItemAmount_' + id).val();
                        var deductionAmount = $('#allowanceAmount_' + id).val();
                        if (deductionAmountType == 'flat') {
                            deductionAmount = deductionItemAmount;
                            $('#deductionAmount_' + id).val(deductionItemAmount);
                        } else {
                            deductionAmount = (Baseamount / 100) * deductionItemAmount;
                            $('#deductionAmount_' + id).val(deductionAmount);
                        }
                        $('#allowanceAmount_' + id).val(deductionAmount);
                        totaldeductionAmount += parseFloat(deductionAmount);
                        $('#deductionSalary').val(totaldeductionAmount);
                    });

                    var totalSalary = ((parseFloat(grossSalary) + parseFloat(totalAllowanceAmount)) - parseFloat(
                        totaldeductionAmount));
                    $("#totalSalary").val(totalSalary);
                }
                $(function() {
                    $(".select2").select2();
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
                            "url": "{{ route('employee.payroll.view', ['academicYearId' => $academicYearId, 'monthId' => $monthId]) }}",
                            "data": function(e) {}
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'paySlipCheckbox',
                                name: 'paySlipCheckbox',
                                orderable: false,
                                searchable: false,
                                "className": "table-checkbox-column",
                            },
                            {
                                data: 'full_name',
                                name: 'full_name'
                            },
                            {
                                data: 'department_name',
                                name: 'department_name'
                            },
                            {
                                data: 'designation_name',
                                name: 'designation_name'
                            },
                            {
                                data: 'gross_salary',
                                name: 'gross_salary'
                            },
                            {
                                data: 'basic_salary',
                                name: 'basic_salary'
                            },
                            {
                                data: 'deduction_salary',
                                name: 'deduction_salary'
                            },
                            {
                                data: 'allowance_salary',
                                name: 'allowance_salary'
                            },
                            {
                                data: 'total_salary',
                                name: 'total_salary'
                            },

                            {
                                data: 'allowance',
                                name: 'allowance',
                                className: 'allowance-column',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'payment_status',
                                name: 'payment_status',
                                className: 'payment-column',
                            },
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
                    setTimeout(() => {
                        isChecked();
                    }, 500);
                    $("#searchBtn").click();
                });

                function checkAll() {
                    if ($('#chkbxAll').is(':checked')) {
                        $(".allCheck").each(function(index) {
                            var key = $(this).attr('keyvalue');

                            if ($("#checkEmployee_" + key)[0].checked == false) {
                                $("#checkEmployee_" + key).prop('checked', true);
                            }
                        });
                    } else {
                        $(".allCheck").each(function(index) {
                            var key = $(this).attr('keyvalue');
                            if ($("#checkEmployee_" + key)[0].checked == true) {
                                $("#checkEmployee_" + key).prop('checked', false);
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
                    if (countChecked == 0) {
                        $('#btnsm').css('disabled', true);
                    }
                }

                $(document).on('click', '.generate-button', function(e) {
                    e.preventDefault();
                    var countChecked = 0;
                    $(".allCheck").each(function(index) {
                        if ($(this)[0].checked == true) {
                            countChecked++;
                        }
                    });
                    if (countChecked > 0) {
                        $("#employeePayslip").submit();
                        return (true);
                    } else {
                        return (false);
                    }
                });
            </script>
        @endsection
