@if (!$editTitle)
    <div class="row">
        <div class="col-md-6">
            <button type="button" name="padd"
                class="btn btn-success btn-sm ml-auto mt-3 waves-effect waves-themed padd" onclick="addGiftType()">+ Add
                More</button>
        </div>
    </div>
@endif



<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('name', 'Gift Type Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                {!! Form::text('name[]', old('name'), [
                    'id' => 'giftTypeName[]',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Gift Type name',
                    'onInput' => 'checkDuplicate(1)',
                ]) !!}
                <span class="error"> {!! $errors->first('name') !!}</span>
            </div>
        </div>
    </div>

    <input type="hidden" id="count" value="0" name="">
    <div class="col-md-6">
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
    <div class="col-md-12">
        <div id="addMore">

        </div>
    </div>
    <div class="col-md-6">

        <div class="float-right mt-3">
            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" onclick="Validator();"
                id="btnsm" type="submit">Save</button>
        </div>

    </div>
</div>
<br>
<script>
    $(function() {

        $("#giftType").validate({
            rules: {
                name: {
                    required: true,
                },

                status: {
                    required: true
                }

            },
            messages: {
                name: 'Please enter Gift Type name',
                status: 'Please choose status',
            }
        });
        $(".select2").select2();

    });
    var k = 1;

    function addGiftType() {
        k = parseInt(k) + parseInt($('#count').val());
        var html = '';
        html +=
            '<div id="giftTypeRow_' + k + '" class="new_data">' +
            '<div class="row">' +
            '<div class="col-md-11">\n' +
            '    <div class="form-group">\n' +
            '        <div class="form-line">\n' +
            '            <label for="name" class="col-form-label">Gift Type Name</label><span class="required" aria-required="true"> *</span>\n' +
            '            <input name="name[]" type="text" class="form-control" onInput="checkDuplicate(this.id)" id='+k+' placeholder="Enter Gift Type name"  autocomplete="off" aria-required="true">\n' +
            '            <span class="error" id="nameError_'+k+'"> </span>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="col-md-1">' +
            '<button type="button" name="remove" onclick="removeGiftType(' + k +
            ')" class="btn btn-danger btn-sm mt-6 remove">Delete</button>' +
            '</div>' +
            '</div>' +
            '</div>';


        $("#addMore").append(html);


        $('.js-example-basic').select2();
        k++;

    }
    function checkDuplicate(id)
    {  

        var values = [];
                $("input[type='text']").each(function() {
                    values.push($(this).val());

                });
                categoryArray = values.sort();
                var duplicate = 0;
                for (let i = 0; i < categoryArray.length; i++) {
                if (categoryArray[i+1] == categoryArray[i]) {
                        duplicate = categoryArray[i];
                }
                if (duplicate != 0) {
                    $('#nameError_'+id).html('This name already exist!');
                            $("#btnsm").attr("disabled", true);
                            $(".padd").attr("disabled", true);
                            $(".remove").attr("disabled", true);
                            $('#' + id).css("background-color", "yellow");
                    }else {
                            $('#nameError_'+id).html('');
                            $("#btnsm").attr("disabled", false);
                            $(".padd").attr("disabled", false);
                            $(".remove").attr("disabled", false);
                            $('#' + id).css("background-color", "#fff");

                        }
                }
                    
    }

    function removeGiftType(giftTypeRow) {
        ($('#giftTypeRow_' + giftTypeRow).remove());
    }
</script>
