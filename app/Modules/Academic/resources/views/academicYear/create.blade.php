@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Academic::label.ACADEMIC')</li>
        <li class="breadcrumb-item active">@lang('Academic::label.ACADEMIC_YEAR')</li>
        <li class="breadcrumb-item active">@lang('Academic::label.ADD_ACADEMIC_YEAR')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Academic::label.ADD_ACADEMIC_YEAR')
        </h1>
        <a style="margin-left: 10px;" href="{{ route('academic.year.index') }}"
            class="btn btn-sm btn-success waves-effect pull-right">@lang('Academic::label.ALL') @lang('Academic::label.ACADEMIC_YEAR')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Academic::label.ACADEMIC_YEAR') <span class="fw-300"><i>Add</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open(['route' => 'academic.year.store', 'files' => true, 'id' => 'academicYear', 'class' => 'form-horizontal']) !!}

                        @include('Academic::academicYear.form')

                        {!! Form::close() !!}
                    @endsection
