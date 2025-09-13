@extends('Admin::layouts.master')
@section('body')
    <style>
        a[target]:not(.btn) {
            text-decoration: none !important;
        }
        td,
        th {
            font-size: {{ $reportFontSize }}px;
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

        /* td,
        th {
            font-size: {{ $reportFontSize }}px;
        } */
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
                        @php
                            $studentCollectionSetItem = json_decode($companyDetails->studentCollectionSetItem);
                        @endphp
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
                                        {!! Form::label('studentTypeId', 'Student Type Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select(
                                            'student_type_id[]',
                                            $studentTypeList,
                                            !empty($request->student_type_id) ? json_decode($request->student_type_id) : null,
                                            [
                                                'id' => 'studentTypeId',
                                                'class' => 'form-control select2',
                                                'multiple' => 'multiple',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="genderId" class="col-form-label">@lang('Student::label.GENDER')</label>
                                        <select name="gender_id" id="genderId" class="form-control select2">
                                            <option value='0'>@lang('Student::label.ALL')</option>
                                            <option value='male' {{ $request->gender_id == 'male' ? 'selected' : '' }}>
                                                @lang('Student::label.BOYS')</option>
                                            <option value='female'{{ $request->gender_id == 'female' ? 'selected' : '' }}>
                                                @lang('Student::label.GIRLS')</option>
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
                                        {!! Form::Select(
                                            'transport_id[]',
                                            $transportList,
                                            !empty($request->transport_id) ? json_decode($request->transport_id) : null,
                                            [
                                                'id' => 'transportId',
                                                'class' => 'form-control select2',
                                                'multiple' => 'multiple',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="admissionTypeId" class="col-form-label">@lang('Student::label.ADMISSION')
                                            @lang('Student::label.TYPE')</label>
                                        <select name="admission_type" id="admissionTypeId" class="form-control select2">
                                            <option value='0'>@lang('Student::label.ALL')</option>
                                            <option value='1'{{ $request->admission_type == '1' ? 'selected' : '' }}>
                                                @lang('Student::label.NEW')</option>
                                            <option value='2'{{ $request->admission_type == '2' ? 'selected' : '' }}>
                                                @lang('Student::label.OLD')</option>
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="col-form-label">Select Items for Student Collection Report</label> <i
                                            style="display:inline;" id="tooltip" class="fa fa-exclamation-circle"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="Select Items for Student Collection Report"></i>
                                        {!! Form::Select('repeatedDefaultItem[]', $items, $studentCollectionSetItem, [
                                            'id' => 'visibleProductElement3',
                                            'class' => 'form-control select2',
                                            'multiple' => 'multiple',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                    <label class="col-form-label" for="type">Type</label>
                                        <select id="type" name="type"class=" form-control select2">
                                            @if ($type == 0)
                                            <option value="0" selected>All</option>
                                            <option value="1">Fixed</option>
                                            <option value="2">Paid</option>
                                        @else
                                            <option value="0">All</option>
                                            <option value="1" @if ($type == 1) selected @endif>Fixed</option>
                                            <option value="2" @if ($type == 2) selected @endif>Paid</option>
                                        @endif
                                        </select>
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
                        @if ($request->search == 'true')
                            @php
                                $secArrId = implode(',', !empty(json_decode($request->section_id)) ? json_decode($request->section_id) : []);
                                $studentArrId = implode(',', !empty(json_decode($request->student_id)) ? json_decode($request->student_id) : []);
                            @endphp

                            <div class="subheader">
                                <button style="margin-left: auto;" class="btn btn-info btn-sm print_full_data mt-5"
                                    onclick="window.print();">Print</button>
                            </div>
                            <div id='printMe'>
                                <center>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}"
                                                height="90" class="example-p-5">
                                            <h2>{{ $companyDetails->company_name }}</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span
                                                style="font-size:{{ Session::get('fontsize') }}px;">{{ $companyDetails->address }}</span><br>
                                            <p style="font-size:{{ Session::get('fontsize') }}px;">Tel:
                                                {{ $companyDetails->phone }}, Email: {{ $companyDetails->email }}</p>
                                        </div>
                                    </div>
                                </center>
                                <center>
                                    <h5 style="margin-bottom: 0px;"><strong>@lang('Student::label.COLLECTION') @lang('Report::label.REPORT')</strong>
                                    </h5>
                                    {{-- <caption>Year: {{$yearName}}, Class : {{$className}}</caption> --}}
                                    {{-- <caption> </caption> --}}
                                </center>
                                <div class="body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="testTable">
                                                <thead class="thead-themed" style="background: #d1d1d1;">

                                                    <tr>
                                                        <th class="text-center"> No</th>
                                                        <th class="text-center"> Name </th>
                                                        <th class="text-center"> Class</th>
                                                        <th class="text-center"> Section</th>
                                                        <th class="text-center"> Shift</th>
                                                        @foreach ($product as $row)
                                                            @if ($type == 0)
                                                                <th class="text-center "> {{ $row->name }}</th>
                                                                <th style="color: blue;" class="text-center"></th>
                                                            @elseif ($type == 1)
                                                                <th class="text-center"> {{ $row->name }}</th>
                                                            @elseif ($type == 2)
                                                                <th class="text-center"> {{ $row->name }}</th>
                                                            @endif
                                                        @endforeach
                                                        @foreach ($month as $row)
                                                        @if ($type == 0)
                                                           <th class="text-center month-column"> {{ $row->short_name }}
                                                            </th>
                                                            <th class="text-center month-column"></th>
                                                            @elseif ($type == 1)
                                                            <th class="text-center month-column"> {{ $row->short_name }}
                                                            </th>
                                                            @elseif ($type == 2)
                                                            <th class="text-center month-column"> {{ $row->short_name }}
                                                            </th>
                                                            @endif
                                                        @endforeach
                                                        <th class="text-center"> Due</th>
                                                    
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center"> </td>
                                                        <td class="text-center"> </td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"> </td>
                                                        <td class="text-center"> </td>
                                                        @foreach ($product as $row)
                                                            @if ($type == 0)
                                                                <td class="text-center">Fixed</td>
                                                                <td class="text-center">Paid</td>
                                                            @elseif ($type == 1)
                                                                <td class="text-center">Fixed</td>
                                                            @elseif ($type == 2)
                                                                <td class="text-center">Paid</td>
                                                            @endif
                                                        @endforeach
                                                        @foreach ($month as $row)
                                                        @if ($type == 0)
                                                        <td class="text-center">Tution Fee </td>
                                                        <td class="text-center">Paid </td>
                                                        @elseif ($type == 1)
                                                        <td class="text-center">Tution Fee </td>
                                                        @elseif ($type == 2)
                                                        <td class="text-center">Paid </td>
                                                        @endif
                                                        @endforeach
                                                        <td class="text-center"></td>
                                                      
                                                    </tr>
                                                    @php
                                                        $total_due = 0;
                                                        $serialNumber = 1;
                                                    @endphp
                                                    @if (!empty($info))
                                                        @foreach ($info as $key => $value)
                                                            <tr>
                                                                <td class="text-center">{{$serialNumber++}}
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="{{ route(app('studentName'), $info[$key]['student_id']) }}"
                                                                        target="_blank" style="text-decoration: none;">
                                                                        {{ $info[$key]['student_name'] }}
                                                                    </a>
                                                                </td>
                                                                <td class="text-center">{{ $info[$key]['class_name'] }}
                                                                </td>
                                                                <td class="text-center">{{ $info[$key]['section_name'] }}
                                                                </td>
                                                                <td class="text-center">{{ $info[$key]['shift_name'] }}
                                                                </td>
                                                                {{-- @foreach ($info[$key]['fixed_tuition_fee'] as $total=>$item)
                                                                    @if ($type == 0 || $type == 1)
                                                                    <td class="text-center">
                                                                            {{ !empty($item->amount) ? $item->amount : 1 }}
                                                                        </td>
                                                                    @elseif ($type == 0 || $type == 2)
                                                                        <td style="color: blue" class="text-center">
                                                                            {{ !empty($item->paid_amount) ? $item->paid_amount : 3 }}
                                                                        </td>
                                                                    @endif
                                                                @endforeach --}}
                                                                @foreach ($info[$key]['paid_amount'] as $key2 => $product_price)
                                                                    @if ($type == 0)
                                                                    <td class="text-center">
                                                                        {{ !empty($info[$key]['amount'][$key2]->amount) ? $info[$key]['amount'][$key2]->amount : 0 }}
                                                                    </td>
                                                                    <td class="text-center" style="color: blue;">
                                                                        {{ !empty($product_price->paid_amount) ? $product_price->paid_amount : 0 }}
                                                                    </td>
                                                                    @elseif ($type == 1)
                                                                    <td class="text-center">
                                                                        {{ !empty($info[$key]['amount'][$key2]->amount) ? $info[$key]['amount'][$key2]->amount : 0 }}
                                                                    </td>
                                                                    @elseif ($type == 2)
                                                                    <td class="text-center" style="color: blue;">
                                                                        {{ !empty($product_price->paid_amount) ? $product_price->paid_amount : 0 }}
                                                                    </td>
                                                                    @endif
                                                                @endforeach
                                                                @foreach ($info[$key]['monthly_amount'] as $month_item)
                                                                    @if ($type == 0)
                                                                    <td class="text-center">
                                                                        {{ !empty($month_item->amount) ? $month_item->amount : 0 }}
                                                                    </td>
                                                                    <td class="text-center" style="color: blue;">
                                                                        {{ !empty($month_item->paid_amount) ? $month_item->paid_amount : 0 }}
                                                                    </td>
                                                                    @elseif ($type == 1)
                                                                    <td class="text-center">
                                                                        {{ !empty($month_item->amount) ? $month_item->amount : 5 }}
                                                                    </td>
                                                                    @elseif ($type == 2)
                                                                    <td class="text-center" style="color: blue;">
                                                                        {{ !empty($month_item->paid_amount) ? $month_item->paid_amount : 0 }}
                                                                    </td>
                                                                    @endif
                                                                @endforeach
                                                                <td class="text-center">
                                                                    {{ !empty($info[$key]['due']) ? $info[$key]['due'] : 0 }}
                                                                </td>
                                                                @php
                                                                    $total_due += !empty($info[$key]['due']) ? $info[$key]['due'] : 0;
                                                                @endphp
                                                            </tr>
                                                            @endforeach
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-center">Total (BDT)</td>
                                                            @foreach ($total_fixed_paid_amount_each_product as $fixed_paid_amount)
                                                                @if ($type == 0)
                                                                <td class="text-center">
                                                                    {{ !empty( $fixed_paid_amount['total_fixed']) ?  $fixed_paid_amount['total_fixed'] : 0 }}
                                                                </td>
                                                                <td class="text-center" style="color: blue;">
                                                                    {{ !empty($fixed_paid_amount['total_paid']) ? $fixed_paid_amount['total_paid'] : 0 }}
                                                                </td>
                                                                @elseif ($type == 1)
                                                                <td class="text-center">
                                                                    {{ !empty( $fixed_paid_amount['total_fixed']) ?  $fixed_paid_amount['total_fixed'] : 5 }}
                                                                </td>
                                                                @elseif ($type == 2)
                                                                <td class="text-center" style="color: blue;">
                                                                    {{ !empty($fixed_paid_amount['total_paid']) ? $fixed_paid_amount['total_paid'] : 0 }}
                                                                </td>
                                                                @endif
                                                            @endforeach
                                                            @foreach ($total_fixed_paid_monthly as $fixed_paid_monthly)
                                                                @if ($type == 0)
                                                                <td class="text-center">
                                                                    {{ !empty( $fixed_paid_monthly['monthly_total_fixed']) ?  $fixed_paid_monthly['monthly_total_fixed'] : 0 }}
                                                                </td>
                                                                <td class="text-center" style="color: blue;">
                                                                    {{ !empty($fixed_paid_monthly['monthly_total_paid']) ? $fixed_paid_monthly['monthly_total_paid'] : 0 }}
                                                                </td>
                                                                @elseif ($type == 1)
                                                                <td class="text-center">
                                                                    {{ !empty( $fixed_paid_monthly['monthly_total_fixed']) ?  $fixed_paid_monthly['monthly_total_fixed'] : 0 }}
                                                                </td>
                                                                @elseif ($type == 2)
                                                                <td class="text-center" style="color: blue;">
                                                                    {{ !empty($fixed_paid_monthly['monthly_total_paid']) ? $fixed_paid_monthly['monthly_total_paid'] : 0 }}
                                                                </td>
                                                                @endif
                                                            @endforeach
                                                       
                                                            <td class="text-center">
                                                               {{ !empty($total_due) ? $total_due : 0 }}</td>
                                                       
                                                           
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

                $("#saveBtn").click(function() {
                    var selectedItems = $('#visibleProductElement3').val();
                    var csrfToken = "{{ csrf_token() }}";
                    $.ajax({
                        method: 'POST',
                        url: "{{ url('save-student-collection-data') }}",
                        data: {
                            _token: csrfToken,
                            items: selectedItems
                        },
                        success: function(response) {
                            console.log(response.message);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });

            });

     $(document).ready(function() {
        $('#type').on('change', function() {
            var selectedOption = $(this).val();
            if (selectedOption === '1') { // '1' represents the value of the "Fixed" option
                // Set the selected attribute for the "Fixed" option
                $('#type option[value="1"]').prop('selected', true);
            }
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
                            var searchStudentText = "<?php echo $request->search; ?>";
                            if (searchStudentText == 'true') {
                                var studentArrayId = "<?php echo $studentArrId; ?>";
                                $("#studentId").val(studentArrayId.split(','));
                                $("#studentId").select2();
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
                            var searchText = "<?php echo $request->search; ?>";
                            if (searchText == 'true') {
                                var sectionArrayId = "<?php echo $secArrId; ?>";
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
            function generateCertificate() {
        // You can put additional logic here if needed
        var selectedOption = '1'; // '1' represents the value of the "Fixed" option

        // Set the selected attribute for the "Fixed" option
        $('#type').val(selectedOption);
    }

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
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    pageLength: 10,
                    searching: false,
                    order: false,
                    bSort: false,
                    bInfo: false,
                    bAutoWidth: false,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        filename: 'Student_Collection_Report',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }]
                });

                var searchText = "<?php echo $request->search; ?>";
                if (searchText == 'true') {
                    getSection();
                    getStudents();
                }
                // console.log(search);
            });
        </script>
    @endsection
