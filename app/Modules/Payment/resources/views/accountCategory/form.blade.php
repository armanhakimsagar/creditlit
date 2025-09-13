<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('accountType', 'Select Account Type', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::Select(
                    'account_type',
                    $accountType,
                    isset($accountCategory) ? $accountCategory->AccountTypeId : null,
                    [
                        'id' => 'accountType',
                        'class' => 'form-control selectheighttype select2',
                    ],
                ) !!}

                <label id="accountTypeError" class="error" for="accountType"></label>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('categoryName', 'Account Category Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                {!! Form::text('category_name', old('category_name'), [
                    'id' => 'categoryName',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Account Category Name',
                ]) !!}
                <span class="error"> {!! $errors->first('category_name') !!}</span>
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
                    old('status'),
                    ['id' => 'status', 'class' => 'form-control selectheight'],
                ) !!}
                <span class="error"> {!! $errors->first('status') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-md-1">
        <div class="float-left mt-6">
            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" onclick="Validator();"
                id="btnsm" type="submit">Save</button>
        </div>
    </div>
</div>
<br>
</div>
</div>
</div>

<script>
    $(function() {

        $("#accountCategory").validate({
            rules: {
                category_name: {
                    required: true,
                },

                status: {
                    required: true,
                },

                account_type: {
                    required: true,
                }

            },
            messages: {
                name: 'Please enter account category name',
                status: 'Please choose status',
                account_type: 'Please choose account type'
            }
        });
        $(".select2").select2();

        $(".select2").change(function() {
            $('#accountTypeError').hide();
        });
    });
</script>
