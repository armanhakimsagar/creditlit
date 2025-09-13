<div class="stprimary">
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('name', 'Gift Name:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::text('name', null, [
                                    'id' => 'name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Gift Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('name') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('type', 'Gift Type', ['class' => 'col-form-label']) !!}

                                {!! Form::Select('type', $type, isset($gift) ? $gift->type : null, [
                                    'id' => 'type',
                                    'class' => 'form-control selectheighttype',
                                    'required' => true,
                                ]) !!}
                                <span class="error"> {!! $errors->first('type') !!}</span>
                                <label id="country-error" class="error" for="type"></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('cost', 'Gift Cost:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::number('cost', null, [
                                    'id' => 'cost',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Gift Cost',
                                ]) !!}
                                <span class="error"> {!! $errors->first('cost') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('customer_type', 'Customer Type', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::Select(
                                    'customer_type',
                                    ['bank' => 'Bank', 'branch' => 'Branch', 'company' => 'Company'],
                                    $gift->customer_type ?? 'branch',
                                    [
                                        'id' => 'customer_type',
                                        'class' => 'form-control selectheighttype',
                                        'onchange' => 'getCustomer();',
                                        'required' => true,
                                    ],
                                ) !!}

                                <span class="error"> {!! $errors->first('customer_type') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('bank_id', 'Customer Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                {!! Form::Select('bank_id', $bankId, $customer ?? null, [
                                    'id' => 'bank_id',
                                    'class' => 'form-control selectheighttype',
                                    'onchange' => 'getBranch();getKeypersonnel()',
                                    'required' => true,
                                ]) !!}
                                <span class="error"> {!! $errors->first('bank_id') !!}</span>
                                <label id="bank_id-error" class="error" for="bank_id"></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('branch_id', 'Branch Name', ['class' => 'col-form-label']) !!}

                                {!! Form::select('branch_id', $branch, $branchId ?? ['' => 'At first select a Bank'], [
                                    'id' => 'branch_id',
                                    'class' => 'form-control selectheighttype',
                                    'onchange' => 'getKeypersonnel()',
                                ]) !!}



                                <span class="error"> {!! $errors->first('branch_id') !!}</span>
                                <label id="branch_id-error" class="error" for="branch_id"></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('keyPersonnel', 'Key Personnel Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                {!! Form::Select(
                                    'key_personnel',
                                    $keyPersonnel,
                                    isset($orderDataDetails) ? $orderDataDetails->key_personnel_id : null,
                                    [
                                        'id' => 'keyPersonnel',
                                        'class' => 'form-control selectheighttype',
                                        'required' => true,
                                    ],
                                ) !!}
                                <span class="error"> {!! $errors->first('key_personnel') !!}</span>
                                <label id="keyPersonnel-error" class="error" for="keyPersonnel"></label>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('delivered_by', 'Gift Delivered By', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                {!! Form::Select('delivered_by', $delivered_by, isset($gift) ? $gift->delivered_by : null, [
                                    'id' => 'delivered_by',
                                    'class' => 'form-control selectheighttype',
                                ]) !!}
                                <span class="error"> {!! $errors->first('delivered_by') !!}</span>
                                <label id="delivered_by-error" class="error" for="delivered_by"></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('date', 'Delivery Date', ['class' => 'col-form-label']) !!}

                                {!! Form::text('date', isset($gift->date) ? old('date') : date('d-m-Y'), [
                                    'id' => 'date',
                                    'class' => 'form-control ',
                                    'placeholder' => 'Enter Delivery Date',
                                ]) !!}
                                <span class="error"> {!! $errors->first('date_of_birth') !!}</span>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>

    <div class="stprimarybutton">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="form-line">
                        <button class="btn btn-primary ml-auto waves-effect waves-themed float-left" id="btnsm"
                            type="submit">Save</button>
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
        $('#existingGuardianId').select2({
            width: '100%',
            minimumInputLength: 1,


        });
        $('#roleName').select2();
        $('#marital_status').select2();
        $('#status').select2();
        $('#customer_type').select2();
        $('#type').select2();
        $('#bank_id').select2();
        $('#branch_id').select2();
        $('#keyPersonnel').select2();
        $('#delivered_by').select2();



        // JS Validation
        $("#giftAdd").validate({
            rules: {
                full_name: {
                    required: true,
                },
                cp_phone_no: {
                    required: true,
                }
            },
            messages: {
                first_name: 'Please Enter First Name',
                cp_phone_no: 'Please Enter Mobile No',
            }
        });
    });


    // Customer Change on select Customer Type
    function getCustomer() {
        var customerType = $('#customer_type').val();
        var html = '';
        if (customerType != 0) {
            $.ajax({
                url: "{{ url('get-customer') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    customerType: customerType
                },
                beforeSend: function() {
                    $('select[name="bank_id"]').empty();
                },
                success: function(response) {
                    if (customerType == 'branch' || customerType == 'bank') {
                        $('select[name="bank_id"]').append('<option value="0">Select Bank</option>');
                    } else if (customerType == 'company') {
                        $('select[name="bank_id"]').append('<option value="0">Select Company</option>');
                    }
                    $.each(response, function(key, data) {
                        $('select[name="bank_id"]').append(
                            '<option value="' + data
                            .id + '">' + data.full_name + '</option>');
                    });
                    $("#bank_id").trigger("change");
                    if (customerType == 'bank' || customerType == 'company') {
                        $("#branch_id").prop('disabled', true);
                    } else {
                        $("#branch_id").prop('disabled', false);
                    }
                }
            });
        }
    }


    // Branch Change on select Bank
    function getBranch() {
        var bankId = $('#bank_id').val();
        var html = '';
        if (bankId != 0) {
            $.ajax({
                url: "{{ url('get-bank') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    bankId: bankId
                },
                beforeSend: function() {
                    $('select[name="branch_id"]').empty();
                },
                success: function(response) {
                    $('select[name="branch_id"]').append(
                        '<option value="0">Select Branch</option>');
                    $.each(response, function(key, data) {
                        $('select[name="branch_id"]').append(
                            '<option value="' + data
                            .id + '">' + data.full_name + '</option>');
                    });
                    $("#branch_id").trigger("change");
                }
            });
        }
    }


    // Key Personnel Change on select Bank Or Branch
    function getKeypersonnel() {
        var customerType = $('#customer_type').val();
        if (customerType == 'bank' || customerType == 'company') {
            var keyPersonnelSearchId = $('#bank_id').val();
        } else if (customerType == 'branch') {
            var keyPersonnelSearchId = $('#branch_id').val();
        }

        // alert(keyPersonnelSearchId);

        var html = '';
        if (keyPersonnelSearchId != 0) {
            $.ajax({
                url: "{{ url('get-order-keypersonnel') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    keyPersonnelSearchId: keyPersonnelSearchId
                },
                beforeSend: function() {

                },
                success: function(response) {
                    // Set the value of the select2
                    $('#keyPersonnel').val(response.id).trigger('change');
                    $('#cp_phone_no').val(response.phone);
                }
            });
        }
    }
</script>
