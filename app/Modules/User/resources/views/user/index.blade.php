@extends('Admin::layouts.master')
@section('body')

    <?php
    use Illuminate\Support\Facades\Input;
    ?>
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('User::label.USER') @lang('User::label.SETUP')</li>
            <li class="breadcrumb-item active">@lang('User::label.USER') @lang('Academic::label.LIST')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('User::label.USER') @lang('Academic::label.LIST')
            </h1>
            <div class="col-xl-10">
                <a href="{{ route('user.create') }}"
                    class="btn btn-primary btn-sm pull-right m-l-10 float-right">@lang('User::label.ADD') @lang('User::label.USER')</a>
            </div>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('User::label.USER') <span class="fw-300"><i>@lang('User::label.LIST')</i></span>
                </h2>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ytable" id="yajraTable">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>No</th>
                                        <th>Admin Role</th>
                                        <th>Role Name</th>
                                        <th>E-mail</th>
                                        <th>Image</th>
                                        <th class="status-column">Status</th>
                                        <th class="action-column">Action</th>
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
        <!-- AJAX -->

        <script type="text/javascript">
            // Class table index
            $(function() {
                $('table').on('draw.dt', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                var table = $('#yajraTable').DataTable({
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('user.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'role_name',
                            name: 'role_name'
                        },
                        {
                            data: 'first_name',
                            name: 'first_name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'image',
                            name: 'image',
                            render: function(data, type, full, meta) {
                                return "<img src=\"backend/images/users/" + data + "\"  height=\"30\" />";
                            }
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
                    }
                });
            });
        </script>
    @endsection
