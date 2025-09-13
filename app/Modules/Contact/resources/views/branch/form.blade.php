<div class="stprimary">
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('full_name', 'Branch Name:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::text('full_name', null, [
                                    'id' => 'full_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Branch Name',
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
                                {!! Form::label('bank_id', 'Bank Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                {!! Form::Select('bank_id', $bankId, isset($branch) ? $branch->bank_id : null, [
                                    'id' => 'bank_id',
                                    'class' => 'form-control selectheighttype',
                                    'onchange' => 'getBankType()',
                                ]) !!}
                                <span class="error"> {!! $errors->first('bank_id') !!}</span>
                                <label id="bank_id-error" class="error" for="bank_id"></label>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('keyPersonnel', 'Key Personnel Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                {!! Form::Select('key_personnel', $keyPersonnel, isset($branch) ? $branch->key_personnel_id : null, [
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
                                    $branch->status ?? 'active',
                                    [
                                        'id' => 'status',
                                        'class' => 'form-control selectheighttype',
                                    ],
                                ) !!}

                                <span class="error"> {!! $errors->first('status') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-3 priceSameCheckbox">
                        <div class="form-group">
                            <div class="form-line mt-5">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input priceSameWithMainBank" name="price_same_with_main_bank" @if (isset($branch->price_same_with_main_bank)) @checked(true) @endif
                                      value="1"  id="priceSameWithMainBank">
                                    <label class="custom-control-label" for="priceSameWithMainBank">Same Price Like It's Main Bank</label>
                                </div>
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
        $('#bank_id').select2();
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
        $("#branchAdd").validate({
            rules: {
                full_name: {
                    required: true,
                },
                cp_phone_no: {
                    required: true,
                },
                bank_id: {
                    required: true,
                },
                key_personnel: {
                    required: true,
                }
            },
            messages: {
                full_name: 'Please Enter Bank Name',
                cp_phone_no: 'Please Enter Mobile No',
                bank_id: 'Please Enter Bank Name',
                key_personnel: 'Please Enter KeyPersonnel Name',
            }
        });
    });



    // Bank Type check
    function getBankType() {
        var bankId = $('#bank_id').val();
        // alert(bankId);
        if (bankId != 0) {
            $.ajax({
                url: "{{ url('get-bank-type') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    bankId: bankId,
                },
                beforeSend: function() {},
                success: function(response) {
                    if (response.bank_type === "centralized") {
                        $('#priceSameWithMainBank').prop( "checked", true);
                        $('.priceSameCheckbox').css('visibility', 'hidden');
                    } else {
                        $('#priceSameWithMainBank').prop( "checked", false);
                        $('.priceSameCheckbox').css('visibility', 'visible');
                    }
                }
            });
        }
    }
</script>
