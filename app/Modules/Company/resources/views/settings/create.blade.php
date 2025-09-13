@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Company::label.SETTINGS')</li>
        <li class="breadcrumb-item active">@lang('Company::label.INSTITUTION') @lang('Company::label.SETTINGS')</li>
        <li class="breadcrumb-item active">@lang('Company::label.ADD') @lang('Company::label.SETTINGS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Company::label.ADD') @lang('Company::label.INSTITUTION')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Company::label.INSTITUTION') <span class="fw-300"><i>@lang('Company::label.ADD')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'institution.store',
                            'files' => true,
                            'id' => 'companyform',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @include('Company::settings.form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endsection
