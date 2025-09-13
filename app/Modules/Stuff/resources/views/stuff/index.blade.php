@extends('Admin::layouts.master')
@section('body')
<style>
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
    .dt-buttons{
        float: right;
    }
    .dataTables_filter{
        float: left;
    }
</style>
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Stuff::label.EMPLOYEE')</li>
            <li class="breadcrumb-item active"> @lang('Stuff::label.EMPLOYEE') @lang('Student::label.DETAILS')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i>@lang('Student::label.ALL') @lang('Stuff::label.EMPLOYEE') 
            </h1>
            <a style="margin-left: 10px;" href="{{ route('employee.create') }}"
                class="btn btn-primary btn-sm waves-effect pull-right">@lang('Student::label.ADD') @lang('Stuff::label.EMPLOYEE')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Student::label.SELECT') @lang('Stuff::label.EMPLOYEE')<span class="fw-300"><i>@lang('Student::label.CRITERIA')</i></span>
                </h2>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row pb-5">
                        <div class="form-group col-4">
                            <label for="department">@lang('Stuff::label.DEPARTMENT')</label>
                            {!! Form::Select('department', $department, null, [
                                'id' => 'department',
                                'class' => 'form-control selectheighttype',
                            ]) !!}
                        </div>

                        <div class="form-group col-4">
                            <label for="classId">@lang('Stuff::label.DESIGNATION')</label>
                            {!! Form::Select('designation', $designation, null, [
                                'id' => 'designation',
                                'class' => 'form-control selectheighttype',
                            ]) !!}
                        </div>

                        <div class="form-group col-4">
                            <button class="btn btn-primary btn-sm ml-auto mt-4 waves-effect waves-themed" type="submit"
                                id="searchBtn" onclick="selectLabel()"><i class="fas fa-search pr-1"></i>@lang('Student::label.SEARCH')</a></button>
                        </div>
                    </div>
                    <div class="student-label-container">
                        <div class="student-label-item"><span>Department: </span><span id="departmentLabel"></span></div>
                        <div class="student-label-item"><span>Designation: </span><span id="designationLabel"></span></div>
                        <div class="student-label-item"><span>Total: </span><span id="totalLabel"></span></div>
                    </div>
                    <div class="frame-wrap table-responsive">
                        <table class="table table-bordered table-striped" id="yajraTable">
                            <thead class="thead-themed">
                                <tr>
                                    <th> SL</th>
                                    <th> Employee Name</th>
                                    <th> Department Name</th>
                                    <th> Designation Name</th>
                                    <th> Date Of Birth</th>
                                    <th> Joining Date</th>
                                    <th> Phone</th>
                                    <th> Email</th>
                                    <th> Gender</th>
                                    <th class="status-column"> Status</th>
                                    <th class="action-column"> Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- AJAX -->

        <script type="text/javascript">
            // Select2 use
            $(function() {
                $("#designation").select2();
                $("#department").select2();
            });


            $(function() {
                $('table').on('draw.dt', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                table = $('#yajraTable').DataTable({
                    "processing": true,
                    "searching": true,
                    "scrollY": true,
                    dom: 'lBfrtip',
                    "iDisplayLength": 50,
                    searchDelay: 1000,
                    "ajax": {
                        "url": "{{ route('employee.index') }}",
                        "data": function(e) {
                            e.department = $("#department").val();
                            e.designation = $("#designation").val();
                           
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
                            data: 'date_of_birth',
                            name: 'date_of_birth'
                        },
                        {
                            data: 'employee_joining_date',
                            name: 'employee_joining_date'
                        },
                        {
                            data: 'cp_phone_no',
                            name: 'cp_phone_no'
                        },
                        {
                            data: 'cp_email',
                            name: 'cp_email'
                        },
                        {
                            data: 'gender',
                            name: 'gender'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            className: 'status-column'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            className: 'action-column',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    select: {
                    style: 'single'
                },
                    "responsive": true ,
                    "autoWidth": false,
                    "buttons": [ {
                            extend: 'csvHtml5',
                            text: 'CSV',
                            filename: 'employee_list', 
                            exportOptions: {
                                 columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                            },
                            titleAttr: 'Generate CSV',
                            className: 'btn-outline-info btn-sm mr-1'
                        },
                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            filename: 'employee_list', 
                            exportOptions: {
                                 columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                            },
                            titleAttr: 'Generate Excel',
                            className: 'btn-outline-success btn-sm mr-1'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            filename: 'employee_list', 
                            exportOptions: {
                                 columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                            },
                            titleAttr: 'Generate PDF',
                            className: 'btn-outline-danger btn-sm mr-1'
                        },
                        {
                            extend: "print",
                            title: 'employee_list', 
                            exportOptions: {
                                 columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                            },
                            titleAttr: 'Generate Print',
                            className: 'btn-outline-info btn-sm mr-1'
                        },
                    ]

                });
                

                $(document).on('click', '#searchBtn', function() {
                    table.ajax.reload()
                });
            });
            function selectLabel() {
                $('#designationLabel').empty();
                $('#departmentLabel').empty();
                $('#totalLabel').empty();
                $('#departmentLabel').append($("#department :selected").text());
                $('#designationLabel').append($("#designation :selected").text());
            }
        </script>
    @endsection
