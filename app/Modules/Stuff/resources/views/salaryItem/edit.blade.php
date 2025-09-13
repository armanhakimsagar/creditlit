@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Stuff::label.EMPLOYEE')</li>
        <li class="breadcrumb-item active">@lang('Item::label.UPDATE') @lang('Stuff::label.SALARY') @lang('Item::label.ITEM')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12, 2020</span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Item::label.UPDATE') @lang('Stuff::label.SALARY') @lang('Item::label.ITEM')
            {{-- <small>
                Default input elements for forms
            </small> --}}
        </h1>
        <a style="margin-left: 10px;" href="{{ route('salary.item.index') }}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Stuff::label.SALARY') @lang('Item::label.ITEM')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Stuff::label.SALARY') @lang('Item::label.ITEM') <span class="fw-300"><i>@lang('Item::label.UPDATE')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">

                        {!! Form::model($item, [
                            'method' => 'PATCH',
                            'route' => ['salary.item.update', $item->id],
                            ' class' => '',
                            'id' => 'item',
                        ]) !!}

                        @include('Stuff::salaryItem.form')

                        {!! Form::close() !!}
                    @endsection
