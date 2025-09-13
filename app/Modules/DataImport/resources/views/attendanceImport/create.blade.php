@extends('Admin::layouts.master')

@section('body')

{{-- <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Easca-Invoice</a></li>
        <li class="breadcrumb-item">Sales</li>
        <li class="breadcrumb-item active">Sales</li>
        <li class="breadcrumb-item active">Add Sales</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol> --}}
{{-- <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> Add Sales --}}
{{-- <small>
                Default input elements for forms
            </small> --}}
{{-- </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()" class="btn btn-warning waves-effect pull-right">Back</a>
    </div> --}}
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    {{$headingText}}
                </h2>
                <a style="margin-left: 10px;" href="{{ asset(config('app.asset') . 'exportfile/attendance/attendance_list_sheet.xlsx') }}" class="btn btn-success waves-effect pull-right"><i class="fa fa-download" aria-hidden="true"></i></a>
                <a style="margin-left: 10px;" href="javascript:history.back()" class="btn btn-warning waves-effect pull-right">Back</a> &nbsp;&nbsp;
                <div class="panel-toolbar">
                    {{-- <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                        <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                        <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button> --}}
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    {!! Form::open(['route' => $formRoute, 'files'=> true, 'id'=>'import', 'class' => 'form-horizontal','name'=>'import']) !!}

                    @include('DataImport::attendanceImport.form')

                    {!! Form::close() !!}

                    <div class="row clearfix" style="margin-top: 100px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                @if(!$batchData->isEmpty())
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="testTable">
                                            <thead class="thead-themed">
                                                <tr>
                                                    <th>SL No</th>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total_rows = 0;
                                                $uniqueKey = 0;
                                                ?>
                                                @foreach($batchData as $values)
                                                <tr>
                                                    <td>{{ ++$total_rows }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($values->created_at)) }}</td>
                                                    <td>{{ $values->name }}</td>
                                                    <td class="text-center">
                                                    <a href="{{ route($route,$values->id) }}" style="font-size: 22px;"><i class="fab fa-delicious"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endsection