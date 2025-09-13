<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('name', 'Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('name', isset($customer->full_name) ? $customer->full_name : null, [
                    'id' => 'name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Customer name',
                ]) !!}
                <span class="error"> {!! $errors->first('name') !!}</span>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('phone', 'Phone No:', ['class' => 'col-form-label']) !!}

                {!! Form::number('cp_phone_no', isset($customer->cp_phone_no) ? $customer->cp_phone_no : null, [
                    'id' => 'phone',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Phone Number',
                ]) !!}
                <span class="error"> {!! $errors->first('phone') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('email', 'E-mail', ['class' => 'col-form-label']) !!}

                {!! Form::email('email', isset($customer->cp_email) ? $customer->cp_email : null, [
                    'id' => 'email',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Email Address',
                ]) !!}
                <span class="error"> {!! $errors->first('email') !!}</span>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('dateOfBirth', 'Date of Birth', ['class' => 'col-form-label']) !!}

                {!! Form::text('date_of_birth', isset($customer->date_of_birth) ? old('date_of_birth') : null, [
                    'id' => 'dateOfBirth',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Birth Date',
                ]) !!}
                <span class="error"> {!! $errors->first('date_of_birth') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('nationality', 'Nationality', ['class' => 'col-form-label']) !!}

                {!! Form::text('nationality', 'Bangladeshi', [
                    'id' => 'nationality',
                    'class' => 'form-control',
                ]) !!}
                <span class="error"> {!! $errors->first('nationality') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('', 'Blood Group', ['class' => 'col-form-label']) !!}

                {!! Form::Select(
                    'blood_group',
                    [
                        null => 'Select Blood Group',
                        'A+' => 'A+',
                        'A-' => 'A-',
                        'O+' => 'O+',
                        'O-' => 'O-',
                        'AB+' => 'AB+',
                        'AB-' => 'AB-',
                        'B+' => 'B+',
                        'B-' => 'B-',
                    ],
                    null,
                    [
                        'id' => 'bloodGroup',
                        'class' => 'form-control selectheighttype',
                    ],
                ) !!}
                <label id="bloodGroup-error" class="error" for="bloodGroup"></label>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('customerAddress', 'Customer Address', ['class' => 'col-form-label']) !!}
                &nbsp;
                {!! Form::textarea(
                    'customer_address',
                    isset($customer->address) ? $customer->address : null,
                    [
                        'class' => 'form-control',
                        'rows' => 1,
                        'id' => 'customerAddress',
                        'placeholder' => 'Enter Customer Address',
                    ],
                ) !!}
                <span class="error"> {!! $errors->first('customer_address') !!}</span>
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
                    $customer->status ?? 'active',
                    [
                        'id' => 'status',
                        'class' => 'form-control selectheighttype',
                    ],
                ) !!}

                <span class="error"> {!! $errors->first('status') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('Gender', 'Gender', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                &nbsp;
                {{ Form::radio('gender', 'male', isset($customer) ? ($customer->gender == 'male' ? 'checked' : '') : 'male') }}
                <span>Boy</span> &nbsp;
                {{ Form::radio('gender', 'female', isset($customer) ? ($customer->gender == 'female' ? 'checked' : '') : null) }}
                <span>Girl</span>&nbsp;
                {{ Form::radio('gender', 'others', isset($customer) ? ($customer->gender == 'others' ? 'checked' : '') : null) }}
                <span>others</span>
                <br>
                <label id="gender-error" class="error" for="gender"></label>
                <span class="error"> {!! $errors->first('blood_group') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('religion', 'Religion', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                &nbsp;
                {{ Form::radio('religion', '1', isset($customer) ? ($customer->religion_id == '1' ? 'checked' : '') : 1) }}
                <span>Islam</span> &nbsp;
                {{ Form::radio('religion', '2', isset($customer) ? ($customer->religion_id == '2' ? 'checked' : '') : null) }}
                <span>Hindu</span>&nbsp;
                {{ Form::radio('religion', '3', isset($customer) ? ($customer->religion_id == '3' ? 'checked' : '') : null) }}
                <span>Buddhism</span>&nbsp;
                {{ Form::radio('religion', '4', isset($customer) ? ($customer->religion_id == '4' ? 'checked' : '') : null) }}
                <span>Christan</span>
                <br>
                <label id="religion-error" class="error" for="religion"></label>
                <span for="blood_group" class="error"> {!! $errors->first('blood_group') !!}</span>
            </div>
        </div>
    </div>


</div>
<div class="row">
    

    <div class="col-md-12 ">
        <div class="form-group">
            <div class="form-line ">
                <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed float-right" id="btnsm"
                    type="submit">Save</button>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    $(function() {
        $('#status').select2();
        $('#bloodGroup').select2();

        // Date of birth JS Data
        $('#dateOfBirth').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });
        
        $("#customer").validate({
            rules: {
                name: {
                    required: true,
                },
                gender: {
                    required: true,
                },
                phone: {
                    required: true,
                }
            },
            messages: {
                first_name: 'Please enter First Name',
                gender: 'Please enter Gender',
                phone: 'Please enter Phone No',
            }
        });
    });
</script>
