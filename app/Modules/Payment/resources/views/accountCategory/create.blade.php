@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Payment::label.ACCOUNT_SETTING')</li>
        <li class="breadcrumb-item">@lang('Payment::label.ACCOUNT') @lang('Payment::label.CATEGORY') </li>
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $pageTitle }}
        </h1>
        <a style="margin-left: 10px;" href="{{route('account.category.index')}}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Payment::label.ACCOUNT') @lang('Payment::label.CATEGORY')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Payment::label.ACCOUNT') @lang('Payment::label.CATEGORY')<span class="fw-300"><i>@lang('Payment::label.ADD')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'account.category.store',
                            'files' => true,
                            'id' => 'accountCategory',
                            'class' => 'form-horizontal',
                        ]) !!}

                        @include('Payment::accountCategory.form')

                        {!! Form::close() !!}
                    @endsection
