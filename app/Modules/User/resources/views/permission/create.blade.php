@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('User::label.USER') @lang('User::label.SETUP')</li>
        <li class="breadcrumb-item active">@lang('User::label.PERMISSION')</li>
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $pageTitle }}
        </h1>
        <a style="margin-left: 10px;" href="{{route('permission.index')}}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('User::label.ALL') @lang('User::label.PERMISSION')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('User::label.PERMISSION') <span class="fw-300"><i>@lang('User::label.ADD')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'permission.store',
                            'files' => true,
                            'name' => 'permission',
                            'id' => 'permission',
                            'class' => 'form-horizontal',
                        ]) !!}

                        @include('User::permission.form')

                        {!! Form::close() !!}
                    @endsection
