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
                                {!! Form::label('email', 'E-mail:', ['class' => 'col-form-label']) !!}

                                {!! Form::email('email', null, [
                                    'id' => 'email',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Email Address',
                                ]) !!}
                                <span class="error"> {!! $errors->first('email') !!}</span>
                            </div>
                        </div>
                    </div>

                    @if (isset($addPage))
                        <div class="col-lg-3 col-md-6 mt-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('password', 'Password', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                    {{ Form::password('password', ['id' => 'password', 'class' => 'form-control']) }}

                                    <span class="error"> {!! $errors->first('password') !!}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($editPage) && $editPage == 'Edit Profile')
                        <div class="col-lg-3 col-md-6 mt-3" style="display: none;">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('roleName', 'Role Name', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select('role_name', $roles, isset($user->roles_id) ? $user->roles_id : null, [
                                        'id' => 'roleName',
                                        'class' => 'form-control selectheighttype',
                                    ]) !!}
                                    <label id="roleName-error" class="error" for="roleName"></label>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-3 col-md-6 mt-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('roleName', 'Role Name', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select('role_name', $roles, isset($user->roles_id) ? $user->roles_id : null, [
                                        'id' => 'roleName',
                                        'class' => 'form-control selectheighttype',
                                    ]) !!}
                                    <label id="roleName-error" class="error" for="roleName"></label>
                                </div>
                            </div>
                        </div>
                    @endif



                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('phone', 'Phone No:', ['class' => 'col-form-label']) !!}

                                {!! Form::number('mobile_no', null, [
                                    'id' => 'phone',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Phone Number',
                                ]) !!}
                                <span class="error"> {!! $errors->first('phone') !!}</span>
                            </div>
                        </div>
                    </div>


                    @if (isset($editPage) && $editPage == 'Edit Profile')
                        <div class="col-lg-3 col-md-6 mt-3" style="display: none;">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('expiryDate', 'Expire Date:', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('expiry_date', isset($user->date_of_birth) ? old('date_of_birth') : null, [
                                        'id' => 'expiryDate',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Start Date',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('date_of_birth') !!}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-3 col-md-6 mt-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('expiryDate', 'Expire Date:', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('expiry_date', isset($user->date_of_birth) ? old('date_of_birth') : null, [
                                        'id' => 'expiryDate',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Start Date',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('date_of_birth') !!}</span>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if (isset($editPage) && $editPage == 'Edit Profile')
                        <div class="col-lg-3 col-md-6 mt-3" style="display: none;">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select(
                                        'status',
                                        ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                                        $user->status ?? 'active',
                                        [
                                            'id' => 'status',
                                            'class' => 'form-control selectheighttype',
                                        ],
                                    ) !!}

                                    <span class="error"> {!! $errors->first('status') !!}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-3 col-md-6 mt-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select(
                                        'status',
                                        ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                                        $user->status ?? 'active',
                                        [
                                            'id' => 'status',
                                            'class' => 'form-control selectheighttype',
                                        ],
                                    ) !!}

                                    <span class="error"> {!! $errors->first('status') !!}</span>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if (isset($editPage) && $editPage == 'Edit Profile')
                        <input type="hidden" name="page_name" value="{{ $editPage }}">
                        <div class="col-12 mt-5">
                            <div class="form-group">
                                <div class="form-line">
                                    <button class="btn btn-primary ml-auto waves-effect waves-themed float-left"
                                        id="btnsm" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    @endif




                </div>
            </div>

            <div class="col-lg-3 col-md-3">
                {!! Form::label('photo', 'User Picture:', ['class' => 'col-form-label pic-header']) !!}
                <div class="profile-images-card">

                    @if (isset($editPage))
                        <div class="profile-images">
                            <img src="{{ asset(config('app.asset') . 'backend/images/users/' . $user->image) }}"
                                name="student_picture" id="upload-img">
                        </div>
                        <div class="custom-file">
                            <label for="fileupload">Upload Profile</label>
                            <span class="text-danger">(Size Less than 1MB)</span>
                            <br>
                            <span class="error"> {!! $errors->first('photo') !!}</span>
                            <input type="file" id="fileupload" class="fileupload" name="photo"
                                onchange="validateSize(this)">
                            <input type="hidden" name="old_photo" value="{{ $user->image }}">
                        </div>
                    @else
                        <div class="profile-images">
                            <img src="{{ asset(config('app.asset') . 'backend/images/users/profile.png') }}"
                                name="student_picture" id="upload-img">
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


        // JS Validation
        $("#userAdmission").validate({
            rules: {
                first_name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                role_name: {
                    required: true,
                },
                mobile_no: {
                    required: true,
                }
            },
            messages: {
                first_name: 'Please Enter First Name',
                email: 'Please Enter E-mail',
                password: 'Please Enter Password',
                role_name: 'Please Enter Role Name',
                mobile_no: 'Please Enter Mobile No',
            }
        });

        // Javascript validation message remove after success
        $("#roleName").change(function() {
            $("#roleName-error").hide();
        });
    });
</script>
