@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Mark::label.MARKS')</li>
            <li class="breadcrumb-item active">@lang('Mark::label.MARK') @lang('Mark::label.CONFIG')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <div class="col-xl-4">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i>@lang('Mark::label.MARK') @lang('Mark::label.CONFIG') @lang('Academic::label.LIST')
            </h1>
            </div>
            <div class="col-xl-8">
                <a href="{{ route('mark.config.create') }}"
                    class="btn btn-primary btn-sm pull-right m-l-10 float-right">@lang('Academic::label.ADD') @lang('Mark::label.MARK')
                    @lang('Mark::label.CONFIG')</a>
            </div>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        @lang('Mark::label.MARK') @lang('Mark::label.CONFIG') <span class="fw-300"><i>@lang('Academic::label.LIST')</i></span>
                    </h2>
                </div>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>SL</th>
                                        <th>Class</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->class_name }}</td>
                                            <td class="text-center"><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#configModal" id="viewConfig"data-classid="{{ $item->class_id }}"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade default-example-modal-right-lg" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-center modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Config History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div id="modalBody">

                    </div>
                </div>
            </div>
        </div>
        <!-- AJAX -->
        <script>
            $('body').on("click","#viewConfig", function(){
                var classId = $(this).data('classid');
            $.get("view-config/"+classId, function(data){
               $("#modalBody").html(data);
            });
         });
        </script>
    @endsection
