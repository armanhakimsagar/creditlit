@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Mark::label.MARKS')</li>
        <li class="breadcrumb-item active">@lang('Mark::label.MARK') @lang('Mark::label.GRADE')</li>
        <li class="breadcrumb-item active">{{ $editTitle }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $editTitle }}
        </h1>
        <a style="margin-left: 10px;" href="{{route('mark.grade.index')}}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Mark::label.MARK') @lang('Mark::label.GRADE')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Mark::label.MARK') @lang('Mark::label.GRADE') <span class="fw-300"><i>@lang('Academic::label.EDIT')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">

                    {!! Form::model($data, ['method' => 'PATCH', 'route' => ['mark.grade.update',$data->id], 'id' => 'markGrade']) !!}

                        @include('Mark::markGrade.form')

                        {!! Form::close() !!}
                    @endsection
