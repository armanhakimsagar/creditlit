@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            .stprimarybutton {
                display: block;
                position: fixed;
                bottom: 30px;
                right: 40px;
            }
        </style>
    @endpush
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Student::label.ALL') @lang('Student::label.STUDENT')</li>
            <li class="breadcrumb-item active">@lang('Student::label.PAYMENT') @lang('Student::label.SETUP')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date">Sunday, April 12,
                    2020</span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fal fa-th-list text-primary"></i> @lang('Student::label.PAYMENT') @lang('Student::label.SETUP')
            </h1>
        </div>
        <input type="hidden" id="count" value="0" name="count">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Student::label.PAYMENT') @lang('Student::label.SETUP') <span class="fw-300"><i>List</i></span>
                </h2>

            </div>

            <div class="panel-container show">
                <div class="row clearfix">
                    <div class="block-header block-header-2">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('payment.setup.store') }}" method="POST">
                                    @csrf
                                    <div class="row" id="notPrintDiv">
                                        <div class="col-md-12">
                                            <table class="table">
                                                <tr>
                                                    <th>Name</th>
                                                    <td>:{{ $contact->full_name }}</td>
                                                    <th>Class</th>
                                                    <td>:{{ $contact->classname }}</td>
                                                    <th>Roll No</th>
                                                    <td>:{{ $contact->class_roll }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Section</th>
                                                    <td>:{{ $contact->sectionname }}</td>
                                                    <th>transposrt</th>
                                                    <td>:{{ $contact->transportsname }}</td>
                                                    <th>Phone</th>
                                                    <td>:{{ $contact->cp_phone_no }}</td>
                                                </tr>

                                            </table>

                                        </div>
                                        <div class="col-md-6 form-data">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="" class='col-form-label'>Select Year</label>
                                                    {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                                        'id' => 'academicYearId',
                                                        'class' => 'form-control select2 academic-year-id search-section',
                                                    ]) !!}
                                                </div>
                                                <span class="error" id="yearError"></span>
                                            </div>
                                        </div>
                                        <input type="hidden" id="studentId"name="student_id" value="{{ $id }}"
                                            data-id="{{ $id }}">
                                        <input type="hidden" id="classId"name="class_id" value="{{ $class_id }}"
                                            data-id="{{ $class_id }}">
                                        <div class="col-md-6 form-data">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="" class='col-form-label'>Select Month</label>
                                                    {!! Form::Select('enum_month_id', $month_List, (int) date('m'), [
                                                        'id' => 'month_id',
                                                        'class' => 'form-control class-id select2 search-section',
                                                    ]) !!}
                                                </div>
                                                <span class="error" id="classError"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>

                                    <div class="row clearfix">
                                        <div class="block-header block-header-2">
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <center>
                                                <div class="row">
                                                    <div class="col-md-12">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    </div>
                                                </div>
                                            </center>
                                            <div class="table-responsive">
                                                <div class="show-table">
                                                    <div class="col-md-12">
                                                        <button type="button" name="padd"
                                                            class="btn btn-success btn-sm ml-auto waves-effect waves-themed padd"
                                                            onclick="addProduct()">Add Item</button>
                                                    </div>
                                                    <table class="table table-bordered table-striped" id="yajraTable">
                                                        <thead class="thead-themed">
                                                            <th class="table-serial-column-center"> SL</th>
                                                            <th style="width: 10%;" class="table-checkbox-header-center">
                                                                <span>
                                                                    Check All</span>
                                                                <input type="checkbox" class="all-check-box" id="chkbxAll"
                                                                    onclick="return checkAll()">
                                                            </th>
                                                            <th class="text-center"> Item Name</th>
                                                            <th style="width: 10%;" class="table-checkbox-header-center">
                                                                <span>
                                                                    Action
                                                                </span>
                                                                {{-- <input type="checkbox" class="all-check-box"> --}}
                                                                {{-- onclick="return checkAll()"> --}}
                                                            </th>

                                                            <th class="table-column-center"> Amount</th>
                                                            <th class="table-column-center">Payable Amount</th>
                                                            <th class="table-column-center">Notes</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="payment_table">

                                                        </tbody>
                                                        <tbody id="added_item">

                                                        </tbody>
                                                        <tbody>
                                                            <td colspan="7" class="text-center"
                                                                style="vertical-align: middle;">
                                                                <input type="checkbox" value="1" class="all-check-box"
                                                                    name="generate_due"> Generate/Update Due
                                                            </td>
                                                        </tbody>
                                                    </table>


                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="stprimarybutton"><button id="submitBtn"
                                                                    class="btn btn-primary btn-sm float-right mb-3 mr-3"
                                                                    type="submit">save</button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @section('javaScript')
        <script>
            var itemCount = 0;
            $(function() {
                $(document).on('change', ".search-section", function() {
                    $("#chkbxAll").prop("checked", false);
                    var getMonthId = $("#month_id").val();
                    var getAcademicId = $("#academicYearId").val();
                    var id = $("#studentId").data('id');
                    var class_id = $("#classId").data('class_id');
                    var html = '';
                    $.ajax({
                        type: "post",
                        url: "{{ url('get-payment-data/') }}/" + id,
                        data: {
                            _token: '{!! csrf_token() !!}',
                            getMonthId: getMonthId,
                            getAcademicId: getAcademicId,
                        },

                        success: function(response, keyId) {

                            $(".show-table").css("display", "block");
                            $('#payment_table').html(response.data);
                            $('#count').val(response.count);
                            itemCount = response.count;
                            $(".new_data").remove();
                        }
                    });
                    setTimeout(() => {
                        isChecked();
                    }, 250);
                    // $(".search-section").trigger('change');
                });
                $('.search-section').trigger('change');
            });

            $(function() {
                $(".select2").select2();
            });


            function checkAll() {
                if ($('#chkbxAll').is(':checked')) {
                    $(".allCheck").each(function(index) {
                        var key = $(this).attr('keyvalue');

                        if ($("#checkSection_" + key)[0].checked == false) {
                            $("#checkSection_" + key).prop('checked', true);
                        }
                    });
                } else {
                    $(".allCheck").each(function(index) {
                        var key = $(this).attr('keyvalue');
                        if ($("#checkSection_" + key)[0].checked == true) {
                            $("#checkSection_" + key).prop('checked', false);
                        }
                    });
                }
            }

            function unCheck(id) {
                if ($('#' + id).is(':not(:checked)')) {
                    $("#chkbxAll").prop("checked", false);
                }
            }

            function isChecked() {
                var countNotChecked = 0;
                var countChecked = 0;
                $(".allCheck").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                    } else {
                        countNotChecked++;
                    }
                });
                if (countNotChecked == 0 && countChecked > 0) {
                    $("#chkbxAll").prop("checked", true);
                }
            }

            var k = 1;
            var c = 1;

            function addProduct() {
                if (c == 1) {
                    k = parseInt(k) + itemCount;
                } else {
                    k = parseInt(k);
                }
                var academic_year_id = $('#academicYearId').val();
                var html = '';
                html += '<tr id="productRow_' + k + '" class="new_data">';
                html += '<td>' + k + '</td';
                html +=
                    '<td>' +
                    '<td class="text-center">' +
                    '<input type="checkbox"  class = "all-check-box allCheck" id="checkItem_' + k + '"  name="item_check[' + k +
                    '][]" value=" ' + k + '" keyValue="' + k + '"  onclick="unCheck(this.id);isChecked()"' + '/>' +
                    '</td>';
                html +=
                    '<td>' +
                    '   <select style="width:400px;" class="form-control js-example-basic selectheighttype product_row" id="new_product_id' +
                    k + '" ' +
                    '     required name="product_id[' + k + '][]" onchange="itemDetails(' + k + ')" tabindex=' + parseInt(k +
                        1) + '>\n' +
                    '     <option value="" >Select Product</option>\n' +
                    '      <?php foreach($productlist as $key => $Thisproduct): ?>\n' +
                    '      <option value="<?php echo $Thisproduct->id; ?>"><?php echo filter_var($Thisproduct->name, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
                    '      <?php endforeach; ?>\n' +
                    '   </select>\n' +
                    '</td>';


                html += '<td class="text-left">';
                html += '<input type="checkbox" class = "all-check-box" name="whole_year[' + k + '][]" value="1"> Whole Year';
                html += '</td>';

                html +=
                    '<td class="text-center">' +
                    '<input style="width:150px; border:1px solid gray;" name="actual_amount[' + k +
                    '][]" type="text" pattern="[0-9.]+" value="0" class="form-control lightGray" id="actual_amount-' + k +
                    '" oninput="amountpass(' + k + ')"  tabindex=' + parseInt(100 + k) + ' required/>' +
                    '</td>';
                // html +=
                //     '<td class="text-center">' +
                //     '<input style="width:150px; border:1px solid gray;" type="text" pattern="[0-9.]+" value="0" name="due_amount[' +
                //     k + '][]" class="form-control lightGray due_amount" id="due-amount-' + k + '" readonly />' +
                //     '</td>';
                html +=
                    '<td class="text-center"><input border:1px solid gray;" type="text" pattern="[0-9.]+" name="payable_amount[' +
                    k + '][]" value="0" class="form-control lightGray paid_amount" id="payable-amount-' + k + '" data-key=' +
                    k +
                    ' oninput=""  tabindex=' + parseInt(200 + k) + ' required/>' +
                    '</td>';

                html +=
                    '<td class="text-center"><input border:1px solid gray;" type="text" name="notes[' +
                    k + '][]" placeholder="Enter Note" class="form-control lightGray" id="notes' + k + '" data-key=' +
                    k +' oninput=""  tabindex=' + parseInt(300 + k) + '/>' +
                    '</td>';

                html +=
                    '<td class="text-center">' +
                    '<button type="button" name="remove" onclick="removeProduct(' + k +
                    ')" class="btn btn-danger btn-sm remove">Delete</button>' +
                    '</td>' +
                    '</tr>';



                $("#added_item").append(html);

                // $('#product_id_'+k).val(value);
                // $('#product_id_'+k).trigger('change.select2');
                $('.js-example-basic').select2();
                k++;
                c++;

            }

            function removeProduct(productRow) {
                $("#productRow_" + productRow).remove();
                // calculation();
            }

            function itemDetails(id) {
                var itemId = $('#new_product_id' + id).val();
                var academicYearId = $('#academicYearId').val();
                var class_id = $('#classId').val();
                var studentID = $('#studentId').val();
                var month_id = $('#month_id').val();

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
                            month_id: month_id,
                        },
                        beforeSend: function() {},
                        success: function(response) {

                            if (response.result == 'success') {
                                $('#actual_amount-' + id).val(response.price);
                                $('#payable-amount-' + id).val(response.price);
                                // calculation();
                            } else {
                                alert(response.message);
                            }
                            // $('select[name="student_id"]').append(
                            //     '<option value="0">Select Student</option>');
                            // $.each(response, function(key, data) {
                            //     $('select[name="student_id"]').append(
                            //         '<option value="' + data
                            //         .id + '">'+ data.full_name + ' ['+data.class_roll+'] [<span style="background-color:red!important">'+data.contact_id+'</span>]</option>');
                            // });
                        }
                    });
                }
            }

            function amountpass(id) {
                var actual_amount = $('#actual_amount-' + id).val();
                var amount = $('#payable-amount-' + id).val(actual_amount);
            }

            function disableButton(id) {
                var payable_amount = $('#payable-amount-' + id).val();
                var paid_amount = $('#paid-amount-' + id).val();
                if (parseFloat(paid_amount) > parseFloat(payable_amount)) {
                    $('#submitBtn').attr("disabled", true);
                    $('#errorMsg' + id).html("<span style='color:red'>Payable Amount Must Be More Than Or Equal To-" +
                        paid_amount + "<span>");
                } else {
                    $('#submitBtn').attr("disabled", false);
                    $('#errorMsg' + id).html("<span><span>");
                }
            }
        </script>
    @endsection
@endsection
