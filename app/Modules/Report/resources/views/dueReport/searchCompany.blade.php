@extends('Admin::layouts.master')
@section('body')
    <style>
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
                font-size: 12px;
            }
        }


        td,
        th {
            font-size: 12px;
        }

        tr:nth-child(even),
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active"> Search Company</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> Search Company
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
                            'route' => 'search.company.submit',
                            'method' => 'post',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">Country</label>
                                        <select class="form-control" required>
                                            <option value="">Select Country</option>
                                            <option value="">China</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <div class="form-line">
                                        <label class="col-form-label">Company Name</label>
                                        <input type="text" class="form-control" name="company_name"
                                            placeholder="Company Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <div class="panel-content align-items-center float-right">
                                    <button class="btn btn-primary ml-auto waves-effect waves-themed btn-sm mt-3"
                                        name="submit" type="submit">Search</button>

                                </div>
                            </div>
                        </div>
                        <br>

                    </div>
                </div>
            </div>

        </div>




        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="panel-content">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Company Name</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @if (isset($result['result']['orgs']))
                                @foreach ($result['result']['orgs'] as $org)
                                    <tbody>
                                        <tr>
                                            <td>{{ $org['id'] ?? 'N/A' }}</td>
                                            <td>{{ $org['englishName'] ?? 'N/A' }}</td>
                                            <td>{{ $org['address'] ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('generate.company.order', ['id' => $org['id'], 'company_name' => $org['name'], 'name' => $org['englishName']]) }}"
                                                    class="btn btn-primary">Generate</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            @endif
                        </table>

                    </div>
                </div>

            </div>

            <script type="text/javascript">
                $(document).ready(function() {
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

                });
                $(document).ready(function() {

                    $('.chart-of-expense-select2').select2({
                        width: "100%",
                        placeholder: "Select Chart of Expense",
                    });
                });

                function printDiv() {
                    var printContents = document.getElementById('printMe').innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                }

                $('input[type=text]').attr('autocomplete', 'off');
                $('input[type=number]').attr('autocomplete', 'off');
                $(document).ready(function() {

                    // initialize datatable
                    $('#testTable').dataTable({

                        responsive: true,
                        lengthChange: false,
                        paging: false,
                        searching: false,
                        order: false,
                        bSort: false,
                        bPaginate: false,
                        bLengthChange: false,
                        bInfo: false,
                        bAutoWidth: false,
                        fixedHeader: {
                            header: true,
                            footer: true
                        },
                        dom:
                            /*  --- Layout Structure
                                --- Options
                                l   -   length changing input control
                                f   -   filtering input
                                t   -   The table!
                                i   -   Table information summary
                                p   -   pagination control
                                r   -   processing display element
                                B   -   buttons
                                R   -   ColReorder
                                S   -   Select

                                --- Markup
                                < and >             - div element
                                <"class" and >      - div with a class
                                <"#id" and >        - div with an ID
                                <"#id.class" and >  - div with an ID and a class

                                --- Further reading
                                https://datatables.net/reference/option/dom
                                --------------------------------------
                             */
                            "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                        buttons: [{
                            extend: 'excelHtml5',
                            text: 'Excel',
                            filename: 'Order Report',
                            titleAttr: 'Generate Excel',
                            className: 'btn-outline-success btn-sm mr-1'
                        }]
                    });

                });
            </script>
        @endsection
