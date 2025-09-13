<div class="stprimary">
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-lg-9 col-md-9">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('first_name', 'First Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                {!! Form::text('first_name', null, [
                                    'id' => 'first_name',
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
                                {!! Form::label('last_name', 'Last Name', ['class' => 'col-form-label']) !!}

                                {!! Form::text('last_name', null, [
                                    'id' => 'last_name',
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

                                {!! Form::text('date_of_birth', isset($student->date_of_birth) ? old('date_of_birth') : null, [
                                    'id' => 'dateOfBirth',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Birth Date',
                                ]) !!}
                                <span class="error"> {!! $errors->first('date_of_birth') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('birth_certificate', 'Birth Certificate No', ['class' => 'col-form-label']) !!}

                                {!! Form::text('birth_certificate', null, [
                                    'id' => 'birth_certificate',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Birth Certificate No',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
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

                    <div class="col-lg-4 col-md-6">
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
                            <div class="form-line mt-5">
                                {!! Form::label('Gender', 'Gender', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
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

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <div class="form-line mt-5">
                                {!! Form::label('religion', 'Religion', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                                &nbsp;
                                {{ Form::radio('religion', '1', isset($student) ? ($student->religion_id == '1' ? 'checked' : '') : 1) }}
                                <span>Islam</span> &nbsp;
                                {{ Form::radio('religion', '2', isset($student) ? ($student->religion_id == '2' ? 'checked' : '') : null) }}
                                <span>Hindu</span>&nbsp;
                                {{ Form::radio('religion', '3', isset($student) ? ($student->religion_id == '3' ? 'checked' : '') : null) }}
                                <span>Buddhism</span>&nbsp;
                                {{ Form::radio('religion', '4', isset($student) ? ($student->religion_id == '4' ? 'checked' : '') : null) }}
                                <span>Christan</span>
                                <br>
                                <label id="religion-error" class="error" for="religion"></label>
                                <span for="blood_group" class="error"> {!! $errors->first('blood_group') !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('transportId', 'Transport', ['class' => 'col-form-label']) !!}

                                {!! Form::Select('transport_id', $transport, null, [
                                    'id' => 'transportId',
                                    'class' => 'form-control selectheighttype',
                                ]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-2 col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!}

                                {!! Form::Select(
                                    'status',
                                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                                    $student->status ?? 'active',
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

            <div class="col-lg-3 col-md-3" style="width: 100%">
                {!! Form::label('photo', 'Student Picture', ['class' => 'col-form-label pic-header']) !!}
                <div class="profile-images-card">

                    @if (isset($editPage))
                        <div class="profile-images">
                            <img src="{{ asset(config('app.asset') . 'backend/images/students/' . $student->old_photo) }}"
                                name="student_picture" id="upload-img">
                        </div>
                        <div class="custom-file">
                            <label for="fileupload">Upload Profile</label>
                            <br>
                            <span class="error"> {!! $errors->first('photo') !!}</span>
                            <input type="file" accept="image/*" id="fileupload" class="fileupload" name="photo"
                                onchange="validateSize(this)">
                            <input type="hidden" name="old_photo" value="{{ $student->old_photo }}">
                        </div>
                    @else
                        <div class="profile-images">
                            <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}"
                                name="student_picture" id="upload-img">
                        </div>
                        <div class="custom-file">
                            <label for="fileupload">Upload Profile</label>
                            <br>
                            <span class="error"> {!! $errors->first('photo') !!}</span>
                            <input type="file" accept="image/*" id="fileupload" class="fileupload" name="photo"
                                onchange="validateSize(this)">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Student Academic Info --}}
    <div class="panel-hdr">
        <h2>
            @lang('Student::label.STUDENTS') @lang('Student::label.ACADEMIC') @lang('Student::label.INFO')
        </h2>
    </div>
    <div class="stprimaryinfo">
        <div class="row">
            @if (isset($editPage))
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('academicYearId', 'Academic Year', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                            {!! Form::Select('academic_year_id', $academic_year, !empty($currentYear) ? $currentYear->id : null, [
                                'id' => 'academicYearId',
                                'class' => 'form-control selectheighttype',
                                'onchange' => 'getSection()',
                            ]) !!}
                            <span class="error"> {!! $errors->first('status') !!}</span>
                            <label id="academicYearId-error" class="error" for="academicYearId"></label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('classID', 'Class Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                            {!! Form::Select('class_id', $classes, null, [
                                'id' => 'classID',
                                'class' => 'form-control selectheighttype',
                                'onchange' => 'getSection()',
                            ]) !!}
                            <span class="error"> {!! $errors->first('class_id') !!}</span>
                            <label id="classID-error" class="error" for="classID"></label>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('academicYearId', 'Academic Year', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                            {!! Form::Select('academic_year_id', $academic_year, !empty($currentYear) ? $currentYear->id : null, [
                                'id' => 'academicYearId',
                                'class' => 'form-control selectheighttype',
                                'onchange' => 'getSection();getItemPrice()',
                            ]) !!}
                            <span class="error"> {!! $errors->first('status') !!}</span>
                            <label id="academicYearId-error" class="error" for="academicYearId"></label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('classID', 'Class Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                            {!! Form::Select('class_id', $classes, null, [
                                'id' => 'classID',
                                'class' => 'form-control selectheighttype',
                                'onchange' => 'getSection();getItemPrice()',
                            ]) !!}
                            <span class="error"> {!! $errors->first('class_id') !!}</span>
                            <label id="classID-error" class="error" for="classID"></label>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('sectionID', 'Section Name', ['class' => 'col-form-label']) !!}

                        {!! Form::Select(
                            'section_id',
                            isset($student) ? $sections : ['' => 'At first select a Academic Year & Class'],
                            isset($selected_sections->section_id) ? $selected_sections->section_id : old('section_id'),
                            [
                                'id' => 'sectionID',
                                'class' => 'form-control selectheighttype',
                            ],
                        ) !!}
                        <label id="sectionID-error" class="error" for="sectionID"></label>
                        <span class="error"> {!! $errors->first('section_id') !!}</span>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('studentTypeId', 'Student Type Name', ['class' => 'col-form-label']) !!}

                        {!! Form::Select('student_type_id', $studentType, null, [
                            'id' => 'studentTypeId',
                            'class' => 'form-control select2',
                        ]) !!}
                        <span class="error"> {!! $errors->first('student_type_id') !!}</span>
                        <label id="studentTypeId-error" class="error" for="studentTypeId"></label>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('shiftId', 'Shift Name', ['class' => 'col-form-label']) !!}

                        {!! Form::Select('shift_id', $shift, null, [
                            'id' => 'shiftId',
                            'class' => 'form-control selectheighttype',
                        ]) !!}
                        <span class="error"> {!! $errors->first('shift_id') !!}</span>
                        <label id="shiftId-error" class="error" for="shiftId"></label>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('versionId', 'Version Name', ['class' => 'col-form-label']) !!}

                        {!! Form::Select('version_id', $version, null, [
                            'id' => 'versionId',
                            'class' => 'form-control selectheighttype',
                        ]) !!}
                        <span class="error"> {!! $errors->first('status') !!}</span>
                        <label id="versionId-error" class="error" for="versionId"></label>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('groupId', 'Group Name', ['class' => 'col-form-label']) !!}

                        {!! Form::Select('group_id', $groupName, null, [
                            'id' => 'groupId',
                            'class' => 'form-control selectheighttype',
                        ]) !!}
                        <span class="error"> {!! $errors->first('group_id') !!}</span>
                        <label id="groupId-error" class="error" for="groupId"></label>
                    </div>
                </div>
            </div>
            @if (Session::get('defaultManualCustomizeSID') == 3)
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('student_id', 'Student ID', ['class' => 'col-form-label']) !!}

                            {!! Form::number('student_id', isset($student) ? $student->contact_id : null, [
                                'id' => 'student_id',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Student ID',
                                'required' => 'required'
                            ]) !!}
                            <span class="error"> {!! $errors->first('student_id') !!}</span>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('roll', 'Roll No', ['class' => 'col-form-label']) !!}

                        {!! Form::number('roll', isset($student) ? $student->class_roll : null, [
                            'id' => 'roll',
                            'class' => 'form-control',
                            'placeholder' => 'Enter Roll Number',
                        ]) !!}
                        <span class="error"> {!! $errors->first('roll') !!}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('registration', 'Registration No', ['class' => 'col-form-label']) !!}
                        {!! Form::number('registration', isset($student) ? $student->registration_no : null, [
                            'id' => 'registration',
                            'class' => 'form-control',
                            'placeholder' => 'Enter Registration Number',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('admissionNo', 'Admission No', ['class' => 'col-form-label']) !!}

                        {!! Form::number('admission_no', isset($student) ? $student->admission_no : null, [
                            'id' => 'admissionNo',
                            'class' => 'form-control',
                            'placeholder' => 'Enter Admission No',
                        ]) !!}
                        <span class="error"> {!! $errors->first('roll') !!}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('admissionDate', 'Admission Date', ['class' => 'col-form-label']) !!}

                        {!! Form::text('admission_date', isset($student->admission_date) ? old('admission_date') : date('d-m-Y'), [
                            'id' => 'admissionDate',
                            'class' => 'form-control',
                        ]) !!}
                        <span class="error"> {!! $errors->first('admission_date') !!}</span>
                    </div>
                </div>
            </div>



            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('preschool', 'Previous School Name', ['class' => 'col-form-label']) !!}

                        {!! Form::text('preschool', null, [
                            'id' => 'preschool',
                            'class' => 'form-control',
                            'placeholder' => 'Enter Previous School Name',
                        ]) !!}
                        <span class="error"> {!! $errors->first('preschool') !!}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('phone', 'Phone No', ['class' => 'col-form-label']) !!}

                        {!! Form::number('phone', null, [
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

                        {!! Form::email('email', null, [
                            'id' => 'email',
                            'class' => 'form-control',
                            'placeholder' => 'Enter Email Address',
                        ]) !!}
                        <span class="error"> {!! $errors->first('email') !!}</span>
                    </div>
                </div>
            </div>



        </div>
    </div>


    {{-- Student Academic Info --}}
    <div class="panel-hdr">
        <h2>
            @lang('Student::label.STUDENTS') @lang('Student::label.Fingerprint_Device') @lang('Student::label.INFO')
        </h2>
    </div>
    <div class="stprimaryinfo">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('fingerprintCardNo', 'Fingerprint Card No', ['class' => 'col-form-label']) !!}

                        {!! Form::number('fingerprint_card_no', isset($student) ? $student->fingerprint_card_no : null, [
                            'id' => 'fingerprintCardNo',
                            'class' => 'form-control',
                            'placeholder' => 'Enter Fingerprint Card Number',
                        ]) !!}
                        <span class="error"> {!! $errors->first('fingerprint_card_no') !!}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('fingerprintCardSerialNo', 'Fingerprint Card Serial No', ['class' => 'col-form-label']) !!}
                        {!! Form::number('fingerprint_card_serial_no', isset($student) ? $student->fingerprint_card_serial_no : null, [
                            'id' => 'fingerprintCardSerialNo',
                            'class' => 'form-control',
                            'placeholder' => 'Enter Fingerprint Card Serial Number',
                        ]) !!}
                    </div>
                </div>
            </div>

        </div>
    </div>




    {{-- Student Guardian Details --}}
    <div class="panel-hdr">
        <h2>
            @lang('Student::label.WHAT_TYPE_OF_GUARDIANS')<span class="required"> *</span>
        </h2>
    </div>

    @if (isset($addPage))
        <div class="stprimaryinfo what-type-guardian">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::radio(
                                'guardian_details',
                                'new',
                                isset($student) ? ($student->gender == 'new' ? 'checked' : '') : 'new',
                                ['id' => 'new'],
                            ) }}
                            <label for="new">New Guardian</label> &nbsp;
                            {{ Form::radio(
                                'guardian_details',
                                'existing',
                                isset($student) ? ($student->gender == 'new' ? 'checked' : '') : null,
                                ['id' => 'existing'],
                            ) }}
                            <label for="existing">Existing Guardian</label>&nbsp;
                            <br>
                            <label id="guardian_details-error" class="error" for="guardian_details"></label>
                            <span class="error"> {!! $errors->first('blood_group') !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if (isset($editPage))
        <div class="stprimaryinfo what-type-guardian">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::radio('guardian_details', 'default', 'default', ['id' => 'default']) }}
                            <label for="default">Default</label>&nbsp;
                            @if (isset($guardian_count) && $guardian_count > 1)
                                {{ Form::radio('guardian_details', 'new', null, ['id' => 'new']) }}
                                <label for="new">Add New Guardian</label> &nbsp;
                            @endif
                            {{ Form::radio('guardian_details', 'existing', null, ['id' => 'existing']) }}
                            <label for="existing">Add Existing Guardian</label>&nbsp;
                            <br>
                            <label id="guardian_details-error" class="error" for="guardian_details"></label>
                            <span class="error"> {!! $errors->first('blood_group') !!}</span>
                        </div>
                        <div class="what-type-guardian-message">
                            <p class='alert alert-danger' role='alert'><b>Note:</b> If you update your student by
                                clicking Edit This Guardian, then the parents and guardians will be updated for this
                                student but no more create</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <div class="row new-guardian-info">
        <div class="col-12 new-guardian-details">
            {{-- Student's Father Info --}}
            <div class="father-info">
                <div class="panel-hdr">
                    <h2>
                        @lang('Student::label.STUDENTS') @lang('Student::label.FATHER') @lang('Student::label.INFO')
                    </h2>
                </div>
                <div class="stprimaryinfo">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('fatherName', 'Father Name', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('father_name', null, [
                                        'id' => 'fatherName',
                                        'class' => 'form-control input-change',
                                        'placeholder' => 'Enter Father Name',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('first_name') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('fatherPhone', 'Father Phone', ['class' => 'col-form-label']) !!}

                                    {!! Form::number('father_phone', null, [
                                        'id' => 'fatherPhone',
                                        'class' => 'form-control input-change',
                                        'placeholder' => 'Enter Father Phone',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('fatherEmail', 'Father E-mail', ['class' => 'col-form-label']) !!}

                                    {!! Form::email('father_email', null, [
                                        'id' => 'fatherEmail',
                                        'class' => 'form-control input-change',
                                        'placeholder' => 'Enter Father E-mail',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('fatherEducation', 'Father Education', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('father_education', null, [
                                        'id' => 'fatherEducation',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Father Education',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('fatherOccupation', 'Father Occupation', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('father_occupation', null, [
                                        'id' => 'fatherOccupation',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Father Occupation',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('fatherCompany', 'Father Company', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('father_company', null, [
                                        'id' => 'fatherCompany',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Father Company',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('fatherIncome', 'Father Income', ['class' => 'col-form-label']) !!}

                                    {!! Form::number('father_income', null, [
                                        'id' => 'fatherIncome',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Father Income',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('fatherNid', 'Father NID', ['class' => 'col-form-label']) !!}

                                    {!! Form::number('father_nid', null, [
                                        'id' => 'fatherNid',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Father NID NO',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            {{-- Student's Mother Info --}}
            <div class="mother-info">
                <div class="panel-hdr">
                    <h2>
                        @lang('Student::label.STUDENTS') @lang('Student::label.MOTHER') @lang('Student::label.INFO')
                    </h2>
                </div>
                <div class="stprimaryinfo">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('motherName', 'Mother Name', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('mother_name', null, [
                                        'id' => 'motherName',
                                        'class' => 'form-control input-change',
                                        'placeholder' => 'Enter Mother Name',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('first_name') !!}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('motherPhone', 'Mother Phone', ['class' => 'col-form-label']) !!}

                                    {!! Form::number('mother_phone', null, [
                                        'id' => 'motherPhone',
                                        'class' => 'form-control input-change',
                                        'placeholder' => 'Enter Mother Phone',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('motherEmail', 'Mother E-mail', ['class' => 'col-form-label']) !!}

                                    {!! Form::email('mother_email', null, [
                                        'id' => 'motherEmail',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Mother E-mail',
                                    ]) !!}
                                </div>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('motherEducation', 'Mother Education', ['class' => 'col-form-label']) !!}
                                    {!! Form::text('mother_education', null, [
                                        'id' => 'motherEducation',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Mother Education',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('motherOccupation', 'Mother Occupation', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('mother_occupation', null, [
                                        'id' => 'motherOccupation',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Mother Occupation',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('motherCompany', 'Mother Company', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('mother_company', null, [
                                        'id' => 'motherCompany',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Mother Company',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('motherIncome', 'Mother Income', ['class' => 'col-form-label']) !!}

                                    {!! Form::number('mother_income', null, [
                                        'id' => 'motherIncome',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Mother Income',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('motherNid', 'Mother NID', ['class' => 'col-form-label']) !!}

                                    {!! Form::number('mother_nid', null, [
                                        'id' => 'motherNid',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Mother NID NO',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Student's Guardian Info --}}
            <div class="guardian-info">
                <div class="panel-hdr">
                    <h2>
                        @lang('Student::label.STUDENTS') @lang('Student::label.GUARDIAN') @lang('Student::label.INFO')
                    </h2>
                </div>
                <div class="stprimaryinfo">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('is_guardian', 'Is Your Guardian?', ['class' => 'col-form-label']) !!}
                                    &nbsp;
                                    {{ Form::radio('is_guardian', 'father') }} <span>Father</span> &nbsp;
                                    {{ Form::radio('is_guardian', 'mother') }} <span>Mother</span>&nbsp;
                                    {{ Form::radio('is_guardian', 'other') }} <span>other</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('guardianName', 'Guardian Name', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('guardian_name', null, [
                                        'id' => 'guardianName',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Guardian Name',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('guardian_name') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('guardianPhone', 'Guardian Phone', ['class' => 'col-form-label']) !!}

                                    {!! Form::number('guardian_phone', null, [
                                        'id' => 'guardianPhone',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Guardian Phone',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('guardian_phone') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('guardianRelation', 'Relationship with Guardian', ['class' => 'col-form-label']) !!}

                                    {!! Form::text('guardian_relation', null, [
                                        'id' => 'guardianRelation',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Realtion With guardian',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('guardian_relation') !!}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('guardianAddress', 'Guardian Address', ['class' => 'col-form-label']) !!}
                                    &nbsp;
                                    {!! Form::textarea(
                                        'guardian_address',
                                        isset($guardian_address->guardian_address) ? $guardian_address->guardian_address : '',
                                        [
                                            'class' => 'form-control',
                                            'rows' => 1,
                                            'id' => 'guardianAddress',
                                            'placeholder' => 'Enter Guardian Address',
                                        ],
                                    ) !!}
                                    <span class="error"> {!! $errors->first('guardian_address') !!}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Existing Guardian Info --}}
    <div class="existing-guardian-info">
        <div class="panel-hdr">
            <h2>
                @lang('Student::label.STUDENTS') @lang('Student::label.GUARDIAN') @lang('Student::label.INFO')
            </h2>
        </div>
        <div class="stprimaryinfo">
            <div class="row">

                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('existingGuardian', 'Existing Guardian ID', ['class' => 'col-form-label']) !!}<br>
                            {!! Form::Select(
                                'existing_guardian_id',
                                $existing_guardian,
                                isset($existing_guardian->id) ? $existing_guardian->id : old('existing_guardian'),
                                [
                                    'id' => 'existingGuardianId',
                                    'class' => 'form-control',
                                ],
                            ) !!}
                            <span class="error"> {!! $errors->first('existing_guardian_id') !!}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- Student's Address Info --}}
    <div class="panel-hdr">
        <h2>
            @lang('Student::label.STUDENTS') @lang('Student::label.ADDRESS') @lang('Student::label.INFO')
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
                                        isset($student) ? $present_district : ['' => 'At first select a Division'],
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
                                        isset($student) ? $present_upazila : ['' => 'At first select a District'],
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
                                        'rows' => 3,
                                        'id' => 'presentAddress',
                                        'placeholder' => 'Enter Present Village & Home Name',
                                        'style' => 'height:68px;',
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
                                        isset($student) ? $permanent_district : ['' => 'At first select a Division'],
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
                                        isset($student) ? $permanent_upazila : ['' => 'At first select a District'],
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
                                    &nbsp;
                                    {!! Form::textarea('permanent_address', null, [
                                        'class' => 'form-control',
                                        'rows' => 3,
                                        'id' => 'permanentAddress',
                                        'placeholder' => 'Enter Permanent Village & Home Name',
                                        'style' => 'height:68px;',
                                    ]) !!}
                                    <span class="error"> {!! $errors->first('permanent_address') !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-line">
                            {!! Form::label('notes', 'Enter Notes', ['class' => 'col-form-label']) !!}
                            &nbsp;
                            {!! Form::textarea('notes', null, [
                                'class' => 'form-control',
                                'style' => 'height:68px;',
                                'id' => 'notes',
                                'placeholder' => 'Enter Notes',
                            ]) !!}
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
    {{-- </div>
</div>
</div> --}}
    <script>
        // Is_guardian readonly True function
        function readonly() {
            $('#guardianName').prop('readonly', true);
            $('#guardianPhone').prop('readonly', true);
            $('#guardianRelation').prop('readonly', true);
        }

        // Section Change on select Class
        function getSection() {
            var classId = $('#classID').val()
            var yearId = $('#academicYearId').val();
            var html = '';
            if (classId != 0 && yearId != 0) {
                $.ajax({
                    url: "{{ url('get-sections') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        classId: classId,
                        yearId: yearId
                    },
                    beforeSend: function() {
                        $('select[name="section_id"]').empty();
                    },
                    success: function(response) {
                        $('select[name="section_id"]').append(
                            '<option value="0">Select Section</option>');
                        $.each(response, function(key, data) {
                            $('select[name="section_id"]').append(
                                '<option value="' + data
                                .id + '">' + data.name + '</option>');
                        });
                    }
                });
            }
        }

        $(function() {
            $('#existingGuardianId').select2({
                width: '100%',
                minimumInputLength: 1,


            });
            $('#classID').select2();
            $('#sectionID').select2();
            $('#bloodGroup').select2();
            $('#presentDivision').select2();
            $('#presentDistrict').select2();
            $('#presentUpazila').select2();
            $('#permanentDivision').select2();
            $('#permanentDistrict').select2();
            $('#permanentUpazila').select2();
            $('#transportId').select2();
            $('#shiftId').select2();
            $('#versionId').select2();
            $('#academicYearId').select2();
            $('#groupId').select2();
            $('#studentTypeId').select2();
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
                autoClose: true
            });
            // Date of birth JS Data
            $('#admissionDate').datepicker({
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


            // Is guardian check up value pass jQuery
            $('input:radio[name="is_guardian"]').change(
                function() {
                    if ($(this).is(':checked')) {
                        var value = $(this).val();
                        if (value == "father") {
                            var father_relation = "Father";
                            $('#guardianName').val($('#fatherName').val());
                            $('#guardianPhone').val($('#fatherPhone').val());
                            $('#guardianRelation').val(father_relation);
                            readonly();
                        } else if (value == "mother") {
                            var mother_relation = "Mother";
                            $('#guardianName').val($('#motherName').val());
                            $('#guardianPhone').val($('#motherPhone').val());
                            $('#guardianRelation').val(mother_relation);
                            readonly();
                        } else {
                            $('#guardianName').val("");
                            $('#guardianPhone').val("");
                            $('#guardianRelation').val("");
                            $('#guardianName').prop('readonly', false);
                            $('#guardianPhone').prop('readonly', false);
                            $('#guardianRelation').prop('readonly', false);
                        }
                    }
                });
            // Relatime Guardian change
            $('.input-change').on('input', function() {
                $('input:radio[name="is_guardian"]').trigger('change');
            })

            $('input:radio[name="guardian_details"]').change(
                function() {
                    if ($(this).is(':checked')) {
                        var value = $(this).val();
                        if (value == "default") {
                            $(".new-guardian-info").css('display', 'block');
                            $(".existing-guardian-info").css('display', 'none');
                            $(".what-type-guardian-message").empty();
                            $(".what-type-guardian-message").append(
                                "<p class='alert alert-danger' role='alert'><b>Note:</b> If you update your student by clicking Edit This Guardian, then the parents and guardians will be updated for this student but no more create</p>"
                            );
                        } else if (value == "new") {
                            $(".new-guardian-info").css('display', 'block');
                            $(".existing-guardian-info").css('display', 'none');
                            $(".what-type-guardian-message").empty();
                            $(".what-type-guardian-message").append(
                                "<p class='alert alert-danger' role='alert'><b>Note:</b> If you update your student by clicking New Guardian, then the parents and guardians will newly create for this student & old parents 7 guardian will be deleted</p>"
                            );
                        } else if (value == "existing") {
                            $(".new-guardian-info").css('display', 'none');
                            $(".existing-guardian-info").css('display', 'block');
                            $(".what-type-guardian-message").empty();
                            $(".what-type-guardian-message").append(
                                "<p class='alert alert-danger' role='alert'><b>Note:</b>  If you update your student by clicking Add Existing Guardian, then this students also asign with old guardian which is matched with Guardian ID</p>"
                            );
                        }
                    }
                });

            $(document).on('click', '#btnsm', function(e) {
                e.preventDefault();

                var total = 0;
                $(".payable").each(function(index) {
                    total += parseInt($(this).val());
                });

                if ($("#first_name").val() != '' && $("#classID").val() != '' && $("#student_id").val() !='') {
                    if (total <= 0) {
                        var link = $(this).attr("type");
                        swal({
                                title: "do you want to submit without payment?",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                            .then((isConfirm) => {
                                if (isConfirm) {
                                    $("#btnsm").attr("disabled", true);
                                    $("#btnsm").html('Wait..');
                                    $("#studentAdmission").submit();
                                } else {
                                    swal("Please insert valid payable value!");
                                }
                            });
                    } else {
                        $("#btnsm").attr("disabled", true);
                        $("#btnsm").html('Wait..');
                        $("#studentAdmission").submit();
                    }

                    return (true);
                } else {
                    $("#studentAdmission").submit();
                }

            });
            $(document).on('click', '#editBtnsm', function(e) {

                if ($("#first_name").val() != '' && $("#classID").val() != '') {
                    $("#studentAdmission").submit();
                }

            });



            // JS Validation
            $("#studentAdmission").validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    gender: {
                        required: true,
                    },
                    religion: {
                        required: true,
                    },
                    academic_year_id: {
                        required: true,
                    },
                    class_id: {
                        required: true,
                    },
                    guardian_details: {
                        required: true,
                    },
                },
                messages: {
                    first_name: 'Please enter First Name',
                    gender: 'Please enter Gender',
                    religion: 'Please enter Religion',
                    academic_year_id: 'Please enter Academic Year',
                    class_id: 'Please enter Class',
                    guardian_details: 'Please enter Guardian Details',
                }
            });


            // Javascript validation message remove after success
            $("#academicYearId").change(function() {
                $("#academicYearId-error").hide();
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

        $(function() {
            @if (isset($editPage))
                // @if ($is_paid > 0)
                //     $("#classID option:not(:selected)").each(function() {
                //         $(this).attr('disabled', 'disabled');
                //     });
                // @endif
            @endif
            var relationValue = $("#guardianRelation").val();
            if (relationValue == "Father") {
                var value = 'father';
                $("input[name=is_guardian][value=" + value + "]").attr('checked', 'checked');
                readonly();
            } else if (relationValue == "Mother") {
                var value = 'mother';
                $("input[name=is_guardian][value=" + value + "]").attr('checked', 'checked');
                readonly();
            }

        });
    </script>
