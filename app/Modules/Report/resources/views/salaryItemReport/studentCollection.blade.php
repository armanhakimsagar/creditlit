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
            <i class='subheader-icon fal fa-list'></i> @lang('Student::label.STUDENT') @lang('Student::label.COLLECTION') @lang('Student::label.REPORTS')
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
                            'route' => 'student.collection.filter',
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
                                        {!! Form::label('classId', 'Class Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('class_id', $class_list, !empty($request->class_id) ? $request->class_id : null, [
                                            'id' => 'classId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getStudents();getSection()',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="sectionId" class="col-form-label">@lang('Student::label.SECTION')</label>
                                    <select name="section_id[]" id="sectionId" class="form-control select2" multiple>
                                        <option value='0'></option>
                                    </select>
                                    </div>
                                </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="shiftId" class="col-form-label">@lang('Student::label.SHIFT')</label>
                                    {!! Form::Select('shift_id[]', $shiftList, !empty($request->shift_id) ? json_decode($request->shift_id) : null, [
                                        'id' => 'shiftId',
                                        'class' => 'form-control select2',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="genderId" class="col-form-label">@lang('Student::label.GENDER')</label>
                                    <select name="gender_id" id="genderId" class="form-control select2">
                                        <option value='0'>@lang('Student::label.ALL')</option>
                                        <option value='male' {{ $request->gender_id =='male' ? 'selected' : ''}}>@lang('Student::label.BOYS')</option>
                                        <option value='female'{{ $request->gender_id =='female' ? 'selected' : ''}}>@lang('Student::label.GIRLS')</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="versionId" class="col-form-label">@lang('Student::label.VERSION')</label>
                                    {!! Form::Select('version_id', $versionList, !empty($request->version_id) ? $request->version_id : null, [
                                        'id' => 'versionId',
                                        'class' => 'form-control select2',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="groupId" class="col-form-label">@lang('Student::label.GROUP')</label>
                                    {!! Form::Select('group_id', $groupList, !empty($request->group_id) ? $request->group_id : null, [
                                        'id' => 'groupId',
                                        'class' => 'form-control select2',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="transportId" class="col-form-label">@lang('Academic::label.TRANSPORT')</label>
                                    {!! Form::Select('transport_id[]', $transportList, !empty($request->transport_id) ? json_decode($request->transport_id) : null, [
                                        'id' => 'transportId',
                                        'class' => 'form-control select2',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="admissionTypeId" class="col-form-label">@lang('Student::label.ADMISSION') @lang('Student::label.TYPE')</label>
                                    <select name="admission_type" id="admissionTypeId" class="form-control select2">
                                        <option value='0'>@lang('Student::label.ALL')</option>
                                        <option value='1'{{$request->admission_type=='1' ? 'selected' : ''}}>@lang('Student::label.NEW')</option>
                                        <option value='2'{{$request->admission_type=='2' ? 'selected' : ''}}>@lang('Student::label.OLD')</option>
                                    </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="studentId" class="col-form-label">@lang('Student::label.STUDENT')</label>
                                        <select name="student_id[]" id="studentId" class="form-control select2" multiple>
                                            <option value='0'></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">From Date</label>
                                        {!! Form::text('from_date', isset($request->from_date) ? $request->from_date : null, [
                                            'id' => 'from_date',
                                            'class' => 'form-control from_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">

                                    <div class="form-line">
                                        <label class="col-form-label">To Date</label>
                                        {!! Form::text('to_date', isset($request->to_date) ? $request->to_date : null, [
                                            'id' => 'to_date',
                                            'class' => 'form-control to_date',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]) !!}

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
                            <center><div class="row">
                                   <div class="col-md-12">
                                        <img src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}" height="90" class="example-p-5">
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
                          <center>
                               <h5 style="margin-bottom: 0px;"><strong>@lang('Student::label.COLLECTION') @lang('Report::label.REPORT')</strong></h5>
                               {{-- <caption>Year: {{$yearName}}, Class : {{$className}}</caption> --}}
                               {{-- <caption> </caption> --}}
                          </center>
                          <div class="body">
                            <div class="" >
                                <div class="table-responsive">
                                <table class="table table-bordered table-hover"  id="testTable">
                                        <thead class="thead-themed" style="background: #d1d1d1;">

                                        <tr>
                                            <th class="text-center"> No</th>
                                            <th class="text-center"> Name </th>
                                            <th class="text-center"> Class</th>
                                            <th class="text-center"> Section</th>
                                            <th class="text-center"> Shift</th>
                                            @foreach ($product as $row)
                                                <th class="text-center"> {{$row->name}}</th>
                                            @endforeach
                                            @foreach ($month as $row)
                                                <th class="text-center"> {{$row->short_name}}</th>
                                            @endforeach
                                            <th class="text-center"> Due</th>
                                            @foreach ($item as $row)
                                                <th class="text-center"> {{$row->name}}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                             $total_due = 0;
                                        @endphp
                                        @if (!empty($info))
                                        @foreach ($info as $key => $value)
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td class="text-center">{{$info[$key]['student_name']}}</td>
                                            <td class="text-center">{{$info[$key]['class_name']}}</td>
                                            <td class="text-center">{{$info[$key]['section_name']}}</td>
                                            <td class="text-center">{{$info[$key]['shift_name']}}</td>
                                            @foreach ($info[$key]['paid_amount'] as $item)
                                                <td class="text-center">{{!empty($item->paid_amount) ? $item->paid_amount : 0}}</td>
                                            @endforeach
                                        @foreach ($info[$key]['monthly_amount'] as $month_item)
                                                <td class="text-center">{{!empty($month_item->paid_amount) ? $month_item->paid_amount : 0}}</td>
                                            @endforeach
                                            <td class="text-center">{{!empty($info[$key]['due']) ? $info[$key]['due'] : 0}}</td>
                                            @php
                                                 $total_due += !empty($info[$key]['due']) ? $info[$key]['due'] : 0;
                                            @endphp
                                            @foreach ($info[$key]['amount'] as $item)
                                                <td class="text-center">{{!empty($item->amount) ? $item->amount : 0}}</td>
                                            @endforeach

                                        </tr>
                                        @endforeach
                                        <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-center">Total (BDT)</td>
                                                @foreach ($info[$key]['total_paid'] as $total)
                                                    <td class="text-center">{{ !empty($total) ? $total : 0 }}</td>
                                                @endforeach
                                                @foreach ($info[$key]['total_monthly_paid'] as $monthly_total)
                                                    <td class="text-center">{{ !empty($monthly_total) ? $monthly_total : 0 }}</td>
                                                @endforeach
                                                <td class="text-center">{{ !empty($total_due) ? $total_due : 0  }}</td>
                                                @foreach ($info[$key]['total_amount'] as $total)
                                                <td class="text-center">{{ !empty($total) ? $total : 0 }}</td>
                                                @endforeach
                                        </tr>
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
