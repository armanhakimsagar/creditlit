@extends('Admin::layouts.master')
@section('body')
    <style>
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
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Stuff::label.EMPLOYEE')</li>
            <li class="breadcrumb-item active">@lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY') @lang('Stuff::label.SETUP')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY') @lang('Stuff::label.LIST')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY') <span class="fw-300"><i>List</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table class="table table-striped myTable" id="yajraTable">
                                <thead class="thead-themed">
                                    <tr>
                                        <th> Sl</th>
                                        <th> Employee Name</th>
                                        <th> Basic Salary</th>
                                        <th> Other Salary</th>
                                        <th> Gross Salary</th>
                                        <th> Deduction Salary</th>
                                        <th> Total Salary</th>
                                        <th> Department</th>
                                        <th> Designation</th>
                                        <th>Status</th>
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
        <script type="text/javascript">
            $(function() {
                var table = $('#yajraTable').DataTable({
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    searchDelay: 1000,
                    scrollX: true,
                    scrollY: true,
                    ajax: "{{ route('employee.salary.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'full_name',
                            name: 'full_name'
                        },
                        {
                            data: 'basic_salary',
                            name: 'basic_salary'
                        },
                        {
                            data: 'other_salary',
                            name: 'other_salary'
                        },
                        {
                            data: 'gross_salary',
                            name: 'gross_salary'
                        },
                        {
                            data: 'deduction_salary',
                            name: 'deduction_salary'
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
                        },
                        {
                            data: 'status',
                            name: 'status',
                            className: 'status-column'
                        },
                    ],
                    select: {
                        style: 'single'
                    }
                });
            });
        </script>
    @endsection
