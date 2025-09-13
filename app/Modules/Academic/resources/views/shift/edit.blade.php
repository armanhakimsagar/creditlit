@extends('Admin::layouts.master')
@section('body')

    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Academic::label.SHIFT')</li>
        {{-- <li class="breadcrumb-item active">Shift</li> --}}
        <li class="breadcrumb-item active">@lang('Academic::label.UPDATE_SHIFT')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Academic::label.UPDATE_SHIFT')
            {{-- <small>
                Default input elements for forms
            </small> --}}
        </h1>
        <a style="margin-left: 10px;" href="{{route('shift.index')}}" class="btn btn-success btn-sm waves-effect pull-right">@lang('Academic::label.ALL') @lang('Academic::label.SHIFT')</a>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Academic::label.SHIFT') <span class="fw-300"><i>Update</i></span>
                    </h2>
                    <div class="panel-toolbar">
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                    {!! Form::model($data, ['method' => 'PATCH', 'files'=> true, 'route'=> ['shift.update', $data->id],"class"=>"", 'id' => 'shift']) !!}

                    @include('Academic::shift._form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
