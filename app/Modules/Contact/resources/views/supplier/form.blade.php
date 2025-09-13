<div class="stprimary">
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('full_name', 'Supplier Name:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::text('full_name', null, [
                                    'id' => 'full_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Supplier Name',
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
                                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!}

                                {!! Form::Select(
                                    'status',
                                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                                    $supplier->status ?? 'active',
                                    [
                                        'id' => 'status',
                                        'class' => 'form-control selectheighttype',
                                    ],
                                ) !!}

                                <span class="error"> {!! $errors->first('status') !!}</span>
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
        $('#status').select2();


        // JS Validation
        $("#supplierAdd").validate({
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
