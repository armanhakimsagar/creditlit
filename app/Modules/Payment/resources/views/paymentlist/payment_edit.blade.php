@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            .marksheet-details-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-auto-flow: column;
                background-color: #EEEEEE;
                padding: 10px;
                /* -webkit-print-color-adjust: exact; */

            }

            .marksheet-details-item {
                background-color: #EEEEEE;
                /* -webkit-print-color-adjust: exact; */
            }

            .marksheet-details-container th,
            .marksheet-details-container td {
                border: 1px solid #ffffff;
                /* -webkit-print-color-adjust: exact; */
            }

            .marksheet-details-container tr:nth-child(even) {
                background-color: #EEEEEE;
            }

            @media print {

                .page-breadcrumb,
                .subheader {
                    display: none;
                }
            }
        </style>
    @endpush
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Payment::label.PAYMENT_EDIT')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Payment::label.PAYMENT_EDIT')
            </h1>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="col-4">
                    <h2>
                        <span class="fw-300"><i>@lang('Payment::label.PAYMENT_EDIT')</i></span>
                    </h2>
                </div>
            </div>

            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        @if ($who == 'Student')
                        <div class="marksheet-details-container">
                            <div class="marksheet-details-item">
                                <table width="100%" class="table">
                                    <tbody>
                                        <tr>
                                            <td width="22%" align="left" valign="middle">Name :</td>
                                            <td width="39%" align="left" valign="middle" id="studentName">
                                                {{ $data->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Student ID :</td>
                                            <td align="left" valign="middle">
                                                {{ $data->contact_id }}</td>
                                        </tr>
                                        <tr id="registrationNo">
                                            <td align="left" valign="middle">Invoice No :</td>
                                            <td align="left" valign="middle">{{ $data->sales_invoice_no }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Roll No :</td>
                                            <td align="left" valign="middle" id="classRoll">{{ $data->class_roll }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Gender :</td>
                                            <td align="left" valign="middle">{{ $data->gender }}</td>
                                        </tr>
                                </table>
                            </div>

                            <div class="marksheet-details-item">
                                <table width="100%" class="table">
                                    <tbody>
                                        <tr>
                                            <td align="left" valign="middle">Academic Year :</td>
                                            <td align="left" valign="middle">{{ $data->year }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Class :</td>
                                            <td align="left" valign="middle">{{ $data->class_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Section :</td>
                                            <td align="left" valign="middle">{{ $data->section_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Version :</td>
                                            <td align="left" valign="middle">{{ $data->version_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Shift :</td>
                                            <td align="left" valign="middle">{{ $data->shift_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="academicYearId" value="{{ $data->academic_year_id }}">
                        <input type="hidden" id="preload_class_id" value="{{ $data->class_id }}">
                        <input type="hidden" id="student_id" value="{{ $data->student_id }}">
                        @elseif($who == 'Customer')
                        <div class="marksheet-details-container">
                            <div class="marksheet-details-item">
                                <table width="100%" class="table">
                                    <tbody>
                                        <tr>
                                            <td width="22%" align="left" valign="middle">Name :</td>
                                            <td width="39%" align="left" valign="middle" id="studentName">
                                                {{ $data->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Customer ID :</td>
                                            <td align="left" valign="middle">
                                                {{ $data->contact_id }}</td>
                                        </tr>
                                        <tr id="registrationNo">
                                            <td align="left" valign="middle">Invoice No :</td>
                                            <td align="left" valign="middle">{{ $data->sales_invoice_no }}</td>
                                        </tr>
                                </table>
                            </div>

                            <div class="marksheet-details-item">
                                <table width="100%" class="table">
                                    <tbody>
                                        <tr>
                                            <td align="left" valign="middle">Phone :</td>
                                            <td align="left" valign="middle">{{ $data->cp_phone_no }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Address :</td>
                                            <td align="left" valign="middle">{{ $data->address }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="middle">Gender :</td>
                                            <td align="left" valign="middle">{{ $data->gender }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="student_id" value="{{ $data->student_id }}">
                        @endif
                        

                        <form action="{{ route('payment.item.update', $data->id) }}" method="POST" id="savePayment">
                            @csrf
                            @if ($who == 'Student')
                                <input name="academic_year_id" type="hidden" id="academicYearId" value="{{ $data->academic_year_id }}">
                                <input name="preload_class_id" type="hidden" id="preload_class_id" value="{{ $data->class_id }}">
                                <input name="student_id" type="hidden" id="student_id" value="{{ $data->student_id }}">
                            @elseif($who == 'Customer')
                                <input name="student_id" type="hidden" id="student_id" value="{{ $data->student_id }}">
                            @endif
                            <div class="col-md-12">
                                <button type="button" name="padd"
                                    class="btn btn-success btn-sm ml-auto waves-effect waves-themed padd"
                                    onclick="addProduct()">Add Item</button>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th width="10%">SL</th>
                                            <th width="20%">ITEM</th>
                                            <th width="10%">PAYMENT OF</th>
                                            <th width="10%">AMOUNT</th>
                                            <th width="10%">PAID</th>
                                            <th width="20%">NOTE</th>
                                            <th width="5%"></th>
                                        </tr>
                                        <tbody>
                                            @foreach ($details as $key => $item)
                                                <tr id="productRow_{{ $loop->iteration }}" class="new_data">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <select style="width:400px;"
                                                            name="product_id[{{ $loop->iteration }}][]"
                                                            data-id="{{ $loop->iteration }}"
                                                            id="new_product_id{{ $loop->iteration }}"
                                                            class="form-control selectheighttype product_row"
                                                            required tabindex="{{ $loop->iteration }}">
                                                            @foreach ($productlist as $product)
                                                            @if ($item->product_id == $product->id)
                                                                <option value="{{ $product->id }}">
                                                                    {{ filter_var($product->name, FILTER_SANITIZE_SPECIAL_CHARS) }}
                                                                </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="month_id[{{ $loop->iteration }}][]"
                                                            id="month_id_{{ $loop->iteration }}"
                                                            class="form-control  selectheighttype" required>
                                                            <option value="">Select Month</option>
                                                            @foreach ($enumMonth as $month)
                                                                <option value="{{ $month->id }}"
                                                                    @if ($item->month_id == $month->id) selected @endif>
                                                                    {{ filter_var($month->name, FILTER_SANITIZE_SPECIAL_CHARS) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <select name="academic_year_ids[{{ $loop->iteration }}][]"
                                                            id="academic_year_id_{{ $loop->iteration }}"
                                                            class="form-control  selectheighttype" required>
                                                            <option value="">Select Year</option>
                                                            @foreach ($academicYear as $year)
                                                                <option value="{{ $year->id }}"
                                                                    @if ($item->academic_year_id == $year->id) selected @endif>
                                                                    {{ filter_var($year->year, FILTER_SANITIZE_SPECIAL_CHARS) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input name="amount[{{ $loop->iteration }}][]"
                                                            style="width:150px; border:1px solid gray;" type="text"
                                                            pattern="[0-9.]+" value="{{ $item->amount }}"
                                                            class="form-control lightGray init_amount"
                                                            id="amount-{{ $loop->iteration }}" oninput="calculation()"
                                                            tabindex="{{ $loop->iteration + 100 }}" required>
                                                    </td>
                                                    <td>
                                                        <input name="paid_amount[{{ $loop->iteration }}][]"
                                                            style="width:150px; border:1px solid gray;" type="text"
                                                            pattern="[0-9.]+" value="{{ $item->price }}"
                                                            class="form-control lightGray paid_amount"
                                                            id="paid-amount-{{ $loop->iteration }}" oninput="calculation()"
                                                            tabindex="{{ $loop->iteration + 200 }}" data-key="{{$key+1}}" required>
                                                            <span id="paid_amount_error-{{$key+1}}" class="error"></span>
                                                    </td>
                                                    <td>
                                                        <input name="note[{{ $loop->iteration }}][]"
                                                            style="border:1px solid gray;" type="text"
                                                            value="{{ $item->note }}" class="form-control lightGray"
                                                            id="notes-{{ $loop->iteration }}"
                                                            tabindex="{{ $loop->iteration + 300 }}">
                                                    </td>
                                                    <td>
                                                        <button type="button" name="remove"
                                                            onclick="removeProduct({{ $loop->iteration }})"
                                                            class="btn btn-danger btn-sm remove">Delete</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tbody id="payment_table">
                                        </tbody>
                                        <tbody id="added_item">
                                        </tbody>

                                    </table>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">

                                </div>
                                <div class="col-md-4">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label class="form-label" for="basic-url">Total Paid Amount</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">&#2547;</span>
                                                        </div>
                                                        <input type="text" class="form-control" value="0"
                                                            id="total-paid" name="total_paid" readonly="">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">TAKA</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <table class="table">
                                        <tbody>
                                            <tr style="background-color:grey">
                                                <th style="color:white">Transaction Details</th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style="background-color:white">
                                                <td colspan="2">
                                                    <select name="category_id" id="category_id" class="form-control">
                                                        @foreach ($account_category as $accountCategoryValue)
                                                            <option value="{{ $accountCategoryValue->id }}" @if($accCatInfo->AccountCategoryId == $accountCategoryValue->id) selected @endif>
                                                                {{ $accountCategoryValue->TypeName }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td colspan="2">
                                                    <input type="text" value="{{ $data->sales_invoice_date }}" id="payment_date"
                                                        name="payment_date" class="form-control">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit"
                                        class="btn btn-primary float-right btn-sm ml-auto waves-effect waves-themed" id="submitBtn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @section('javaScript')
        <script type="text/javascript">
            $('body').on('click', '.edit', function() {
                let item_id = $(this).data('id');
                $.get("due-item/edit/" + item_id, function(data) {
                    $("#modal_body").html(data);
                });
            });

            $(document).ready(function() {
                $('#category_id').select2();
                $('.month-id').select2();
                $('.year-id').select2();
                $('.js-example-basic').select2();
                $('#payment_date').datepicker({
                    language: 'en',
                    dateFormat: 'dd-mm-yyyy',
                    autoClose: true
                });
                calculateTotalPayment();

                $(document).on('change', '.product_row', function() {
                    var id = $(this).attr('data-id');
                    var itemId = $('#new_product_id' + id).val();
                    var academicYearId = $('#academicYearId').val();
                    var class_id = $('#preload_class_id').val();
                    var studentID = $('#student_id').val();

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
                                studentID: studentID,
                            },
                            beforeSend: function() {},
                            success: function(response) {

                                if (response.result == 'success') {
                                    $('#amount-' + id).val(response.price);
                                    calculation();
                                } else {
                                    alert(response.message);
                                }
                            }
                        });
                    }
                });

                // $('.product_row').trigger('change');
                calculation();
            });



            function calculateTotalPayment() {
                var total = 0;
                $('.payment-amount').each(function() {
                    var amount = $(this).val() || 0;
                    amount = parseFloat(amount);
                    total += amount;
                });
                $("#totalPaid").val(total);
            }

            var k = {{ count($details)+1 }};

            function addProduct() {
                var academic_year_id = $('#academicYearId').val();
                var html = '';
                html += '<tr id="productRow_' + k + '" class="new_data">';
                html +=
                    '<td>' + k +
                    '<input type="hidden" name="due_id[' + k + '][]" value="0" placeholder="" ></td>';
                html +=
                    '<td>' +
                    '   <select style="width:400px;" class="form-control js-example-basic selectheighttype product_row" id="new_product_id' +
                    k + '" ' +
                    '     required name="product_id[' + k + '][]" data-id="' + k + '" tabindex=' + parseInt(k + 1) + '>\n' +
                    '     <option value="" >Select Product</option>\n' +
                    '      <?php foreach($productlist as $key => $Thisproduct): ?>\n' +
                    '      <option value="<?php echo $Thisproduct->id; ?>"><?php echo filter_var($Thisproduct->name, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
                    '      <?php endforeach; ?>\n' +
                    '   </select>\n' +
                    '</td>';
                html +=

                    '<td>' +
                    '   <select class="form-control  selectheighttype" id="month_id_' + k + '" ' +
                    '     required name="month_id[' + k + '][]">\n' +
                    '     <option value="" >Select Month</option>\n' +
                    '      <?php foreach($enumMonth as $key => $enumMonthValue): ?>\n' +
                    '      <option value="<?php echo $enumMonthValue->id; ?>"><?php echo filter_var($enumMonthValue->name, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
                    '      <?php endforeach; ?>\n' +
                    '   </select><br>\n' +
                    '   <select class="form-control  selectheighttype" id="academic_year_id_' + k + '" ' +
                    '     required name="academic_year_ids[' + k + '][]">\n' +
                    '     <option value="" >Select Year</option>\n' +
                    '      <?php foreach($academicYear as $key => $academicYearValue): ?>\n' +
                    '      <option value="<?php echo $academicYearValue->id; ?>"><?php echo filter_var($academicYearValue->year, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
                    '      <?php endforeach; ?>\n' +
                    '   </select>\n' +
                    '</td>';
                html +=
                    '<td class="text-center">' +
                    '<input style="width:150px; border:1px solid gray;" name="amount[' + k +
                    '][]" type="text" pattern="[0-9.]+" value="0" class="form-control lightGray init_amount" id="amount-' + k +
                    '" oninput="calculation()"  tabindex=' + parseInt(100 + k) + ' required/>' +
                    '</td>';
                html +=
                    '<td><input style="width:150px; border:1px solid gray;" type="text" pattern="[0-9.]+" name="paid_amount[' +
                    k + '][]" value="0" class="form-control lightGray paid_amount" id="paid-amount-' + k + '" data-key=' + k +
                    ' oninput="calculation()"  tabindex=' + parseInt(200 + k) + ' required/>' +
                    '<span id="paid_amount_error-' + k + '" class="error">' +
                    '</span>' +
                    '</td>';

                html +=
                    '<td>' +
                    '<input border:1px solid gray;" type="text" name="note[' + k +
                    '][]" class="form-control lightGray"  tabindex=' + parseInt(300 + k) + ' id="notes-' + k + '"/>' +
                    '</td>';

                html +=
                    '<td>' +
                    '<button type="button" name="remove" onclick="removeProduct(' + k +
                    ')" class="btn btn-danger btn-sm remove">Delete</button>' +
                    '</td>' +
                    '</tr>';



                $("#added_item").append(html);

                // $('#product_id_'+k).val(value);
                // $('#product_id_'+k).trigger('change.select2');
                $('.js-example-basic').select2();
                k++;

            }

            function removeProduct(productRow) {
                $("#productRow_" + productRow).remove();
                calculation();
            }

            function calculation() {
                var total_paid = 0;
                var total_due = 0;
                var error=0;
                $('.paid_amount').each(function() {
                    var id = ($(this).attr('data-key'));
                    var paid = $(this).val() || 0;
                    var amount = parseFloat($('#amount-' + id).val() || 0);
                    if (paid > amount) {
                        $('#paid_amount_error-' + id).text("Paid amount cannot be Greater than amount").css("display",
                            "block");
                        $('#submitBtn').attr("disabled", true);
                        error=1;
                    } else {
                        $('#paid_amount_error-' + id).css("display", "none");
                    }
                    total_paid = parseFloat(total_paid) + parseFloat(paid);

                    var amount = $('#amount-' + id).val() || 0;
                    var old_paid = $('#hidden-paid-' + id).val() || 0;
                    $('#due-amount-' + id).val(parseFloat(amount) - (parseFloat(old_paid) + parseFloat(paid)));

                });
                if(error==0){
                    $('#submitBtn').attr("disabled", false);
                }
                $('#total-paid').val(total_paid);
                $('#total-due').val(total_due);
            }
        </script>
    @endsection
@endsection
