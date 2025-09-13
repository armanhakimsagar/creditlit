@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            .pic-header {
                margin-left: 30px;
            }

            .profile-images-card {
                display: table;
                background: #fff;
                margin-left: 30px;
            }

            .profile-images {
                width: 130px;
                height: 130px;
                background: #fff;
                overflow: hidden;
            }

            .profile-images img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .custom-file label {
                cursor: pointer;
                color: #fff;
                display: table;
                margin-top: 15px;
                background-color: #25316D;
                padding: 6px 8px;
            }

            .panel .panel-container .panel-content {
                padding: 0px;
            }

            .stprimary{
                position: relative;
            }

            .stprimaryinfo,
            .stprimarybutton {
                padding: 1rem 1rem;
            }

            .stprimarybutton{
                position: sticky;
                bottom: 22px;
                right: 30px;
            }
            .panel-hdr {
                font-size: 20px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.26);
            }

            .fileupload {
                width: 0px;
            }

            .required{
                color: red;
            }

            .existing-guardian-info{
                display: none;
            }
        </style>
    @endpush
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Contact::label.CONTACTS') @lang('Student::label.INFORMATION')</li>
        <li class="breadcrumb-item active">@lang('Contact::label.BRANCH') @lang('Student::label.DETAILS')</li>
        <li class="breadcrumb-item active">{{ $editPage }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $editPage }}
        </h1>
        <a style="margin-left: 10px;" href="{{ route('branch.index') }}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Student::label.ALL') @lang('Contact::label.BRANCH')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel pb-5">
                <div class="panel-hdr">
                    <h2>
                        @lang('Contact::label.BRANCH') @lang('Student::label.INFO') :
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::model($branch, [
                            'method' => 'PATCH',
                            'route' => ['branch.update', $branch->id],
                            'id' => 'branchAdd',
                            'class' => 'form-horizontal',
                            'name' => 'branch-add',
                            'autocomplete' => true,
                            'files' => true,
                        ]) !!}

                        @include('Contact::branch.form')

                        {!! Form::close() !!}
                    @endsection
