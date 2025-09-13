@extends('Admin::layouts.master')
@section('body')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Company::label.SETTINGS')</li>
        <li class="breadcrumb-item active">@lang('Company::label.INSTITUTION') @lang('Company::label.SETTINGS')</li>
        <li class="breadcrumb-item active">@lang('Company::label.SMS') @lang('Company::label.SETTINGS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Company::label.ADD') @lang('Company::label.SMS') @lang('Company::label.SETTINGS')
            {{-- <small>
                Default input elements for forms
            </small> --}}
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Company::label.SMS') @lang('Company::label.SETTINGS') <span class="fw-300"><i>@lang('Company::label.ADD')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                        {{-- <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                        <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                        <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button> --}}
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">

                        {!! Form::open([
                            'route' => 'admin.sms.store',
                            'files' => true,
                            'id' => 'notificationform',
                            'class' => 'form-horizontal',
                        ]) !!}
                        <div class="row">


                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="smsEnableAfterPayment"
                                                @if ($data->smsEnableAfterPayment) @checked(true) @endif
                                                id="smsEnableAfterPayment" value="1">
                                            <label class="custom-control-label"
                                                for="smsEnableAfterPayment">@lang('Company::label.ENABLE') @lang('Company::label.SMS')
                                                @lang('Company::label.AFTER_PAYMENT')</label>
                                        </div>
                                        <div>
                                            <br>
                                            <b>KeyWords:</b> <span> {GuardianName},{StudentName}, {PaidAmount}, {Items},
                                                {SID}, {TotalAmount}</span>
                                            <br>
                                            <br>
                                            <textarea id="smsAfterStudentPaymentFormat" class="form-control" required="required" style="height:50px"
                                                placeholder="Enter Message Format" name="smsAfterStudentPaymentFormat" cols="50" rows="10">{{ $data->smsAfterStudentPaymentFormat }}</textarea><br>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="smsEnableDueSms"
                                                @if ($data->smsEnableDueSms) @checked(true) @endif id="smsEnableDueSms"
                                                value="1">
                                            <label class="custom-control-label" for="smsEnableDueSms">@lang('Company::label.ENABLE')
                                                @lang('Company::label.SMS') @lang('Company::label.FOR') @lang('Company::label.STUDENT_DUE')</label>
                                        </div>
                                        <div>
                                            <br>
                                            <b>KeyWords: </b><span>{GuardianName}, {StudentName}, {SID}, {Due}</span>
                                            <br>
                                            <br>
                                            <br>
                                            <textarea id="smsStudentDueFormat" class="form-control" required="required" style="height:50px"
                                                placeholder="Enter Message Format" name="smsStudentDueFormat" cols="50" rows="10">{{ $data->smsStudentDueFormat }}</textarea><br>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="smsEnableOwnerSms"
                                                @if ($data->smsEnableOwnerSms) @checked(true) @endif id="smsEnableOwnerSms"
                                                value="1">
                                            <label class="custom-control-label" for="smsEnableOwnerSms">@lang('Company::label.ENABLE')
                                                @lang('Company::label.SMS') @lang('Company::label.FOR') @lang('Company::label.OWNER_SMS')</label>
                                        </div>
                                        <div>
                                            <br>
                                            <b>KeyWords: </b><span>{Expense}, {Recieve}, {Cashbook}, {Bankbook}</span>
                                            <br>
                                            <br>
                                            <br>
                                            <textarea id="ownerSmsFormat" class="form-control" required="required" style="height:50px"
                                                placeholder="Enter Message Format" name="ownerSmsFormat" cols="50" rows="10">{{ $data->ownerSmsFormat }}</textarea><br>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                            </div>


                            <div class="col-md-3 mt-5">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="smsEnableGuardianSms"
                                                @if ($data->smsEnableGuardianSms) @checked(true) @endif value="1"
                                                id="smsEnableGuardianSms">
                                            <label class="custom-control-label"
                                                for="smsEnableGuardianSms">@lang('Company::label.ENABLE') @lang('Company::label.SMS')
                                                @lang('Company::label.FOR') @lang('Company::label.GUARDIAN_SMS')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-5">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="smsEnableTeacherSms"
                                                @if ($data->smsEnableTeacherSms) @checked(true) @endif value="1"
                                                id="smsEnableTeacherSms">
                                            <label class="custom-control-label" for="smsEnableTeacherSms">Enable SMS For
                                                Teacher SMS</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mt-5">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input"
                                                name="smsEnableDynamicSms"
                                                @if ($data->smsEnableDynamicSms) @checked(true) @endif value="1"
                                                id="smsEnableDynamicSms">
                                            <label class="custom-control-label"
                                                for="smsEnableDynamicSms">@lang('Company::label.ENABLE') @lang('Company::label.SMS')
                                                @lang('Company::label.FOR') @lang('Company::label.DYNAMIC_SMS')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="col-md-12">

                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">


                                    <button class="btn btn-primary ml-auto waves-effect waves-themed" id="btnsm"
                                        type="submit">Save</button>
                                </div>
                            </div>



                        </div>

                        {!! Form::close() !!}



                    </div>
                </div>
            </div>
        </div>
    @endsection
