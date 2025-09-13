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


    <div class="col-md-5">
        <div class="form-group">
            <div class="form-line">
                <label for="" class='col-form-label'>
                    @lang('Item::label.COUNTRY') @lang('Item::label.NAME')</label>
                {!! Form::text('name[]', null, [
                    'id' => 'nameId_0',
                    'class' => 'form-control required item-name',
                    'placeholder'=> "Enter Country name",
                    'onInput' => 'checkItemDuplicate(0)',
                    'required' => 'true'
                ]) !!}
                <span class="error" id="nameError_0"> </span>
            </div>
        </div>
    </div>


    <div class="col-md-5">
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
                {{-- <span class="error" id="nameError"></span> --}}
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

    <div class="col-md-12">
        <div id="addMore">

        </div>
    </div>
    <div class="col-md-6">
        <div class="float-right mt-3">
            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" 
                id="btnsm" type="submit">Save</button>
        </div>
    </div>
</div>



</div>

<script>
    $(function() {
        $(".select2").select2();

    });

 
    var k = 1;
function addItem() {
    
    k = parseInt(k) + parseInt($('#count').val());
    console.log(k);
    var html = '';
    html +=
        '<div id="itemRow_' + k + '" class="new_data">' +
        '<div class="row">' +
        '<div class="col-md-5">\n' +
        '    <div class="form-group">\n' +
        '        <div class="form-line">\n' +
        '            <label for="name" class="col-form-label">Country name</label>\n' +
        '            <input name="name[]" type="text" class="form-control item-name" placeholder="Enter Country name" onInput="checkItemDuplicate('+k+')" id=nameId_'+k+'  autocomplete="off" aria-required="true" required="true">\n' +
        '            <span class="error" id="nameError_'+k+'"> </span>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>' +
        '<div class="col-md-5">\n' +
        '    <div class="form-group">\n' +
        '        <div class="form-line">\n' +
        '            <label for="name" class="col-form-label">Short name</label>\n' +
        '            <input name="short_name[]" type="text" class="form-control short-name" placeholder="Enter Short name" onInput="checkDuplicate('+k+')" id=shortName_'+k+'  autocomplete="off" aria-required="true">\n' +
        '            <span class="error" id="shortNameError_'+k+'"> </span>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>' +
        '<div class="col-md-1">' +
        '<button type="button" name="remove" onclick="removeItem(' + k +
        ')" class="btn btn-danger btn-sm mt-6 remove">Delete</button>' +
        '</div>' +
        '</div>' +
        '</div>';

        

    $("#addMore").append(html);

    $('.js-example-basic').select2();
    k++;

}
    function checkItemDuplicate(id)
    {  
        console.log(id);
        var values = [];
                $(".item-name").each(function() {
                    values.push($(this).val());

                });
                itemArray = values.sort();
                var duplicate = 0;
                for (let i = 0; i < itemArray.length; i++) {
                if (itemArray[i+1] == itemArray[i]) {
                        duplicate = itemArray[i];
                }
                if (duplicate != 0) {
                    $('#nameError_'+id).html('This name already exist!');
                            $("#btnsm").attr("disabled", true);
                            $(".padd").attr("disabled", true);
                            $(".remove").attr("disabled", true);
                            $('#nameId_' + id).css("background-color", "yellow");
                    }else {
                            $('#nameError_'+id).html('');
                            $("#btnsm").attr("disabled", false);
                            $(".padd").attr("disabled", false);
                            $(".remove").attr("disabled", false);
                            $('#nameId_' + id).css("background-color", "#fff");

                        }
                }
                    
    }

    function checkDuplicate(id)
    {  

        var values = [];
                $(".short-name").each(function() {
                    values.push($(this).val());

                });
                itemArray = values.sort();
                var duplicate = 0;
                for (let i = 0; i < itemArray.length; i++) {
                if (itemArray[i+1] == itemArray[i]) {
                        duplicate = itemArray[i];
                }
                if (duplicate != 0) {
                    $('#shortNameError_'+id).html('This name already exist!');
                            $("#btnsm").attr("disabled", true);
                            $(".padd").attr("disabled", true);
                            $(".remove").attr("disabled", true);
                            $('#shortName_' + id).css("background-color", "red");
                    }else {
                            $('#shortNameError_'+id).html('');
                            $("#btnsm").attr("disabled", false);
                            $(".padd").attr("disabled", false);
                            $(".remove").attr("disabled", false);
                            $('#shortName_' + id).css("background-color", "#fff");

                        }
                }
                    
    }

function removeItem(itemRow) {
    ($('#itemRow_' + itemRow).remove());
}
</script>
