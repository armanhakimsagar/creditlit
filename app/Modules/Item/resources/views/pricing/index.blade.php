@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Item::label.COUNTRY') @lang('Item::label.DETAILS') @lang('Item::label.SETUP')</li>
            <li class="breadcrumb-item">@lang('Item::label.COUNTRY') @lang('Item::label.WISE') @lang('Item::label.PRICING')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i>@lang('Item::label.COUNTRY') @lang('Item::label.WISE') @lang('Item::label.PRICING') @lang('Academic::label.LIST')
            </h1>
            <a href="{{ route('add.price') }}"
                class="btn btn-primary  btn-sm waves-effect pull-right m-l-10">@lang('Academic::label.ADD')
                @lang('Item::label.COUNTRY') @lang('Item::label.WISE') @lang('Item::label.PRICING')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Item::label.ITEM_PRICING') <span class="fw-300"><i>List</i></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="panel-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="yajraTable" style="width: 100%;">
                            <thead class="thead-themed">
                                <th>No</th>
                                <th>Country Name</th>
                                <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <table>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            $('table').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
            var table = $('#yajraTable').DataTable({
                stateSave: true,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: "{{ route('price.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                ],
                select: {
                    style: 'single'
                }
            });
            $('#yajraTable_filter input').unbind();
            $('#yajraTable_filter input').bind('keyup', function(e) {
                if (e.keyCode === 13) {
                    $('#yajraTable').DataTable().search(this.value).draw();
                }
            });
        });
    </script>
@endsection
