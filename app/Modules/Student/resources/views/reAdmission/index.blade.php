@extends('Admin::layouts.master')

@section('body')
    <style>
        a[target]:not(.btn) {
            text-decoration: none !important;
        }

        table,
        th,
        td {
            /* border: 1px solid black; */
            border-collapse: collapse;
        }

        .stock {
            overflow-x: scroll;
        }

        .subheader a:focus {
            background-color: black;
        }

        .show-table {
            display: none;
        }

        .certificate-generate {
            display: none;
        }

        #generateButton {
            display: none;
        }

        #generateSave {
            display: none;
        }

        @media print {

            .page-sidebar,
            .page-header,
            .page-breadcrumb,
            .subheader {
                display: none !important;
            }


            .form-data {
                display: none !important;
            }
        }

        #printbtn .waves-ripple {
            opacity: 0 !important;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
        <li class="breadcrumb-item active">@lang('Student::label.READMISSION')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Student::label.REALL') @lang('Student::label.RESTUDENT')
        </h1>
    </div>
    <div class="row clearfix">
        <div class="block-header block-header-2">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        Academic Year</label>
                                    {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                        'id' => 'academicYearId',
                                        'class' => 'form-control select2 academic-year-id',
                                        'onchange' => 'getSection()',
                                    ]) !!}
                                    <span class="error" id="yearError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.CLASS')</label><span class="required"> *</span>
                                    {!! Form::Select('class_id', $classList, !empty($request->class_id) ? $request->class_id : null, [
                                        'id' => 'class_id',
                                        'class' => 'form-control class-id select2',
                                        'onchange' => 'getSection();getClassWeight()',
                                    ]) !!}
                                </div>
                                <span class="error" id="classError"></span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.SECTION')</label>
                                    <select name="section_id" id="section_id" class="form-control select2" required>
                                        <option value='0'>@lang('Certificate::label.SELECT') @lang('Certificate::label.SECTION')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="align-items-start">
                                        <button
                                            class="btn btn-primary btn-sm ml-auto mt-5  waves-effect waves-themed"onclick="assignValues()"
                                            type="submit" id="searchBtn"><i
                                                class="fas fa-search pr-1"></i>@lang('Certificate::label.SEARCH')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="body">
                        <div class="">
                            <div class="table-responsive">
                                {!! Form::open([
                                    'route' => 'readmission.create',
                                    'id' => 'admitCardGenerate',
                                    'class' => 'form-horizontal',
                                    'method' => 'POST',
                                ]) !!}
                                <table class="table table-bordered myTable show-table" id="yajraTable">
                                    <thead class="">
                                        <tr>
                                            <th width="8%">Sl</th>
                                            <th width="8%">Check All<input type="checkbox" id="chkbxAll"
                                                    class="all-check-box" onclick="return checkAll()"
                                                    style="margin-top: 15px;margin-left: 5px;">
                                            </th>
                                            <th width="20%" class="">Student Name</th>
                                            <th width="8%" class="">Roll No</th>
                                            <th width="10%" class="text-center">SID</th>
                                            <th width="10%" class="text-center">New Roll</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <div id="generateSave">
                                    <br><br>
                                    <h2 class=" text-center">Readmission/Promotion to new class info</h2>
                                    <div class="form-group ">
                                        <div class="form-line">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        Academic Year</label>
                                                    {!! Form::Select('session_year', $academicYearList, null, [
                                                        'id' => 'sessionYearId',
                                                        'class' => 'form-control  select2',
                                                        'onchange' => 'getSection2();getItemPrice()',
                                                        'style' => 'width: 100%;',
                                                    ]) !!}
                                                    <span class="error" id="sessionError"></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        @lang('Certificate::label.CLASS')</label><span class="required"> *</span>
                                                    {{-- {!! Form::Select('new_class', $classList, null, [
                                                        'id' => 'newClass',
                                                        'class' => 'form-control select2',
                                                        'onchange' => 'getSection2();getItemPrice()',
                                                        'style' => 'width: 100%;',
                                                    ]) !!} --}}
                                                    <select name="new_class" id="newClass"style="width: 100%;"
                                                        class="form-control select2"onchange="getSection2();getItemPrice()">
                                                        <option value='0'class="form-control">@lang('Certificate::label.SELECT')
                                                            @lang('Academic::label.CLASS')
                                                        </option>
                                                    </select>
                                                    <span class="error" id="newClassError"></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        @lang('Certificate::label.SECTION')</label>
                                                    <select name="section_id" id="section_id" class="form-control select2">
                                                        <option value='0'>@lang('Certificate::label.SELECT') @lang('Certificate::label.SECTION')
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        @lang('Academic::label.GROUP')</label>
                                                    {!! Form::Select('group_id', $group_list, null, [
                                                        'id' => 'groupId',
                                                        'class' => 'form-control select2',
                                                        'style' => 'width: 100%;',
                                                    ]) !!}
                                                    <span class="error" id="groupError"></span>

                                                </div>

                                                <div class="col-md-4">
                                                    <label for="studentTypeId" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        @lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')</label>
                                                    {!! Form::Select('student_type_id', $studentTypeList, null, [
                                                        'id' => 'studentTypeId',
                                                        'class' => 'form-control select2',
                                                        'style' => 'width: 100%;',
                                                    ]) !!}
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        @lang('Academic::label.SHIFT')</label>
                                                    {!! Form::Select('shift_id', $shift_list, null, [
                                                        'id' => 'shiftId',
                                                        'class' => 'form-control select2',
                                                        'style' => 'width: 100%;',
                                                    ]) !!}
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        @lang('Academic::label.TRANSPORT')</label>
                                                    {!! Form::Select('transport_id', $transport_list, null, [
                                                        'id' => 'transportId',
                                                        'class' => 'form-control select2',
                                                        'style' => 'width: 100%;',
                                                    ]) !!}
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-line">
                                                        {!! Form::label('notes', 'Enter Notes', ['class' => 'col-form-label']) !!}
                                                        &nbsp;
                                                        {!! Form::textarea('notes', null, [
                                                            'class' => 'form-control',
                                                            'style' => 'height:68px;',
                                                            'id' => 'notes',
                                                            'placeholder' => 'Enter Notes',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <br><br>
                                            <div class="row">

                                                <input type="hidden" id="count" value="{{ count($selectedItems) }}" name="">
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="button" name="padd" class="btn btn-success btn-sm ml-auto waves-effect waves-themed padd"
                                                        onclick="addProduct()">Add Item</button>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <tr>
                                                                <th width="5%">SL</th>
                                                                <th width="10%">ITEM</th>
                                                                <th width="10%">AMOUNT</th>
                                                                {{-- <th width="35%">Affected Month</th> --}}
                                                                <th width="15%">Month</th>
                                                                <th width="10%"class="text-center"><span>
                                                                    Due generate upto current month</span><br>
                                                                <input type="checkbox" class="all-check-box" id="chkbxAll1"
                                                                    onclick="return checkTillCurrentMonth()"></th>
                                                                <th width="10%"class="text-center"><span>
                                                                    Whole year</span><br><br>
                                                                <input type="checkbox" class="all-check-box" id="chkbxAll2"
                                                                    onclick="return checkAllWholeYear()"></th>
                                                                <th width="10%">Waiver</th>
                                                                <th width="20%">Payable</th>
                                                                <th width="10%">Notes</th>
                                                                <th width="5%"></th>
                                                            </tr>
                                                            <tbody id="selectedItem">
                                                                @if (!empty($selectedItems))
                                                                    @foreach ($selectedItems as $key => $item)
                                                                        <tr id="productRow_{{ $key + 1 }}" class="new_data">
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>{{ $item->name }}
                                                                                <input type="hidden" name="product_id[{{ $key + 1 }}][]"
                                                                                    id="new_product_id{{ $key + 1 }}" value="{{ $item->id }}"
                                                                                    class="setting-product" tabindex="{{$key+1}}">
                                                                            </td>
                                                                            <td><span id="amountShow{{ $key + 1 }}" name="amount[{{ $key + 1 }}][]">0</span>
                                                                                <input style="width:150px; border:1px solid gray;"
                                                                                    name="amount[{{ $key + 1 }}][]" type="hidden" pattern="[0-9.]+" value="0"
                                                                                    class="form-control lightGray" id="amount-{{ $key + 1 }}"
                                                                                    oninput="calculatePayable({{ $key + 1 }})" required="" tabindex="{{100+$key}}"></td>
                                                                            <td>
                                                                                <select name="month_id[{{ $key + 1 }}][]" id="month_id{{ $key + 1 }}" class="form-control" onchange="itemDetails({{ $key + 1 }})">
                                                                                     @foreach ($enumMonth as $lkey => $enumMonthValue)
                                                                                     <option value="{{ $enumMonthValue->id }}" @if((int)date('m')==$enumMonthValue->id) selected="" @endif>{{ $enumMonthValue->name }}</option>
                                                                                     @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td class="text-center"><input type="checkbox" class="allCheck1 all-check-box"  id="checkCurrent_{{ $key + 1 }}" name="till_current_month[{{ $key + 1 }}]" value="{{ $key + 1 }}" keyValue="{{ $key + 1 }}" onclick="unCheck1(this.id);isChecked1()"></td>
                                                                            <td class="text-center"><input type="checkbox" class="allCheck2 all-check-box"  id="checkWholeYear_{{ $key + 1 }}" name="check_whole_year[{{ $key + 1 }}]" value="{{ $key + 1 }}" keyValue="{{ $key + 1 }}" onclick="unCheck2(this.id);isChecked2()"></td>
                                                                            <td><input type="text" name="discount[{{ $key + 1 }}][]"
                                                                                    id="discount_{{ $key + 1 }}" value="0" class="form-control"
                                                                                    placeholder="" oninput="calculatePayable({{ $key + 1 }})" tabindex="{{200+$key}}"></td>
                                                                            <td><input type="text" id="payable_{{ $key + 1 }}"
                                                                                    name="payable[{{ $key + 1 }}][]" value="0" class="form-control payable"
                                                                                    placeholder="" tabindex="{{300+$key}}"></td>
                                                                            <td><input type="text" id="note_{{ $key + 1 }}" name="note[{{ $key + 1 }}][]"
                                                                                    value="" class="form-control" placeholder="Enter Note" tabindex="{{400+$key}}"></td>
                                                                            <td><button type="button" name="remove" onclick="removeProduct({{ $key + 1 }})"
                                                                                    class="btn btn-danger btn-sm remove">Delete</button></td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                            <tbody id="added_item">
                                                            </tbody>
                                                        </table>
                                                </div>
                                            </div>
                                            <div class="col-md-8">

                                            </div>


                                        </div>
                                        <div class="float-right"><button type="submit"
                                                class="btn btn-success btn-sm ml-auto mt-5 waves-effect waves-themed generate-button"id="generateButton">@lang('Student::label.SAVE')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </body>
    <script>
        $(function() {
            $(document).on('click', '#searchBtn', function() {
                $("#chkbxAll").prop("checked", false);
                if ($("#class_id").val() != 0 && $("#academicYearId").val() != 0) {
                    $('#yearError').html('');
                    $('#classError').html('');
                    $(".show-table").css("display", "block");
                    $("#generateButton").css("display", "block");
                    $("#generateSave").css("display", "block");
                    var table = $('#yajraTable').DataTable({
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        scrollY: true,
                        iDisplayLength: 50,
                        searchDelay: 1000,
                        ajax: {
                            "url": "{{ route('readmission.index') }}",
                            "data": function(e) {
                                e.class_id = $("#class_id").val();
                                e.academicYearId = $("#academicYearId").val();
                                e.section_id = $("#section_id").val();
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'checkbox',
                                name: 'checkbox',
                                orderable: false,
                                searchable: false,
                                "className": "table-checkbox-column",
                            },
                            {
                                data: 'full_name',
                                name: 'full_name'
                            },
                            {
                                data: 'class_roll',
                                name: 'class_roll'
                            },
                            {
                                data: 'student_id',
                                name: 'student_id',
                                "className": "text-center",
                            },
                            {
                                data: 'newroll',
                                name: 'newroll'
                            },


                        ],
                        "bDestroy": true,
                        select: {
                            style: 'single'
                        }
                    });
                } else {
                    if ($("#class_id").val() == 0) {
                        $('#classError').html('Please Select Class');
                    }
                    if ($("#academicYearId").val() == 0) {
                        $('#yearError').html('Please Select Academic Year');
                    }
                }
            });
            $(".select2").select2();
            $(".select2").select2();
            $(".class-id").select2();
            $(document).on('click', '#saveBtn', function() {

            });
            var elements = $("input[type!='submit'], textarea, select");
            elements.focus(function() {
                $(this).parents('li').addClass('highlight');
            });
            elements.blur(function() {
                $(this).parents('li').removeClass('highlight');
            });
            $(document).on('click', '.generate-button', function(e) {
                e.preventDefault();

                var countChecked = 0;

                $(".allCheck").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                    }

                });
                var total = 0;
                $(".payable").each(function(index) {
                    total += parseInt($(this).val());
                });

                if (countChecked > 0) {

                    if ($("#newClass").val() == 0) {
                        $('#newClassError').html('Please Select Class');
                    } else {
                        if (total <= 0) {
                            var link = $(this).attr("type");
                            swal({
                                    title: "Are you want to submit without payment?",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((isConfirm) => {
                                    if (isConfirm) {
                                        $("#generateButton").attr("disabled", true);
                                        $("#generateButton").html('Wait..');
                                        $("#admitCardGenerate").submit();
                                    } else {
                                        swal("Please insert valid payable value!");
                                    }
                                });
                        } else {
                            $("#generateButton").attr("disabled", true);
                            $("#generateButton").html('Wait..');
                            $("#admitCardGenerate").submit();
                        }

                    }

                    return (true);
                } else {
                    alert("please select student")
                }

            });
        });

        function getSection() {
            var classId = $('#class_id').val()
            var yearId = $('#academicYearId').val();
            var html = '';
            console.log(`${classId} --- ${yearId}`);
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
                        $('select[name="section_id"]').empty();
                    },
                    success: function(response) {
                        $('select[name="section_id"]').append(
                            '<option value="0">Select Section</option>');
                        $.each(response, function(key, data) {
                            $('select[name="section_id"]').append(
                                '<option value="' + data
                                .id + '">' + data.name + '</option>');
                        });
                    }
                });
            }
        }


        function getClassWeight() {
        var classId = $("#class_id").val();
        $.ajax({
            url : "{{ url('get-class-list') }}",
            type : "post",
            dataType : "json",
            data: {
                _token: '{!! csrf_token() !!}',
                classId : classId
            },
            success : function(response){
                $.each(response, function(key, data) {
                    $('#newClass').append($('<option>', {
                    value: data.id,
                    text:  data.name,
                }));
            });
            }
        })
    }

        function getSection2() {
            var classId = $('#newClass').val()
            var yearId = $('#sessionYearId').val();
            var html = '';
            console.log(`${classId} --- ${yearId}`);
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
                        $('select[name="section_id"]').empty();
                    },
                    success: function(response) {
                        $('select[name="section_id"]').append(
                            '<option value="0">Select Section</option>');
                        $.each(response, function(key, data) {
                            $('select[name="section_id"]').append(
                                '<option value="' + data
                                .id + '">' + data.name + '</option>');
                        });
                    }
                });
            }
        }

        function checkAll() {
            if ($('#chkbxAll').is(':checked')) {
                $(".allCheck").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#checkStudent_" + key)[0].checked == false) {
                        $("#checkStudent_" + key).prop('checked', true);
                    }
                });
            } else {
                $(".allCheck").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#checkStudent_" + key)[0].checked == true) {
                        $("#checkStudent_" + key).prop('checked', false);
                    }
                });
            }
        }

        function unCheck(id) {
            if ($('#' + id).is(':not(:checked)')) {
                $("#chkbxAll").prop("checked", false);
            }
        }

        function isChecked() {
            var countNotChecked = 0;
            $(".allCheck").each(function(index) {
                if ($(this)[0].checked == false) {
                    countNotChecked++;
                }
            });
            if (countNotChecked == 0) {
                $("#chkbxAll").prop("checked", true);
            }
        }

        function assignValues() {
            var getClassId = $("#class_id").val();
            $("#className").val(getClassId);
            var getAcademicId = $("#academicYearId").val();
            $("#yearId").val(getAcademicId);
        }

        function rollValidate(elementId) {
            var values = [];
            $("input[type='text']").each(function() {
                values.push($(this).val());

            });
            var rollArray = values.sort();
            var duplicate = 0;
            for (var i = 0; i < rollArray.length - 1; i++) {
                if (rollArray[i + 1] == rollArray[i]) {
                    duplicate = rollArray[i];
                    if (duplicate != 0) {
                        $('#error_' + elementId).html('Duplicate Entry!!!');
                        $(".new_roll").attr("disabled", true);
                        $('#' + elementId).attr("disabled", false);
                        $('#' + elementId).css("background-color", "yellow");
                        $('.generate-button').attr("disabled", true);
                    } else {
                        $('#error_' + elementId).html('');
                        $(".new_roll").attr("disabled", false);
                        $('.generate-button').attr("disabled", false);
                        $('#' + elementId).css("background-color", "#fff");

                    }
                }
            }


        };
    </script>
    <script>
        var k = 1;
        k = parseInt(k) + parseInt($('#count').val());
        function addProduct() {
            var academic_year_id = $('#academicYearId').val();
            var html = '';
            html += '<tr id="productRow_' + k + '" class="new_data">';
            html +=
                '<td>' + k +
                '<input type="hidden" name="due_id[' + k + '][]" value="0" placeholder="" ></td>';
            html +=
                '<td>' +
                '   <select style="width:200px;" class="form-control js-example-basic selectheighttype product_row" id="new_product_id' +
                k + '" ' +
                ' name="product_id[' + k + '][]" required onchange="itemDetails(' + k + ')" tabindex='+ parseInt(k+1) +'>\n' +
                '     <option value="" >Select Product</option>\n' +
                '      <?php foreach($productlist as $key => $Thisproduct): ?>\n' +
                '      <option value="<?php echo $Thisproduct->id; ?>"><?php echo filter_var($Thisproduct->name, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
                '      <?php endforeach; ?>\n' +
                '   </select>\n' +
                '<label for="new_product_id' +
                k + '" class="error"></label></td>';
            html +=
                '<td>' +
                '<span id="amountShow' + k +'" name="amount[' + k +'][]">0</span>'+
                '<input style="width:150px; border:1px solid gray;" name="amount[' + k +
                '][]" type="hidden" pattern="[0-9.]+" value="0" class="form-control lightGray" id="amount-' + k +
                '" oninput="calculatePayable(' + k + ')" required/>' +
                '</td>';
            html +=
                '<td><select name="month_id[' + k + '][]" id="month_id'+k+'" onchange="itemDetails('+k+')" class="form-control">'+
                '@foreach ($enumMonth as $lkey => $enumMonthValue)'+
                '<option value="{{ $enumMonthValue->id }}" @if(date('m') == $enumMonthValue->id) selected @endif>{{ $enumMonthValue->name }}</option>'+
                '@endforeach'+
                '</select></td>\n';
             
            html +=
                '<td class="text-center">'+
                '<input type="checkbox" class="allCheck1 all-check-box"  id="checkCurrent_' + k + '" name="till_current_month[' + k + ']" value="' + k + '" keyValue="' + k + '" onclick="unCheck1(this.id);isChecked1()"></td>';
    
            html += '<td class="text-center">'+
                '<input type="checkbox" class="allCheck2 all-check-box"  id="checkWholeYear_' + k + '" name="check_whole_year[' + k + ']" value="' + k + '" keyValue="' + k + '" onclick="unCheck2(this.id);isChecked2()"></td>';                  
    
            html +=
                '</td><td><input type="text" name="discount[' + k +
                '][]" id="discount_' + k +
                '" value="0" class="form-control" placeholder="" onInput="calculatePayable(' + k +
                ')" tabindex='+ parseInt(200+k) +'></td><td><input type="text" id="payable_' + k +
                '" name="payable[' + k +
                '][]" value="0" class="form-control payable" placeholder="" tabindex='+ parseInt(300+k) +'></td>';
            html += '<td><input type="text" id="note_' + k +
                '" name="note[' + k +
                '][]" value="" class="form-control" placeholder="Enter Note" tabindex='+ parseInt(400+k) +'></td>';
            html +=
                '<td>' +
                '<button type="button" name="remove" onclick="removeProduct(' + k +
                ')" class="btn btn-danger btn-sm remove">Delete</button>' +
                '</td>' +
                '</tr>';
            $("#added_item").append(html);
            $('.js-example-basic').select2();
            k++;
        }
    
        function removeProduct(productRow) {
            $("#productRow_" + productRow).remove();
        }
    
        function itemDetails(id) {
            var itemId = $('#new_product_id' + id).val();
            var academicYearId = $('#academicYearId').val();
            var month_id = $('#month_id' + id).val();
            var class_id = $('#classID').val();
            var discountAmount = $('#discount_' + id).val();
            discountAmount = parseFloat(discountAmount);
            if (academicYearId != 0) {
                $.ajax({
                    url: "{{ url('get-item-details') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        itemId: itemId,
                        academicYearId: academicYearId,
                        class_id: class_id,
                        month_id:month_id,
                    },
                    beforeSend: function() {},
                    success: function(response) {
                        if (response.result == 'success') {
                            $('#amount-' + id).val(response.price);
                            $('#amountShow' + id).html(response.price);
                            $('#payable_' + id).val(response.price - discountAmount);
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        }
    
        function calculatePayable(id) {
            var discount = $('#discount_' + id).val() || 0;
            discount = parseFloat(discount);
            var amount = $('#amount-' + id).val() || 0;
            amount = parseFloat(amount);
            var payable = amount - discount;
            $('#payable_' + id).val(payable);
        }
    
        function checkAll2(id) {
        console.log('#allMonthChecked_' + id);
        var itemChckbox = '#allMonthChecked_' + id;
        if ($(itemChckbox).is(':checked')) {
            console.log('lol');
            $(".allCheck_" + id).each(function(index) {
                if ($(this)[0].checked == false) {
                    $(this).prop('checked', true);
                }
            });
        } else {
            $(".allCheck_" + id).each(function(index) {
                if ($(this)[0].checked == true) {
                    $(this).prop('checked', false);
                }
            });
        }
    }


    function isAllMonthChecked(id) {
        var stringArrForIdSplit = id.split("_");
        var countNotChecked = 0;
        for (var i = 0; i < 12; i++) {
            var monthId = '#' + stringArrForIdSplit[0] + '_' + stringArrForIdSplit[1] + '_' + i;
            if ($(monthId).is(':not(:checked)')) {
                countNotChecked++;
                console.log(monthId);
            }
        }
        if (countNotChecked > 0) {
            $("#allMonthChecked_" + stringArrForIdSplit[1]).prop("checked", false);
        }
        if (countNotChecked == 0) {
            $("#allMonthChecked_" + stringArrForIdSplit[1]).prop("checked", true);
        }
    }

        
        function checkTillCurrentMonth() {
                    if ($('#chkbxAll1').is(':checked')) {
                        $(".allCheck1").each(function(index) {
                            var key = $(this).attr('keyvalue');
    
                            if ($("#checkCurrent_" + key)[0].checked == false) {
                                $("#checkCurrent_" + key).prop('checked', true);
                            }
                        });
                    } else {
                        $(".allCheck1").each(function(index) {
                            var key = $(this).attr('keyvalue');
                            if ($("#checkCurrent_" + key)[0].checked == true) {
                                $("#checkCurrent_" + key).prop('checked', false);
                            }
                        });
                    }
                }
        function checkAllWholeYear() {
                    if ($('#chkbxAll2').is(':checked')) {
                        $(".allCheck2").each(function(index) {
                            var key = $(this).attr('keyvalue');
    
                            if ($("#checkWholeYear_" + key)[0].checked == false) {
                                $("#checkWholeYear_" + key).prop('checked', true);
                            }
                        });
                    } else {
                        $(".allCheck2").each(function(index) {
                            var key = $(this).attr('keyvalue');
                            if ($("#checkWholeYear_" + key)[0].checked == true) {
                                $("#checkWholeYear_" + key).prop('checked', false);
                            }
                        });
                    }
                }
    
        function isChecked1() {
                var countNotChecked = 0;
                var countChecked = 0;
                $(".allCheck1").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                    } else {
                        countNotChecked++;
                    }
                });
                if (countNotChecked == 0 && countChecked > 0) {
                    $("#chkbxAll1").prop("checked", true);
                }
    
            }  
            function unCheck1(id) {
                    if ($('#' + id).is(':not(:checked)')) {
                        $("#chkbxAll1").prop("checked", false);
                    }
                }              
        function isChecked2() {
            // alert();
                var countNotChecked = 0;
                var countChecked = 0;
                $(".allCheck2").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                        
                    } else {
                        countNotChecked++;
                        
                    }
                });
                if (countNotChecked == 0 && countChecked > 0) {
                    $("#chkbxAll2").prop("checked", true);
                }
    
            }  
            function unCheck2(id) {
                    if ($('#' + id).is(':not(:checked)')) {
                        $("#chkbxAll2").prop("checked", false);
                    }
                }              
        
        function isAllMonthChecked(id) {
            var stringArrForIdSplit = id.split("_");
            var countNotChecked = 0;
            for (var i = 0; i < 12; i++) {
                var monthId = '#' + stringArrForIdSplit[0] + '_' + stringArrForIdSplit[1] + '_' + i;
                if ($(monthId).is(':not(:checked)')) {
                    countNotChecked++;
                    console.log(monthId);
                }
            }
            if (countNotChecked > 0) {
                $("#allMonthChecked_" + stringArrForIdSplit[1]).prop("checked", false);
            }
            if (countNotChecked == 0) {
                $("#allMonthChecked_" + stringArrForIdSplit[1]).prop("checked", true);
            }
        }

        function getItemPrice() {
            $('.setting-product').each(function() {
                var itemId = $(this).val();
                var idText = $(this).attr('id');
                idText = idText.split('new_product_id');
                var id = idText[1];
                var academicYearId = $('#sessionYearId').val();
                var class_id = $('#newClass').val();
                var discountAmount = $('#discount_' + id).val();
                discountAmount = parseFloat(discountAmount);
                if (academicYearId != 0) {
                    $.ajax({
                        url: "{{ url('get-item-details') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            itemId: itemId,
                            academicYearId: academicYearId,
                            class_id: class_id
                        },
                        beforeSend: function() {},
                        success: function(response) {
                            if (response.result == 'success') {
                                $('#amount-' + id).val(response.price);
                                $('#amountShow' + id).html(response.price);
                                $('#payable_' + id).val(response.price - discountAmount);
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                }
            });
        }

        function getClassWeight() {
            var classId = $("#class_id").val();
            $.ajax({
                url: "{{ url('get-class-list') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    classId: classId
                },
                success: function(response) {
                    $.each(response, function(key, data) {
                        $('#newClass').append($('<option>', {
                            value: data.id,
                            text: data.name,
                        }));
                    });
                }
            })
        }
    </script>

@endsection
