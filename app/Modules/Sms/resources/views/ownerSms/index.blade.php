@extends('Admin::layouts.master')

@section('body')
    <style>
        #generateButton {
            display: none;
        }

        .sms-field {
            display: none;
        }

        .generate-button {
            display: block;
            position: fixed;
            bottom: 30px;
            right: 40px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Sms::label.SMS')</li>
        <li class="breadcrumb-item active">@lang('Sms::label.OWNER') @lang('Sms::label.SMS')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Sms::label.OWNER') @lang('Sms::label.SMS')
        </h1>
        <a style="margin-left: 10px;" href="javascript:history.back()"
            class="btn btn-warning waves-effect pull-right btn-sm">@lang('Certificate::label.BACK')</a>
    </div>
    @if (is_null($hasSmsToday))
        <div class="alert alert-danger" role="alert">
            Please, Send today's owner SMS
        </div>
    @else
        <div class="alert alert-primary" role="alert">
            You have already sent today's Owner an SMS
        </div>
    @endif
    <div class="body">
        {!! Form::open([
            'id' => 'ownerMessage',
            'route' => 'owner.send.sms',
            'class' => 'form-horizontal',
        ]) !!}
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow"
                    data-toggle="tooltip" data-placement="top" title="Today's Expense">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500">
                            @php
                                $count_student = DB::table('contacts')
                                    ->where('contacts.type', 1)
                                    ->where('contacts.status', 'active')
                                    ->where('contacts.is_trash', 0)
                                    ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                                    ->leftjoin('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                                    ->where('is_current', 1)
                                    ->count();
                            @endphp
                            <small class="m-0 l-h-n">@lang('Admin::label.TODAYS') @lang('Admin::label.EXPENSE')</small>
                            {{ $expense }} @lang('Admin::label.BDT')
                            <input type="hidden" name="today_expense" value="{{ $expense }}">
                        </h3>
                    </div>
                    <a href="" class="stretched-link"><i
                            class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow"
                    data-toggle="tooltip" data-placement="top" title="Today's Recieve">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500">
                            @php
                                $count_stuff = DB::table('contacts')
                                    ->where('type', 5)
                                    ->where('status', 'active')
                                    ->where('is_trash', 0)
                                    ->count();
                            @endphp
                            <small class="m-0 l-h-n">@lang('Admin::label.TODAYS') @lang('Admin::label.RECIEVE')</small>
                            {{ $recieve }} @lang('Admin::label.BDT')
                            <input type="hidden" name="today_recieve" value="{{ $recieve }}">
                        </h3>
                    </div>
                    <a href="" class="stretched-link"><i
                            class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow d-flex"
                    data-toggle="tooltip" data-placement="top" title="Today's Cash-Book">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500">
                            <a href="" class="stretched-link"><small class="m-0 l-h-n text-white">@lang('Admin::label.TODAYS')
                                    @lang('Admin::label.CASHBOOK')</small></a>
                            {{ $cashBook }} @lang('Admin::label.BDT')
                            <input type="hidden" name="today_cashBook" value="{{ $cashBook }}">
                        </h3>
                    </div>
                    <a href="" class="stretched-link"><i
                            class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-danger-400 rounded overflow-hidden position-relative text-white mb-g shadow"
                    data-toggle="tooltip" data-placement="top" title="Today's Bank-Book">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500">
                            @php
                                $today_payment = DB::table('sales')
                                    ->where('sales_invoice_date', date('d-m-Y'))
                                    ->sum('grand_total');
                            @endphp
                            <small class="m-0 l-h-n">@lang('Admin::label.TODAYS') @lang('Admin::label.BANKBOOK')</small>
                            {{ $bankBook }} @lang('Admin::label.BDT')
                            <input type="hidden" name="today_bankBook" value="{{ $bankBook }}">
                        </h3>
                    </div>
                    <a href="" class="stretched-link"><i
                            class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                </div>
            </div>

            <div class="col text-right">
                <button type="submit" class="btn btn-primary">Send Owner SMS</button>
            </div>
        </div>

        <div id="panel-1" class="panel mt-5">

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ytable" id="yajraTable">
                                <thead class="thead-themed">
                                    <tr>
                                        <th> No</th>
                                        <th> Phone</th>
                                        <th> Time</th>
                                        <th> SMS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allSms as $item)
                                        <tr>
                                            <td>{{ $allSms->perPage() * ($allSms->currentPage() - 1) + $loop->iteration }}
                                            </td>
                                            <td>{{ $item->MobileNumbers }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('jS F, Y g:i:s A') }}</td>
                                            <td>{{ $item->SentMessage }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $allSms->links() }}
                        </div>
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
    </body>
    <script>
        $(function() {
            $("#teacherMessage").validate({
                rules: {
                    smsId: {
                        required: true,
                    }
                },
                messages: {
                    smsId: 'Please enter Message'
                }
            });
            $(document).on('click', '#searchBtn', function() {
                $('#chkbxAll').prop('checked', false);
                $('#department_id').val($("#departmentId").val());
                $('#designation_id').val($("#designationId").val());
                $(".show-table").css("display", "block");
                $(".sms-field").css("display", "block");
                $("#generateButton").css("display", "block");
                $(".certificate-generate").css("display", "block");
                var table = $('#yajraTable').DataTable({
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    iDisplayLength: 50,
                    ajax: {
                        "url": "{{ route('teacher.sms') }}",
                        "data": function(e) {
                            e.department_id = $("#departmentId").val();
                            e.designation_id = $("#designationId").val();

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
                            data: 'cp_phone_no',
                            name: 'cp_phone_no'
                        },
                        {
                            data: 'department_name',
                            name: 'department_name'
                        },
                        {
                            data: 'designation_name',
                            name: 'designation_name'
                        }
                    ],
                    "bDestroy": true,
                    select: {
                        style: 'single'
                    }
                });

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
                    $("#teacherMessage").submit();
                    return (true);
                } else {
                    return (false);
                }
            });
            $(document).on('input', '.sms-input', function() {
                var sms = $(this).val();
                var smsLength = sms.length;
                var banglaLetters = 0;
                for (var i = 0; i < smsLength; i++) {
                    var letterCode = sms.charCodeAt(i);
                    console.log(letterCode);
                    if (letterCode >= 2432 && letterCode <= 2559) {
                        banglaLetters++;
                    }
                }
                $('#character').html('');
                $('#persms').html('');
                $('#remaining').html('');
                $('#smsnums').html('');
                if (banglaLetters > 0) {
                    var perSms = smsLength / 67;
                    var remainingL = smsLength % 67;
                    var remainingLength = 67 - remainingL;
                    perSms = Math.ceil(perSms);
                    $('#persms').html('67');
                    $('#smsnums').html(perSms);
                    $('#smsCount').val(perSms);
                    $('#remaining').html(remainingLength);
                } else {
                    var perSms = smsLength / 153;
                    var remainingL = smsLength % 153;
                    var remainingLength = 153 - remainingL;
                    perSms = Math.ceil(perSms);
                    $('#persms').html('153');
                    $('#smsnums').html(perSms);
                    $('#smsCount').val(perSms);
                    $('#remaining').html(remainingLength);
                }
                $('#character').html(smsLength);
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
