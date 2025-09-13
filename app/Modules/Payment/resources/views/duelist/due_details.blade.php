@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            .marksheet-details-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-auto-flow: column;
                background-color: #EEEEEE;
                padding: 10px;
                /* -webkit-print-color-adjust: exact; */

            }

            .marksheet-details-item {
                background-color: #EEEEEE;
                /* -webkit-print-color-adjust: exact; */
            }

            .marksheet-details-container th,
            .marksheet-details-container td {
                border: 1px solid #ffffff;
                /* -webkit-print-color-adjust: exact; */
            }

            .marksheet-details-container tr:nth-child(even) {
                background-color: #EEEEEE;
            }

            @media print {

                .page-breadcrumb,
                .subheader {
                    display: none;
                }
            }
        </style>
    @endpush
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Payment::label.DUELIST')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> {{ $data->full_name }} @lang('Payment::label.DUELIST')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        {{ $data->full_name }} <span class="fw-300"><i>@lang('Payment::label.DUE') @lang('Academic::label.LIST')</i></span>
                    </h2>
                </div>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="marksheet-details-container">
                            <div class="marksheet-details-item">
                                <table width="100%" class="table">
                                    <tbody>
                                        <tr>
                                            <td width="22%" align="left" valign="middle">Name :</td>
                                            <td width="39%" align="left" valign="middle" id="studentName">
                                                {{ $data->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Student ID :</td>
                                            <td align="left" valign="middle">
                                                {{ $data->contact_id }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Roll No :</td>
                                            <td align="left" valign="middle" id="classRoll">{{ $data->class_roll }}</td>
                                        </tr>
                                        <tr id="registrationNo">
                                            <td align="left" valign="middle">Registration No :</td>
                                            <td align="left" valign="middle">{{ $data->registration_no }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Gender :</td>
                                            <td align="left" valign="middle">{{ $data->gender }}</td>
                                        </tr>
                                </table>
                            </div>

                            <div class="marksheet-details-item">
                                <table width="100%" class="table">
                                    <tbody>
                                        <tr>
                                            <td align="left" valign="middle">Academic Year :</td>
                                            <td align="left" valign="middle">{{ $data->year }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Class :</td>
                                            <td align="left" valign="middle">{{ $data->class_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Section :</td>
                                            <td align="left" valign="middle">{{ $data->section_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Version :</td>
                                            <td align="left" valign="middle">{{ $data->version_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Shift :</td>
                                            <td align="left" valign="middle">{{ $data->shift_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ytable" id="yajraTable">
                                <thead class="thead-themed">
                                    <tr>
                                        <th> Sl</th>
                                        <th> Item Name</th>
                                        <th> Month</th>
                                        <th> Amount</th>
                                        <th> Paid</th>
                                        <th> Due</th>
                                        <th> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $key => $item)
                                        <tr>
                                            <td> {{ ++$key }}</td>
                                            <td> {{ $item->item_name }}</td>
                                            <td> {{ $item->month_name }}</td>
                                            <td> {{ $item->amount }}</td>
                                            <td> {{ $item->paid_amount }}</td>
                                            <td> {{ $item->due }}</td>
                                            <td>
                                                @if ($item->paid_amount <= 0)
                                                <a href="" class="btn btn-primary edit" data-id="{{ $item->id }}" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></a> <a href="{{ route('due.item.delete', $item->id) }}" class="btn btn-danger" id="delete"><i class="fas fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <td colspan="6" class="text-right bg-white h6" style="padding-right: 5%;">Total Due :
                                        {{ $data->student_due }}</td>
                                        <td></td>
                                </tbody>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed float-right"
                                            onclick="window.print();" id="btnsm" type="submit">Print</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('Payment::label.EDIT') @lang('Payment::label.DUELIST')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal_body">

                    </div>
                </div>
            </div>
        </div>


    @section('javaScript')
        <script type="text/javascript">
            $('body').on('click', '.edit', function() {
                let item_id = $(this).data('id');
                $.get("due-item/edit/" + item_id, function(data) {
                    $("#modal_body").html(data);
                });
            });
        </script>
    @endsection
@endsection
