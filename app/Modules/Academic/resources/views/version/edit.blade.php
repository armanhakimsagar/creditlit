@extends('Admin::layouts.master')
@section('body')

    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Academic::label.VERSION')</li>
        {{-- <li class="breadcrumb-item active">Version</li> --}}
        <li class="breadcrumb-item active">@lang('Academic::label.UPDATE_VERSION')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Academic::label.UPDATE_VERSION')
            {{-- <small>
                Default input elements for forms
            </small> --}}
        </h1>
        <a style="margin-left: 10px;" href="{{route('version.index')}}" class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Academic::label.VERSION')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Academic::label.VERSION') <span class="fw-300"><i>Update</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                    {!! Form::model($version, ['method' => 'PATCH', 'files'=> true, 'route'=> ['version.update', $version->id],"class"=>"", 'id' => 'version']) !!}

                    @include('Academic::version._form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
