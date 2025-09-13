@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Company::label.INSTITUTION') @lang('Company::label.SETTINGS')</li>
        <li class="breadcrumb-item active">@lang('Company::label.SETTINGS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Company::label.UPDATE') @lang('Company::label.SETTINGS')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Company::label.SETTINGS') <span class="fw-300"><i>@lang('Company::label.UPDATE')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::model($data, [
                            'method' => 'PATCH',
                            'files' => true,
                            'route' => ['institution.update', $data->id],
                            'class' => '',
                            'id' => 'companyform',
                        ]) !!}
                        @include('Company::settings.settingsForm')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endsection
