@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            .stprimarybutton {
                display: block;
                position: fixed;
                bottom: 30px;
                right: 40px;
            }
        </style>
    @endpush
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Academic::label.ACADEMIC')</li>
            <li class="breadcrumb-item active">@lang('Academic::label.SECTION_ASIGN_TO_CLASS')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Academic::label.SECTION_RELATION_LIST')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Academic::label.SECTION_RELATION') <span class="fw-300"><i>List</i></span>
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
                                    <div class="col-md-6 form-data">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="" class='col-form-label'>Select Session</label>
                                                {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                                    'id' => 'academicYearId',
                                                    'class' => 'form-control select2 academic-year-id search-section',
                                                    'onchange' => 'assignValues()',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="yearError"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-data">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="" class='col-form-label'>Select Class</label>
                                                {!! Form::Select('class_id', $classList, !empty($request->class_id) ? $request->class_id : null, [
                                                    'id' => 'class_id',
                                                    'class' => 'form-control class-id select2 search-section',
                                                    'onchange' => 'assignValues()',
                                                ]) !!}
                                            </div>
                                            <span class="error" id="classError"></span>
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
                                        <div class="table-responsive">
                                            <div class="show-table">
                                                <form action="{{ route('section.asign.store') }}" method="POST">
                                                    @csrf
                                                    <table class="table table-bordered table-striped" id="yajraTable">
                                                        <thead class="thead-themed">
                                                            <th class="table-serial-column-center"> SL</th>
                                                            <th style="width: 10%;" class="table-checkbox-header-center">
                                                                <span>
                                                                    Check All</span>
                                                                <input type="checkbox" class="all-check-box" id="chkbxAll"
                                                                    onclick="return checkAll()">
                                                            </th>
                                                            <th> Section Name</th>
                                                            <th class="table-column-center"> Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="class_name" id="className">
                                                    <input type="hidden" name="academic_year" id="yearId">

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="stprimarybutton"><button
                                                                class="btn btn-primary btn-sm float-right mb-3 mr-3"
                                                                type="submit">save</button></div>
                                                        </div>
                                                    </div>
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
        </div>

    @section('javaScript')
        <script>
            $(function() {
                $(document).on('change', ".search-section", function() {
                    $("#chkbxAll").prop("checked", false);
                    if ($("#class_id").val() != 0 && $("#academicYearId").val() != 0) {
                        $('#yearError').html('');
                        $('#classError').html('');
                        $(".show-table").css("display", "block");
                        $('#yajraTable').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "paging": false,
                            iDisplayLength: 50,
                            "ajax": {
                                "url": "{{ route('section.asign.create') }}",
                                "data": function(e) {
                                    e.class_id = $("#class_id").val();
                                    e.academicYearId = $("#academicYearId").val();
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
                                    data: 'status',
                                    name: 'status',
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
                    }
                    setTimeout(() => {
                        isChecked();
                    }, 250);
                });

                $(".search-section").trigger('change');
            });

            $(function() {
                $(".select2").select2();
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
            }
        </script>
    @endsection
@endsection
