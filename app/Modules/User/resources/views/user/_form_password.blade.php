<?php

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;

?>


<div class="row">
    <div class="col-md-6">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('old_password', 'Current Password', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::password('old_password', [
                    'id' => 'old_password',
                    'class' => 'form-control',
                    'required' => 'required',
                    'title' => 'Enter Current old_password',
                ]) !!}

                {!! $errors->first('old_password') !!}
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('password', 'New Password', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::password('password', [
                    'id' => 'password',
                    'class' => 'form-control',
                    'required' => 'required',
                    'title' => 'Enter User New password',
                ]) !!}

                {!! $errors->first('password') !!}

            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::password('password_confirmation', [
                    'id' => 'password_confirmation',
                    'class' => 'form-control',
                    'required' => 'required',
                    'title' => 'Retype Password',
                ]) !!}

                {!! $errors->first('password_confirmation') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mt-3">
    
            <button class="btn btn-primary ml-auto waves-effect waves-themed" type="submit">Save</button>
        </div>
    </div>
</div>



<script>
    $(function() {
        // highlight
        var elements = $("input[type!='submit'], textarea, select");
        elements.focus(function() {
            $(this).parents('li').addClass('highlight');
        });
        elements.blur(function() {
            $(this).parents('li').removeClass('highlight');
        });

        $("#password_resetfrom").validate({
            rules: {

                old_password: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                password_confirmation: {
                    required: true,
                    equalTo: '#password',
                },


            },
            messages: {
                old_password: 'Please enter old password',
                password: 'Please enter new password',
                password_confirmation: 'Please retype password',
            }
        });
    });
</script>
