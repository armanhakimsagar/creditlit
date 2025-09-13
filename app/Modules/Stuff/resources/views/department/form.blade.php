<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('name', 'Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('name', null, [
                    'id' => 'name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Department name',
                ]) !!}
                <span class="error"> {!! $errors->first('name') !!}</span>
            </div>
        </div>
    </div>



    <div class="col-md-5">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::Select(
                    'status',
                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                    $department->status ?? 'active',
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
        $("#department").validate({
            rules: {
                name: {
                    required: true,
                },
                status: {
                    required: true
                }

            },
            messages: {
                name: 'Please enter Department name',
                status: 'Please choose status'
            }
        });
    });
</script>
