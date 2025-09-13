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
                font-size: {{ $reportFontSize }}px;
            }
        }

        td,
        th {
            font-size: {{ $reportFontSize }}px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item"> @lang('Student::label.REPORTS')</li>
        <li class="breadcrumb-item active">@lang('Stuff::label.SALARY') @lang('Stuff::label.ITEM') @lang('Student::label.REPORTS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> @lang('Stuff::label.SALARY') @lang('Stuff::label.ITEM') @lang('Student::label.REPORTS')
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
                            'route' => 'salary.item.filter',
                            'method' => 'post',
                            'id' => 'collectionReportId',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @csrf
                        <div class="row"id="notPrintDiv">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('academicYearId', 'Academic Year', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('academic_year_id', $academic_year_list, !empty($request->yearId) ? $request->yearId : null, [
                                            'id' => 'academicYearId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getEmployee();',
                                        ]) !!}
                                        <span class="error" id="yearError"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('monthId', 'Month Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('month_id', $enumMonth, !empty($request->monthId) ? $request->monthId : $currentMonth, [
                                            'id' => 'monthId',
                                            'class' => 'form-control select2',
                                            'onchange' => 'getEmployee();',
                                        ]) !!}
                                        <span class="error" id="monthError"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="form-line">
                                        {!! Form::label('itemId', 'Item Name', ['class' => 'col-form-label']) !!}

                                        {!! Form::Select('item_id[]', $item_list, !empty($request->item_id) ? json_decode($request->item_id) : null, [
                                            'id' => 'itemId',
                                            'class' => 'form-control select2',
                                            'multiple' => 'multiple',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="employeeId" class="col-form-label">@lang('Stuff::label.EMPLOYEE')</label>
                                        <select name="employee_id[]" id="employeeId" class="form-control select2" multiple>
                                            <option value='0'></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mt-1">

                                <div class="panel-content d-flex flex-row align-items-center">
                                    <button class="btn btn-primary waves-effect waves-themed btn-sm mt-5" name="submit"
                                        onclick="validate()" type="button" id="saveBtn">@lang('Certificate::label.GENERATE')</button>

                                </div>
                            </div>
                        </div>
                        <br><br>
                        @php
                            $employeeArrId = '';
                        @endphp
                        {!! Form::close() !!}
                        @if ($request->search == 'true')
                            @php
                                $employeeArrId = implode(',', !empty(json_decode($request->employee_id)) ? json_decode($request->employee_id) : []);
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
                                    <h5 style="margin-bottom: 0px;"><strong>{{ $pageTitle }}</strong>
                                    </h5>
                                    <caption> Details Report of: {{ $monthName }}, {{ $yearName }}</caption><br>
                                    @php
                                        
                                        $employeeId = explode(',', $employeeArrId);
                                    @endphp
                                    <?php $employeeName = DB::table('contacts')->where('is_trash', 0)->where('type', 5)->whereIn('id',$employeeId)->select('full_name')->get(); 
                                    $employeeNameList = [];
                                    if($employeeName->isNotEmpty())
                                    {
                                        foreach ($employeeName as $employee) {
                                            $employeeNameList[] = $employee->full_name;
                                        }
                                        $employeeNames = implode(', ', $employeeNameList);
                                        ?>
                                    <caption> <span style="font-weight: bolder">Employees:</span> {{ $employeeNames }}
                                    </caption>
                                    <br>
                                    <?php } ?>

                                    @php
                                        $itemId = json_decode($request->item_id);
                                        if ($itemId === null) {
                                            $itemId = []; 
                                        }
                                    @endphp
                                    <?php $itemName = DB::table('salary_item')->where('is_trash', '0')->where('status', 'active')->whereIn('id', $itemId)->select('name')->get(); 
                                    $itemNameList = [];
                                    if($itemName->isNotEmpty())
                                    {
                                        foreach ($itemName as $item) {
                                            $itemNameList[] = $item->name;
                                        }
                                        $itemNames = implode(', ', $itemNameList);
                                        ?>
                                    <caption><span style="font-weight: bolder">Items:</span> {{ $itemNames }} </caption>
                                    <?php } ?>

                                </center>
                                <div class="body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="testTable">
                                                <thead class="thead-themed" style="background: #d1d1d1;">

                                                    <tr>
                                                        <th class="text-left"> No</th>
                                                        <th class="text-center"> Employee Name </th>
                                                        <th class="text-center"> Item Name</th>
                                                        <th class="text-center"> Salary Of</th>
                                                        <th class="text-center"> Amount (BDT)</th>

                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    @if (count($model) > 0)
                                                        <?php
                                                        $total_rows = 1;
                                                        $sum = 0;
                                                        ?>
                                                        @if (!empty($model))
                                                            @foreach ($model as $values)
                                                                <tr>
                                                                    <td>
                                                                        <?= $total_rows++ ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $values->full_name }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $values->name }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $values->short_name }}-{{ $values->year }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $values->total_amount }}
                                                                    </td>
                                                                    @php
                                                                        $sum += $values->total_amount;
                                                                    @endphp
                                                                </tr>
                                                            @endforeach
                                                </tbody>
                                                <tfoot style="background: #d1d1d1;">
                                                    <tr>
                                                        <td colspan="4">Total (BDT)</td>
                                                        <td class="text-center">{{ $sum }}</td>
                                                    </tr>
                                                </tfoot>
                        @endif

                        @endif
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
            getEmployee();

        });

        function getEmployee() {
            var yearId = $('#academicYearId').val();
            var monthId = $('#monthId').val();
            var html = '';
            if (yearId != 0) {
                $.ajax({
                    url: "{{ url('get-employee') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        yearId: yearId,
                        monthId: monthId
                    },
                    beforeSend: function() {
                        $('select[name="employee_id[]"]').empty();
                    },
                    success: function(response) {
                        $.each(response, function(key, data) {
                            $('select[name="employee_id[]"]').append(
                                '<option value="' + data
                                .id + '">' + data.full_name + '</option>');
                        });
                        var searchStudentText = "<?php echo $request->search; ?>";
                        if (searchStudentText == 'true') {
                            var employeeArrId = "<?php echo $employeeArrId; ?>";
                            $("#employeeId").val(employeeArrId.split(','));
                            $("#employeeId").select2();
                        }
                    }
                });
            }
        }

        function validate() {
            if ($("#monthId").val() != 0 || $("#academicYearId").val() != 0) {

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
                    filename: 'Salary_Item_Wise_Cost_Report',
                    titleAttr: 'Generate Excel',
                    className: 'btn-outline-success btn-sm mr-1'
                }]
            });

            var searchText = "<?php echo $request->search; ?>";

        });
    </script>
@endsection
