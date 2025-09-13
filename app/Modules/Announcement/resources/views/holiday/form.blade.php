<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('title', 'Title', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('title', null, [
                    'id' => 'title',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Title name',
                ]) !!}
                <span class="error"> {!! $errors->first('title') !!}</span>
            </div>
        </div>
    </div>





    <div class="col-md-3">
        <div class="form-group">

            <div class="form-line">
                <label class="col-form-label">From Date</label><span class="required"> *</span>
                {!! Form::text('from_date', !empty($request->from_date) ? $request->from_date : null, [
                    'id' => 'fromDate',
                    'class' => 'form-control from_date',
                    'placeholder' => 'dd-mm-yyyy',
                ]) !!}

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">

            <div class="form-line">
                <label class="col-form-label">To Date</label><span class="required"> *</span>
                {!! Form::text('to_date', !empty($request->to_date) ? $request->to_date : null, [
                    'id' => 'toDate',
                    'class' => 'form-control to_date',
                    'placeholder' => 'dd-mm-yyyy',
                ]) !!}

            </div>
        </div>
    </div>


    <div class="col-md-6 mt-5">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('guardianAddress', 'Details ', ['class' => 'col-form-label']) !!}
                {!! Form::textarea(
                    'principal_details_in_certificate',
                    isset($holiday) ? $holiday->details : null,
                    [
                        'class' => 'form-control textarea',
                        'rows' => 5,
                        'id' => 'principalDetailsInCertificate',
                        'placeholder' => 'Enter Principle Details',
                        'style' => 'height:100px;',
                    ],
                ) !!}
                <span class="error"> {!! $errors->first('principal_details_in_certificate') !!}</span>
            </div>
        </div>
    </div>


    <div class="col-md-1 align-self-end mb-5">
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
    $(document).ready(function() {
        jQuery('#fromDate').datepicker({
            language: 'en',
            dateFormat: 'yyyy-mm-dd',
            autoClose: true
        });
        jQuery('#toDate').datepicker({
            language: 'en',
            dateFormat: 'yyyy-mm-dd',
            autoClose: true
        });

    });

    $(function() {
        $("#holiday").validate({
            rules: {
                title: {
                    required: true,
                },
                from_date: {
                    required: true,
                },
                to_date: {
                    required: true,
                }

            },
            messages: {
                title: 'Please enter title',
                from_date: 'Please choose from date',
                to_date: 'Please enter class to date'
            }
        });
    });
</script>
