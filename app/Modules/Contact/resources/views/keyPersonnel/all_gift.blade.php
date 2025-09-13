@extends('Admin::layouts.master')
@section('body')
    <style>
        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        table.dataTable thead {
            background: #40E0D0 !important;
        }

        table.dataTable thead>tr>th {
            font-size: 14px;
            font-weight: 500;
        }

        table.dataTable tbody>tr>td {
            font-size: 14px;
            font-weight: 400;
        }

        table.dataTable thead tr:nth-child(odd):first-child {
            background-color: #40E0D0 !important;
        }

        @media print {

            #notPrintDiv,
            .page-breadcrumb,
            .subheader {
                display: none !important;
            }

            @page {
                size: a4 portrait;
            }

            td,
            th {
                font-size: {{ $reportFontSize }}px;
            }
        }


        td,
        th {
            font-size: {{ $reportFontSize }}px;
        }

        tr:nth-child(even),
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Contact::label.CONTACTS') @lang('Student::label.INFORMATION')</li>
        <li class="breadcrumb-item active">@lang('Contact::label.KEY_PERSONNEL') @lang('Student::label.DETAILS')</li>
        <li class="breadcrumb-item active">@lang('Contact::label.SELECTED') @lang('Contact::label.KEY_PERSONNEL') @lang('Contact::label.ALL') @lang('Contact::label.GIFT')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Contact::label.SELECTED') @lang('Contact::label.KEY_PERSONNEL') @lang('Contact::label.ALL')
            @lang('Contact::label.GIFT')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right">Back</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'selected.keypersonnel.gift.filter',
                            'method' => 'get',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-lg-3 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">From Date</label>
                                        {!! Form::text('from_date', isset($request->from_date) ? $request->from_date : old('from_date'), [
                                            'id' => 'from_date',
                                            'class' => 'form-control from_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">To Date</label>
                                        {!! Form::text('to_date', isset($request->to_date) ? $request->to_date : old('to_date'), [
                                            'id' => 'to_date',
                                            'class' => 'form-control to_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">Key Personnel Name</label><span class="required"> *</span>

                                        {!! Form::Select('customer_id', $keyPersonnel, isset($request->customer_id) ? $request->customer_id : null, [
                                            'id' => 'customer_id',
                                            'class' => 'form-control selectheighttype',
                                            'onchange' => '',
                                            'required' => true,
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 mt-3">

                                <div class="panel-content align-items-end">
                                    <button class="btn btn-primary ml-auto waves-effect waves-themed btn-sm mt-3"
                                        name="submit" type="submit">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>
                        <br>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>




        </div>

        @if ($request->search == 'true')
            <div class="col-12">
                <div class="panel-container font-increase">
                    <div class="panel-content">
                        <div class="frame-wrap">
                            <div class="table-responsive pt-5">
                                <table class="table table-bordered table-striped ytable" id="yajraTable">
                                    <thead class="thead-themed">
                                        <tr>
                                            <th> Sl</th>
                                            <th> Gift Name</th>
                                            <th> Gift Type</th>
                                            <th> Customer Name</th>
                                            <th> Key Personnel Name</th>
                                            <th> Cost</th>
                                            <th> Gift Date</th>
                                            <th> Delivered By</th>
                                            <th> Issued By</th>
                                            <th> Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td> {{ ++$key }}</td>
                                                <td> {{ $item->gift_name }}</td>
                                                <td> {{ $item->gift_type_name }}</td>
                                                <td> {{ $item->customer_name }}</td>
                                                <td> {{ $item->keyPersonnel_name }}</td>
                                                <td> {{ $item->cost }}</td>
                                                <td> {{ $item->gift_date }}</td>
                                                <td> {{ $item->deliveredMan_name }}</td>
                                                <td> {{ $item->issued_by }}</td>
                                                <td width="15%">
                                                    <a href="{{ route('gift.edit', [$item->id]) }}" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id="{{ $item->id }}"><i class="fas fa-edit"></i></a>
                                                    <a href="{{ route('gift.delete', [$item->id]) }}" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id="delete"><i class="fas fa-trash"></i></a>
                                                </td>
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <script type="text/javascript">
        // Select2 use
        $(function() {
            jQuery('.from_date').datepicker({
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                autoClose: true
            });
            jQuery('.to_date').datepicker({
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                autoClose: true
            });
            $("#customer_id").select2();
        });


        $(document).ready(function() {

            // initialize datatable
            $('#yajraTable').dataTable({

                responsive: true,
                lengthChange: false,
                paging: true,
                searching: true,
                order: true,
                bSort: false,
                bPaginate: false,
                bLengthChange: true,
                bInfo: false,
                bAutoWidth: false,
                fixedHeader: {
                    header: true,
                    footer: true
                }
            });

            var searchText = "<?php echo $request->search; ?>";
            // console.log(search);
        });
    </script>
@endsection
