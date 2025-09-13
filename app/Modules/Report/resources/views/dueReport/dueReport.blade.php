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

            td,th {
                font-size: {{ $reportFontSize }}px;
            }
        }

        td,th {
            font-size: {{ $reportFontSize }}px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active">@lang('Student::label.STUDENT') @lang('Student::label.COLLECTION') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            {{-- <i class='subheader-icon fal fa-list'></i> @lang('Student::label.STUDENT') @lang('Student::label.COLLECTION') @lang('Student::label.REPORTS') --}}
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
                            'route' => 'due.report.filter',
                            'method' => 'post',
                            'id' => 'collectionReportId',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-3">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('academicYearId', 'Academic Year', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('academic_year_id', $academic_year_list, !empty($request->yearId) ? $request->yearId : null, [
                                            'id' => 'academicYearId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getStudents();getSection()',
                                        ]) !!}
                                        <span class="error" id="yearError"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('monthId', 'Month', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('month_id', $month_list, !empty($request->monthId) ? $request->monthId : null, [
                                            'id' => 'monthId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getStudents();getSection()',
                                        ]) !!}
                                        <span class="error" id="yearError"></span>
                                    </div>
                                </div>
                            </div>
                    
                            
                            <div class="col-md-12">

                                <div class="panel-content d-flex flex-row align-items-center float-right">
                                    <button class="btn btn-primary  waves-effect waves-themed btn-sm mt-6" name="submit"
                                        onclick="validate()" type="button" id="saveBtn">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>
                        <br>
                        @php
                            $secArrId = '';
                            $studentArrId = '';
                        @endphp
                        {!! Form::close() !!}
                        @if($request->search == 'true')
                        @php
                            $secArrId = implode(",",!empty(json_decode($request->section_id)) ? json_decode($request->section_id) : []);
                            $studentArrId = implode(",",!empty(json_decode($request->student_id)) ? json_decode($request->student_id) : []);
                        @endphp
                        
                        <div class="subheader">
                            <button style="margin-left: auto;" class="btn btn-info btn-sm print_full_data mt-5"
                                onclick="window.print();">Print</button>
                        </div>
                        <div id='printMe'>
                     {{--        <center><div class="row">
                                   <div class="col-md-12">
                                       <img style="height: 50px;"
                                       src="{{ asset(config('app.asset') . 'uploads/company/menu-logo.svg') }}">

                                           <h2>{{ $companyDetails->company_name }}</h2>

                                   </div>
                               </div>
                               <div class="row">
                                   <div class="col-md-12">
                                      <span style="font-size:{{Session::get('fontsize')}}px;">{{ $companyDetails->address }}</span><br>
                                      <p  style="font-size:{{Session::get('fontsize')}}px;">Tel: {{ $companyDetails->phone }}, Email: {{ $companyDetails->email }}</p>
                                   </div>
                                </div>
                               </center>
                          <center> --}}
                               {{-- <h5 style="margin-bottom: 0px;"><strong>@lang('Student::label.COLLECTION') @lang('Report::label.REPORT')</strong></h5> --}}
                               {{-- <caption>Year: {{$yearName}}, Class : {{$className}}</caption> --}}
                               {{-- <caption> </caption> --}}
                          </center>
                          <div class="body">
                            <div class="" >
                                <div class="table-responsive">
                                <table class="table table-bordered table-hover"  id="testTable">
                                        <thead class="thead-themed" style="background: #d1d1d1;">

                                        <tr>
                                            <th class="text-center"> SID</th>
                                            <th class="text-center"> Name </th>
                                            <th class="text-center"> Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                             $total_due = 0;
                                        @endphp
                                        @if (!empty($data_array))
                                        @foreach ($data_array as $key => $value)
                                        <tr>
                                            <td class="text-center">{{$value['sid']}}</td>
                                            <td class="text-center">{{$value['name']}}</td>
                                            <td class="text-center">{{$value['class']}}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                </table>

                                        </div>
                                    </div>
                        @endif
                    </div>
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

            function getStudents() {
                var yearId = $('#academicYearId').val();
                var classId = $('#classId').val();
                var html = '';
                if (yearId != 0) {
                    $.ajax({
                        url: "{{ url('get-student-details') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            classId: classId,
                            yearId: yearId
                        },
                        beforeSend: function() {
                            $('select[name="student_id[]"]').empty();
                        },
                        success: function(response) {
                            $.each(response, function(key, data) {
                                $('select[name="student_id[]"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.full_name + '</option>');
                            });
                            var searchStudentText ="<?php echo $request->search ?>";
                            if(searchStudentText == 'true'){
                                var studentArrayId = "<?php echo $studentArrId ?>";
                                $("#studentId").val(studentArrayId.split(','));
                            }
                        }
                    });
                }
            }

            function getSection() {
                var classId = $('#classId').val()
                var yearId = $('#academicYearId').val();
                // alert(classId)
                var html = '';
                if (classId != 0 && yearId != 0) {
                    $.ajax({
                        url: "{{ url('get-sections') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            classId: classId,
                            yearId: yearId
                        },
                        beforeSend: function() {
                            $('select[name="section_id[]"]').empty();
                        },
                        success: function(response) {
                            $.each(response, function(key, data) {
                                $('select[name="section_id[]"]').append(
                                    '<option value="' + data
                                    .id + '">' + data.name + '</option>');
                            });
                            var searchText ="<?php echo $request->search ?>";
                            if(searchText == 'true'){
                                var sectionArrayId = "<?php echo $secArrId ?>";
                                $("#sectionId").val(sectionArrayId.split(','));
                            }
                        }
                    });
                }
            }

            function validate() {
                if ($("#classId").val() != 0) {

                    if ($("#academicYearId").val() == 0) {
                        $('#yearError').html('Please Select Year');

                    } else {
                        $("#saveBtn").attr('type', 'submit');
                        $("#saveBtn").submit();
                    }
                } else {
                    $("#saveBtn").attr('type', 'submit');
                    $("#saveBtn").submit();
                }
            }
            $(document).ready(function() {

                $('.chart-of-expense-select2').select2({
                    width: "100%",
                    placeholder: "Select Chart of Expense",
                });
                $('.select2').select2({
                    width: "100%",
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
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }]
                });

                var searchText ="<?php echo $request->search ?>";
                if (searchText == 'true') {
                    getSection();
                    getStudents();
                }
                // console.log(search);
            });
        </script>
    @endsection
