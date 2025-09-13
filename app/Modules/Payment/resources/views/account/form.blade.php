
        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('accountType', 'Select Account Type', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                        {!! Form::Select('account_type', $accountType,isset($account) ? $account->AccountTypeId:null, [
                            'id' => 'accountType',
                            'class' => 'form-control selectheighttype select2',
                            'onChange' => 'getAccountCategory()',
                        ]) !!}
                        
                        <label id="accountTypeError" class="error" for="accountType"></label>
                    </div>
                </div>
            </div>

            <div class="col-md-3 form-data">
                <div class="form-group">
                    <div class="form-line">
                            {!! Form::label('accountCategoryId', 'Select Account Category', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
    
                            {!! Form::Select('account_category_id',
                                 isset($account) ? $category: ['' => 'At first select Account Type'],
                                 isset($account) ? $account->AccountCategoryId:null, 
                                 [
                                'id' => 'accountCategoryId',
                                'class' => 'form-control selectheighttype select2',
                                ]) !!}
                            
                            <label id="accountCategoryError" class="error" for="accountCategoryId"></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('shortName', 'Short Name', array('class' => 'col-form-label')) !!} <span class="required"> *</span>
                        {!! Form::text('short_name',old('short_name'),['id'=>'shortName','class' => 'form-control',  'placeholder'=>'Enter Short Name']) !!}
                        <span class="error"> {!! $errors->first('short_name') !!}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('bankName', 'Bank Name', array('class' => 'col-form-label')) !!} <span class="required"> *</span>
                        {!! Form::text('bank_name',old('bank_name'),['id'=>'bankName','class' => 'form-control',  'placeholder'=>'Enter Bank Name']) !!}
                        <span class="error"> {!! $errors->first('bank_name') !!}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('bankBranch', 'Bank Branch', array('class' => 'col-form-label')) !!} <span class="required"> *</span>
                        {!! Form::text('bank_branch',old('bank_branch'),['id'=>'bankBranch','class' => 'form-control',  'placeholder'=>'Enter Bank Branch Name']) !!}
                        <span class="error"> {!! $errors->first('bank_branch') !!}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('accountName', 'Account Name', array('class' => 'col-form-label')) !!} <span class="required"> *</span>
                        {!! Form::text('account_name',old('account_name'),['id'=>'accountName','class' => 'form-control',  'placeholder'=>'Enter Account Name']) !!}
                        <span class="error"> {!! $errors->first('account_name') !!}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::label('accountNumber', 'Account Number', array('class' => 'col-form-label')) !!} <span class="required"> *</span>
                        {!! Form::text('account_number',old('account_number'),['id'=>'accountNumber','class' => 'form-control','required'=> 'required',  'placeholder'=>'Enter Account Number Number']) !!}
                        <span class="error"> {!! $errors->first('account_number') !!}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="form-line">
                                {!!  Form::label('status', 'Status', array('class' => 'col-form-label')) !!} <span
                                        class="required"> *</span>

                                {!! Form::Select('status',array('active'=>'Active','inactive'=>'Inactive','cancel' => 'Cancel'),old('status'),['id'=>'status', 'class'=>'form-control selectheight']) !!}
                                <span class="error"> {!! $errors->first('status') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="float-right mt-6">
                            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" onclick="Validator();"  id="btnsm"   type="submit">Save</button>
                        </div>
                    </div>    
                </div>   
            </div>
        </div>
        <br>
    </div>
  </div>  
</div>

<script>

    $(function () {

        $("#account").validate({
            rules: {
                short_name: {
                    required: true,
                },
    
                status: {
                    required: true,
                },

                account_type: {
                    required: true,
                },
                account_type: {
                    required: true,
                },
                account_number: {
                    required: true,
                },


            },
            messages: {
                short_name: 'Please enter Short name',
                status: 'Please choose status',
                account_type: 'Please choose account type',
                account_number: 'Please enter Account Number',
            }
        });
        $( ".select2").select2();

        $(".select2").change(function(){  
            $('#accountTypeError').hide(); 
        });

        
    });
    function getAccountCategory() {
    
            var accountType = $('#accountType').val()
            var html = '';
            if (accountType != 0) {
                $.ajax({
                    url: "{{ url('get-account-category') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        accountType: accountType,
                    },
                    beforeSend: function() {
                        $('select[name="account_category_id"]').empty();
                    },
                    success: function(response) {
                        $('select[name="account_category_id"]').append(
                            '<option value="0">Select Account Category</option>');
                        $.each(response, function(key, data) {
                            $('select[name="account_category_id"]').append(
                                '<option value="' + data
                                .id + '">' + data.TypeName + '</option>');
                        });
                    }
                });
            }
        }


</script>