@extends('Admin::layouts.master')
@section('body')

    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Examination::label.EXAM_RESULT')</li>
        <li class="breadcrumb-item active">@lang('Examination::label.EXAM')</li>
        {{-- <li class="breadcrumb-item active">Exam</li> --}}
        <li class="breadcrumb-item active">@lang('Academic::label.EDIT') @lang('Examination::label.EXAM')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Academic::label.EDIT') @lang('Examination::label.EXAM')
            {{-- <small>
                Default input elements for forms
            </small> --}}
        </h1>
        <a style="margin-left: 10px;" href="{{route('exam.index')}}" class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Examination::label.EXAM')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Examination::label.EXAM') <span class="fw-300"><i>@lang('Academic::label.EDIT')</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                    {!! Form::model($exam, ['method' => 'PATCH', 'files'=> true, 'route'=> ['exam.update', $exam->id], 'id' => 'exam']) !!}

                    @include('Examination::exam._form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
