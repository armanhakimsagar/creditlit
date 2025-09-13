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

            .file-show-container {
                background-color: #FAF8FB;
                padding: 6px 12px;
                box-shadow: 1px 1px 1px 1px #cccccc;
                vertical-align: middle;
                border-radius: 10px;
            }

            .file-show-item {
                font-size: 16px;
                font-weight: 600;
                color: #813de8;
                vertical-align: middle;
                padding: 12px 0px;
            }

            .extenstion_name {
                background-color: #7453A6;
                color: #fff;
                text-align: center;
                font-size: 14px;
                font-weight: 500;
                margin-right: 10px;
            }

            .stprimaryinfo,
            .stprimarybutton {
                padding: 0rem 1rem;
            }

            h5 {
                font-weight: 800;
                color: #000;
                font-size: 16px;
            }

            p {
                font-weight: 500;
                font-size: 14px;
            }

            .order-status ul li {
                font-weight: 500;
                font-size: 14px;
                color: #7453A6;
            }

            .u-text-center {
                text-align: center !important;
            }

            .u-text-green {

                color: green !important;
            }

            .u-border-green {
                border-color: green !important'

            }

            .u-typo-36 {
                font-size: 36px;
            }

            .u-typo-24 {
                font-size: 24px;
            }


            .msg {
                padding: 70px 0;
                width: 100%;
                display: block;
                padding: 0 15px;
                height: 45px;
                line-height: 45px;
                border-radius: 5px;
                border: 1px solid #eee;
                margin: 0 0 24px;
            }

            /* &__input
                                
                            
                            &__email */


            .msg__iconwrap {

                width: 85px;
                height: 85px;
                border: 2px solid;
                display: block;
                line-height: 100px;
                border-radius: 50%;
                margin: 40px auto 24px;
            }

            .msg__box {
                height: 250px;
                border-radius: 5px;
            }
        </style>
    @endpush
    @php
        use Carbon\Carbon;
    @endphp
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Contact::label.ORDER') @lang('Student::label.INFORMATION')</li>
        <li class="breadcrumb-item active">@lang('Contact::label.ORDER') @lang('Student::label.DETAILS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Contact::label.ORDER') @lang('Student::label.DETAILS')
        </h1>
        <a style="margin-left: 10px;" href="{{ route('all.order') }}"
            class="btn btn-success btn-sm waves-effect pull-right">@lang('Student::label.ALL') @lang('Contact::label.ORDER')</a>
    </div>

    <div class="row">
        <div class="col-xl-7 col-lg-7">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Contact::label.COMPANY') <span class="fw-300"><i>@lang('Contact::label.INFORMATION')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="stprimary">
                            <div class="stprimaryinfo pb-3">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('company_name', 'Company Name:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                        {!! Form::text('company_name', isset($orderDataDetails) ? $orderDataDetails->company_name : null, [
                                                            'id' => 'company_name',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: Highmark Limited',
                                                            'readonly' => true,
                                                        ]) !!}
                                                        <span class="error"> {!! $errors->first('company_name') !!}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('country', 'Country Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                                        {!! Form::text('country', isset($orderDataDetails) ? $orderDataDetails->country_name : null, [
                                                            'id' => 'country',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: Bangladesh',
                                                            'readonly' => true,
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('phone_no', 'Phone No:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                        {!! Form::number('phone_no', isset($orderDataDetails) ? $orderDataDetails->cm_phone : null, [
                                                            'id' => 'phone_no',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: 01838399293',
                                                            'readonly' => true,
                                                        ]) !!}
                                                        <span class="error"> {!! $errors->first('phone_no') !!}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('website', 'Website:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                        {!! Form::text('website', isset($orderDataDetails) ? $orderDataDetails->cm_website : null, [
                                                            'id' => 'website',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: www.test.com',
                                                            'readonly' => true,
                                                        ]) !!}
                                                        <span class="error"> {!! $errors->first('website') !!}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('email', 'E-mail:', ['class' => 'col-form-label']) !!}

                                                        {!! Form::email('email', isset($orderDataDetails) ? $orderDataDetails->cm_email : null, [
                                                            'id' => 'email',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: test@gmail.com',
                                                            'readonly' => true,
                                                        ]) !!}
                                                        <span class="error"> {!! $errors->first('email') !!}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('company_reg_no', 'Company Reg No:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                        {!! Form::text('company_reg_no', isset($orderDataDetails) ? $orderDataDetails->cm_reg_no : null, [
                                                            'id' => 'company_reg_no',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: TH27383JH77',
                                                            'readonly' => true,
                                                        ]) !!}
                                                        <span class="error"> {!! $errors->first('company_reg_no') !!}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('address', 'Address', ['class' => 'col-form-label']) !!}
                                                        &nbsp;
                                                        {!! Form::textarea('address', isset($orderDataDetails) ? $orderDataDetails->cm_address : null, [
                                                            'class' => 'form-control',
                                                            'rows' => 3,
                                                            'id' => 'address',
                                                            'placeholder' => 'e.g: Uttara SME Service Center, Holding- 18 Sonargaon Janapath, Sector# 09 Uttara, 1230',
                                                            'style' => 'height:80px;',
                                                            'readonly' => true,
                                                        ]) !!}
                                                        <span class="error"> {!! $errors->first('address') !!}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('note', 'Note', ['class' => 'col-form-label']) !!}
                                                        &nbsp;
                                                        {!! Form::textarea('note', isset($orderDataDetails) ? $orderDataDetails->cm_note : null, [
                                                            'class' => 'form-control',
                                                            'rows' => 3,
                                                            'id' => 'note',
                                                            'placeholder' => 'Enter Address',
                                                            'style' => 'height:80px;',
                                                            'readonly' => true,
                                                        ]) !!}
                                                        <span class="error"> {!! $errors->first('note') !!}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Contact::label.CUSTOMER') <span class="fw-300"><i>@lang('Contact::label.INFORMATION')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="stprimary">
                            <div class="stprimaryinfo pb-3">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="row">

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('customer_type', 'Customer Type', ['class' => 'col-form-label']) !!}

                                                        <input type="text" class="form-control" placeholder="e.g: Bank"
                                                            readonly
                                                            value="{{ isset($orderDataDetails) ? ($orderDataDetails->customer_type == 'bank' ? 'Bank' : ($orderDataDetails->customer_type == 'branch' ? 'Branch' : 'Company')) : '' }}">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('bank_id', 'Customer Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                                                        <input type="text" class="form-control"
                                                            placeholder="e.g: Key Personnel Name"
                                                            value="{{ isset($customer) ? $customer : '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('branch_id', 'Branch Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                                                        <input type="text" class="form-control"
                                                            placeholder="e.g: Key Personnel Name"
                                                            value="@if (isset($branchId)) {{ $branchId }} @endif"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('bank_reference', 'Bank Referance:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                        {!! Form::text('bank_reference', isset($orderDataDetails) ? $orderDataDetails->bank_reference : null, [
                                                            'id' => 'bank_reference',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: SE2938u2',
                                                            'readonly' => true,
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('keyPersonnel', 'Key Personnel Name', ['class' => 'col-form-label']) !!}

                                                        <input type="text" class="form-control"
                                                            placeholder="e.g: Key Personnel Name"
                                                            value="{{ isset($orderDataDetails) ? $orderDataDetails->key_personnel_name : '' }}"
                                                            readonly>

                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('cp_phone_no', 'Phone No:', ['class' => 'col-form-label']) !!}

                                                        {!! Form::number('cp_phone_no', $orderDataDetails->key_personnel_phone ?? null, [
                                                            'id' => 'cp_phone_no',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter Phone Number',
                                                            'readonly' => true,
                                                        ]) !!}
                                                        <span class="error"> {!! $errors->first('cp_phone_no') !!}</span>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="hidden" name="selling_price" id="selling_price"
                                                            value="@if (isset($orderDataDetails->selling_price)) {{ $orderDataDetails->selling_price }} @endif">
                                                        <span class="error"> {!! $errors->first('selling_price') !!}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Contact::label.SUPPLIER') <span class="fw-300"><i>@lang('Contact::label.INFORMATION')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="stprimary">
                            <div class="stprimaryinfo pb-3">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="row">


                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('supplier_id', 'Supplier Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                                        {!! Form::text('supplier_reference', isset($orderDataDetails) ? $orderDataDetails->supplier_name : null, [
                                                            'id' => 'supplier_reference',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: Supplier Name',
                                                            'readonly' => true,
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('supplier_reference', 'Supplier Reference:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                        {!! Form::text('supplier_reference', isset($orderDataDetails) ? $orderDataDetails->supplier_reference : null, [
                                                            'id' => 'supplier_reference',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'e.g: SUP2893892',
                                                            'readonly' => true,
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="hidden" name="buying_price" id="buying_price"
                                                            value="@if (isset($orderDataDetails->buying_price)) {{ $orderDataDetails->buying_price }} @endif">
                                                        <span class="error"> {!! $errors->first('buying_price') !!}</span>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-5 col-lg-5">

            <div id="panel-1" class="panel">
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="stprimary">
                            <div class="stprimaryinfo pb-3">
                                <div class="row">

                                    <div class="col-lg-12 col-md-12">
                                        <div class="row">

                                            <!-- Upload Button trigger modal -->
                                            <div class="col-lg-12 col-md-12 mt-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <button type="button" class="btn btn-success mr-1"
                                                            data-toggle="modal" data-target="#uploadDrop">
                                                            Upload
                                                        </button>

                                                        <button type="button" class="btn btn-info mr-1"
                                                            data-toggle="modal" data-target="#statusDrop">
                                                            Status
                                                        </button>

                                                        <button type="button" class="btn btn-secondary mr-1"
                                                            data-toggle="modal" data-target="#statusDrop">
                                                            Mail
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Upload Modal -->
                                            <div class="modal fade" id="uploadDrop" data-backdrop="static"
                                                data-keyboard="false" tabindex="-1" aria-labelledby="uploadDropLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog">

                                                    {!! Form::open([
                                                        'method' => 'PATCH',
                                                        'route' => ['order.attachment.update', $orderDataDetails->id],
                                                        'id' => 'orderAttachmentUpdate',
                                                        'class' => 'form-horizontal',
                                                        'name' => 'order-attachment-update',
                                                        'autocomplete' => true,
                                                        'files' => true,
                                                    ]) !!}

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="uploadDropLabel">Upload Attachment
                                                            </h5>
                                                        </div>
                                                        
                                                        @if ($orderDataDetails->order_status != 'delivered')
                                                        <div class="modal-body">
                                                            <div class="form-line">
                                                                <div class="form-group">
                                                                    <label for="formFile" class="form-label">Select an
                                                                        Attachment</label>
                                                                    <input class="form-control" type="file"
                                                                        id="formFile" name="attachment" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                        
                                                        @else
                                                        <div class="u-text-center msg__box">
                                                            <div class="msg__box__inner">
                                                                <span class="msg__iconwrap u-border-green">
                                                                    <i class="fas fa-check u-text-green u-typo-36"></i>
                                                                </span>
                                                                <p class="u-mTop-24 u-typo-24 u-text-green">Your Order
                                                                    Successfully Delivered</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    {!! Form::close() !!}
                                                </div>
                                            </div>


                                            <!-- Status Modal -->
                                            <div class="modal fade" id="statusDrop" data-keyboard="false" tabindex="-1"
                                                aria-labelledby="statusDropLabel" aria-hidden="true">
                                                {!! Form::open([
                                                    'method' => 'PATCH',
                                                    'route' => ['order.status.update', $orderDataDetails->id],
                                                    'id' => 'orderStatusUpdate',
                                                    'class' => 'form-horizontal',
                                                    'name' => 'order-status-update',
                                                    'autocomplete' => true,
                                                    'files' => true,
                                                ]) !!}

                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        @if ($orderDataDetails->order_status != 'delivered')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="statusDropLabel">Update Order
                                                                    Status
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="form-line">

                                                                    {!! Form::label('update_order_status', 'Update Order Status', ['class' => 'col-form-label']) !!}<span class="required">
                                                                        *</span>
                                                                    @if ($orderDataDetails->order_status == 'pending')
                                                                        {!! Form::Select(
                                                                            'order_status',
                                                                            ['processing' => 'Processing', 'query' => 'Query', 'cancel' => 'Cancel'],
                                                                            'active',
                                                                            [
                                                                                'id' => 'update_order_status',
                                                                                'class' => 'form-control selectheighttype',
                                                                            ],
                                                                        ) !!}
                                                                    @endif
                                                                    @if ($orderDataDetails->order_status == 'processing')
                                                                        {!! Form::Select(
                                                                            'order_status',
                                                                            ['query' => 'Query', 'completed' => 'Completed', 'cancel' => 'Cancel'],
                                                                            'active',
                                                                            [
                                                                                'id' => 'update_order_status',
                                                                                'class' => 'form-control selectheighttype',
                                                                            ],
                                                                        ) !!}
                                                                    @endif
                                                                    @if ($orderDataDetails->order_status == 'query')
                                                                        {!! Form::Select('order_status', ['completed' => 'Completed', 'cancel' => 'Cancel'], 'active', [
                                                                            'id' => 'update_order_status',
                                                                            'class' => 'form-control selectheighttype',
                                                                        ]) !!}
                                                                    @endif
                                                                    @if ($orderDataDetails->order_status == 'completed')
                                                                        {!! Form::Select(
                                                                            'order_status',
                                                                            ['delivered' => 'Delivered', 'query' => 'Query', 'cancel' => 'Cancel'],
                                                                            'active',
                                                                            [
                                                                                'id' => 'update_order_status',
                                                                                'class' => 'form-control selectheighttype',
                                                                            ],
                                                                        ) !!}
                                                                    @endif




                                                                    {!! Form::label('note', 'Note', ['class' => 'col-form-label']) !!}
                                                                    &nbsp;
                                                                    {!! Form::textarea('note', null, [
                                                                        'class' => 'form-control',
                                                                        'rows' => 3,
                                                                        'id' => 'note',
                                                                        'placeholder' => 'Enter Address',
                                                                        'style' => 'height:80px;',
                                                                    ]) !!}


                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Save</button>
                                                            </div>
                                                        @else
                                                            <div class="u-text-center msg__box">
                                                                <div class="msg__box__inner">
                                                                    <span class="msg__iconwrap u-border-green">
                                                                        <i class="fas fa-check u-text-green u-typo-36"></i>
                                                                    </span>
                                                                    <p class="u-mTop-24 u-typo-24 u-text-green">Your Order
                                                                        Successfully Delivered</p>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {!! Form::close() !!}
                                            </div>


                                        </div>



                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Contact::label.ORDER') <span class="fw-300"><i>@lang('Contact::label.INFORMATION')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="stprimary">
                            <div class="stprimaryinfo pb-3">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="row">


                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <h5 class="mt-2">Order ID</h5>
                                                        <p>{{ $orderDataDetails->order_invoice_no }}</p>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <h5 class="mt-2">Order Status</h5>
                                                        <p>
                                                            @if ($orderDataDetails->delivered_status == 1)
                                                                <span class="u-text-green">Delivered</span>
                                                            @elseif($orderDataDetails->completed_status == 1)
                                                                Completed
                                                            @elseif($orderDataDetails->cancel_status == 1)
                                                                Canceled
                                                            @elseif($orderDataDetails->query_status == 1)
                                                                Queried
                                                            @elseif($orderDataDetails->processing_status == 1)
                                                                Processing
                                                            @elseif($orderDataDetails->pending_status == 1)
                                                                Pending
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-lg-12 col-md-12 order-status">
                                                <ul style="list-style-type:circle;">
                                                    @foreach ($order_status_timeline as $item)
                                                        @if ($item->order_status == 'pending')
                                                            <li>Order Placed by <span>{{ $item->full_name }}</span>
                                                                @if (!empty($item->note))  
                                                                        <a class="mt-2 text-primary waves-effect" data-toggle="tooltip" data-placement="top" title="{{ $item->note }}"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                                                    @endif
                                                                </li>
                                                            <label
                                                                for="">{{ Carbon::parse($item->order_time)->format('M j, Y, h:i:s A') }}</label>
                                                        @elseif($item->order_status == 'processing')
                                                            <li>Order Processed by <span>{{ $item->full_name }}</span>
                                                                @if (!empty($item->note))  
                                                                    <a class="mt-2 text-primary waves-effect" data-toggle="tooltip" data-placement="top" title="{{ $item->note }}"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                                                @endif
                                                            </li>
                                                            <label
                                                                for="">{{ Carbon::parse($item->order_time)->format('M j, Y, h:i:s A') }}</label>
                                                        @elseif($item->order_status == 'query')
                                                            <li>Order Queried by <span>{{ $item->full_name }}</span>
                                                                @if (!empty($item->note))  
                                                                    <a class="mt-2 text-primary waves-effect" data-toggle="tooltip" data-placement="top" title="{{ $item->note }}"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                                                @endif
                                                            </li>
                                                            <label
                                                                for="">{{ Carbon::parse($item->order_time)->format('M j, Y, h:i:s A') }}</label>
                                                        @elseif($item->order_status == 'cancel')
                                                            <li>Order Canceled by <span>{{ $item->full_name }}</span>
                                                                @if (!empty($item->note))  
                                                                    <a class="mt-2 text-primary waves-effect" data-toggle="tooltip" data-placement="top" title="{{ $item->note }}"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                                                @endif
                                                            </li>
                                                            <label
                                                                for="">{{ Carbon::parse($item->order_time)->format('M j, Y, h:i:s A') }}</label>
                                                        @elseif($item->order_status == 'completed')
                                                            <li>Order Completed by <span>{{ $item->full_name }}</span>
                                                                @if (!empty($item->note))  
                                                                    <a class="mt-2 text-primary waves-effect" data-toggle="tooltip" data-placement="top" title="{{ $item->note }}"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                                                @endif
                                                            </li>
                                                            <label
                                                                for="">{{ Carbon::parse($item->order_time)->format('M j, Y, h:i:s A') }}</label>
                                                        @elseif($item->order_status == 'delivered')
                                                            <li>Order Delivered by <span>{{ $item->full_name }}</span>
                                                                @if (!empty($item->note))  
                                                                    <a class="mt-2 text-primary waves-effect" data-toggle="tooltip" data-placement="top" title="{{ $item->note }}"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                                                @endif
                                                            </li>
                                                            <label
                                                                for="">{{ Carbon::parse($item->order_time)->format('M j, Y, h:i:s A') }}</label>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            @php
                $allAttachment = json_decode($orderDataDetails->attachment);
                if (isset($allAttachment)) {
                    $hasAttachment = count($allAttachment);
                } else {
                    $hasAttachment = 0;
                }
            @endphp
            @if ($hasAttachment > 0)
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Attachment
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="stprimary">
                                <div class="stprimaryinfo pb-3">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="row">
                                                @foreach ($allAttachment as $item)
                                                    @if (file_exists(public_path('/backend/images/order_attachment/' . $item)))
                                                        <div class="col-lg-12 col-md-12 mt-2 mb-2">
                                                            <div class="form-group">
                                                                <div class="card">
                                                                    <div class="file-show-container">
                                                                        <div class="row">
                                                                            <div class="col-3">
                                                                                <div
                                                                                    class="file-show-item extenstion_name">
                                                                                    @if (pathinfo($item, PATHINFO_EXTENSION) == 'docx')
                                                                                        DOCX
                                                                                    @elseif(pathinfo($item, PATHINFO_EXTENSION) == 'pdf')
                                                                                        PDF
                                                                                    @elseif(pathinfo($item, PATHINFO_EXTENSION) == 'csv')
                                                                                        CSV
                                                                                    @elseif(pathinfo($item, PATHINFO_EXTENSION) == 'ods')
                                                                                        ODS
                                                                                    @elseif(pathinfo($item, PATHINFO_EXTENSION) == 'xlsx')
                                                                                        XLSX
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-7">
                                                                                <div class="file-show-item"
                                                                                    style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                                                    title="{{ $item }}">
                                                                                    {{ $item }}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <div class="file-show-item">
                                                                                    <a href="{{ asset(config('app.asset') . 'backend/images/order_attachment/' . $item) }}"
                                                                                        download="">
                                                                                        <i class="fa fa-download"
                                                                                            aria-hidden="true"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>




                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif



        </div>

    </div>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        $(function() {
            // $('#update_order_status').select2();

            $("#fileupload").change(function(event) {
                var x = URL.createObjectURL(event.target.files[0]);
                $("#upload-img").attr("src", x);
                console.log(event);
            });

        });
    </script>


    {!! Form::close() !!}
@endsection
