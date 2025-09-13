<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('year', 'Academic Year', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('year', isset($data) ? old('year') : date('Y'), [
                    'id' => 'year',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Year',
                ]) !!}
                <span class="error"> {!! $errors->first('year') !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('start_date', 'Start Date', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('start_date', isset($data) ? old('start_date') : null, [
                    'id' => 'start_date',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Start Date',
                ]) !!}
                <span class="error"> {!! $errors->first('start_date') !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('end_date', 'End Date', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('end_date', isset($data) ? old('end_date') : null, [
                    'id' => 'end_date',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter End Date',
                ]) !!}
                <span class="error"> {!! $errors->first('end_date') !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                {!! Form::Select(
                    'status',
                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                    old('status'),
                    ['id' => 'status', 'class' => 'form-control selectheighttype'],
                ) !!}
                <span class="error"> {!! $errors->first('status') !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                <div class="custom-control custom-switch">
                    <br>
                    <br>
                    <input type="checkbox" class="custom-control-input" name="is_current" value="1"
                       @if(isset($data)) @if ($data->is_current == 1) checked @endif @endif id="isCurrent">
                    <label class="custom-control-label" for="isCurrent">Current Session</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <div class="form-line">
                <div
                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 border-top-0 d-flex flex-row align-items-end">
                    <button class="btn btn-sm btn-primary mt-4 waves-effect waves-themed" onclick="Validator();"
                        id="btnsm" type="submit">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function convert_to_slug() {
        var str = document.getElementById("name").value;
        str = str.replace(/[^a-zA-Z0-12\s]/g, "");
        str = str.toLowerCase();
        str = str.replace(/\s/g, '-');
        document.getElementById("slug").value = str;

    }

    $(function() {
        // highlight
        var elements = $("input[type!='submit'], textarea, select");
        elements.focus(function() {
            $(this).parents('li').addClass('highlight');
        });
        elements.blur(function() {
            $(this).parents('li').removeClass('highlight');
        });

        $("#academicYear").validate({
            rules: {
                year: {
                    required: true,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                status: {
                    required: true
                }
            },
            messages: {
                year: 'Please enter academic year',
                start_date: 'Please enter date',
                end_date: 'Please enter date',
                status: 'Please choose status'
            }
        });
        // $('#year').datepicker({
        //     language: 'en',
        //     view: 'years',
        //     minView: 'years',
        //     dateFormat: 'yyyy'
        // });
        $('#start_date').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });
        $('#end_date').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });
    });

    function Validator() {
        //  ...bla bla bla... the checks
        if ($("#year").val() != '' && $("#start_date").val() != '' && $("#end_date").val() != '') {
            $("#btnsm").attr("disabled", true);
            $("#btnsm").html('Wait..');
            $("#academicYear").submit();
            return (true);
        } else {
            return (false);
        }
    }

    function saveButtonEnableDisable() {
        var count_err = 0;
        $('.error').each(function() {
            var this_err = $(this).text();
            if (this_err != '') {
                count_err++;
            }
        });

        if (count_err == 0) {
            $('#btnsm').prop('disabled', false);
        } else {
            $('#btnsm').prop('disabled', true);
        }
    }

    function createField() {
        var html = '' +
            '<div class="col-md-12">\n' +
            '    <div class="form-group">\n' +
            '        <div class="form-line">\n' +
            '            <label for="name" class="col-form-label">Name</label><span class="required" aria-required="true"> *</span>\n' +
            '            <input name="name[]" type="text" id="name" class="form-control" placeholder="Enter transport name"  autocomplete="off" aria-required="true">\n' +
            '            <span class="error"> </span>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>';
        $('#dynamic_content').append(html);
    }
</script>
