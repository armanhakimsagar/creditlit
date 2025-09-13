@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Item::label.ITEM') @lang('Item::label.CATEGORY')</li>
        <li class="breadcrumb-item active">@lang('Item::label.CATEGORY')</li>
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $pageTitle }}
        </h1>
        <a style="margin-left: 10px;" href="{{ route('category.index') }}"
            class="btn btn-sm btn-success waves-effect pull-right">@lang('Academic::label.ALL') @lang('Item::label.CATEGORY')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Item::label.CATEGORY') <span class="fw-300"><i>@lang('Academic::label.EDIT')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::model($category, [
                            'method' => 'PATCH',
                            'route' => ['category.update', $category->id],
                            ' class' => '',
                            'id' => 'category',
                        ]) !!}

                        @include('Item::category.form')

                        {!! Form::close() !!}
                    @endsection
