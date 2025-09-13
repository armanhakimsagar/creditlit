@extends('Admin::layouts.master')
@section('body')
    <style>
        #submitBtn {
            display: none;
        }

        .generate-button {
            display: block;
            position: fixed;
            bottom: 30px;
            right: 40px;
        }
    </style>
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Item::label.ITEM') @lang('Item::label.SETUP')</li>
            <li class="breadcrumb-item active">@lang('Item::label.GENERATE') @lang('Item::label.STUDENT') @lang('Item::label.MONTHLY') @lang('Item::label.DUE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i>@lang('Item::label.GENERATE') @lang('Item::label.STUDENT') @lang('Item::label.MONTHLY')
                @lang('Item::label.DUE')
            </h1>
            <a style="margin-left: 10px;" href="{{ route('monthly.item.index') }}"
                class="btn btn-success btn-sm waves-effect pull-right">@lang('Item::label.GENERATED') @lang('Item::label.STUDENT')
                @lang('Item::label.MONTHLY') @lang('Item::label.DUE') @lang('Item::label.LIST')</a>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Item::label.GENERATE') @lang('Item::label.STUDENT') @lang('Item::label.MONTHLY') @lang('Item::label.DUE') <span class="fw-300"></span>
                </h2>

            </div>

            <div class="panel-container show">

                <div class="row clearfix">
                    <div class="block-header block-header-2">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row" id="notPrintDiv">
                                    <div class="col-md-4 form-data">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="" class='col-form-label'>Select Session</label>
                                                {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                                    'id' => 'academicYearId',
                                                    'class' => 'form-control select2 academic-year-id search-item',
                                                    'onchange' => 'assignValues()',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="yearError"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-data">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="" class='col-form-label'>Select Class</label>
                                                {!! Form::Select('class_id', $classList, !empty($request->class_id) ? $request->class_id : null, [
                                                    'id' => 'class_id',
                                                    'class' => 'form-control class-id select2 search-item',
                                                    'onchange' => 'assignValues()',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="classError"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-data">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="" class='col-form-label'>Select Month</label>
                                                {!! Form::Select('month', $months, !empty($request->month_id) ? $request->month_id : null, [
                                                    'id' => 'months',
                                                    'class' => 'form-control select2 search-item',
                                                    'onchange' => 'assignValues()',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="monthsError"></span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="row clearfix">
                                    <div class="block-header block-header-2">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <center>
                                            <div class="row">
                                                <div class="col-md-12">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                </div>
                                            </div>
                                        </center>
                                        <form action="{{ route('store.monthly.item') }}" id="monthlyItem" method="POST">
                                            @csrf
                                            <div class="table-responsive">
                                                <div class="show-table">
                                                    <table class="table table-bordered table-striped" id="yajraTable">
                                                        <thead class="thead-themed">
                                                            <th class="table-serial-column-center"> SL</th>
                                                            <th style="width: 10%;" class="table-checkbox-header-center">
                                                                <span>
                                                                    Check All</span>
                                                                <input type="checkbox" class="all-check-box" id="chkbxAll"
                                                                    onclick="return checkAll()">
                                                            </th>
                                                            <th>Item Name</th>
                                                            <th style="width: 20%;" class="table-column-center">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <input type="hidden" name="class_name" id="className">
                                                <input type="hidden" name="academic_year" id="yearId">
                                                <input type="hidden" name="month_id" id="monthId">

                                                <td><button id="submitBtn"
                                                        class="btn btn-primary btn-sm generate-button float-right mb-3 mr-3" type="submit">save</button></td>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $(document).on('change', ".search-item", function() {
                $("#chkbxAll").prop("checked", false);
                if ($("#class_id").val() != 0 && $("#academicYearId").val() != 0 && $("#months").val() !=
                    0) {
                    $('#yearError').html('');
                    $('#classError').html('');
                    $('#monthsError').html('');
                    $(".show-table").css("display", "block");
                    $("#submitBtn").css("display", "block");
                    $('#yajraTable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "paging": false,
                        iDisplayLength: 50,
                        "ajax": {
                            "url": "{{ route('monthly.item.create') }}",
                            "data": function(e) {
                                e.class_id = $("#class_id").val();
                                e.academicYearId = $("#academicYearId").val();
                                e.monthId = $("#months").val();
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                "className": "table-serial-column-center",
                            },
                            {
                                data: 'checkbox',
                                name: 'checkbox',
                                orderable: false,
                                searchable: false,
                                "className": "table-checkbox-column",
                            },

                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'fees',
                                name: 'fees',
                                "className": "table-column-center",
                            }
                        ],
                        "bDestroy": true
                    });
                } else {
                    if ($("#class_id").val() == 0) {
                        $('#classError').html('Please Select Class');
                        $(".show-table").css("display", "none");
                    }
                    if ($("#academicYearId").val() == 0) {
                        $('#yearError').html('Please Select Session');
                        $(".show-table").css("display", "none");
                    }
                    if ($("#months").val() == 0) {
                        $('#monthsError').html('Please Select Month');
                        $(".show-table").css("display", "none");
                    }
                }
                setTimeout(() => {
                    isChecked();
                }, 250);
            });

            $(".select2").select2();
            <?php if(!empty($request->academic_year_id) && !empty($request->class_id)){ ?>
            $(".search-item").trigger('change');
            <?php
            }
            ?>
        });

        function checkAll() {
            if ($('#chkbxAll').is(':checked')) {
                $(".allCheck").each(function(index) {
                    var key = $(this).attr('keyvalue');

                    if ($("#checkSection_" + key)[0].checked == false) {
                        $("#checkSection_" + key).prop('checked', true);
                    }
                });
            } else {
                $(".allCheck").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#checkSection_" + key)[0].checked == true) {
                        $("#checkSection_" + key).prop('checked', false);
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
            var countChecked = 0;
            $(".allCheck").each(function(index) {
                if ($(this)[0].checked == true) {
                    countChecked++;
                } else {
                    countNotChecked++;
                }
            });
            if (countNotChecked == 0 && countChecked > 0) {
                $("#chkbxAll").prop("checked", true);
            }
        }

        function assignValues() {
            var getClassId = $("#class_id").val();
            $("#className").val(getClassId);
            var getAcademicId = $("#academicYearId").val();
            $("#yearId").val(getAcademicId);
            var getMonth = $("#months").val();
            $("#monthId").val(getMonth);
        }

        $(document).on('click','#submitBtn',function(e){
            e.preventDefault();
            var countNotChecked = 0;
            var countChecked = 0;
            $(".allCheck").each(function(index) {
                if ($(this)[0].checked == true) {
                    countChecked++;
                }
            });
            if(countChecked == 0){
                alert('Please check atleast one item');
                return false;
            }else{
                $('form').submit();
            }
        });
    </script>
@endsection
