<input type="hidden" id="count" value="{{ count($selectedItems) }}" name="">
</div>
<div class="col-md-12">
    <button type="button" name="padd" class="btn btn-success btn-sm ml-auto waves-effect waves-themed padd"
        onclick="addProduct()">Add Item</button>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table">
            <tr>
                <th width="5%">SL</th>
                <th width="10%">ITEM</th>
                <th width="10%">AMOUNT</th>
                {{-- <th width="35%">Affected Month</th> --}}
                <th width="15%">Month</th>
                <th width="10%"class="text-center"><span>
                    Due generate upto current month</span><br>
                <input type="checkbox" class="all-check-box" id="chkbxAll1"
                    onclick="return checkTillCurrentMonth()"></th>
                <th width="10%"class="text-center"><span>
                    Whole year</span><br><br>
                <input type="checkbox" class="all-check-box" id="chkbxAll2"
                    onclick="return checkAllWholeYear()"></th>
                <th width="10%">Waiver</th>
                <th width="20%">Payable</th>
                <th width="10%">Notes</th>
                <th width="5%"></th>
            </tr>
            <tbody id="selectedItem">
                @if (!empty($selectedItems))
                    @foreach ($selectedItems as $key => $item)
                        <tr id="productRow_{{ $key + 1 }}" class="new_data">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->name }}
                                <input type="hidden" name="product_id[{{ $key + 1 }}][]"
                                    id="new_product_id{{ $key + 1 }}" value="{{ $item->id }}"
                                    class="setting-product" tabindex="{{$key+1}}">
                            </td>
                            <td><span id="amountShow{{ $key + 1 }}" name="amount[{{ $key + 1 }}][]">0</span>
                                <input style="width:150px; border:1px solid gray;"
                                    name="amount[{{ $key + 1 }}][]" type="hidden" pattern="[0-9.]+" value="0"
                                    class="form-control lightGray" id="amount-{{ $key + 1 }}"
                                    oninput="calculatePayable({{ $key + 1 }})" required="" tabindex="{{100+$key}}"></td>
                            <td>
                                <select name="month_id[{{ $key + 1 }}][]" id="month_id{{ $key + 1 }}" class="form-control" onchange="itemDetails({{ $key + 1 }})">
                                     @foreach ($enumMonth as $lkey => $enumMonthValue)
                                     <option value="{{ $enumMonthValue->id }}" @if((int)date('m')==$enumMonthValue->id) selected="" @endif>{{ $enumMonthValue->name }}</option>
                                     @endforeach
                                </select>
                            </td>
                            <td class="text-center"><input type="checkbox" class="allCheck1 all-check-box"  id="checkCurrent_{{ $key + 1 }}" name="till_current_month[{{ $key + 1 }}]" value="{{ $key + 1 }}" keyValue="{{ $key + 1 }}" onclick="unCheck1(this.id);isChecked1()"></td>
                            <td class="text-center"><input type="checkbox" class="allCheck2 all-check-box"  id="checkWholeYear_{{ $key + 1 }}" name="check_whole_year[{{ $key + 1 }}]" value="{{ $key + 1 }}" keyValue="{{ $key + 1 }}" onclick="unCheck2(this.id);isChecked2()"></td>
                            <td><input type="text" name="discount[{{ $key + 1 }}][]"
                                    id="discount_{{ $key + 1 }}" value="0" class="form-control"
                                    placeholder="" oninput="calculatePayable({{ $key + 1 }})" tabindex="{{200+$key}}"></td>
                            <td><input type="text" id="payable_{{ $key + 1 }}"
                                    name="payable[{{ $key + 1 }}][]" value="0" class="form-control payable"
                                    placeholder="" tabindex="{{300+$key}}"></td>
                            <td><input type="text" id="note_{{ $key + 1 }}" name="note[{{ $key + 1 }}][]"
                                    value="" class="form-control" placeholder="Enter Note" tabindex="{{400+$key}}"></td>
                            <td><button type="button" name="remove" onclick="removeProduct({{ $key + 1 }})"
                                    class="btn btn-danger btn-sm remove">Delete</button></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tbody id="added_item">
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-8">

</div>

<script>
    var k = 1;
    k = parseInt(k) + parseInt($('#count').val());
    function addProduct() {
        var academic_year_id = $('#academicYearId').val();
        var html = '';
        html += '<tr id="productRow_' + k + '" class="new_data">';
        html +=
            '<td>' + k +
            '<input type="hidden" name="due_id[' + k + '][]" value="0" placeholder="" ></td>';
        html +=
            '<td>' +
            '   <select style="width:200px;" class="form-control js-example-basic selectheighttype product_row" id="new_product_id' +
            k + '" ' +
            ' name="product_id[' + k + '][]" required onchange="itemDetails(' + k + ')" tabindex='+ parseInt(k+1) +'>\n' +
            '     <option value="" >Select Product</option>\n' +
            '      <?php foreach($productlist as $key => $Thisproduct): ?>\n' +
            '      <option value="<?php echo $Thisproduct->id; ?>"><?php echo filter_var($Thisproduct->name, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
            '      <?php endforeach; ?>\n' +
            '   </select>\n' +
            '<label for="new_product_id' +
            k + '" class="error"></label></td>';
        html +=
            '<td>' +
            '<span id="amountShow' + k +'" name="amount[' + k +'][]">0</span>'+
            '<input style="width:150px; border:1px solid gray;" name="amount[' + k +
            '][]" type="hidden" pattern="[0-9.]+" value="0" class="form-control lightGray" id="amount-' + k +
            '" oninput="calculatePayable(' + k + ')" required/>' +
            '</td>';
        html +=
            '<td><select name="month_id[' + k + '][]" id="month_id'+k+'" onchange="itemDetails('+k+')" class="form-control">'+
            '@foreach ($enumMonth as $lkey => $enumMonthValue)'+
            '<option value="{{ $enumMonthValue->id }}">{{ $enumMonthValue->name }}</option>'+
            '@endforeach'+
            '</select></td>\n';
         
        html +=
            '<td class="text-center">'+
            '<input type="checkbox" class="allCheck1 all-check-box"  id="checkCurrent_' + k + '" name="till_current_month[' + k + ']" value="' + k + '" keyValue="' + k + '" onclick="unCheck1(this.id);isChecked1()"></td>';

        html += '<td class="text-center">'+
            '<input type="checkbox" class="allCheck2 all-check-box"  id="checkWholeYear_' + k + '" name="check_whole_year[' + k + ']" value="' + k + '" keyValue="' + k + '" onclick="unCheck2(this.id);isChecked2()"></td>';                  

        html +=
            '</td><td><input type="text" name="discount[' + k +
            '][]" id="discount_' + k +
            '" value="0" class="form-control" placeholder="" onInput="calculatePayable(' + k +
            ')" tabindex='+ parseInt(200+k) +'></td><td><input type="text" id="payable_' + k +
            '" name="payable[' + k +
            '][]" value="0" class="form-control payable" placeholder="" tabindex='+ parseInt(300+k) +'></td>';
        html += '<td><input type="text" id="note_' + k +
            '" name="note[' + k +
            '][]" value="" class="form-control" placeholder="Enter Note" tabindex='+ parseInt(400+k) +'></td>';
        html +=
            '<td>' +
            '<button type="button" name="remove" onclick="removeProduct(' + k +
            ')" class="btn btn-danger btn-sm remove">Delete</button>' +
            '</td>' +
            '</tr>';
        $("#added_item").append(html);
        $('.js-example-basic').select2();
        k++;
    }

    function removeProduct(productRow) {
        $("#productRow_" + productRow).remove();
    }

    function itemDetails(id) {
        var itemId = $('#new_product_id' + id).val();
        var academicYearId = $('#academicYearId').val();
        var month_id = $('#month_id' + id).val();
        var class_id = $('#classID').val();
        var discountAmount = $('#discount_' + id).val();
        discountAmount = parseFloat(discountAmount);
        if (academicYearId != 0) {
            $.ajax({
                url: "{{ url('get-item-details') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    itemId: itemId,
                    academicYearId: academicYearId,
                    class_id: class_id,
                    month_id:month_id,
                },
                beforeSend: function() {},
                success: function(response) {
                    if (response.result == 'success') {
                        $('#amount-' + id).val(response.price);
                        $('#amountShow' + id).html(response.price);
                        $('#payable_' + id).val(response.price - discountAmount);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    function calculatePayable(id) {
        var discount = $('#discount_' + id).val() || 0;
        discount = parseFloat(discount);
        var amount = $('#amount-' + id).val() || 0;
        amount = parseFloat(amount);
        var payable = amount - discount;
        $('#payable_' + id).val(payable);
    }

    function checkAll(id) {
        console.log('#allMonthChecked_' + id);
        var itemChckbox = '#allMonthChecked_' + id;
        if ($(itemChckbox).is(':checked')) {
            console.log('lol');
            $(".allCheck_" + id).each(function(index) {
                if ($(this)[0].checked == false) {
                    $(this).prop('checked', true);
                }
            });
        } else {
            $(".allCheck_" + id).each(function(index) {
                if ($(this)[0].checked == true) {
                    $(this).prop('checked', false);
                }
            });
        }
    }
    
    function checkTillCurrentMonth() {
                if ($('#chkbxAll1').is(':checked')) {
                    $(".allCheck1").each(function(index) {
                        var key = $(this).attr('keyvalue');

                        if ($("#checkCurrent_" + key)[0].checked == false) {
                            $("#checkCurrent_" + key).prop('checked', true);
                        }
                    });
                } else {
                    $(".allCheck1").each(function(index) {
                        var key = $(this).attr('keyvalue');
                        if ($("#checkCurrent_" + key)[0].checked == true) {
                            $("#checkCurrent_" + key).prop('checked', false);
                        }
                    });
                }
            }
            
    function checkAllWholeYear() {
                if ($('#chkbxAll2').is(':checked')) {
                    $(".allCheck2").each(function(index) {
                        var key = $(this).attr('keyvalue');

                        if ($("#checkWholeYear_" + key)[0].checked == false) {
                            $("#checkWholeYear_" + key).prop('checked', true);
                        }
                    });
                } else {
                    $(".allCheck2").each(function(index) {
                        var key = $(this).attr('keyvalue');
                        if ($("#checkWholeYear_" + key)[0].checked == true) {
                            $("#checkWholeYear_" + key).prop('checked', false);
                        }
                    });
                }
            }

    function isChecked1() {
            var countNotChecked = 0;
            var countChecked = 0;
            $(".allCheck1").each(function(index) {
                if ($(this)[0].checked == true) {
                    countChecked++;
                } else {
                    countNotChecked++;
                }
            });
            if (countNotChecked == 0 && countChecked > 0) {
                $("#chkbxAll1").prop("checked", true);
            }

        }  
        function unCheck1(id) {
                if ($('#' + id).is(':not(:checked)')) {
                    $("#chkbxAll1").prop("checked", false);
                }
            }              
    function isChecked2() {
        // alert();
            var countNotChecked = 0;
            var countChecked = 0;
            $(".allCheck2").each(function(index) {
                if ($(this)[0].checked == true) {
                    countChecked++;
                    
                } else {
                    countNotChecked++;
                    
                }
            });
            if (countNotChecked == 0 && countChecked > 0) {
                $("#chkbxAll2").prop("checked", true);
            }

        }  
        function unCheck2(id) {
                if ($('#' + id).is(':not(:checked)')) {
                    $("#chkbxAll2").prop("checked", false);
                }
            }              
    
    function isAllMonthChecked(id) {
        var stringArrForIdSplit = id.split("_");
        var countNotChecked = 0;
        for (var i = 0; i < 12; i++) {
            var monthId = '#' + stringArrForIdSplit[0] + '_' + stringArrForIdSplit[1] + '_' + i;
            if ($(monthId).is(':not(:checked)')) {
                countNotChecked++;
                console.log(monthId);
            }
        }
        if (countNotChecked > 0) {
            $("#allMonthChecked_" + stringArrForIdSplit[1]).prop("checked", false);
        }
        if (countNotChecked == 0) {
            $("#allMonthChecked_" + stringArrForIdSplit[1]).prop("checked", true);
        }
    }

    function getItemPrice() {
        $('.setting-product').each(function() {
            var itemId = $(this).val();
            var idText = $(this).attr('id');
            idText = idText.split('new_product_id');
            var id = idText[1];
            var academicYearId = $('#academicYearId').val();
            var class_id = $('#classID').val();
            var discountAmount = $('#discount_' + id).val();
            discountAmount = parseFloat(discountAmount);
            if (academicYearId != 0) {
                $.ajax({
                    url: "{{ url('get-item-details') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        itemId: itemId,
                        academicYearId: academicYearId,
                        class_id: class_id
                    },
                    beforeSend: function() {},
                    success: function(response) {
                        if (response.result == 'success') {
                            $('#amount-' + id).val(response.price);
                            $('#amountShow' + id).html(response.price);
                            $('#payable_' + id).val(response.price - discountAmount);
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        });
    }
</script>
