<div class="row" id="notPrintDiv">
    <div class="col-md-3 form-data">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                    @lang('Certificate::label.SESSION')</label>
                {!! Form::Select(
                    'academic_year_id',
                    $academicYearList,
                    !empty($currentYear) ? $currentYear->id:null,
                    [
                        'id' => 'academicYearId',
                        'class' => 'form-control select2 academic-year-id',
                        'onchange' => 'getSection()',
                    ],
                ) !!}
                <span class="error" id="yearError"></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 form-data">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                    @lang('Certificate::label.CLASS')</label>
                {!! Form::Select('class_id', $classList, !empty($request->class_id) ? $request->class_id : null, [
                    'id' => 'class_id',
                    'class' => 'form-control class-id select2',
                    'onchange' => 'getSection()',
                ]) !!}
                <span class="error" id="classError"></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 form-data">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                    @lang('Certificate::label.SECTION')</label>
                <select name="section_id" id="section_id" class="form-control select2">
                    <option value='0'>@lang('Certificate::label.SELECT') @lang('Certificate::label.SECTION')</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3 form-data">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.FILTER')
                    @lang('Certificate::label.ADDRESS')</label>
                {!! Form::Select(
                    'address_id',
                    ['1' => 'With Address', '2' => 'Without Address'],
                    !empty($request->address_id) ? $request->address_id : null,
                    [
                        'id' => 'address_id',
                        'class' => 'form-control select2 address-id',
                    ],
                ) !!}
            </div>
        </div>
    </div>
    <div class="col-md-3 form-data">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.FILTER')
                    @lang('Certificate::label.DOB')</label>
                {!! Form::Select(
                    'date_of_birth_option',
                    ['1' => 'With Date of Birth', '2' => 'Without Date of Birth'],
                    !empty($request->date_of_birth_option) ? $request->date_of_birth_option : null,
                    [
                        'id' => 'date_of_birth_option',
                        'class' => 'form-control select2 date-of-birth-option',
                    ],
                ) !!}
            </div>
        </div>
    </div>
    <div class="col-md-3 form-data">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.FILTER')
                    @lang('Certificate::label.DESIGN')</label>
                {!! Form::Select(
                    'design_option',
                    ['1' => 'A4', '2' => 'A3'],
                    !empty($request->design_option) ? $request->design_option : null,
                    [
                        'id' => 'design_option',
                        'class' => 'form-control select2 design-option',
                    ],
                ) !!}
            </div>
        </div>
    </div>
    <div class="col-md-3 form-data">
        <div class="panel-content align-items-center">

            <button class="btn btn-primary ml-auto mt-5 waves-effect waves-themed btn-sm" type="submit"
                id="searchBtn"><i class="fas fa-search pr-1"></i>@lang('Certificate::label.SEARCH')</button>
        </div>
    </div>
</div>
