<div class="row">
    <div class="col-xl-6 col-lg-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Contact::label.COMPANY') <span class="fw-300"><i>@lang('Contact::label.INFORMATION')</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="stprimary">
                        <div class="stprimaryinfo">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('company_name', 'Company Name:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                    {!! Form::text('company_name', isset($orderDataDetails) ? $orderDataDetails->company_name : null, [
                                                        'id' => 'company_name',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'e.g: Highmark Limited',
                                                        'required' => true,
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('company_name') !!}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('country', 'Country Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                                    {!! Form::Select('country', $country, isset($orderDataDetails) ? $orderDataDetails->country_id : null, [
                                                        'id' => 'country',
                                                        'class' => 'form-control selectheighttype',
                                                        'onchange' => 'getBuyingPrice();getSellingPrice()',
                                                        'required' => true,
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('country') !!}</span>
                                                    <label id="country-error" class="error" for="country"></label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('phone_no', 'Phone No:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                    {!! Form::number('phone_no', isset($orderDataDetails) ? $orderDataDetails->cm_phone : null, [
                                                        'id' => 'phone_no',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'e.g: 01838399293',
                                                        'required' => true,
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('phone_no') !!}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('email', 'E-mail:', ['class' => 'col-form-label']) !!}

                                                    {!! Form::email('email', isset($orderDataDetails) ? $orderDataDetails->cm_email : null, [
                                                        'id' => 'email',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'e.g: test@gmail.com',
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('email') !!}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('company_reg_no', 'Company Reg No:', ['class' => 'col-form-label']) !!}

                                                    {!! Form::text('company_reg_no', isset($orderDataDetails) ? $orderDataDetails->cm_reg_no : null, [
                                                        'id' => 'company_reg_no',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'e.g: TH27383JH77',
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('company_reg_no') !!}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('website', 'Website:', ['class' => 'col-form-label']) !!}

                                                    {!! Form::text('website', isset($orderDataDetails) ? $orderDataDetails->cm_website : null, [
                                                        'id' => 'website',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'e.g: www.test.com',
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('website') !!}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('address', 'Address', ['class' => 'col-form-label']) !!}
                                                    &nbsp;
                                                    {!! Form::textarea('address', isset($orderDataDetails) ? $orderDataDetails->cm_address : null, [
                                                        'class' => 'form-control',
                                                        'rows' => 3,
                                                        'id' => 'address',
                                                        'placeholder' => 'e.g: Uttara SME Service Center, Holding- 18 Sonargaon Janapath, Sector# 09 Uttara, 1230',
                                                        'style' => 'height:80px;',
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('address') !!}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('note', 'Note', ['class' => 'col-form-label']) !!}
                                                    &nbsp;
                                                    {!! Form::textarea('note', isset($orderDataDetails) ? $orderDataDetails->cm_note : null, [
                                                        'class' => 'form-control',
                                                        'rows' => 3,
                                                        'id' => 'note',
                                                        'placeholder' => 'Enter Address',
                                                        'style' => 'height:80px;',
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('note') !!}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-6 col-lg-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Contact::label.CUSTOMER') <span class="fw-300"><i>@lang('Contact::label.INFORMATION')</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="stprimary">
                        <div class="stprimaryinfo">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">

                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('customer_type', 'Customer Type', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                    {!! Form::Select(
                                                        'customer_type',
                                                        ['bank' => 'Bank', 'branch' => 'Branch', 'company' => 'Company'],
                                                        $orderDataDetails->customer_type ?? 'branch',
                                                        [
                                                            'id' => 'customer_type',
                                                            'class' => 'form-control selectheighttype',
                                                            'onchange' => 'getCustomer();',
                                                            'required' => true,
                                                        ],
                                                    ) !!}

                                                    <span class="error"> {!! $errors->first('customer_type') !!}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('bank_id', 'Customer Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                                    {!! Form::Select('bank_id', $bankId, $customer ?? null, [
                                                        'id' => 'bank_id',
                                                        'class' => 'form-control selectheighttype',
                                                        'onchange' => 'getBranch();getKeypersonnel();getSellingPrice()',
                                                        'required' => true,
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('bank_id') !!}</span>
                                                    <label id="bank_id-error" class="error" for="bank_id"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('branch_id', 'Branch Name', ['class' => 'col-form-label']) !!}

                                                    {!! Form::select('branch_id', $branch, $branchId ?? ['' => 'At first select a Bank'], [
                                                        'id' => 'branch_id',
                                                        'class' => 'form-control selectheighttype',
                                                        'onchange' => 'getKeypersonnel();getSellingPrice()',
                                                    ]) !!}



                                                    <span class="error"> {!! $errors->first('branch_id') !!}</span>
                                                    <label id="branch_id-error" class="error" for="branch_id"></label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('bank_reference', 'Bank Referance:', ['class' => 'col-form-label']) !!}

                                                    {!! Form::text('bank_reference', isset($orderDataDetails) ? $orderDataDetails->bank_reference : null, [
                                                        'id' => 'bank_reference',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'e.g: SE2938u2',
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('bank_reference') !!}</span>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('keyPersonnel', 'Key Personnel Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                                    {!! Form::Select(
                                                        'key_personnel',
                                                        $keyPersonnel,
                                                        isset($orderDataDetails) ? $orderDataDetails->key_personnel_id : null,
                                                        [
                                                            'id' => 'keyPersonnel',
                                                            'class' => 'form-control selectheighttype',
                                                            'required' => true,
                                                        ],
                                                    ) !!}
                                                    <span class="error"> {!! $errors->first('key_personnel') !!}</span>
                                                    <label id="keyPersonnel-error" class="error"
                                                        for="keyPersonnel"></label>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('cp_phone_no', 'Phone No:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                    {!! Form::number('cp_phone_no', $keyPersonnelPhone ?? null, [
                                                        'id' => 'cp_phone_no',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Enter Phone Number',
                                                        'required' => true,
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('cp_phone_no') !!}</span>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="hidden" name="selling_price" id="selling_price"
                                                        value="@if (isset($orderDataDetails->selling_price)) {{ $orderDataDetails->selling_price }} @endif">
                                                    <span class="error"> {!! $errors->first('selling_price') !!}</span>
                                                </div>
                                            </div>
                                        </div>

                                        @if (isset($editPage))
                                            @php
                                                $allAttachment = json_decode($orderDataDetails->attachment);
                                                $hasAttachment = count($allAttachment);
                                            @endphp
                                            @if ($hasAttachment > 0)
                                                @foreach ($allAttachment as $item)
                                                    @if (file_exists(public_path('/backend/images/order_attachment/' . $item)))
                                                        <div class="col-lg-12 col-md-12 mt-2 mb-2">
                                                            <div class="form-group">
                                                                <div class="card">
                                                                    <div class="file-show-container">

                                                                        <div class="file-show-item extenstion_name">
                                                                            @if (pathinfo($item, PATHINFO_EXTENSION) == 'docx')
                                                                                DOCX
                                                                            @elseif(pathinfo($item, PATHINFO_EXTENSION) == 'pdf')
                                                                                PDF
                                                                            @elseif(pathinfo($item, PATHINFO_EXTENSION) == 'csv')
                                                                                CSV
                                                                            @elseif(pathinfo($item, PATHINFO_EXTENSION) == 'ods')
                                                                                ODS
                                                                            @elseif(pathinfo($item, PATHINFO_EXTENSION) == 'xlsx')
                                                                                XLSX
                                                                            @endif
                                                                        </div>
                                                                        <div class="file-show-item">
                                                                            {{ $item }}
                                                                        </div>
                                                                        <div class="file-show-item">
                                                                            <a href="{{ asset(config('app.asset') . 'backend/images/order_attachment/' . $item) }}"
                                                                                download="">
                                                                                <i class="fa fa-download"
                                                                                    aria-hidden="true"></i>
                                                                            </a>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif


                                        <div class="col-lg-12 col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="formFile" class="form-label">Select an
                                                    Attachment</label>
                                                <input type="hidden" name="old_attachment"
                                                    value="@if (isset($orderDataDetails)) {{ $orderDataDetails->attachment }} @endif">
                                                <input class="form-control" type="file" id="formFile"
                                                    name="attachment">
                                            </div>
                                        </div>






                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    @lang('Contact::label.SUPPLIER') <span class="fw-300"><i>@lang('Contact::label.INFORMATION')</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="stprimary">
                        <div class="stprimaryinfo">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">


                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('supplier_id', 'Supplier Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                                                    {!! Form::Select('supplier_id', $supplierId, isset($orderDataDetails) ? $orderDataDetails->supplier_id : null, [
                                                        'id' => 'supplier_id',
                                                        'class' => 'form-control selectheighttype',
                                                        'onchange' => 'getBuyingPrice()',
                                                        'required' => true,
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('supplier_id') !!}</span>
                                                    <label id="supplier_id-error" class="error"
                                                        for="supplier_id"></label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6 mt-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {!! Form::label('supplier_reference', 'Supplier Reference:', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                                                    {!! Form::text('supplier_reference', isset($orderDataDetails) ? $orderDataDetails->supplier_reference : null, [
                                                        'id' => 'supplier_reference',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'e.g: SUP2893892',
                                                    ]) !!}
                                                    <span class="error"> {!! $errors->first('supplier_reference') !!}</span>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="hidden" name="buying_price" id="buying_price"
                                                        value="@if (isset($orderDataDetails->buying_price)) {{ $orderDataDetails->buying_price }} @endif">
                                                    <span class="error"> {!! $errors->first('buying_price') !!}</span>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="stprimarybutton">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <button
                                                class="btn btn-primary ml-auto waves-effect waves-themed float-right"
                                                id="btnsm" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#supplier_id').select2();
        $('#country').select2();
        $('#bank_id').select2();
        $('#branch_id').select2();
        $('#keyPersonnel').select2();
        $('#customer_type').select2();
        // $("#customer_type").trigger("change");
        // getCustomer();

        $("#fileupload").change(function(event) {
            var x = URL.createObjectURL(event.target.files[0]);
            $("#upload-img").attr("src", x);
            console.log(event);
        });

        // Date of birth JS Data
        $('#expiryDate').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });

        //  Date of birth JS Data
        $('#dateOfBirth').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });


        // JS Validation
        $("#bankAdd").validate({
            rules: {
                full_name: {
                    required: true,
                },
                cp_phone_no: {
                    required: true,
                }
            },
            messages: {
                first_name: 'Please Enter First Name',
                cp_phone_no: 'Please Enter Mobile No',
            }
        });

    });


    // Customer Change on select Customer Type
    function getCustomer() {
        var customerType = $('#customer_type').val();
        var html = '';
        if (customerType != 0) {
            $.ajax({
                url: "{{ url('get-customer') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    customerType: customerType
                },
                beforeSend: function() {
                    $('select[name="bank_id"]').empty();
                },
                success: function(response) {
                    if (customerType == 'branch' || customerType == 'bank') {
                        $('select[name="bank_id"]').append('<option value="0">Select Bank</option>');
                    } else if (customerType == 'company') {
                        $('select[name="bank_id"]').append('<option value="0">Select Company</option>');
                    }
                    $.each(response, function(key, data) {
                        $('select[name="bank_id"]').append(
                            '<option value="' + data
                            .id + '">' + data.full_name + '</option>');
                    });
                    $("#bank_id").trigger("change");
                    if (customerType == 'bank' || customerType == 'company') {
                        $("#branch_id").prop('disabled', true);
                    } else {
                        $("#branch_id").prop('disabled', false);
                    }
                }
            });
        }
    }


    // Branch Change on select Bank
    function getBranch() {
        var bankId = $('#bank_id').val();
        var html = '';
        if (bankId != 0) {
            $.ajax({
                url: "{{ url('get-bank') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    bankId: bankId
                },
                beforeSend: function() {
                    $('select[name="branch_id"]').empty();
                },
                success: function(response) {
                    $('select[name="branch_id"]').append(
                        '<option value="0">Select Branch</option>');
                    $.each(response, function(key, data) {
                        $('select[name="branch_id"]').append(
                            '<option value="' + data
                            .id + '">' + data.full_name + '</option>');
                    });
                    $("#branch_id").trigger("change");
                }
            });
        }
    }

    // Key Personnel Change on select Bank Or Branch
    function getKeypersonnel() {
        var customerType = $('#customer_type').val();
        if (customerType == 'bank' || customerType == 'company') {
            var keyPersonnelSearchId = $('#bank_id').val();
        } else if (customerType == 'branch') {
            var keyPersonnelSearchId = $('#branch_id').val();
        }

        // alert(keyPersonnelSearchId);

        var html = '';
        if (keyPersonnelSearchId != 0) {
            $.ajax({
                url: "{{ url('get-order-keypersonnel') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token: '{!! csrf_token() !!}',
                    keyPersonnelSearchId: keyPersonnelSearchId
                },
                beforeSend: function() {

                },
                success: function(response) {
                    // Set the value of the select2
                    $('#keyPersonnel').val(response.id).trigger('change');
                    $('#cp_phone_no').val(response.phone);
                }
            });
        }
    }


    // Buying Price
    function getBuyingPrice() {
        var countryId = $('#country').val();
        var supplierId = $('#supplier_id').val();
        if (supplierId != 0 && countryId != 0) {
            $.ajax({
                url: "{{ url('/get-supplier-wise-price/') }}/" + supplierId + "/" + countryId,
                type: 'get',
                success: function(data) {
                    if (!isNaN(Number(data))) {
                        $('#buying_price').val(data);
                        $('#supplier_id-error').text('');
                        $('#btnsm').show();
                    } else {
                        $('#buying_price').val('');
                        $('#supplier_id-error').text('Please set buying price for this supplier');
                        $('#btnsm').hide();
                    }
                    console.log(data);
                }
            });
        }
    }


    // Selling Price
    function getSellingPrice() {
        var countryId = $('#country').val();
        var customerType = $('#customer_type').val();
        if (customerType == 'bank' || customerType == 'company') {
            var customerId = $('#bank_id').val();
        } else if (customerType == 'branch') {
            var customerId = $('#branch_id').val();
        }

        if (customerId != 0 && countryId != 0) {
            $.ajax({
                url: "{{ url('/get-customer-wise-price/') }}/" + customerId + "/" + countryId,
                type: 'get',
                success: function(data) {
                    if (!isNaN(Number(data))) {
                        $('#selling_price').val(data);
                        $('#bank_id-error').text('');
                    } else {
                        $('#selling_price').val('');
                        $('#bank_id-error').text('Please set buying price for this Customer');
                    }

                }
            });
        }
    }
</script>
