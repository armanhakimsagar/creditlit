{{-- <input type="hidden" id="count" value="{{ count($selectedItems) }}" name=""> --}}
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
                <th width="10%">Waiver</th>
                <th width="10%">Payable</th>
                <th>Notes</th>
                <th width="5%"></th>
            </tr>
            <tbody id="selectedItem">
                @foreach ($contactwise_item as $key => $item)
                    <tr id="productRow_" class="new_data">
                        <td> {{ $key + 1 }} </td>
                        <td>{{ $item->product_name }}
                            <input type="hidden" name="product_id[{{ $key + 1 }}][]"
                                id="new_product_id{{ $key + 1 }}" value="{{ $item->product_id }}"
                                class="setting-product">
                        </td>
                        <td class="text-center">
                            <span id="amountShow{{ $key + 1 }}" name="amount[{{ $key + 1 }}][]">{{ $item->actual_amount }}</span>
                            <input style="width:150px; border:1px solid gray;"
                                name="amount[{{ $key + 1 }}][]" type="hidden" pattern="[0-9.]+"
                                value="{{ $item->actual_amount }}" class="form-control lightGray"
                                id="amount-{{ $key + 1 }}" oninput="calculatePayable({{ $key + 1 }})"
                                required=""></td>
                        <td><input type="text" name="discount[{{ $key + 1 }}][]"
                                id="discount_{{ $key + 1 }}" value="{{ $item->discount_amount }}"
                                class="form-control" placeholder="" oninput="calculatePayable()"></td>
                        <td><input type="text" id="payable_{{ $key + 1 }}"
                                name="payable[{{ $key + 1 }}][]" value="{{ $item->amount }}" class="form-control payable"
                                placeholder=""></td>
                        <td><input type="text" id="note_{{ $key + 1 }}" name="note[{{ $key + 1 }}][]"
                                value="{{ $item->notes }}" class="form-control" placeholder="Enter Note"></td>
                        {{-- <td><button type="button" name="remove" onclick="removeProduct()"
                            class="btn btn-danger btn-sm remove">Delete</button></td> --}}
                    </tr>
                @endforeach
                <input type="hidden" id="count" value="{{ count($contactwise_item) }}">
            </tbody>
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
            ' name="product_id[' + k + '][]" required onchange="itemDetails(' + k + ')">\n' +
            '     <option value="" >Select Product</option>\n' +
            '      <?php foreach($productlist as $key => $Thisproduct): ?>\n' +
            '      <option value="<?php echo $Thisproduct->id; ?>"><?php echo filter_var($Thisproduct->name, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
            '      <?php endforeach; ?>\n' +
            '   </select>\n' +
            '<label for="new_product_id' +
            k + '" class="error"></label></td>';
        html +=
            '<td class="text-center">' +
            '<span id="amountShow' + k +'" name="amount[' + k +'][]">0</span>'+
            '<input style="width:150px; border:1px solid gray;" name="amount[' + k +
            '][]" type="hidden" pattern="[0-9.]+" value="0" class="form-control lightGray" id="amount-' + k +
            '" oninput="calculatePayable(' + k + ')" required/>' +
            '</td>';
        html +=
            '</td><td><input type="text" name="discount[' + k +
            '][]" id="discount_' + k +
            '" value="0" class="form-control payable" placeholder="" onInput="calculatePayable(' + k +
            ')"></td><td><input type="text" id="payable_' + k +
            '" name="payable[' + k +
            '][]" value="0" class="form-control" placeholder="" ></td>';
        html += '<td><input type="text" id="note_' + k +
            '" name="note[' + k +
            '][]" value="" class="form-control" placeholder="Enter Note" ></td>';
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
    }

    function calculatePayable(id) {
        var discount = $('#discount_' + id).val() || 0;
        discount = parseFloat(discount);
        var amount = $('#amount-' + id).val() || 0;
        amount = parseFloat(amount);
        var payable = amount - discount;
        $('#payable_' + id).val(payable);
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
