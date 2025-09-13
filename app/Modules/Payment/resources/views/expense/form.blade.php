@if (!$editTitle)
    <div class="row">
        <div class="col-md-6">
            <button type="button" name="padd"
                class="btn btn-success btn-sm ml-auto mt-3 waves-effect waves-themed padd" onclick="addExpenseChart()">+
                Add
                More</button>
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-4">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('payment_date', 'Payment Date', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('payment_date', isset($data) ? old('payment_date') : date('d-m-Y'), [
                    'id' => 'payment_date',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Payment date',
                ]) !!}
                <span class="error"> {!! $errors->first('payment_date') !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('receive_type', 'Receive Type', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::Select(
                    'receive_type',
                    [
                        'regular' => 'Regular',
                        // 'consumption' => 'Consumption',
                        // 'systemloss' => 'System Loss',
                        // 'gift' => 'Gift',
                        // 'wastage' => 'Wastage',
                        // 'cnf' => 'CNF',
                    ],
                    old('receive_type'),
                    ['id' => 'receive_type', 'class' => 'form-control selectheight'],
                ) !!}

                <span class="error"> {!! $errors->first('receive_type') !!}</span>
            </div>
        </div>
    </div>


    <div class="col-md-4 hide">
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

    <div class="col-md-3">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('sales_chart_id', 'Expense Chart Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::select('sales_chart_id[]', $chartList, old('sales_chart_id'), [
                    'id' => 'sales_chart_id',
                    'class' => 'form-control sales-chart-id',
                    'required' => 'required',
                ]) !!}

                <span class="error" id="chartError_" data-key="1"></span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('payment_amount', 'Payment Amount', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('payment_amount[]', old('payment_amount'), [
                    'id' => 'payment_amount',
                    'class' => 'form-control only-number-accept-in',
                    'required' => 'required',
                    'placeholder' => 'Enter ayment amount',
                ]) !!}
                <span class="error"> {!! $errors->first('payment_amount') !!}</span>
                <span class="error errorMsg-payment_amount"></span>
            </div>
        </div>
    </div>
    <div class="col-md-3">

        <div class="form-group">
            <div class="form-line">
                {!! Form::label('note', 'Notes', ['class' => 'col-form-label']) !!}

                {!! Form::text('note[]', isset($data_cashbank) ? $data_cashbank->note : old('note'), [
                    'id' => 'note',
                    'class' => 'form-control',
                    'placeholder' => 'Enter note',
                    'rows' => '3',
                    'cols' => '50',
                ]) !!}

                {!! $errors->first('note') !!}
            </div>
        </div>
    </div>


    <div class="col-md-3 hide">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('AccountCategoryId', 'Select Payment Method', ['class' => 'col-form-label']) !!}
                <span class="required"> *</span>

                {!! Form::select(
                    'AccountCategoryId[]',
                    $accountCategory,
                    isset($account_category_select->id) ? $account_category_select->id : $companyDetails->DefaultPaymentMethod,
                    ['id' => 'AccountCategoryId1', 'class' => 'form-control selectheighttype', 'required' => 'required', 'onchange' => 'getAccount(1)'],
                ) !!}

                <span class="error"> {!! $errors->first('AccountCategoryId') !!}</span>
            </div>
        </div>
    </div>

    <input type="hidden" id="AccountTypeId1" name="AccountTypeId[]" value="@if (isset($data)) {{ $data->AccountTypeId }} @endif">
    <input type="hidden" id="count" value="0" name="">
    <div class="col-md-12">
        <div id="addMore">

        </div>
    </div>

    
    <div class="col-md-4 hide" style="display: none;">
        <div class="form-group">

            <div class="form-line">
                {!! Form::label('AccountId', 'Select Payment Account', ['class' => 'col-form-label']) !!}
                {{-- <span class="required"> *</span> --}}
                <select name="AccountId[]" id="AccountId1" class="form-control selectheighttype">
                    @if (isset($account))
                        <option value="{{ $account->id }}">{{ $account->ShortName }}</option>
                    @endif
                </select>

                <span class="error"> {!! $errors->first('AccountId') !!}</span>
            </div>
        </div>
    </div>

    {!! Form::hidden('cheque_no', null) !!}
</div>


<div class="row">
    <div class="col-md-12">
        <div id="dynamic_content"></div>
    </div>
    <div class="col-md-12">
        <div class="panel-content border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">

            <button class="btn btn-primary ml-auto waves-effect waves-themed" id="btnsm"
                type="submit">Save</button>
        </div>
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


    $(document).ready(function() {
        getAccount(1);
        if ($('#receive_type').val() === 'consumption' || $('#receive_type').val() === 'systemloss' || $(
                '#receive_type').val() === 'gift' || $('#receive_type').val() === 'wastage' || $(
                '#receive_type').val() === 'cnf') {
            //retrieving the HTML content
            document.getElementById("contents").style.display = "block";
            $('#payment_amount').val(0);
            document.getElementById('payment_amount').readOnly = true;
            document.getElementById("AccountCategoryId").required = false;
            $('.hide').hide();

        }

        if ($('#receive_type').val() === 'regular') {
            //retrieving the HTML content
            document.getElementById("contents").style.display = "none";
            // $('#payment_amount').val('');
            document.getElementById('payment_amount').removeAttribute('readonly');
            $('.hide').show();
            // document.getElementById("payment_amount").style.display = "readonly";
        }

        $('#product_id').select2({
            placeholder: 'Select Product',
            width: '100%',
            // tags: true
        }).on("select2:select", function(evt) {
            var id = evt.params.data.id;

            var element = $(this).children("option[value=" + id + "]");

            moveElementToEndOfParent(element);

            $(this).trigger("change");
        });

        $('#receive_type').on('change', function() {
            //weather the sales type is installment of partial
            var $receive_type = $('#receive_type').val();

            //dynamic content is the div where we will put the html
            var $dynamic_content = $('#dynamic_content');

            //clearing the div first
            if ($receive_type === 'consumption' || $receive_type === 'systemloss' || $receive_type ===
                'gift' || $receive_type === 'wastage' || $receive_type === 'cnf') {
                document.getElementById("contents").style.display = "block";
                $('#payment_amount').val(0);
                document.getElementById('payment_amount').readOnly = true;
                document.getElementById("AccountCategoryId").required = false;
                $('.hide').hide();

            }

            if ($receive_type === 'regular') {
                document.getElementById("contents").style.display = "none";
                $('#payment_amount').val('');
                document.getElementById('payment_amount').removeAttribute('readonly');
                $('.hide').show();
            }
        });
    }); //end of


    $(function() {

        var elements = $("input[type!='submit'], textarea, select");
        elements.focus(function() {
            $(this).parents('li').addClass('highlight');
        });
        elements.blur(function() {
            $(this).parents('li').removeClass('highlight');
        });

        // $("#paymentForm").validate({
        //     rules: {
        //         sales_chart_id: {
        //             required: true,
        //         },
        //         payment_amount: {
        //             required: true,
        //         },
        //         status: {
        //             required: true
        //         }

        //     },
        //     messages: {
        //         sales_chart_id: 'Please choose Chart name',
        //         payment_amount: 'Please enter payment amount',
        //         status: 'Please choose status'
        //     }
        // });

        $('#payment_date').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });

    }); //end of $ function

    // $(document).ready(function() {
    //     $(document).delegate('#AccountCategoryId', 'change', function() {

    //         var payment_method = $(this).val();
    //         alert(payment_method);

    //         $.ajax({
    //             url: '{{ url('get-payment-account') }}',
    //             type: 'POST',
    //             data: {
    //                 _token: '{!! csrf_token() !!}',
    //                 payment_method: payment_method
    //             },
    //             dataType: "json",
    //             success: function(data) {

    //                 if (data.result === 'success') {
    //                     console.log(data.data);
    //                     $('#AccountId').html(data.data);
    //                     $('#AccountTypeId').val(data.type_id);

    //                 } else {
    //                     alert(data.message);
    //                 }
    //             }
    //         });
    //         return false;
    //     });

    // });


    $(document).ready(function() {

        $('#sales_chart_id').select2();
        $('#AccountCategoryId1').select2();

    });

    $(document).ready(function() {
        orderSortedValues = function() {
            var value = ''
            $("#product_id").parent().find("ul.select2-selection__rendered").children("li[title]").each(
                function(i, obj) {

                    var element = $("#product_id").children('option').filter(function() {
                        return $(this).html() == obj.title
                    });
                    moveElementToEndOfParent(element)
                });
        };

        moveElementToEndOfParent = function(element) {
            var parent = element.parent();

            element.detach();

            parent.append(element);
        };


    });


    function check_duplicate() {
        var values = $('#product_id').val();
        var arr = new Array();
        $(".des").each(function() {
            arr.push($(this).val());

        });
        var val = $.grep(arr, function(idx) {
            return values == idx;
        }).length;
        return val;
    }

    $('.alert-success').delay(3000).fadeOut('slow', function() {
        $(this).remove();
    });

    function onlyNumberAccept(dataKey) {
        var custom = $("#isCustom" + dataKey).val();
        if (custom == 1) {
            $("#quantity-id-" + dataKey).attr('pattern', '[0-9.]*');
            check();
        }

    }
    // End:: adjustment amount input validation

    // function Validator() {
    //     //  ...bla bla bla... the checks
    //     if ($("#sales_chart_id").val() != '' && $("#AccountTypeId").val() != '' && $("#payment_amount").val() != '' &&
    //         $("#AccountCategoryId").val() != '' && $("#payment_invoice").val() != '' && $("#payment_date").val() != ''
    //     ) {
    //         $("#btnsm").attr("disabled", true);
    //         $("#btnsm").html('Wait..');
    //         $("#paymentForm").submit();
    //         return (true);
    //     } else {
    //         return (false);
    //     }
    // }

    var k = 2;

    function addExpenseChart() {

        k = parseInt(k) + parseInt($('#count').val());
        var html = '';
        html +=
            '<div id="expenseRow_' + k + '" class="new_data">' +
            '<div class="row">' +
            '<div class="col-md-2">' +
            ' <div class="form-group">' +
            '<div class="form-line">' +
            '    <label for="sales_chart_id" class="col-form-label">Expense Chart Name</label><span class="required"> *</span>' +
            '    <select name="sales_chart_id[]" required class="form-control selectheighttype select2 sales-chart-id" id="sales_chart_id_' + k + '">' +
            '        <?php foreach ($chartList as $id => $chart): ?> ' +
            '            <option value="<?php echo $id; ?>"><?php echo filter_var($chart, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
            '        <?php endforeach; ?> ' +
            '    </select>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-3">\n' +
            '    <div class="form-group">\n' +
            '        <div class="form-line">\n' +
            '            <label for="sales_chart_id" class="col-form-label">Payment Amount</label><span class="required"> *</span>' +
            '            <input name="payment_amount[]" required type="text" class="form-control" id="paymentAmount_' + k + '" placeholder="Enter payment amount"  autocomplete="off">\n' +
            '            <span class="error" id="paymentAmountError_' + k + '"> </span>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="col-md-3">\n' +
            '    <div class="form-group">\n' +
            '        <div class="form-line">\n' +
            '            <label for="sales_chart_id" class="col-form-label">Note</label>' +
            '            <input name="note[]" type="text" class="form-control" placeholder="Enter Note"  autocomplete="off" aria-required="true">\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="col-md-3 hide">\n' +
            '    <div class="form-group">\n' +
            '        <div class="form-line">\n' +
            '            <label for="AccountCategoryId" class="col-form-label">Select Payment Method</label>' +
            '           <select name="AccountCategoryId[]" required class="form-control selectheighttype select2" id="AccountCategoryId' + k + '" onchange="getAccount(' + k + ')">' +
            '                   <?php foreach ($accountCategory as $id => $chart): ?> ' +
            '                        <option value="<?php echo $id; ?>" <?php echo ($id == $companyDetails->DefaultPaymentMethod) ? "selected" : ""; ?>><?php echo filter_var($chart, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
            '                   <?php endforeach; ?> ' +
            '            </select>' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>' +
            '<input type="hidden" id="AccountTypeId' + k + '" name="AccountTypeId[]" value="<?php if (isset($data)) { echo $data->AccountTypeId;} ?>">' +
            '<div class="col-md-4 hide" style="display: none;">\n' +
            '    <div class="form-group">\n' +
            '        <div class="form-line">\n' +
            '            <label for="AccountId" class="col-form-label">Select Payment Account</label>\n' +
            '            <select name="AccountId[]" id="AccountId' + k + '" class="form-control selectheighttype">\n' +
            '               <?php foreach ($accountCategory as $id => $chart): ?>' +
            '                   <?php if (isset($account)): ?>' +
            '                       <option value="<?php echo $account->id; ?>"><?php echo htmlspecialchars($account->ShortName); ?></option>\n' +
            '                   <?php endif; ?>' +
            '               <?php endforeach; ?>' +
            '            </select>\n' +
            '            <span class="error" id="AccountId' + k + '"> </span>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="col-md-1">' +
            '<button type="button" name="remove" onclick="removeExpenseChart(' + k +')" class="btn btn-danger btn-sm mt-6 mr-3 remove">Delete</button>' +
            '</div>' +
            '</div>' +
            '</div>';

        $("#addMore").append(html);
        $("#sales_chart_id_" + k).select2();
        $("#AccountCategoryId" + k).select2();
        getAccount(k);
        k++;


    }


    function getAccount(k){
        var payment_method = $("#AccountCategoryId" + k).val();
        $.ajax({
            url: '{{ url('get-payment-account') }}',
            type: 'POST',
            data: {
                _token: '{!! csrf_token() !!}',
                payment_method: payment_method
            },
            dataType: "json",
            success: function(data) {

                if (data.result === 'success') {
                    console.log(data.data);
                    $('#AccountId' + k).html(data.data);
                    $('#AccountTypeId' + k).val(data.type_id);
                } else {
                    alert(data.message);
                }
            }
        });
        return false;
    }



    function removeExpenseChart(expenseRow) {
        ($('#expenseRow_' + expenseRow).remove());
    }
</script>
