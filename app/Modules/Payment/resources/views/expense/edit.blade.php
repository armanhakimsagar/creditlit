@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Payment::label.PAYMENT') @lang('Payment::label.AND') @lang('Payment::label.EXPENSE')</li>
        <li class="breadcrumb-item active">@lang('Payment::label.OTHER') @lang('Payment::label.EXPENSE')</li>
        <li class="breadcrumb-item active">@lang('Payment::label.UPDATE') @lang('Payment::label.OTHER') @lang('Payment::label.EXPENSE')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Payment::label.UPDATE') @lang('Payment::label.EXPENSE')
        </h1>
        <a style="margin-left: 10px;" href="{{ route('expense.index') }}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Payment::label.EXPENSE')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Payment::label.EXPENSE')<span class="fw-300"><i>@lang('Payment::label.UPDATE')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::model($data, [
                            'method' => 'PATCH',
                            'route' => ['update.expense', $data->id],
                            ' class' => '',
                            'id' => 'paymentForm',
                        ]) !!}

                        @include('Payment::expense.form')

                        {!! Form::close() !!}
                    @endsection
