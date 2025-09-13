@extends('Admin::layouts.master')
@section('body')
@push('css')
     <style>
        .col-form-label{
            font-size: 20px;
            font-weight: 600;
        }
        .group-container{
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
     </style>
@endpush
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('User::label.USER') @lang('User::label.SETUP')</li>
        <li class="breadcrumb-item active">@lang('User::label.ROLES')</li>
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $pageTitle }}
        </h1>
        <a style="margin-left: 10px;" href="{{ route('role.index') }}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('User::label.ALL') @lang('User::label.ROLE')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('User::label.ROLE') <span class="fw-300"><i>@lang('User::label.EDIT')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">

                        {!! Form::model($role, ['method' => 'PATCH', 'route' => ['role.update', $role->id], 'id' => 'class']) !!}

                        @include('User::role.form')

                        {!! Form::close() !!}
                    @endsection
