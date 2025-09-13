@extends('Admin::layouts.master')

@section('body')

    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: {{ Session::get('allreportfontsize') }}px;
        }

        .remove-style {
            border: none;
            overflow: auto;
            outline: none;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            resize: none;
            background-color: none !important;
        }

        tr:nth-child(even),
        tr:nth-child(odd) {
            background-color: #fff;
        }
    </style>

    <?php
    
    use Illuminate\Support\Facades\Input;
    ?>

    <!--Filter :Starts -->
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Easca-Invoice</a></li>
        <li class="breadcrumb-item">Data Import</li>
        <li class="breadcrumb-item active">Data Insert</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-list'></i> Data
        </h1>
        <div class="col-xl-10">
            <button class="btn btn-success waves-effect float-right m-l-10" id="btn"
                style="margin-right: 10px; margin-bottom: 10px;">Import</button>
        </div>
    </div>
    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                @if (!empty($data))
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="testTable">
                                <thead class="thead-themed">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Punch Date</th>
                                        <th class="text-center">Card No</th>
                                        <th class="text-center">In Gate Name</th>
                                        <th class="text-center">In Time</th>
                                        <th class="text-center">Out Gate Name</th>
                                        <th class="text-center">Out Time</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Is Validate</th>
                                        <th class="text-center">Error Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $uniqueKey = 0;
                                    ?>
                                    @foreach ($data as $values)
                                        <tr>
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                <textarea field-name="name" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="name_{{ $values->id }}" class="editable remove-style">{{ $values->name }}</textarea>
                                            </td>
                                            <td>
                                                <textarea field-name="punch_date" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="punchDate_{{ $values->id }}" class="editable remove-style">{{ $values->punch_date }}</textarea>
                                            </td>
                                            <td>
                                                <textarea field-name="card_no" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="cardNo_{{ $values->id }}" class="editable remove-style">{{ $values->card_no }}</textarea>
                                            </td>
                                            <td>
                                                <textarea field-name="in_gate_name" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="inGateName_{{ $values->id }}" class="remove-style">{{ $values->in_gate_name }}</textarea>
                                            </td>
                                            <td>
                                                <textarea field-name="in_time" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="inTime_{{ $values->id }}" class="editable remove-style">{{ $values->in_time }}</textarea>
                                            </td>
                                            <td>
                                                <textarea field-name="out_gate_name" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="outGateName_{{ $values->id }}" class="remove-style">{{ $values->out_gate_name }}</textarea>
                                            </td>
                                            <td>
                                                <textarea field-name="out_time" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="outTime_{{ $values->id }}" class="editable remove-style">{{ $values->out_time }}</textarea>
                                            </td>
                                            <td>
                                                <textarea field-name="status" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="status_{{ $values->id }}" class="remove-style">{{ $values->status }}</textarea>
                                            </td>
                                            <td>
                                                <textarea field-name="type" data-key="{{ $values->id }}" data-unique="{{ ++$uniqueKey }}"
                                                    id="type_{{ $values->id }}" class="remove-style">{{ $values->type }}</textarea>
                                            </td>

                                            <td>
                                                @if ($values->is_validate == 1)
                                                    <span id="validateId_{{ $values->id }}">True</span>
                                                @else
                                                    <span class="error">False</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($values->error_message))
                                                    <span class="error"
                                                        id="errorMessageId_{{ $values->id }}">{{ $values->error_message }}</span>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#testTable').dataTable();

            $(document).on('click', '.editable', function(e) {
                e.preventDefault();
                var dataKey = $(this).attr('data-key');
                var fieldName = $(this).attr('field-name');
                // alert(fieldName);
                var id = $(this).attr('id');
                var uniqueKey = $(this).attr('data-unique');
                $('.editable').each(function() {
                    var key = $(this).attr('data-unique');
                    if (uniqueKey != key) {
                        $(this).addClass("remove-style");
                    } else {
                        $(this).removeClass("remove-style");
                    }
                });

                $("#" + id).on('keydown', function(event) {
                    if (event.keyCode == 13) {
                        event.preventDefault();
                        var inputVal = $("#" + id).val();
                        $('.editable').each(function() {
                            $(this).addClass("remove-style");
                            $(this).blur();
                        });
                        $.ajax({
                            url: "{{ url('attendance-data-insert-update') }}",
                            type: "post",
                            dataType: "json",
                            data: {
                                _token: '{!! csrf_token() !!}',
                                name_id: dataKey,
                                field_name: fieldName,
                                input_value: inputVal
                            },
                            success: function(res) {
                                if (res.success == true) {
                                    $("#" + id).css({
                                        'background-color': 'yellow'
                                    });
                                    setTimeout(function() {
                                        $("#" + id).css("background-color",
                                            "white");
                                    }, 1000);
                                }
                            }
                        });
                    }
                });

            });

            $(document).on('click', '#btn', function(e) {
                e.preventDefault();
                $('#btn').prop("disabled", true);
                window.location.href = "{{ route('attendance.data.insert.import.data', $batchId) }}";
            });
        });
    </script>
@endsection
