<div class="stprimary">
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('full_name', 'Bank Name:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::text('full_name', null, [
                                    'id' => 'full_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Bank Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('full_name') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('cp_email', 'E-mail:', ['class' => 'col-form-label']) !!}

                                {!! Form::email('cp_email', null, [
                                    'id' => 'cp_email',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Email Address',
                                ]) !!}
                                <span class="error"> {!! $errors->first('cp_email') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('cp_phone_no', 'Phone No:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::number('cp_phone_no', null, [
                                    'id' => 'cp_phone_no',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Phone Number',
                                ]) !!}
                                <span class="error"> {!! $errors->first('cp_phone_no') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('bank_type', 'Bank Type', ['class' => 'col-form-label']) !!}

                                {!! Form::Select(
                                    'bank_type',
                                    ['centralized' => 'Centralized', 'decentralized' => 'Decentralized'],
                                    $bank->bank_type ?? 'active',
                                    [
                                        'id' => 'bank_type',
                                        'class' => 'form-control selectheighttype',
                                    ],
                                ) !!}

                                <span class="error"> {!! $errors->first('bank_type') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('keyPersonnel', 'Key Personnel Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
    
                                {!! Form::Select('key_personnel', $keyPersonnel, isset($bank) ? $bank->key_personnel_id : null, [
                                    'id' => 'keyPersonnel',
                                    'class' => 'form-control selectheighttype',
                                ]) !!}
                                <span class="error"> {!! $errors->first('key_personnel') !!}</span>
                                <label id="keyPersonnel-error" class="error" for="keyPersonnel"></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('address', 'Address', ['class' => 'col-form-label']) !!}
                                &nbsp;
                                {!! Form::textarea('address', null, [
                                    'class' => 'form-control',
                                    'rows' => 3,
                                    'id' => 'address',
                                    'placeholder' => 'Enter Address',
                                    'style' => 'height:68px;',
                                ]) !!}
                                <span class="error"> {!! $errors->first('address') !!}</span>
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!}

                                {!! Form::Select(
                                    'status',
                                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                                    $bank->status ?? 'active',
                                    [
                                        'id' => 'status',
                                        'class' => 'form-control selectheighttype',
                                    ],
                                ) !!}

                                <span class="error"> {!! $errors->first('status') !!}</span>
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
        $('#keyPersonnel').select2();
        $('#bank_type').select2();
        $("#presentDivision").trigger("change");

        $("#fileupload").change(function(event) {
            var x = URL.createObjectURL(event.target.files[0]);
            $("#upload-img").attr("src", x);
            console.log(event);
        });

        // Date of birth JS Data
        $('#expiryDate').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });

        //  Date of birth JS Data
        $('#dateOfBirth').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });


        // JS Validation
        $("#bankAdd").validate({
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
</script>
