@extends('Admin::layouts.master')
@section('body')
    <style>
        .d-flex {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-end;
            height: 100%;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Announcement::label.ANNOUNCEMENT')</li>
        <li class="breadcrumb-item active">@lang('Announcement::label.HOLIDAY')</li>
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $pageTitle }}
        </h1>
        <a style="margin-left: 10px;" href="{{ route('holiday.index') }}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Announcement::label.HOLIDAY')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Announcement::label.HOLIDAY') <span class="fw-300"><i>@lang('Academic::label.ADD')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'holiday.store',
                            'files' => true,
                            'name' => 'holiday',
                            'id' => 'holiday',
                            'class' => 'form-horizontal',
                        ]) !!}

                        @include('Announcement::holiday.form')

                        {!! Form::close() !!}
                    @endsection
