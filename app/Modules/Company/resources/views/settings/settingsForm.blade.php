<?php
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
?>
<style>
    .disc-level-display {
        display: none;
    }

    .verifier-details-display {
        display: none;
    }

    .shadow-css {
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
    }

    .panel-hdr {
        font-size: 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.26);
        /* border-bottom: 2px solid #886ab5; */
    }

    .profile-images-card {
        display: table;
        background: #fff;
    }

    .profile-images {
        width: 130px;
        height: 130px;
        background: #fff;
        overflow: hidden;
    }

    .profile-images img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    .custom-file label {
        cursor: pointer;
        color: #fff;
        display: table;
        margin-top: 15px;
        background-color: #25316D;
        padding: 6px 8px;
    }

    .panel .panel-container .panel-content .water-pressure-info {
        padding: 0px;
    }

    .stprimary {
        position: relative;
    }

    .stprimaryinfo,
    .stprimarybutton {
        padding: 1rem 1rem;
    }

    .stprimarybutton {
        position: sticky;
        bottom: 22px;
        right: 30px;
    }

    .fileupload,
    .signatureUpload {
        width: 0px;
    }
</style>

<div class="row">

    <div class="col-md-4">
        <div class="form-group">

            <div class="form-line">
                {!! Form::hidden('company_name', $data->company_name, [
                    'id' => 'company_name',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter company_name',
                    'onkeyup' => 'convert_to_slug();',
                ]) !!}
                <span class="error"> {!! $errors->first('company_name') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">

            <div class="form-line">
                {!! Form::hidden('phone', $data->phone, [
                    'id' => 'phone',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter phone',
                    'onkeyup' => 'convert_to_slug();',
                ]) !!}
                <span class="error"> {!! $errors->first('phone') !!}</span>
            </div>
        </div>
    </div>

    {!! Form::hidden('email', $data->email, [
        'id' => 'email',
        'class' => 'form-control',
        'required' => 'required',
        'placeholder' => 'Enter email',
        'onkeyup' => 'convert_to_slug();',
    ]) !!}
    <span class="error"> {!! $errors->first('email') !!}</span>
    {!! Form::hidden('address', $data->address, [
        'id' => 'address',
        'class' => ' form-control',
        'placeholder' => 'Enter address',
    ]) !!}

    <div class="col-md-12">
        <center>
            <h3>GENERAL SETTINGS</h3>
        </center>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            @php
                // $array1 = ['1', '2', '3', '4', '5', '6'];
                // $array2 = json_decode($data->visibleProductElement);
                // $decodedDetails = json_decode($data->verifier_details, true);
                // $unis = array_merge(array_diff($array1, $array2), array_diff($array2, $array1));
                // echo "<pre>";
                //     print_r($unis);
                // dd(count($array2));
            @endphp
            <div class="form-line">
                <label>Select Student Admission Default Items</label> <i style="display:inline;" id="tooltip"
                    class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="top"
                    data-original-title="Select Student Admission default Items"></i>
                {!! Form::Select('studentDefaultItem[]', $items, isset($data) ? json_decode($data->studentDefaultItem) : null, [
                    'id' => 'visibleProductElement',
                    'class' => 'form-control selectheighttype',
                    'multiple' => 'multiple',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                <label>Select Student Readmission Default Items</label> <i style="display:inline;" id="tooltip"
                    class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="top"
                    data-original-title="Select Student Readmission default Items"></i>
                {!! Form::Select(
                    'studentReadmissionDefaultItem[]',
                    $items,
                    isset($data) ? json_decode($data->studentReadmissionDefaultItem) : null,
                    [
                        'id' => 'visibleProductElement2',
                        'class' => 'form-control',
                        'multiple' => 'multiple',
                    ],
                ) !!}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                <label>Select Repeated Default Items</label> <i style="display:inline;" id="tooltip"
                    class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="top"
                    data-original-title="Select Repeated default Items"></i>
                {!! Form::Select(
                    'repeatedDefaultItem[]',
                    $items,
                    isset($data) ? json_decode($data->repeatedDefaultItem) : null,
                    [
                        'id' => 'visibleProductElement3',
                        'class' => 'form-control',
                        'multiple' => 'multiple',
                    ],
                ) !!}
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('name', 'All Report Font Size') !!}<span class="required"> *</span>

                <div class="input-group">
                    {!! Form::text('ReportFontSize', null, [
                        'id' => 'reportFontSize',
                        'class' => 'form-control',
                        'required' => 'required',
                    ]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mt-5">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('guardianAddress', 'Certificate Principal Details', ['class' => 'col-form-label']) !!}<i style="display:inline;" id="tooltip" class="fa fa-exclamation-circle"
                    data-toggle="tooltip" data-placement="top" data-original-title="Select Repeated default Items"></i>
                {!! Form::textarea(
                    'principal_details_in_certificate',
                    isset($data) ? $data->principal_details_in_certificate : null,
                    [
                        'class' => 'form-control textarea',
                        'rows' => 5,
                        'id' => 'principalDetailsInCertificate',
                        'placeholder' => 'Enter Principle Details',
                        'style' => 'height:100px;',
                    ],
                ) !!}
                <span class="error"> {!! $errors->first('principal_details_in_certificate') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-3">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('salary_system', 'Salary System', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                &nbsp;
                <br>
                {{ Form::radio('salary_system', '1', isset($data) ? ($data->salary_system == '1' ? 'checked' : '') : 1) }}
                <span>Basic To Gross </span> &nbsp;
                {{ Form::radio('salary_system', '2', isset($data) ? ($data->salary_system == '2' ? 'checked' : '') : null) }}
                <span>Gross To Basic</span>&nbsp;
                <br>
                <label id="salary_system-error" class="error" for="salary_system"></label>
                <span for="salary_system" class="error"> {!! $errors->first('salary_system') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-2">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('admit_card_design', 'Admit Card Design', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                &nbsp;
                <br>
                {{ Form::radio('admit_card_design', '1', isset($data) ? ($data->admit_card_design == '1' ? 'checked' : '') : 1) }}
                <span>Design 1 (D) </span> &nbsp;
                {{ Form::radio('admit_card_design', '2', isset($data) ? ($data->admit_card_design == '2' ? 'checked' : '') : null) }}
                <span>Design 2 (I)</span>&nbsp;
                <br>
                <label id="admit_card_design-error" class="error" for="admit_card_design"></label>
                <span for="admit_card_design" class="error"> {!! $errors->first('admit_card_design') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-3">
        {!! Form::label('principle_signature', 'Principle Signature', ['class' => 'col-form-label pic-header']) !!}
        <div class="profile-images-card">

            @if (isset($companyDetails->principle_signature))
                <div class="profile-images">
                    <img src="{{ asset(config('app.asset') . 'image/principalSignature/' . $companyDetails->principle_signature) }}"
                        name="signature" id="upload-signature-img">
                </div>
                <div class="custom-file">
                    <label for="signatureUpload">Upload Principal Signature</label>
                    <span class="text-danger">(Size Less than 1MB)</span>
                    <span class="error"> {!! $errors->first('photo') !!}</span>
                    <input type="file" id="signatureUpload" class="signatureUpload" name="principle_signature"
                        onchange="validateSize(this)">
                    <input type="hidden" name="old_principle_signature"
                        value="{{ $companyDetails->principle_signature }}">
                </div>
            @else
                <div class="profile-images">
                    <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}" name="signature"
                        id="upload-signature-img">
                </div>
                <div class="custom-file">
                    <label for="signatureUpload">Upload Principal Signature</label>
                    <span class="text-danger ">(Size Less than 1MB)</span>
                    <span class="error"> {!! $errors->first('photo') !!}</span>
                    <input type="file" id="signatureUpload" class="signatureUpload" name="principle_signature"
                        onchange="validateSize(this)">
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-3 col-md-3">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('principleSignatureHeight', 'Principle Signature Height') !!}

                <div class="input-group">
                    {!! Form::text('principle_signature_height', null, [
                        'id' => 'principleSignatureHeight',
                        'class' => 'form-control',
                    ]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-4">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('dollarExhangeRateBDT', 'Dollar Exhange Rate For BDT') !!}

                <div class="input-group">
                    {!! Form::number('dollarExhangeRateBDT', null, [
                        'id' => 'dollarExhangeRateBDT',
                        'class' => 'form-control',
                    ]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">TK</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-4">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('invoiceVatPercent', 'Invoice Vat Percentage') !!}

                <div class="input-group">
                    {!! Form::text('invoiceVatPercent', null, [
                        'id' => 'invoiceVatPercent',
                        'class' => 'form-control',
                    ]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="col-lg-3 col-md-3">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('ownerMobileNumber', 'Owner Mobile Number') !!}

                <div class="input-group">
                    {!! Form::text('ownerMobileNumber', null, [
                        'id' => 'ownerMobileNumber',
                        'class' => 'form-control',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-4">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('accountTitle', 'Account Title') !!}

                <div class="input-group">
                    {!! Form::text('accountTitle', null, [
                        'id' => 'accountTitle',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Account Title'
                    ]) !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-4">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('AccountNo', 'Account No') !!}

                <div class="input-group">
                    {!! Form::text('AccountNo', null, [
                        'id' => 'AccountNo',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Account No'
                    ]) !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-4">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('bankName', 'Bank Name') !!}

                <div class="input-group">
                    {!! Form::text('bankName', null, [
                        'id' => 'bankName',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Bank Name'
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-4">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('branchName', 'Branch Name') !!}

                <div class="input-group">
                    {!! Form::text('branchName', null, [
                        'id' => 'branchName',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Branch Name'
                    ]) !!}
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" name="settings" value="1" id="">
    <input type="hidden" name="brand_image_reset" id="brand_image_reset" value="0" placeholder="">
    <input type="hidden" name="image_reset" id="image_reset" value="0" placeholder="">
    <div class="col-md-12">
        <div class="panel-content d-flex flex-row align-items-center">

            <button class="btn btn-primary ml-auto waves-effect waves-themed" onclick="Validator();" id="btnsm"
                type="submit">Save</button>
        </div>
    </div>
</div>
{{-- $('#visibleProductElement').on("select2:select", function(evt) {
    var element = evt.params.data.element;
    var $element = $(element);

    $element.detach();
    $(this).append($element);
    $(this).trigger("change");
});
var items = [];
@foreach (json_decode($data->visibleProductElement) as $value)

    items.push({{ $value }});
@endforeach --}}

<script>
    $(document).ready(function() {
        divremoves();
        fontSizeShow();
        termsConditionShow();
        scheduleShow();
        quotationPreloadedFieldShow();
        discFirstShow();
        discSecondShow();
        discThirdShow();
        defaultVatShow();
        verificationField();
    });

    function brandimagereset() {

        $("#brand_image").hide();
        $("#brand_image_link").hide();
        $("#brand_image_reset").val(1);
    }

    function imagereset() {

        $("#image").hide();
        $("#image_reset_link").hide();
        $("#bimage_reset").val(1);
    }

    function convert_to_slug() {
        var str = document.getElementById("title").value;
        str = str.replace(/[^a-zA-Z0-12\s]/g, "");
        str = str.toLowerCase();
        str = str.replace(/\s/g, '-');
        document.getElementById("slug").value = str;

    }

    $("#fileupload").change(function(event) {
        var x = URL.createObjectURL(event.target.files[0]);
        $("#upload-img").attr("src", x);
        console.log(event);
    });

    $("#signatureUpload").change(function(event) {
        var x = URL.createObjectURL(event.target.files[0]);
        $("#upload-signature-img").attr("src", x);
        console.log(event);
    });
</script>
<script>
    $(function() {
        // highlight
        var elements = $("input[type!='submit'], textarea, select");
        elements.focus(function() {
            $(this).parents('li').addClass('highlight');
        });
        elements.blur(function() {
            $(this).parents('li').removeClass('highlight');
        });

        $("#industryform").validate({
            rules: {
                company_name: {
                    required: true,
                },
                title: {
                    required: true,
                },
                slug: {
                    required: true
                },
                status: {
                    required: true
                }

            },
            messages: {
                company_name: 'Please enter company',
                title: 'Please enter title',
                slug: 'Please enter slug',
                status: 'Plese choose status'
            }
        });
    });

    $(document).ready(function() {

        $("#selectedPaymentMethod").select2();
        $("#selectedInvoicePage").select2();
        $("#adjustmentChartId").select2();
        $("#selectedCategory").select2();
        $("#DefaultPaymentMethod").select2();
        $('#visibleProductElement').select2({
            tags: false,
            width: '100%'
        });

        $("#visibleProductElement").on("select2:select", function(evt) {
            var element = evt.params.data.element;
            var $element = $(element);

            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });

        var serverRenderData = <?php echo $data->studentDefaultItem; ?>;
        //usually we render data by this way, but select2 will auto sorting
        $("#visibleProductElement").val(serverRenderData).trigger('change');

        //so we re-append select data
        var options = [];
        for (var i = 0; i < serverRenderData.length; i++) {
            options.push($("select#visibleProductElement option[value=" + serverRenderData[i] + "]"));
        }
        $("#visibleProductElement").append(...options).trigger('change');


        $('#visibleProductElement2').select2({
            tags: false,
            width: '100%'
        });

        $("#visibleProductElement2").on("select2:select", function(evt) {
            var element = evt.params.data.element;
            var $element = $(element);

            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });

        var serverRenderData2 = <?php echo $data->studentReadmissionDefaultItem; ?>;
        //usually we render data by this way, but select2 will auto sorting
        $("#visibleProductElement2").val(serverRenderData2).trigger('change');

        //so we re-append select data
        var options = [];
        for (var i = 0; i < serverRenderData2.length; i++) {
            options.push($("select#visibleProductElement2 option[value=" + serverRenderData2[i] + "]"));
        }
        $("#visibleProductElement2").append(...options).trigger('change');

        $('#visibleProductElement3').select2({
            tags: false,
            width: '100%'
        });
        //ekhne maro
        // console.log(items);

        // alert(items);
        $('#defaultCustomerId').select2({
            tags: true,
            width: '100%'
        });

        $("#defaultCustomerId").select2({
            // minimumInputLength:1,
            ajax: {
                url: "{{ url('customer-details') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    //alert(1);
                    return {
                        _token: '{!! csrf_token() !!}',
                        search: params.term // search term
                    };
                },
                processResults: function(response) {
                    var resultsToShow = [];
                    $.each(response, function(index, value) {
                        resultsToShow.push({
                            "id": value.id,
                            "text": value.text
                        });
                    });
                    return {
                        results: resultsToShow
                    };
                }
            }

        });

        // $(function() {
        //     $("#visibleProductElement").select2().val(items).trigger('change.select2');
        // });

    });

    function divremoves() {
        var cbs = $("input[name='headerDisable']");

        if (cbs.is(":checked")) {
            $('.header_height').show();
            // $('#viewcal').show();
        } else {
            $('.header_height').hide();
        }
    }

    function fontSizeShow() {
        var cbs = $("input[name='ReportFontSizeEnable']");

        if (cbs.is(":checked")) {
            $('.font_size').show();
            // $('#viewcal').show();
        } else {
            $('.font_size').hide();
        }
    }

    function defaultVatShow() {
        var rcvVatButton = $("input[name='defaultVatEnable']");

        if (rcvVatButton.is(":checked")) {
            $('.Default-Vat').show();
            // $('#viewcal').show();
        } else {
            $('.Default-Vat').hide();
        }
    }

    function termsConditionShow() {
        var cbs = $("input[name='termsConditionEnable']");

        if (cbs.is(":checked")) {
            $('.termsconditionfiled').show();
            // $('#viewcal').show();
        } else {
            $('.termsconditionfiled').hide();
        }
    }

    function quotationPreloadedFieldShow() {
        var qpe = $("input[name='quotationPreloaderEnable']");

        if (qpe.is(":checked")) {
            $('.quotationPreloadedFields').show();
            // $('#viewcal').show();
        } else {
            $('.quotationPreloadedFields').hide();
        }
    }

    function scheduleShow() {
        var cbs = $("input[name='scheduleEnable']");

        if (cbs.is(":checked")) {
            $('.schedule').show();
            // $('#viewcal').show();
        } else {
            $('.schedule').hide();
        }
    }

    function discFirstShow() {
        var discFirst = $("input[name='disc_first_enable']");

        if (discFirst.is(":checked")) {
            $('#discFirst').removeClass("disc-level-display");
            $('#discFirstLevel').attr('type', 'text');
        } else {
            $('#discFirst').addClass("disc-level-display");
            $('#discFirstLevel').attr('type', 'hidden');
        }
    }

    function discSecondShow() {
        var discSecond = $("input[name='disc_second_enable']");

        if (discSecond.is(":checked")) {
            $('#discSecond').removeClass("disc-level-display");
            $('#discSecondLevel').attr('type', 'text');
        } else {
            $('#discSecond').addClass("disc-level-display");
            $('#discSecondLevel').attr('type', 'hidden');
        }
    }

    function discThirdShow() {
        var discThird = $("input[name='disc_third_enable']");

        if (discThird.is(":checked")) {
            $('#discThird').removeClass("disc-level-display");
            $('#discThirdLevel').attr('type', 'text');
        } else {
            $('#discThird').addClass("disc-level-display");
            $('#discThirdLevel').attr('type', 'hidden');
        }
    }

    function verificationField() {
        var invoiceVerification = $("input[name='enable_invoice_verification']");

        if (invoiceVerification.is(":checked")) {
            $('.verifier-details-display').css('display', 'block');
            $('.allVerificationField').attr('type', 'text');
        } else {
            $('.verifier-details-display').css('display', 'none');
            $('.allVerificationField').attr('type', 'hidden');
        }
    }

    function Validator() {
        //  ...bla bla bla... the checks
        if ($("#company_name").val() != '' && $("#phone").val() != '' && $("#email").val() != '') {
            $("#btnsm").attr("disabled", true);
            $("#btnsm").html('Wait..');
            $("#companyform").submit();
            return (true);
        } else {
            return (false);
        }
    }
</script>
