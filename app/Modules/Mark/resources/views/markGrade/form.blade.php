<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('start_mark', 'Start Mark', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::number('start_mark', null, [
                    'id' => 'startMark',
                    'class' => 'form-control',
                    'placeholder' => 'Enter start mark',
                ]) !!}
                <span class="error"> {!! $errors->first('start_mark') !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('end_mark', 'End Mark', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::number('end_mark', null, [
                    'id' => 'endMark',
                    'class' => 'form-control',
                    'placeholder' => 'Enter end mark',
                ]) !!}
                <span class="error"> {!! $errors->first('end_mark') !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('grade', 'Grade', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('grade', null, [
                    'id' => 'grade',
                    'class' => 'form-control',
                    'placeholder' => 'Enter end mark',
                ]) !!}
                <span class="error"> {!! $errors->first('grade') !!}</span>
            </div>
        </div>
    </div>


    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::Select(
                    'status',
                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                    $class->status ?? 'active',
                    [
                        'id' => 'status',
                        'class' => 'form-control selectheighttype',
                    ],
                ) !!}

                <span class="error"> {!! $errors->first('status') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-md-1 align-items-end">
        <div class="form-group">
            <div class="form-line">
                <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed float-left mt-5" id="btnsm"
                    type="submit">Save</button>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    $(function() {
        $("#markAttribute").validate({
            rules: {
                name: {
                    required: true,
                },
                status: {
                    required: true,
                }

            },
            messages: {
                name: 'Please enter mark attribute name',
                status: 'Please choose status',
            }
        });
    });
</script>
