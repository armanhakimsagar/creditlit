@extends('Admin::layouts.master')
@section('body')
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Company::label.INSTITUTION') @lang('Company::label.SETTINGS')</li>
            <li class="breadcrumb-item active">@lang('Company::label.INSTITUTION')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Company::label.INSTITUTION')
            </h1>
            @if (count($data) == 0)
                <a href="{{ route('institution.create') }}" class="btn btn-primary waves-effect pull-right m-l-10">@lang('Company::label.ADD') @lang('Company::label.INSTITUTION')</a>
            @endif
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Company::label.INSTITUTION') <span class="fw-300"><i></i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead class="thead-themed">
                                    <tr>
                                        <th> No</th>
                                        <th> Institution Name</th>
                                        <th> Address</th>
                                        <th> Phone </th>
                                        <th> Email </th>
                                        <th> Image </th>
                                        <th> Status</th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if (count($data) > 0)
                                        @foreach ($data as $key => $values)
                                            <tr>
                                                <td>
                                                    {{ ($data->currentpage() - 1) * $data->perpage() + $key + 1 }}
                                                </td>

                                                <td>
                                                    {{ $values->company_name }}
                                                </td>
                                                <td>
                                                    {{ $values->address }}
                                                </td>
                                                <td>
                                                    {!! $values->phone !!}
                                                </td>
                                                <td>
                                                    {!! $values->email !!}
                                                </td>
                                                <td>
                                                    @if ($values->image !='')
                                                        <img width="50" height="50"
                                                            src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $values->image }}"
                                                            class="example-p-5" id="{{ $values->id }}">
                                                    @endif
                                                </td>
                                                @if ($values->status == 'cancel')
                                                    <td style="color: red;">
                                                    @else
                                                    <td>
                                                @endif
                                                {{ $values->status }}
                                                </td>
                                                <td>
                                                    <a href=""
                                                        class="demo-google-material-icon"><i class="fal fa-eye"></i></a>
                                                    <a href="{{ route('institution.edit', $values->id) }}"
                                                        class="demo-google-material-icon"><i class="fal fa-edit"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
