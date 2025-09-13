<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('name', 'Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('name', old('name'), [
                    'id' => 'name',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Group name',
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
                <div
            class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 border-top-0 d-flex flex-row align-items-end">
            <button class="btn btn-sm btn-primary mt-4 waves-effect waves-themed"
                onclick="Validator();" id="btnsm" type="submit">Save</button>
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

        $("#group").validate({
            rules: {
                name: {
                    required: true,
                },
                status: {
                    required: true
                }
            },
            messages: {
                name: 'Please enter group name',
                status: 'Please choose status'
            }
        });
    });

    function Validator() {
        //  ...bla bla bla... the checks
        if ($("#name").val() != '') {
            $("#btnsm").attr("disabled", true);
            $("#btnsm").html('Wait..');
            $("#group").submit();
            return (true);
        } else {
            return (false);
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
