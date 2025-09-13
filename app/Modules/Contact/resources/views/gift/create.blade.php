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
                width: 100px;
                height: 100px;
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
        </style>
    @endpush
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Contact::label.GIFT') @lang('Contact::label.DETAILS')</li>
        <li class="breadcrumb-item active">@lang('Contact::label.ADD') @lang('Contact::label.GIFT')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $addPage }}
        </h1>
        <a style="margin-left: 10px;" href="{{route('gift.index')}}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('User::label.ALL') @lang('Contact::label.GIFT')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Contact::label.GIFT') <span class="fw-300"><i>@lang('User::label.ADD')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'gift.store',
                            'files' => true,
                            'name' => 'gift-add',
                            'id' => 'giftAdd',
                            'class' => 'form-horizontal',
                            'autocomplete' => true,
                        ]) !!}

                        @include('Contact::gift.form')

                        {!! Form::close() !!}
                    @endsection
