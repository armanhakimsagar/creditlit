@extends('Admin::layouts.master')
@section('body')

<ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Easca-Invoice</a></li>
        <li class="breadcrumb-item active">Reset Password</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> Password Reset
            {{-- <small>
                Default input elements for forms
            </small> --}}
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()" class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        Reset <span class="fw-300"><i>Password</i></span>
                    </h2>
                    <div class="panel-toolbar">
                        {{-- <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                        <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                        <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button> --}}
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">

                    {!! Form::model($data, ['method' => 'PATCH', 'files'=> true, 'route'=> ['user.password.update', $data->id],"class"=>"form-horizontal", 'id' => 'password_resetfrom']) !!}

                    <div class="row">

                        <div class="col-md-12">
                            @include('User::user._form_password')
                        </div>

                    </div>

                    {!! Form::close() !!}
   
@endsection