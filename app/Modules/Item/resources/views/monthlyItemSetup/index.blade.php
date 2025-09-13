@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Item::label.ITEM') @lang('Item::label.SETUP')</li>
            <li class="breadcrumb-item active">@lang('Item::label.GENERATE') @lang('Item::label.STUDENT') @lang('Item::label.DUE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i>@lang('Item::label.GENERATED') @lang('Item::label.STUDENT') @lang('Item::label.DUE')
                @lang('Item::label.LIST')
            </h1>
            <a href="{{ route('monthly.item.create') }}"
                class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">@lang('Academic::label.ADD')
                @lang('Item::label.STUDENT') @lang('Item::label.DUE')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Item::label.GENERATED') @lang('Item::label.STUDENT') @lang('Item::label.DUE') <span class="fw-300"><i>List</i></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="row clearfix">
                    <div class="block-header block-header-2">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row clearfix">
                                    <div class="block-header block-header-2">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <center>
                                            <div class="row">
                                                <div class="col-md-12">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                </div>
                                            </div>
                                        </center>
                                        <div class="table-responsive">
                                            <div class="">
                                                <table style="width: 100%" class="table table-bordered table-striped"
                                                    id="yajraTable">
                                                    <thead class="thead-themed">
                                                        <th class="table-serial-column-center"> SL</th>
                                                        <th>Academic Year</th>
                                                        <th>Month</th>
                                                        <th>Class</th>
                                                        <th>Generation Date</th>
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
                </div>
            </div>
        </div>

        <script>
            $(function() {
                $('#yajraTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "paging": true,
                    iDisplayLength: 50,
                    "ajax": {
                        "url": "{{ route('monthly.item.index') }}",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            "className": "table-serial-column-center",
                        },
                        {
                            data: 'year',
                            name: 'year'
                        },
                        {
                            data: 'month_name',
                            name: 'month_name'
                        },
                        {
                            data: 'class_name',
                            name: 'class_name'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        }
                    ],
                    select: {
                        style: 'single'
                    },
                    "bDestroy": true
                });
                $('#yajraTable_filter input').unbind();
                $('#yajraTable_filter input').bind('keyup', function(e) {
                    if (e.keyCode === 13) {
                        $('#yajraTable').DataTable().search(this.value).draw();
                    }
                });
                $(".select2").select2();
                <?php if(!empty($request->academic_year_id) && !empty($request->class_id)){ ?>
                $(".search-item").trigger('change');
                <?php
                        }
                        ?>
            });
        </script>
    @endsection
