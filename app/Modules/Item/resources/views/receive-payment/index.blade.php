@extends('Admin::layouts.master')
@section('body')
    <style>
        th:nth-child(3),
        th:nth-child(4) {
            text-align: center;
            vertical-align: middle;
        }

        td:nth-child(3),
        td:nth-child(4) {
            text-align: center;
            vertical-align: middle;
        }
    </style>
    <?php
    use Illuminate\Support\Facades\Input;
    ?>
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item active">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">{{ $pageTitle }}</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-edit'></i> @lang('Payment::label.RECEIVE_CUSTOMER_PAYMENT')
            </h1>
            {{-- <a style="margin-left: 10px;" href="{{ route('student.create') }}"
                class="btn btn-primary btn-sm waves-effect pull-right">@lang('Student::label.ADD') @lang('Student::label.STUDENT')</a> --}}
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Student::label.SELECT') @lang('Payment::label.RECEIVE_CUSTOMER_PAYMENT')<span class="fw-300"><i>@lang('Student::label.CRITERIA')</i></span>
                </h2>
            </div>

            <div class="panel-container show">
                {!! Form::open([
                    'route' => 'customer.payment.store',
                    'files' => true,
                    'name' => 'payment-store',
                    'id' => 'payment_store',
                    'class' => 'form-horizontal',
                    'autocomplete' => true,
                ]) !!}
                <div class="panel-content">
                    <div class="row pb-5">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="academicYearId">@lang('Student::label.ACADEMIC') @lang('Student::label.YEAR')</label>
                                {!! Form::Select('academic_year_id', $academic_year, !empty($currentYear) ? $currentYear->id : null, [
                                    'id' => 'academicYearId',
                                    'class' => 'form-control selectheighttype',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="studentID">@lang('Payment::label.SELECT-CUSTOMER')</label>
                                @if ($selected_student)
                                    <select class="form-control select2" name="student_id" id="student_id"
                                        onchange="getStudentDetails()">
                                        <option value="{{ $selected_student->id }}">
                                            {{ $selected_student->full_name }}[{{ $selected_student->class_roll }}][{{ $selected_student->contact_id }}]
                                        </option>
                                    </select>
                                @else
                                    {{-- {!! Form::Select('student_id', $studentList, !empty($request->student_id) ? $request->student_id : 'Select Student', [
                                    'id' => 'student_id',
                                    'onchange' => 'getStudentDetails()',
                                    'class' => 'form-control select2',
                                ]) !!} --}}
                                    <select class="form-control select2" name="student_id" id="student_id"
                                        onchange="getCustomerDetails()">
                                        {{-- <option value="" disabled selected>Select Customer</option> --}}
                                        @foreach ($customerList as $item)
                                            <option value="{{ $item->id }}">{{ $item->full_name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="form-group col-3">
                            <label for="versionId">@lang('Payment::label.STUDENT-ID')</label>
                                {!! Form::text('sid', null, [
                                    'id' => 'sid',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Student ID',
                                ]) !!}
                                <span class="error"> {!! $errors->first('sid') !!}</span>
                        </div> --}}
                        {{-- <div class="form-group col-3">
                            <button class="btn btn-primary btn-sm ml-auto mt-4 waves-effect waves-themed" type="submit"
                                id="searchBtn"><i class="fas fa-search pr-1"></i>@lang('Student::label.SEARCH')</a></button>
                        </div> --}}
                    </div>
                    <div class="frame-wrap">

                        <div class="row student_details">

                            <div class="col-md-12">
                                <center>
                                    <img style="width: 30px; margin-right: 50px; display: none;" class="preloader"
                                        src="{{ asset(config('app.asset') . 'image/my-loading.gif') }}">
                                </center>
                                <input type="hidden" id="preload_class_id" name="preload_class_id">
                                <input type="hidden" id="count" value="0" name="">
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr id="month">

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            {{-- <div class="col-md-6">
                                <div class="table table-responsive">
                                <table class="table">
                                    <tr style="background-color:#BAD1C2">
                                        <th width="20%">Student ID</th>
                                        <td id="sid"></td>
                                        <td width="20%">G.Name</td>
                                        <td id="gName"></td>
                                    </tr>
                                    <tr style="background-color:white">
                                        <th>Name</th>
                                        <td id="student_name"></td>
                                        <td width="20%">G.Number</td>
                                        <td id="gNumber"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%">Class</th>
                                        <td id="student_class"></td>
                                        <th width="10%">Section</th>
                                        <td id="student_section"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%">Shift</th>
                                        <td id="student_shift"></td>
                                        <th width="10%">Transport</th>
                                        <td id="student_transport"></td>
                                    </tr>
                                </table>
                            </div> --}}

                            <div class="col-md-6">
                                <div class="table table-responsive">
                                    <table class="table">
                                        <tr style="background-color:#BAD1C2">
                                            <th width="20%">Customer ID</th>
                                            <td id="sid"></td>
                                        </tr>
                                        <tr style="background-color:white">
                                            <th width="20%">Name</th>
                                            <td id="student_name"></td>
                                        </tr>
                                        <tr style="background-color:white">
                                            <th>Phone</th>
                                            <td id="customer_phone"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%">Address</th>
                                            <td id="address"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table table-responsive">
                                    <table class="table">
                                        <tr style="background-color:grey">
                                            <th style="color:white">Transaction Details</th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr style="background-color:white">
                                            <td colspan="4">
                                                <select name="category_id" id="category_id" class="form-control">
                                                    @foreach ($account_category as $accountCategoryValue)
                                                        <option value="{{ $accountCategoryValue->id }}">
                                                            {{ $accountCategoryValue->TypeName }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td colspan="7">
                                                <input type="text" value="{{ date('d-m-Y') }}" id="payment_date"
                                                    name="payment_date" class="form-control">
                                            </td>
                                        </tr>
                                        <tr style="background-color: yellow;">
                                            <th style="font-size: 16px;">Due</th>
                                            <td colspan="9" class="text-bold" id="previuos-due" style="font-size: 16px;">
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>Last Payment</th>
                                            <td colspan="9" class="text-bold" id="lastPayment" style=""></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>
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
                                            <th width="10%">DUE</th>
                                            <th width="10%">PAID</th>
                                            <th width="20%">NOTE</th>
                                            <th width="5%"></th>
                                        </tr>
                                        <tbody id="payment_table">
                                        </tbody>
                                        <tbody id="added_item">
                                        </tbody>
                                        <tr>
                                            <td colspan="8">
                                                <center>
                                                    <img style="width: 30px; margin-right: 50px; display: none;"
                                                        class="month_preloader"
                                                        src="{{ asset(config('app.asset') . 'image/my-loading.gif') }}">
                                                </center>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
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
                                    <tr>
                                        <td style="background-color:white">
                                            <div class="form-group">
                                                <label class="form-label" for="basic-url">Total Current Due</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">&#2547;</span>
                                                    </div>
                                                    <input type="text" class="form-control" value="0"
                                                        id="total-due" name="total_due" readonly="">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">TAKA</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="padd" id="submitBtn"
                                    class="btn btn-primary float-right btn-sm ml-auto waves-effect waves-themed">Save</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- AJAX -->
        {{-- @if ($studentID > 0)
        <script type="text/javascript">
              $(document).ready(function(){
                getStudentDetails();
            });
        </script>
        @endif --}}

        <script type="text/javascript">
            // Select2 use
            $(function() {
                getCustomerDetails();
                $('#submitBtn').attr("disabled", true);
                // $(".student_details").hide();
                $("#academicYearId").select2({
                    width: "100%"
                });
                $("#classId").select2({
                    width: "100%"
                });
                $("#sectionId").select2({
                    width: "100%"
                });
                $("#shiftId").select2({
                    width: "100%"
                });
                $("#versionId").select2({
                    width: "100%"
                });
                $("#groupId").select2({
                    width: "100%"
                });

                // $("#student_id").select2({
                //     // minimumInputLength:1,
                //     width: "100%",
                //     ajax: {
                //       url: "{{ url('student-id-details') }}",
                //       type: "post",
                //       dataType: 'json',
                //       delay: 250,
                //       data: function (params, page) {
                //         var sectionId = $('#sectionId').val();
                //         var academicYearId = $('#academicYearId').val();
                //         var classId = $('#classId').val();
                //         //alert(1);
                //         return {
                //           _token: '{!! csrf_token() !!}',
                //           search: params.term, // search term
                //           sectionId: sectionId,
                //           academicYearId: academicYearId,
                //           classId: classId,
                //         };
                //       },
                //       processResults: function (response) {
                //         var resultsToShow = [];
                //         $.each(response, function( index, value ) {
                //           resultsToShow.push({
                //             "id": value.id,
                //             "text": value.text
                //           });
                //         });
                //         return {
                //           results: resultsToShow
                //         };
                //       }
                //     }

                // });
                $("#student_id").select2();
                $('#payment_date').datepicker({
                    language: 'en',
                    dateFormat: 'dd-mm-yyyy',
                    autoClose: true
                });
            });






            // function getStudent() {
            //     var sectionId = $('#sectionId').val();
            //     // alert(classId)
            //     var html = '';
            //     if (sectionId != 0) {
            //         $.ajax({
            //             url: "{{ url('get-students') }}",
            //             type: "post",
            //             dataType: "json",
            //             data: {
            //                 _token: '{!! csrf_token() !!}',
            //                 sectionId: sectionId,
            //             },
            //             beforeSend: function() {
            //                 $('select[name="student_id"]').empty();
            //             },
            //             success: function(response) {
            //                 $('select[name="student_id"]').append(
            //                     '<option value="0">Select Student</option>');
            //                 $.each(response, function(key, data) {
            //                     $('select[name="student_id"]').append(
            //                         '<option value="' + data
            //                         .id + '">' + data.full_name + ' [' + data.class_roll +
            //                         '] [<span style="background-color:red!important">' + data
            //                         .contact_id + '</span>]</option>');

            //                 });

            //             }
            //         });
            //     }
            // }

            // function getStudentDetails() {
            //     var studentID = $('#student_id').val();
            //     var classId = $('#classId').val();
            //     var academicYearId = $('#academicYearId').val();
            //     // alert(classId)
            //     $('.preloader').css('display', 'inline-block');
            //     var html = '';
            //     if (studentID != 0) {
            //         $.ajax({
            //             url: "{{ url('get-student-details-and-items') }}",
            //             type: "post",
            //             dataType: "json",
            //             data: {
            //                 _token: '{!! csrf_token() !!}',
            //                 studentID: studentID,
            //                 classId: classId,
            //                 academicYearId: academicYearId,
            //             },
            //             beforeSend: function() {
            //                 $(".student_details").show();
            //             },
            //             success: function(response) {

            //                 if (response.result == 'success') {
            //                     $('#sid').html(response.sid);
            //                     $('#sid').val(response.studentID);
            //                     $('#student_name').html(response.student_name);
            //                     $('#gName').text(': ' + response.guardian_name);
            //                     $('#gNumber').text(': ' + response.guardian_number);
            //                     $('#student_class').text(': ' + response.student_class);
            //                     $('#student_section').text(': ' + response.student_section);
            //                     $('#student_shift').text(': ' + response.shift_name);
            //                     $('#student_transport').text(': ' + response.transport_name);
            //                     $('#previuos-due').text(': ' + response.due);
            //                     $('#lastPayment').text(': ' + response.paid_amount+' ('+response.sales_invoice_date+')');
            //                     $('#preload_class_id').val(response.student_class_id);
            //                     $('#payment_table').html(response.data);
            //                     $('#month').html(response.month);
            //                     $('#count').val(response.count);
            //                     $(".new_data").remove();
            //                     calculation();
            //                 } else {
            //                     alert(response.message);
            //                 }
            //                 // $('select[name="student_id"]').append(
            //                 //     '<option value="0">Select Student</option>');
            //                 // $.each(response, function(key, data) {
            //                 //     $('select[name="student_id"]').append(
            //                 //         '<option value="' + data
            //                 //         .id + '">'+ data.full_name + ' ['+data.class_roll+'] [<span style="background-color:red!important">'+data.contact_id+'</span>]</option>');
            //                 // });

            //                 $('.preloader').css('display', 'none');
            //             }
            //         });
            //     }
            // }


            function getCustomerDetails() {
                var studentID = $('#student_id').val();
                var academicYearId = $('#academicYearId').val();
                // alert(academicYearId)
                $('.preloader').css('display', 'inline-block');
                var html = '';
                if (studentID != 0) {
                    $.ajax({
                        url: "{{ url('get-customer-details-and-items') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            studentID: studentID,
                            academicYearId: academicYearId,
                        },
                        beforeSend: function() {
                            $(".student_details").show();
                        },
                        success: function(response) {

                            if (response.result == 'success') {
                                $('#sid').html(response.sid);
                                $('#sid').val(response.studentID);
                                $('#student_name').html(response.student_name);
                                $('#customer_phone').html(response.customer_phone);
                                $('#address').html(response.address);
                                $('#previuos-due').text(': ' + response.due);
                                $('#lastPayment').text(': ' + response.paid_amount + ' (' + response
                                    .sales_invoice_date + ')');
                                $('#payment_table').html(response.data);
                                $('#month').html(response.month);
                                $('#count').val(response.count);
                                $(".new_data").remove();
                                calculation();
                            } else {}
                            // $('select[name="student_id"]').append(
                            //     '<option value="0">Select Student</option>');
                            // $.each(response, function(key, data) {
                            //     $('select[name="student_id"]').append(
                            //         '<option value="' + data
                            //         .id + '">'+ data.full_name + ' ['+data.class_roll+'] [<span style="background-color:red!important">'+data.contact_id+'</span>]</option>');
                            // });

                            $('.preloader').css('display', 'none');
                        }
                    });
                }
            }

            function getMonthlyDue(id) {
                var studentID = $('#student_id').val();
                var classId = $('#preload_class_id').val();
                var academicYearId = $('#academicYearId').val();
                // alert(classId)
                $('.month_preloader').css('display', 'inline-block');
                var monthArr = [];
                // Initializing array with Checkbox checked values
                $(".month_check:checked").each(function(){
                    monthArr.push(this.value);
                });
                if (monthArr <= 0) {
                    setTimeout(function() {
                    $(".month_preloader").css("display", "none");
                    }, 100);
                }
                var html = '';
                if (studentID != 0) {
                    $.ajax({
                        url: "{{ url('get-student-monthly-due') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            studentID: studentID,
                            classId: classId,
                            academicYearId: academicYearId,
                            month_arr: monthArr,
                        },
                        beforeSend: function() {
                           $(".student_details").show();
                        },
                        success: function(response) {

                            if (response.result == 'success') {
                                $('#payment_table').html(response.data);
                                $('#count').val(response.count);
                                $(".new_data").remove();
                                calculation();
                                // alert(response.sid);
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

                            $('.month_preloader').css('display', 'none');
                        }
                    });
                }
            }

            var k = 1;

            function addProduct() {
                k = parseInt(k) + parseInt($('#count').val());
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
                    '     required name="product_id[' + k + '][]" onchange="itemDetails(' + k + ')" tabindex=' + parseInt(k +
                    1) + '>\n' +
                    '     <option value="" >Select Product</option>\n' +
                    '      <?php foreach($productlist as $key => $Thisproduct): ?>\n' +
                    '      <option value="<?php echo $Thisproduct->id; ?>"><?php echo filter_var($Thisproduct->name, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
                    '      <?php endforeach; ?>\n' +
                    '   </select>\n' +
                    '<div id="div_error_' + k + '"></div></td>';
                html +=

                    '<td>' +
                    '   <select class="form-control  selectheighttype" onchange="itemDetails(' + k + ')" id="month_id_' + k +
                    '" ' +
                    '     required name="month_id[' + k + '][]">\n' +
                    '     <option value="" >Select Month</option>\n' +
                    '      <?php foreach($enumMonth as $key => $enumMonthValue): ?>\n' +
                    '      <option value="<?php echo $enumMonthValue->id; ?>"><?php echo filter_var($enumMonthValue->name, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
                    '      <?php endforeach; ?>\n' +
                    '   </select><br>\n' +
                    '   <select class="form-control  selectheighttype" id="academic_year_id_' + k + '" ' +
                    '     required name="academic_year_ids[' + k + '][]" onchange="itemDetails(' + k + ')">\n' +
                    '     <option value="" >Select Year</option>\n' +
                    '      <?php foreach($academicYear as $key => $academicYearValue): ?>\n' +
                    '      <option value="<?php echo $academicYearValue->id; ?>"><?php echo filter_var($academicYearValue->year, FILTER_SANITIZE_SPECIAL_CHARS); ?></option>\n' +
                    '      <?php endforeach; ?>\n' +
                    '   </select>\n' +
                    '</td>';
                html +=
                    '<td class="text-center">' +
                    '<input style="width:150px; border:1px solid gray;" name="amount[' + k +
                    '][]" type="text" pattern="[0-9.]+" value="0" class="form-control lightGray" id="amount-' + k +
                    '" oninput="calculation()"  tabindex=' + parseInt(100 + k) + ' required/>' +
                    '</td>';
                html +=
                    '<td>' +
                    '<input style="width:150px; border:1px solid gray;" type="text" pattern="[0-9.]+" value="0" name="due_amount[' +
                    k + '][]" class="form-control lightGray due_amount" data-key=' + k + ' id="due-amount-' + k +
                    '" readonly />' +
                    '<span id="due_amount_error-' + k + '" class="error">' +
                    '</span>' +
                    '</td>';
                html +=
                    '<td><input style="width:150px; border:1px solid gray;" type="text" pattern="[0-9.]+" name="paid_amount[' +
                    k + '][]" value="0" class="form-control lightGray paid_amount" id="paid-amount-' + k + '" data-key=' + k +
                    ' oninput="calculation()"  tabindex=' + parseInt(200 + k) + ' required/>' +
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
                // calculation();
            }

            function itemDetails(id) {
                var itemId = $('#new_product_id' + id).val();
                var month_id=$('#month_id_'+ id).val();
                var academicYearId = $('#academic_year_id_'+id).val();
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
                            studentID:studentID,
                            month_id:month_id,
                        },
                        beforeSend: function() {},
                        success: function(response) {

                            if (response.result == 'success') {
                                $('#amount-' + id).val(response.price);
                                if(response.error !=1){
                                    $('#div_error_'+id).html("<p style='color:red'>"+response.error+"</p>");
                                     $('#paid-amount-'+id).val('0');
                                     $('#paid-amount-'+id).attr('readonly',true);
                                }
                                calculation();
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

            function calculation() {
                var total_paid = 0;
                var total_due = 0;
                $('.paid_amount').each(function() {
                    id = ($(this).attr('data-key'));
                    var paid = $(this).val() || 0;
                    total_paid = parseFloat(total_paid) + parseFloat(paid);
                    if (total_paid <= 0) {
                        $('#submitBtn').attr("disabled", true);
                    }else{
                        $('#submitBtn').attr("disabled", false);
                    }
                    var amount = $('#amount-' + id).val() || 0;
                    var old_paid = $('#hidden-paid-' + id).val() || 0;
                    $('#due-amount-' + id).val(parseFloat(amount) - (parseFloat(old_paid) + parseFloat(paid)));

                });

                $('.due_amount').each(function() {
                    id = ($(this).attr('data-key'));
                    var due = $(this).val() || 0;
                    if (due < 0) {
                       $('#due_amount_error-' + id).text("Due amount cannot be Less than paid amount").css("display", "block");
                       $('#submitBtn').attr("disabled", true);
                    }else{
                        $('#due_amount_error-' + id).css("display", "none");
                        total_due = parseFloat(total_due) + parseFloat(due);
                    }

                });
                $('#total-paid').val(total_paid);
                $('#total-due').val(total_due);
            }

            $(function() {

                $("#payment_store").validate({
                    rules: {
                        month_id_: {
                            required: true,
                        }
                    },
                    messages: {
                        month_id_: 'Please enter month_id_'

                    }
                });
            });
        </script>
    @endsection
