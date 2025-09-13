@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            .panel-hdr {
                background-color: #7453A6;
            }

            .panel-hdr h2 {
                color: #fff;
            }

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

            .stprimary {
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

            .required {
                color: red;
            }

            ::-webkit-file-upload-button {
                visibility: hidden;
            }

            input {
                border-radius: 3px;
                display: block;
                box-sizing: border-box;
                width: 100%;
                padding: .8em;
            }

            input[type="file"] {
                border: 1px solid rgba(0, 0, 0, 0.1);
                background: #fff;
                padding-left: 3em;
                padding-bottom: 2em;
                color: #000
            }

            input[type="file"]:before {
                content: "Select Attachment:";
                background: #7453A6;
                padding: 1.2em .9em;
                margin-left: -3em;
                font-weight: bold;
                color: #fff;
            }

            input[type="submit"] {
                background: rgb(144, 238, 144);
                border: 1px solid rgba(144, 238, 144, .3);
                box-shadow: inset 0 1px rgba(255, 255, 255, 0.3), inset 0 -1px rgba(0, 0, 0, 0.1)
            }

            input[type="submit"]:hover {
                background: hsl(120, 73%, 65%);
            }

            input[type="submit"]:active {
                background: hsl(120, 65%, 60%);
                box-shadow: inset 0 1px rgba(0, 0, 0, 0.2), inset 0 -1px rgba(255, 255, 255, 0.1)
            }

            .box {
                display: inline-block;
                padding: 5px 10px;
                background-color: #eee;
                border: 1px solid #aaa;
                border-radius: 5px;
            }

            .file-show-container{
                display: grid;
                grid-template-columns: 2fr 10fr 1fr;
                background-color: #FAF8FB;
                padding: 6px 12px;
                box-shadow: 1px 1px 1px 1px #cccccc;
                vertical-align: middle;
                border-radius: 10px;
            }

            .file-show-item{
                font-size: 16px;
                font-weight: 600;
                color: #813de8;
                vertical-align: middle;
                padding: 12px 0px;
            }

            .extenstion_name{
                background-color: #7453A6;
                color: #fff;
                text-align: center;
                font-size: 14px;
                font-weight: 500;
                margin-right: 10px;
            }
        </style>
    @endpush
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Contact::label.CONTACTS') @lang('Student::label.INFORMATION')</li>
        <li class="breadcrumb-item active">@lang('Contact::label.ORDER') @lang('Student::label.DETAILS')</li>
        <li class="breadcrumb-item active">{{ $editPage }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $editPage }}
        </h1>
        <a style="margin-left: 10px;" href="{{ route('all.order') }}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Student::label.ALL') @lang('Contact::label.ORDER')</a>
    </div>
    {!! Form::model($orderDataDetails, [
        'method' => 'PATCH',
        'route' => ['order.update', $orderDataDetails->id],
        'id' => 'orderAdd',
        'class' => 'form-horizontal',
        'name' => 'order-add',
        'autocomplete' => true,
        'files' => true,
    ]) !!}

    @include('Order::order.form')

    {!! Form::close() !!}
@endsection
