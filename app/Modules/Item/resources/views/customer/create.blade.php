@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
        <li class="breadcrumb-item active">@lang('Item::label.ADD') @lang('Item::label.CUSTOMER')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $pageTitle }}
        </h1>
        <a style="margin-left: 10px;" href="{{ route('customer.index') }}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Item::label.CUSTOMER')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Item::label.CUSTOMER') <span class="fw-300"><i>@lang('Academic::label.ADD')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'customer.store',
                            'files' => true,
                            'name' => 'customer',
                            'id' => 'customer',
                            'class' => 'form-horizontal',
                        ]) !!}

                        @include('Item::customer.form')

                        {!! Form::close() !!}
                    @endsection
