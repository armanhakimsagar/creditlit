@extends('Admin::layouts.master')
@section('body')
    <style>
        tr>th,
        tr>td {
            text-align: center;
        }

        tr:nth-child(even),
        tr:nth-child(odd) {
            background-color: #fff;
        }

        /* for switch button */
        .switch {
            display: inline-block;
            position: relative;
            width: 50px;
            height: 25px;
            border-radius: 20px;
            background: #dfd9ea;
            transition: background 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            vertical-align: middle;
            cursor: pointer;
        }

        .switch::before {
            content: '';
            position: absolute;
            top: 1px;
            left: 2px;
            width: 22px;
            height: 22px;
            background: #fafafa;
            border-radius: 50%;
            transition: left 0.28s cubic-bezier(0.4, 0, 0.2, 1), background 0.28s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .switch:active::before {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.28), 0 0 0 20px rgba(128, 128, 128, 0.1);
        }

        input:checked+.switch {
            background: #7453A6;
        }

        input:checked+.switch::before {
            left: 27px;
            background: #fff;
        }

        input:checked+.switch:active::before {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.28), 0 0 0 20px rgba(0, 150, 136, 0.2);
        }

        .input-container {
            display: inline-block;
            margin-right: 10px;
            vertical-align: top;
            margin-top: -20px;
        }

        .input-container label {
            display: block;
            font-weight: bold;
        }

        .input-container input[type="number"] {
            width: 150px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            margin: 0px 5px 0px;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Company::label.INSTITUTION') @lang('Company::label.SETTINGS')</li>
        <li class="breadcrumb-item active">@lang('Company::label.FINESETTING') </li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $pageTitle }}
        </h1>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        {{ $pageTitle }}</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'fine.setting.store',
                            'files' => true,
                            'name' => 'lateFine',
                            'id' => 'lateFine',
                            'class' => 'form-horizontal',
                        ]) !!}

                        @if ($companyDetails->late_fine_system == 1)
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <div class="frame-wrap">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <label>Select Fine Counting Items</label> <i style="display:inline;"
                                                            id="tooltip" class="fa fa-exclamation-circle"
                                                            data-toggle="tooltip" data-placement="top"
                                                            data-original-title="Select Fine Counting Items"></i>
                                                        {!! Form::Select('item_id[]', $items, isset($data) ? json_decode($data->item_id) : null, [
                                                            'id' => 'visibleProductElement2',
                                                            'class' => 'form-control',
                                                            'multiple' => 'multiple',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <label>Select Fine Items</label> <i style="display:inline;"
                                                            id="tooltip" class="fa fa-exclamation-circle"
                                                            data-toggle="tooltip" data-placement="top"
                                                            data-original-title="Select Fine Items"></i>
                                                        {!! Form::Select('fine_item_id', $items, isset($data) ? $data->fine_item_id : null, [
                                                            'id' => 'fineItemId',
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!! Form::label('late_fine_system', 'Late Fine Date & Amount') !!} <span class="required"> *</span>
                                                        &nbsp;
                                                        <br>
                                                        {{ Form::radio('late_fine_system', '1', null, ['id' => 'fixed_radio']) }}
                                                        <label for="fixed_radio">Fixed</label> &nbsp; &nbsp; &nbsp;
                                                        {{ Form::radio('late_fine_system', '2', 2, ['id' => 'custom_radio']) }}
                                                        <label for="custom_radio">Custom</label> &nbsp; &nbsp; &nbsp;
                                                        <br>
                                                        <label id="late_fine_system-error" class="error" for="late_fine_system"></label>
                                                        <span for="late_fine_system" class="error"> {!! $errors->first('late_fine_system') !!}</span>
                                                        <div id="fixed_inputs" style="display: none;">
                                                            <div class="input-container">
                                                                <span class="error" id="date_error_fixedFine"></span><br>
                                                                <input type="number" name="input1" id="fixedFineDate" placeholder="Enter Fine Date" oninput="validateDay(this)" data-key="fixedFine">
                                                                <input type="number" name="input2" id="fixedFineAmount" placeholder="Enter Fine Amount">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="table-responsive mt-5">
                                            <table class="table table-bordered table-striped ytable" id="yajraTable">
                                                <thead class="thead-themed">
                                                    <tr>
                                                        <th width="50%"> Class Name</th>
                                                        <th> Last Fine Payment Date</th>
                                                        <th> Late Fine Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($classList as $id => $name)
                                                        @php
                                                            $data = DB::table('late_fines')
                                                                ->where('class_id', $id)
                                                                ->first();
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                {{ $name }}
                                                                <input type="hidden" name="class_id[{{ $id }}]"
                                                                    value="{{ $id }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" min="1" max="31" placeholder="Day" class="form-control fine_date" id="date_{{ $id }}" name="date[{{ $id }}]" oninput="validateDay(this)" style="color: #000; font-weight: 600;" data-key="{{ $id }}" value="{{ isset($data) ? $data->date : null }}">
                                                                <span class="error" id="date_error_{{ $id }}"></span>

                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" style="color: #000; font-weight: 500;" class="form-control fine_amount" id="amount{{ $id }}" name="amount[{{ $id }}]" placeholder="Enter Fine Amount" value="{{ isset($data) ? $data->amount : null }}">
                                                                <span class="error" id="inputError_1_1"></span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <div class="text-right">
                                                <button
                                                    class="btn btn-primary btn-sm ml-auto waves-effect waves-themed mt-5"
                                                    id="btnsm" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif




                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // To check date
        function validateDay(input) {
            var day = parseInt(input.value);
            var dataKey = input.getAttribute('data-key');
            var errorSpanId = 'date_error_' + dataKey;
            var errorSpan = document.getElementById(errorSpanId);
            // alert(errorSpan);
            var btn = document.getElementById('btnsm');

            if (day < 1 || day > 31) {
                errorSpan.textContent = "Invalid day. Please enter a value between 1 and 31.";
                btn.disabled = true;
            } else {
                errorSpan.textContent = "";
                btn.disabled = false;
            }
        }

        // if anyone hit fixed
        $(document).ready(function() {
            function toggleInputBoxes() {
                var fixedInputs = $('#fixed_inputs');
                var customRadio = $('#custom_radio');
                if (customRadio.is(':checked')) {
                    fixedInputs.hide();
                } else {
                $('.fine_date').val('');
                    $('.fine_date').val('');
                    $('.fine_amount').val('');
                    $('#fixedFineDate').on('input', function() {
                        var inputValue = $(this).val();
                        $('.fine_date').val(inputValue);
                    }); 
                    $('#fixedFineAmount').on('input', function() {
                        var inputValue = $(this).val();
                        $('.fine_amount').val(inputValue);
                    }); 
                    fixedInputs.show();
                }
            }

            // Trigger the change event on page load
            toggleInputBoxes();

            $('input[name="late_fine_system"]').on('change', function() {
                toggleInputBoxes();
            });
        });





        $(document).ready(function() {
            $('#visibleProductElement2').select2({
                tags: false,
                width: '100%'
            });
            $('#fineItemId').select2({
                tags: false,
                width: '100%'
            });
        });
    </script>
@endsection
