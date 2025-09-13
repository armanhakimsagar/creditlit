<div class="stprimary">
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-lg-9 col-md-9">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('first_name', 'First Name:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::text('first_name', null, [
                                    'id' => 'first_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter First Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('first_name') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('last_name', 'Last Name:', ['class' => 'col-form-label']) !!}

                                {!! Form::text('last_name', null, [
                                    'id' => 'last_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Last Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('last_name') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('father_name', 'Father Name:', ['class' => 'col-form-label']) !!}

                                {!! Form::text('father_name', null, [
                                    'id' => 'father_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Father Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('father_name') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('mother_name', 'Mother Name:', ['class' => 'col-form-label']) !!}

                                {!! Form::text('mother_name', null, [
                                    'id' => 'mother_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Mother Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('mother_name') !!}</span>
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
                                {!! Form::label('dateOfBirth', 'Date of Birth', ['class' => 'col-form-label']) !!}

                                {!! Form::text('date_of_birth', isset($student->date_of_birth) ? old('date_of_birth') : null, [
                                    'id' => 'dateOfBirth',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Birth Date',
                                ]) !!}
                                <span class="error"> {!! $errors->first('date_of_birth') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('nid', 'NID Card No', ['class' => 'col-form-label']) !!}

                                {!! Form::text('nid', null, [
                                    'id' => 'nid',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter NID Card No',
                                ]) !!}
                                <span class="error"> {!! $errors->first('date_of_birth') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
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


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('marital_status', 'Marital Status', ['class' => 'col-form-label']) !!}

                                {!! Form::Select(
                                    'marital_status',
                                    ['married' => 'Married', 'single' => 'Single', 'divorced' => 'Divorced', 'widowed' => 'Widowed'],
                                    $keyPersonnel->marital_status ?? 'single',
                                    [
                                        'id' => 'marital_status',
                                        'class' => 'form-control selectheighttype',
                                    ],
                                ) !!}

                                <span class="error"> {!! $errors->first('marital_status') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('spouse_name', 'Spouse Name:', ['class' => 'col-form-label']) !!}

                                {!! Form::text('spouse_name', null, [
                                    'id' => 'spouse_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Spouse Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('spouse_name') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('child_name', 'Child Name:', ['class' => 'col-form-label']) !!}

                                {!! Form::text('child_name', null, [
                                    'id' => 'child_name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Child Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('child_name') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('current_job_place', 'Current Job Place:', ['class' => 'col-form-label']) !!}

                                {!! Form::text('current_job_place', null, [
                                    'id' => 'current_job_place',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Current Bank/Branch/Company',
                                ]) !!}
                                <span class="error"> {!! $errors->first('current_job_place') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('previous_job_place', 'Previous Job Place:', ['class' => 'col-form-label']) !!}

                                {!! Form::text('previous_job_place', null, [
                                    'id' => 'previous_job_place',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Child Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('previous_job_place') !!}</span>
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line mt-5">
                                {!! Form::label('Gender', 'Gender', ['class' => 'col-form-label']) !!}
                                &nbsp;
                                {{ Form::radio('gender', 'male', isset($student) ? ($student->gender == 'male' ? 'checked' : '') : 'male') }}
                                <span>Boy</span> &nbsp;
                                {{ Form::radio('gender', 'female', isset($student) ? ($student->gender == 'female' ? 'checked' : '') : null) }}
                                <span>Girl</span>&nbsp;
                                {{ Form::radio('gender', 'others', isset($student) ? ($student->gender == 'others' ? 'checked' : '') : null) }}
                                <span>others</span>
                                <br>
                                <label id="gender-error" class="error" for="gender"></label>
                                <span class="error"> {!! $errors->first('blood_group') !!}</span>
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
                                    $keyPersonnel->status ?? 'active',
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

            <div class="col-lg-3 col-md-3">
                {!! Form::label('photo', 'User Picture:', ['class' => 'col-form-label pic-header']) !!}
                <div class="profile-images-card">

                    @if (isset($editPage))
                    @php
                        $photoExists = file_exists(public_path('backend/images/keypersonnel/' . $keyPersonnel->photo));
                    @endphp
                        <div class="profile-images">
                            @if($photoExists)
                            <img src="{{ asset(config('app.asset') . 'backend/images/keypersonnel/'.$keyPersonnel->photo) }}"
                                name="photo" id="upload-img">
                            @else
                            <img src="{{ asset(config('app.asset') . 'backend/images/users/profile.png') }}"
                                name="photo" id="upload-img">
                            @endif
                        </div>
                        <div class="custom-file">
                            <label for="fileupload">Upload Profile</label>
                            <span class="text-danger">(Size Less than 1MB)</span>
                            <br>
                            <span class="error"> {!! $errors->first('photo') !!}</span>
                            <input type="file" id="fileupload" class="fileupload" name="photo"
                                onchange="validateSize(this)">
                            <input type="hidden" name="old_photo" value="{{ $keyPersonnel->photo }}">
                        </div>
                    @else
                        <div class="profile-images">
                            <img src="{{ asset(config('app.asset') . 'backend/images/users/profile.png') }}"
                                name="photo" id="upload-img">
                        </div>
                        <div class="custom-file">
                            <label for="fileupload">Upload Profile</label>
                            <span class="text-danger">(Size Less than 1MB)</span>
                            <br>
                            <span class="error"> {!! $errors->first('photo') !!}</span>
                            <input type="file" id="fileupload" class="fileupload" name="photo"
                                onchange="validateSize(this)">
                        </div>
                    @endif
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
        $("#keyPersonnelAdd").validate({
            rules: {
                first_name: {
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
