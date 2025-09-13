@extends('Admin::layouts.master')
@section('body')

    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Academic::label.ACADEMICS')</li>
        <li class="breadcrumb-item active">@lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Academic::label.UPDATE') @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')
        </h1>
        <a style="margin-left: 10px;" href="{{route('student.type.index')}}" class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE') <span class="fw-300"><i>@lang('Academic::label.UPDATE')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                    {!! Form::model($data, ['method' => 'PATCH', 'files'=> true, 'route'=> ['student.type.update', $data->id], "class"=>"", 'id' => 'studentType']) !!}

                    @include('Academic::studentType._form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
