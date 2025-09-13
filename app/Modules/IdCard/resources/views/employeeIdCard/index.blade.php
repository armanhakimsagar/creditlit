@extends('Admin::layouts.master')

@section('body')
    <style>
        

        .generate-button {
            display: block;
            position: fixed;
            bottom: 30px;
            right: 40px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Examination::label.EXAM_RESULT')</li>
        <li class="breadcrumb-item active">@lang('Admin::label.EMPLOYEE') @lang('Admin::label.ID_CARD_GENERATE')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Admin::label.EMPLOYEE') @lang('Admin::label.ID_CARD_GENERATE')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right btn-sm">@lang('Certificate::label.BACK')</a>
    </div>
    <div class="row clearfix">
        <div class="block-header block-header-2">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="body">
                        <div class="">
                            <div class="table-responsive">
                                {!! Form::open([
                                    'id' => 'employeeIdCardGenerate',
                                    'route' => 'generate.employee.id.card',
                                    'class' => 'form-horizontal',
                                    'target' => '_blank',
                                ]) !!}
                                <table class="table table-bordered table-striped" id="yajraTable">
                                    <thead class="">
                                        <tr>
                                            <th>Sl</th>
                                            <th width="8%">Check All<input type="checkbox" id="chkbxAll"
                                                    class="all-check-box" onclick="return checkAll();"
                                                    style="margin-left: 5px;">
                                            </th>
                                            <th class="">Employee Name</th>
                                            <th class="">Department</th>
                                            <th class="">Designation</th>
                                            <th class="">Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="float-right">
                                    <button type="submit"
                                        class="btn btn-success ml-auto mt-5 waves-effect waves-themed generate-button btn-sm"
                                        id="generateButton">@lang('Certificate::label.GENERATE')</button>
                                </div>
                                <script></script>
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
            var table = $('#yajraTable').DataTable({
                stateSave: true,
                processing: true,
                serverSide: true,
                iDisplayLength: 50,
                ajax: {
                    "url": "{{ route('all.employee.id.card.index') }}"
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
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        data: 'designation_name',
                        name: 'designation_name'
                    },
                    {
                        data: 'contact_no',
                        name: 'contact_no'
                    }
                ],
                "bDestroy": true,
                select: {
                    style: 'single'
                }
            });


            $(".select2").select2();
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
                if (countChecked > 0) {
                    $("#employeeIdCardGenerate").submit();
                    return (true);
                } else {
                    return (false);
                }
            });
        });

        function getSection() {
            var classId = $('#class_id').val()
            var yearId = $('#academicYearId').val();
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
            return countNotChecked;
        }
    </script>
@endsection
