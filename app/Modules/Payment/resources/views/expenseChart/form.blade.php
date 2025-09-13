@if (!$editTitle)
    <div class="row">
        <div class="col-md-6">
            <button type="button" name="padd"
                class="btn btn-success btn-sm ml-auto mt-3 waves-effect waves-themed padd" onclick="addExpenseChart()">+ Add
                More</button>
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-6">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('name', 'Expense Chart Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('name[]', old('name'), [
                    'id' => 'name',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Expense Chart Name',
                    'onkeyup' => 'convert_to_slug();',
                ]) !!}
                <span class="error"> {!! $errors->first('name') !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
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
    <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                <div class="custom-control custom-switch mt-4">
                    <br>
                    <input type="hidden" class="custom-control-input" name="withdraw" value="0">
                    <input type="checkbox" class="custom-control-input" name="withdraw" value="1" id="withdraw"
                        @if (isset($data)) @if ($data->withdraw == 1) checked @endif @endif>
                    <label class="custom-control-label" for="withdraw">@lang('Payment::label.WITHDRAW')</label>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="count" value="0" name="">
    <div class="col-md-12">
        <div id="addMore">

        </div>
    </div>
    <div class="col-md-6">

        <div class=" float-right mt-5">
            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" id="btnsm"
                type="submit">Save</button>
        </div>
    </div>
    <script>
        function convert_to_slug() {
            var str = document.getElementById("name").value;
            str = str.replace(/[^a-zA-Z0-9\s]/g, "");
            str = str.toLowerCase();
            str2 = str.replace(/((\s*\S+)*)\s*/, "$1");
            str = str2.replace(/\s/g, '-');
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

            // $("#salesChart").validate({
            //     rules: {
            //         name: {
            //             required: true,
            //         },
            //         slug: {
            //             required: true
            //         },
            //         status: {
            //             required: true
            //         }

            //     },
            //     messages: {
            //         name: 'Please enter Chart name',
            //         slug: 'Please enter slug',
            //         status: 'Please choose status'
            //     }
            // });

            $('#sales_invoice_date').datepicker({
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                autoClose: true
            });

        }); //end of $ function


        function Validator() {
            //  ...bla bla bla... the checks
            if ($("#name").val() != '') {
                $("#btnsm").attr("disabled", true);
                $("#btnsm").html('Wait..');
                $("#salesChart").submit();
                return (true);
            } else {
                return (false);
            }
        }
        
var k = 1;
function addExpenseChart() {

    k = parseInt(k) + parseInt($('#count').val());
    var html = '';
    html +=
        '<div id="expenseRow_' + k + '" class="new_data">' +
            '<div class="row">' +
                '<div class="col-md-10">\n' +
                '    <div class="form-group">\n' +
                '        <div class="form-line">\n' +
                '            <input name="name[]" type="text" required class="form-control mt-5" placeholder="Enter Expense Chart Name" onInput="checkDuplicate(this.id)" id=' + k + '  autocomplete="off" aria-required="true">\n' +
                '            <span class="error" id="nameError_' + k + '"> </span>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>' +
                '<div class="col-md-2">'+
                    '<button type="button" name="remove"  onclick="removeExpenseChart(' + k +')" class="btn btn-danger btn-sm mt-5 mr-3 remove">Delete</button>' +
                '</div>' +
            '</div>' +
        '</div>';


    $("#addMore").append(html);

    k++;

}
function checkDuplicate(id) {

var values = [];
$("input[type='text']").each(function() {
    values.push($(this).val());

});
examArray = values.sort();
var duplicate = 0;
for (let i = 0; i < examArray.length; i++) {
    if (examArray[i + 1] == examArray[i]) {
        duplicate = examArray[i];
    }
    if (duplicate != 0) {
        $('#nameError_' + id).html('This name already exist!');
        $("#btnsm").attr("disabled", true);
        $(".padd").attr("disabled", true);
        $(".remove").attr("disabled", true);
    } else {
        $('#nameError_' + id).html('');
        $("#btnsm").attr("disabled", false);
        $(".padd").attr("disabled", false);
        $(".remove").attr("disabled", false);

    }
}

}
function removeExpenseChart(expenseRow) {
        ($('#expenseRow_' + expenseRow).remove());
    }
    
</script>
