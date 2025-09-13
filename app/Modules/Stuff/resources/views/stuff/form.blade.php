<div class="stprimary">
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-lg-9 col-md-9">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('firstName', 'First Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>
                                {!! Form::text('first_name', null, [
                                    'id' => 'firstName',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter First Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('first_name') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('lastName', 'Last Name', ['class' => 'col-form-label']) !!}

                                {!! Form::text('last_name', null, [
                                    'id' => 'lastName',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Last Name',
                                ]) !!}
                                <span class="error"> {!! $errors->first('last_name') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('dateOfBirth', 'Date of Birth', ['class' => 'col-form-label']) !!}

                                {!! Form::text('date_of_birth', isset($employee->date_of_birth) ? old('date_of_birth') : null, [
                                    'id' => 'dateOfBirth',
                                    'class' => 'form-control ',
                                    'placeholder' => 'Enter Date of Birth',
                                ]) !!}
                                <span class="error"> {!! $errors->first('date_of_birth') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('joiningDate', 'Joining Date', ['class' => 'col-form-label']) !!}

                                {!! Form::text(
                                    'employee_joining_date',
                                    isset($employee->employee_joining_date) ? old('employee_joining_date') : date('d-m-Y'),
                                    [
                                        'id' => 'joiningDate',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Joining Date',
                                    ],
                                ) !!}
                                <span class="error"> {!! $errors->first('employee_joining_date') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('bloodGroup', 'Blood Group', ['class' => 'col-form-label']) !!}

                                {!! Form::Select(
                                    'blood_group',
                                    [
                                        '' => 'Select Blood group',
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
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('education', 'Education', ['class' => 'col-form-label']) !!}

                                {!! Form::text('education_qualification', null, [
                                    'id' => 'education',
                                    'class' => 'form-control',
                                    'placeholder' => "Enter Employee's Education",
                                ]) !!}
                                <span class="error"> {!! $errors->first('education_qualification') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4  col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('department', 'Select Department', ['class' => 'col-form-label']) !!}
                                {!! Form::Select('employee_department_id', $department, !empty($request->name) ? $request->name : null, [
                                    'id' => 'department',
                                    'class' => 'form-control selectheighttype',
                                ]) !!}
                                <span class="error"> {!! $errors->first('employee_department_id') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('designation', 'Select Designation', ['class' => 'col-form-label']) !!}<span class="required"> *</span>
                                {!! Form::Select('employee_designation_id', $designation, !empty($request->name) ? $request->name : null, [
                                    'id' => 'designation',
                                    'class' => 'form-control selectheighttype',
                                ]) !!}
                                <label id="designationError" class="error" for="designation"></label>
                                {{-- <span class="error"> {!! $errors->first('employee_designation_id') !!}</span> --}}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('empoloyeePhone', 'Phone Number', ['class' => 'col-form-label']) !!}

                                {!! Form::number('cp_phone_no', null, [
                                    'id' => 'empoloyeePhone',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Phone Number',
                                ]) !!}
                                <span class="error"> {!! $errors->first('cp_phone_no') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('employeeEmail', 'E-mail', ['class' => 'col-form-label']) !!}

                                {!! Form::email('cp_email', null, [
                                    'id' => 'employeeEmail',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter E-mail',
                                ]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('accessGroup', 'Access Group', ['class' => 'col-form-label']) !!}
                                {!! Form::Select(
                                    'access_group',
                                    [
                                        '' => 'Choose Access Group',
                                        'Admin' => 'Admin',
                                        'Super Admin' => 'Super Admin',
                                        'User' => 'User',
                                    ],
                                    null,
                                    [
                                        'id' => 'accessGroup',
                                        'class' => 'form-control selectheighttype',
                                    ],
                                ) !!}
                                <span class="error"> {!! $errors->first('access_group') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!}

                                {!! Form::Select(
                                    'status',
                                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                                    $employee->status ?? 'active',
                                    [
                                        'id' => 'status',
                                        'class' => 'form-control selectheighttype',
                                    ],
                                ) !!}

                                <span class="error"> {!! $errors->first('status') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <div class="form-group bank-name">
                            <div class="form-line">
                                {!! Form::label('bank_name', 'Bank Name', ['class' => 'col-form-label']) !!}

                                {!! Form::text('bank_name', isset($employee->bank_name) ? $employee->bank_name : null, [
                                    'id' => 'bankName',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Bank Name',
                                ]) !!}

                                <span class="error"> {!! $errors->first('status') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <div class="form-group bank-account-name">
                            <div class="form-line">
                                {!! Form::label('bank_account_name', 'Bank Account Name', ['class' => 'col-form-label']) !!}

                                {!! Form::text('bank_account_name', isset($employee->bank_account_name) ? $employee->bank_account_name : null, [
                                    'id' => 'bankAccountName',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Bank Account Name',
                                ]) !!}

                                <span class="error"> {!! $errors->first('status') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group bank-account-number">
                            <div class="form-line">
                                {!! Form::label('bank_account_number', 'Bank Account Number', ['class' => 'col-form-label']) !!}

                                {!! Form::text('bank_account_number', isset($employee->bank_account_number) ? $employee->bank_account_number : null, [
                                    'id' => 'bankAccountNumber',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Bank Account Number',
                                ]) !!}

                                <span class="error"> {!! $errors->first('status') !!}</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line mt-5">
                                {!! Form::label('Gender', 'Gender', ['class' => 'col-form-label']) !!}
                                &nbsp;
                                {{ Form::radio('gender', 'male', isset($employee) ? ($employee->gender == 'male' ? 'checked' : '') : 'male') }}
                                <span>Male</span> &nbsp;
                                {{ Form::radio('gender', 'female', isset($employee) ? ($employee->gender == 'female' ? 'checked' : '') : null) }}
                                <span>Female</span>&nbsp;
                                {{ Form::radio('gender', 'others', isset($employee) ? ($employee->gender == 'others' ? 'checked' : '') : null) }}
                                <span>others</span>
                                <br>
                                <label id="gender-error" class="error" for="gender"></label>
                                <span class="error"> {!! $errors->first('gender') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line mt-5">
                                {!! Form::label('religion', 'Religion', ['class' => 'col-form-label']) !!}
                                &nbsp;
                                {{ Form::radio('religion', '1', isset($employee) ? ($employee->religion_id == '1' ? 'checked' : '') : 1) }}
                                <span>Islam</span> &nbsp;
                                {{ Form::radio('religion', '2', isset($employee) ? ($employee->religion_id == '2' ? 'checked' : '') : null) }}
                                <span>Hindu</span>&nbsp;
                                {{ Form::radio('religion', '3', isset($employee) ? ($employee->religion_id == '3' ? 'checked' : '') : null) }}
                                <span>Buddhism</span>&nbsp;
                                {{ Form::radio('religion', '4', isset($employee) ? ($employee->religion_id == '4' ? 'checked' : '') : null) }}
                                <span>Christan</span>
                                <br>
                                <label id="religion-error" class="error" for="religion"></label>
                                <span class="error"> {!! $errors->first('religion') !!}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-3 col-md-3">
                {!! Form::label('photo', 'Employee Picture', ['class' => 'col-form-label pic-header']) !!}
                <div class="profile-images-card">

                    @if (isset($editPage))
                        <div class="profile-images">
                            <img src="{{ asset(config('app.asset') . 'backend/images/employees/' . $employee->old_photo) }}"
                                name="employee_picture" id="upload-img">
                        </div>
                        <div class="custom-file">
                            <label for="fileupload">Upload Profile</label>
                            <span class="text-danger">(Size Less than 1MB)</span>
                            <br>
                            <span class="error"> {!! $errors->first('photo') !!}</span>
                            <input type="file" id="fileupload" class="fileupload" name="photo"
                                onchange="validateSize(this)">
                            <input type="hidden" name="old_photo" value="{{ $employee->old_photo }}">
                        </div>
                    @else
                        <div class="profile-images">
                            <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}"
                                name="employee_picture" id="upload-img">
                        </div>
                        <div class="custom-file ml-3 text-center">
                            <label for="fileupload">Upload Profile</label>
                            <span class="text-danger ">(Size Less than 1MB)</span>
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

    {{-- Employee's Father Info --}}
    <div class="father-info">
        <div class="panel-hdr">
            <h2>
                @lang('Stuff::label.EMPLOYEE_S') @lang('Stuff::label.PARENT') @lang('Student::label.INFO')
            </h2>
        </div>
        <div class="stprimaryinfo">
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('fatherName', 'Father Name', ['class' => 'col-form-label']) !!}

                            {!! Form::text('fathers_name', null, [
                                'id' => 'fatherName',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Father Name',
                            ]) !!}
                            <span class="error"> {!! $errors->first('fathers_name') !!}</span>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('motherName', 'Mother Name', ['class' => 'col-form-label']) !!}

                            {!! Form::text('mother_name', null, [
                                'id' => 'motherName',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Mother Name',
                            ]) !!}
                            <span class="error"> {!! $errors->first('mother_name') !!}</span>
                        </div>
                    </div>
                </div>



                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label(
                                'emergency                                                                                                       Phone',
                                'Emergency Phone Number',
                                ['class' => 'col-form-label'],
                            ) !!}

                            {!! Form::number('emergency_phone_no', !empty($employee->mobile) ? $employee->mobile : null, [
                                'id' => 'emergencyPhone',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Emergency Phone Number',
                            ]) !!}
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>


    <div class="panel-hdr">
        <h2>
            @lang('Stuff::label.EMPLOYEE_S') @lang('Student::label.ADDRESS') @lang('Student::label.INFO')
        </h2>
    </div>
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="row present_address p-2">
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('presentDivision', 'Present Devision', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select(
                                        'present_division',
                                        $division,
                                        isset($present_selected_division->id) ? $present_selected_division->id : old('present_division'),
                                        [
                                            'id' => 'presentDivision',
                                            'class' => 'form-control selectheighttype',
                                        ],
                                    ) !!}
                                    <span class="error"> {!! $errors->first('present_division') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('presentDistrict', 'Present District', ['class' => 'col-form-label']) !!}
                                    {!! Form::Select(
                                        'present_district',
                                        isset($employee) ? $present_district : ['' => 'At first select a Division'],
                                        isset($present_selected_district->id) ? $present_selected_district->id : old('present_district'),
                                        [
                                            'id' => 'presentDistrict',
                                            'class' => 'form-control selectheighttype',
                                        ],
                                    ) !!}
                                    <span class="error"> {!! $errors->first('present_district') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('presentUpazila', 'Present Upazila', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select(
                                        'present_upazila',
                                        isset($employee) ? $present_upazila : ['' => 'At first select a District'],
                                        isset($present_selected_upazila->id) ? $present_selected_upazila->id : old('present_upazila'),
                                        [
                                            'id' => 'presentUpazila',
                                            'class' => 'form-control selectheighttype',
                                        ],
                                    ) !!}
                                    <span class="error"> {!! $errors->first('present_upazila') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('presentAddress', 'Present Address', ['class' => 'col-form-label']) !!}
                                    &nbsp;
                                    {!! Form::textarea('present_address', null, [
                                        'class' => 'form-control',
                                        'style' => 'height:68px;',
                                        'id' => 'presentAddress',
                                        'placeholder' => 'Enter Present Village & Home Name',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('present_address') !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row permanent_address p-2">
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('permanentDivision', 'Permanent Division', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select(
                                        'permanent_division',
                                        $division,
                                        isset($permanent_selected_division->id) ? $permanent_selected_division->id : old('permanent_division'),
                                        [
                                            'id' => 'permanentDivision',
                                            'class' => 'form-control selectheighttype',
                                        ],
                                    ) !!}
                                    <span class="error"> {!! $errors->first('permanent_division') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('permanentDistrict', 'Permanent District', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select(
                                        'permanent_district',
                                        isset($employee) ? $permanent_district : ['' => 'At first select a Division'],
                                        isset($permanent_selected_district->id) ? $permanent_selected_district->id : old('permanent_district'),
                                        [
                                            'id' => 'permanentDistrict',
                                            'class' => 'form-control selectheighttype',
                                        ],
                                    ) !!}
                                    <span class="error"> {!! $errors->first('permanent_district') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('permanentUpazila', 'Permanent Upazila', ['class' => 'col-form-label']) !!}

                                    {!! Form::Select(
                                        'permanent_upazila',
                                        isset($employee) ? $permanent_upazila : ['' => 'At first select a District'],
                                        isset($permanent_selected_upazila->id) ? $permanent_selected_upazila->id : old('permanent_upazila'),
                                        [
                                            'id' => 'permanentUpazila',
                                            'class' => 'form-control selectheighttype',
                                        ],
                                    ) !!}
                                    <span class="error"> {!! $errors->first('permanent_upazila') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('permanentAddress', 'Permanent Address', ['class' => 'col-form-label']) !!}

                                    {!! Form::textarea('permanent_address', null, [
                                        'id' => 'permanentAddress',
                                        'class' => 'form-control selectheighttype',
                                        'placeholder' => 'Enter Permanent Village & Home Name',
                                        'style' => 'height:68px;',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('permanent_address') !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>


            <div class="col-md-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-line mt-5">
                            {!! Form::label('same_address', 'Is permanent same?', ['class' => 'col-form-label']) !!}
                            &nbsp;
                            {!! Form::checkbox('same_address', 'same_address', isset($same_address) ? 'checked' : null, [
                                'onclick' => 'sameAddress()',
                            ]) !!}
                            <br>
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
                        @if (isset($addPage))
                            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed float-right"
                                id="btnsm" type="submit">Save</button>
                            <button class="btn btn-danger btn-sm ml-auto waves-effect waves-themed float-right mr-2"
                                id="btnsm" type="reset">Reset</button>
                        @elseif(isset($editPage))
                            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed float-right"
                                id="btnsm" type="submit">Save</button>
                        @endif
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
        $('#bloodGroup').select2();
        $('#presentDivision').select2();
        $('#presentDistrict').select2();
        $('#presentUpazila').select2();
        $('#permanentDivision').select2();
        $('#permanentDistrict').select2();
        $('#permanentUpazila').select2();
        $('#designation').select2();
        $('#department').select2();
        $('#accessGroup').select2();
        $('#accessGroup').select2();
        $('.select3').select2();
        $("#presentDivision").trigger("change");

        $("#fileupload").change(function(event) {
            var x = URL.createObjectURL(event.target.files[0]);
            $("#upload-img").attr("src", x);
            console.log(event);
        });
        // Date of birth JS Data
        $('#dateOfBirth').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true,
        });
        // Date of birth JS Data
        $('#joiningDate').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });





        //Present District Change on select Devision
        $("#presentDivision").change(function() {
            var id = $(this).val();
            $.ajax({
                url: "{{ url('/get-presenst-district/') }}/" + id,
                type: 'get',
                success: function(data) {
                    $('select[name="present_district"]').empty();
                    $('select[name="present_district"]').append('<option value="">' +
                        'Please Select a District' + '</option>');
                    $.each(data, function(key, data) {
                        $('select[name="present_district"]').append(
                            '<option value="' + data
                            .id + '">' + data.name + '</option>');
                    });
                }
            });
        });


        //Present Upazila Change on select District
        $("#presentDistrict").change(function() {
            var id = $(this).val();
            $.ajax({
                url: "{{ url('/get-presenst-upazila/') }}/" + id,
                type: 'get',
                success: function(data) {
                    $('select[name="present_upazila"]').empty();
                    $('select[name="present_upazila"]').append('<option value="">' +
                        'Please Select a Upazila' + '</option>');
                    $.each(data, function(key, data) {
                        $('select[name="present_upazila"]').append(
                            '<option value="' + data
                            .id + '">' + data.name + '</option>');
                    });
                }
            });
        });



        @if (!empty($same_address))
            $(".permanent_address").hide()
        @endif
        //Permanent District Change on select Devision
        $("#permanentDivision").change(function() {
            var id = $(this).val();
            $.ajax({
                url: "{{ url('/get-permanent-district/') }}/" + id,
                type: 'get',
                success: function(data) {
                    $('select[name="permanent_district"]').empty();
                    $('select[name="permanent_district"]').append('<option value="">' +
                        'Please Select a District' + '</option>');
                    $.each(data, function(key, data) {
                        $('select[name="permanent_district"]').append(
                            '<option value="' + data
                            .id + '">' + data.name + '</option>');
                    });
                }
            });
        });


        //Permanent Upazila Change on select District
        $("#permanentDistrict").change(function() {
            var id = $(this).val();
            $.ajax({
                url: "{{ url('/get-permanent-upazila/') }}/" + id,
                type: 'get',
                success: function(data) {
                    $('select[name="permanent_upazila"]').empty();
                    $('select[name="permanent_upazila"]').append('<option value="">' +
                        'Please Select a Upazila' + '</option>');
                    $.each(data, function(key, data) {
                        $('select[name="permanent_upazila"]').append(
                            '<option value="' + data
                            .id + '">' + data.name + '</option>');
                    });
                }
            });
        });




        // JS Validation
        $("#addEmployee").validate({
            rules: {
                first_name: {
                    required: true,
                },
                employee_designation_id: {
                    required: true,
                },
                salary_year_id: {
                    required: true,
                },
            },
            messages: {
                first_name: 'Please Enter First Name',
                employee_designation_id: "Please Select Designation ",
                salary_year_id: "Please Select Salary Year ",
            }
        });



        // Javascript validation message remove after success
        $("#designation").change(function() {
            $("#designationError").hide();
        });
        $("#classID").change(function() {
            $("#classID-error").hide();
        });


    });

    function sameAddress() {
        // Is permanent address is same?
        $('input:checkbox[name="same_address"]').change(
            function() {
                if ($(this).is(':checked')) {
                    $(".permanent_address").hide(500);
                } else {
                    $(".permanent_address").show(500);
                }
            });
    }
</script>
