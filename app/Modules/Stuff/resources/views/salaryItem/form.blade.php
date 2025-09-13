@if (!$editTitle)
    <div class="row">
        <div class="col-md-6">
            <button type="button" name="padd"
                class="btn btn-success btn-sm ml-auto mt-3 waves-effect waves-themed padd" onclick="addItem()">+ Add
                More</button>
        </div>
    </div>
@endif


<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>
                    @lang('Item::label.ITEM') @lang('Item::label.NAME')</label><span class="required"> *</span>
                {!! Form::text('name[]', null, [
                    'id' => 'nameId_0',
                    'class' => 'form-control required item-name',
                    'placeholder' => 'Enter Salary Item name',
                    'onInput' => 'checkItemDuplicate(0)',
                ]) !!}
                <span class="error" id="nameError_0"> </span>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.SELECT') @lang('Item::label.TYPE')</label>
                {!! Form::Select('type[]', ['1' => 'gross', '2' => 'deduction', '3' => 'allowance'], null, [
                    'id' => 'type',
                    'class' => 'form-control select2',
                ]) !!}
                <span class="error" id="typeError"></span>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.SELECT') @lang('Stuff::label.AMOUNT')
                    @lang('Item::label.TYPE')</label>
                {!! Form::Select('amount_type[]', ['' => 'Select One', 'flat' => 'flat', 'percentage' => 'percentage'], null, [
                    'id' => 'amountType',
                    'class' => 'form-control select2',
                ]) !!}
                <span class="error" id="amountTypeError"></span>
            </div>
        </div>
    </div>


    <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('amount', 'Amount', ['class' => 'col-form-label']) !!}

                {!! Form::number('amount[]', isset($student) ? $student->class_roll : null, [
                    'id' => 'amount',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Amount',
                ]) !!}
                <span class="error"> {!! $errors->first('roll') !!}</span>
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
                    ['id' => 'status', 'class' => 'form-control selectheight select2'],
                ) !!}
                <span class="error"> {!! $errors->first('status') !!}</span>
            </div>
        </div>
    </div>

    @if ($editTitle == false)
    <div class="col-md-2 mt-5" id="isBasicRow">
        <div class="form-group">
            <div class="form-line">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="is_basic" value="1" id="isBasic">
                    <label class="custom-control-label" for="isBasic">Basic Item</label>
                </div>
            </div>
        </div>
    </div> 
    @else
    <div class="col-md-2 mt-5">
        <div class="form-group">
            <div class="form-line">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="is_basic" value="1"
                       @if(isset($item)) @if ($item->is_basic == 1) checked @endif @endif id="isBasic">
                    <label class="custom-control-label" for="isBasic">Basic Item</label>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    

    



    <input type="hidden" id="count" value="0" name="">
    {{-- <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                    @lang('Item::label.CATEGORY')</label>
                {!! Form::Select(
                    'category_id',
                    $categorys,
                    null,
                    [
                        'id' => 'categoryId',
                        'class' => 'form-control select2',
                    ],
                ) !!}
                <label for="categoryId" class="error"></label>
                <span class="error" id="categoryIdError"></span>
            </div>
        </div>
    </div> --}}



    {{-- <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>
                    @lang('Item::label.SHORT') @lang('Item::label.NAME')</label>
                {!! Form::text('short_name[]', null, [
                    'id' => 'shortName_0',
                    'class' => 'form-control required short-name',
                    'placeholder'=> "Enter Short name",
                    'onInput' => 'checkDuplicate(0)',
                ]) !!}
                <span class="error" id="shortNameError_0"> </span>
            </div>
        </div>
    </div> --}}

    <div class="col-md-12">
        <div id="addMore">

        </div>
    </div>
    <div class="col-md-6">
        <div class="float-right mt-3">
            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" id="btnsm"
                type="submit">Save</button>
        </div>
    </div>
</div>



</div>

<script>
    $(function() {

        // Initialize the form validation
        $("#item").validate({
            // Specify the validation rules for each field
            rules: {
                "name[]": {
                    required: true,
                }
            },
            messages: {
                "name[]": {
                    required: "Please enter an item name",
                }
            }
        });
        $(".select2").select2();

    });

    function Validator() {
        let categoryValue = $('#categoryId').val();
        if (categoryValue) {
            $("#btnsm").attr("disabled", true);
            $("#btnsm").html('Wait..');
            $("#item").submit();
            return (true);
        } else {
            return (false);
        }
    }

    var k = 1;

    function addItem() {

        k = parseInt(k) + parseInt($('#count').val());
        console.log(k);
        var html = '';
        html +=
            '<div id="itemRow_' + k + '" class="new_data">' +
            '<div class="row">' +
            '<div class="col-md-3">\n' +
            '    <div class="form-group">\n' +
            '        <div class="form-line">\n' +
            '            <label for="name" class="col-form-label">Item name</label><span class="required" aria-required="true"> *</span>\n' +
            '            <input name="name[]" type="text" class="form-control item-name required" placeholder="Enter Item name" onInput="checkItemDuplicate(' +
            k + ')" id=nameId_' + k + '  autocomplete="off" aria-required="true" required>\n' +
            '            <span class="error" id="nameError_' + k + '"> </span>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="col-md-2">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<label for="" class="col-form-label">Select Type</label>' +
            '<select id="type_' + k + '" class="form-control select2" name="type[]">' +
            '<option value="1">deduction</option>' +
            '<option value="2">gross</option>' +
            '<option value="3">allowance</option>' +
            '</select>' +
            '<span class="error" id="typeError"></span>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2">' +
            '    <div class="form-group">' +
            '        <div class="form-line">' +
            '            <label for="" class="col-form-label">Select Amount Type</label>' +
            '            <select id="amountType_' + k +
            '" class="form-control select2" name="amount_type[]"><option value="1">flat</option><option value="2">percentage</option></select>' +
            '            <span class="error" id="amountTypeError"></span>' +
            '        </div>' +
            '    </div>' +
            '</div>' +
            '<div class="col-md-3">' +
            '    <div class="form-group">' +
            '        <div class="form-line">' +
            '            <label for="amount" class="col-form-label">Amount</label>' +
            '            <input id="amount" class="form-control" placeholder="Enter Amount" name="amount[]" type="number">' +
            '            <span class="error"> </span>' +
            '        </div>' +
            '    </div>' +
            '</div>' +
            '<div class="col-md-1">' +
            '<button type="button" name="remove" onclick="removeItem(' + k +
            ')" class="btn btn-danger btn-sm mt-6 remove">Delete</button>' +
            '</div>' +
            '</div>' +
            '</div>';


        $("#addMore").append(html);
        $('.select2').select2();
        $("#isBasicRow").remove();
        k++;


    }

    function checkItemDuplicate(id) {
        console.log(id);
        var values = [];
        $(".item-name").each(function() {
            values.push($(this).val());

        });
        itemArray = values.sort();
        var duplicate = 0;
        for (let i = 0; i < itemArray.length; i++) {
            if (itemArray[i + 1] == itemArray[i]) {
                duplicate = itemArray[i];
            }
            if (duplicate != 0) {
                $('#nameError_' + id).html('This name already exist!');
                $("#btnsm").attr("disabled", true);
                $(".padd").attr("disabled", true);
                $(".remove").attr("disabled", true);
                $('#nameId_' + id).css("background-color", "yellow");
            } else {
                $('#nameError_' + id).html('');
                $("#btnsm").attr("disabled", false);
                $(".padd").attr("disabled", false);
                $(".remove").attr("disabled", false);
                $('#nameId_' + id).css("background-color", "#fff");

            }
        }

    }


    function removeItem(itemRow) {
        ($('#itemRow_' + itemRow).remove());
    }
</script>
