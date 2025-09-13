<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('name', 'Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('name', null, [
                    'id' => 'name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter mark attribute name',
                ]) !!}
                <span class="error"> {!! $errors->first('name') !!}</span>
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
