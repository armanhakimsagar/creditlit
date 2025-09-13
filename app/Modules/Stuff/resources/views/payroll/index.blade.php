@extends('Admin::layouts.master')
@section('body')
    <style>
        @media (min-width: 767px) {
            .table-responsive {
                overflow-y: auto;
            }

            .dataTables_scrollBody {
                overflow-x: hidden !important;
                overflow-y: hidden !important;
            }
        }

        table {
            table-layout: fixed;
            width: 100% !important;
        }
    </style>
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Stuff::label.EMPLOYEE')</li>
            <li class="breadcrumb-item active">@lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.PAYROLL')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.PAYROLL') @lang('Stuff::label.LIST')
            </h1>
            <a href="{{ route('employee.payroll.create') }}"
                class="btn btn-sm btn-primary waves-effect pull-right m-l-10">@lang('Stuff::label.ADD') @lang('Stuff::label.EMPLOYEE')
                @lang('Stuff::label.PAYROLL')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.PAYROLL') <span class="fw-300"><i>List</i></span>
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
                                        <th> Year</th>
                                        <th> Generated Month</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
    <script type="text/javascript">
        $(function() {
            var table = $('#yajraTable').DataTable({
                stateSave: true,
                processing: true,
                serverSide: true,
                searchDelay: 1000,
                scrollX: true,
                scrollY: true,
                ajax: "{{ route('employee.payroll.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '5%'
                    },
                    {
                        data: 'year',
                        name: 'year',
                        width: '15%'
                    },
                    {
                        data: 'month_name',
                        name: 'month_name',
                        width: '30%'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'status-column',
                        width: '20%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'action-column',
                        orderable: false,
                        searchable: true,
                        width: '20%'
                    },
                ],
                select: {
                    style: 'single'
                }
            });
        });
    </script>
@endsection
