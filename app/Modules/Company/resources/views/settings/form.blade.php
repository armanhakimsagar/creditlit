<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('company_name', 'Institution Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('company_name', isset($data) ? $data->company_name :old('company_name'), [
                    'id' => 'company_name',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Institution Name',
                    'onkeyup' => 'convert_to_slug();',
                ]) !!}
                <span class="error"> {!! $errors->first('company_name') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('phone', 'Phone', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('phone', isset($data) ? $data->phone : old('phone'), [
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

    <div class="col-md-2">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('email', 'Email', ['class' => 'col-form-label']) !!}<span class="required"> *</span>
                {!! Form::text('email', isset($data) ? $data->email :old('email'), [
                    'id' => 'email',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter email',
                    'onkeyup' => 'convert_to_slug();',
                ]) !!}
                <span class="error"> {!! $errors->first('email') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('address', 'Address', ['class' => 'col-form-label']) !!}
                {!! Form::textarea('address', isset($data) ? $data->address :old('address'), [
                    'id' => 'address',
                    'class' => ' form-control',
                    'placeholder' => 'Enter address',
                    'rows' => '3',
                    'style' => 'height:68px;',
                    'cols' => '50',
                ]) !!}
                {!! $errors->first('address') !!}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('image', 'Logo', ['class' => 'col-form-label']) !!}
                <span class="error">Supported format :: jpeg,png,jpg,gif & file size max :: 1MB</span>
                <div style="position:relative;">
                    <a class='btn btn-primary btn-sm font-10' href='javascript:;'>
                        Choose File...
                        <input name="image" type="file"
                            style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;'
                            name="file_source" size="40" onchange='$("#upload-file-info").html($(this).val());'>
                    </a>
                    &nbsp;
                    <span class='label label-info' id="upload-file-info"></span>
                </div>
                <input type="hidden" name="image_reset" id="image_reset" value="0" placeholder="">
            </div>
        </div>
        <input type="button" value="Reset" onclick="imagereset();">
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('brand_image', 'Logo', ['class' => 'col-form-label']) !!}
                <span class="error">Supported format :: jpeg,png,jpg,gif & file size max :: 1MB</span>

                <div style="position:relative;">
                    <a class='btn btn-primary btn-sm font-10' href='javascript:;'>
                        Choose File...
                        <input name="brand_image" type="file"
                            style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;'
                            name="file_source" size="40"
                            onchange='$("#upload-brand-file-info").html($(this).val());'>
                    </a>
                    &nbsp;
                    <span class='label label-info' id="upload-brand-file-info"></span>
                </div>
                <input type="hidden" name="brand_image_reset" id="brand_image_reset" value="0" placeholder="">
            </div>
        </div>
        <input type="button" value="Reset" onclick="brandimagereset();">
    </div>


    <div class="col-md-3" style="display: none;">
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

    <div class="col-md-6 mt-5" style="display: none;">
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


    <div class="col-lg-4 col-md-6" style="display: none;">
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
    <div class="col-lg-2 col-md-2"  style="display: none;">
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

    <div class="col-lg-3 col-md-3" style="display: none;">
        {!! Form::label('principle_signature', 'Principle Signature', ['class' => 'col-form-label pic-header']) !!}
        <div class="profile-images-card">

            @if (isset($companyDetails->principle_signature))
                <div class="profile-images">
                    <img src="{{ asset(config('app.asset') . '/image/principalSignature/' . $companyDetails->principle_signature) }}"
                        name="signature" id="upload-signature-img">
                </div>
                <div class="custom-file">
                    <label for="signatureUpload">Upload Principal Signature</label>
                    <span class="text-danger">(Size Less than 1MB)</span>
                    <span class="error"> {!! $errors->first('photo') !!}</span>
                    <input type="file" id="signatureUpload" class="signatureUpload" name="principle_signature"
                        onchange="validateSize(this)">
                    <input type="hidden" name="old_principle_signature" value="{{ $companyDetails->principle_signature }}">
                </div>
            @else
                <div class="profile-images">
                    <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}"
                        name="signature" id="upload-signature-img">
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


    <div class="col-lg-2 col-md-2" style="display: none;">
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

    <div class="col-lg-2 col-md-2" style="display: none;">
        <div class="form-group">
            <div class="form-line mt-5">
                {!! Form::label('dollarExhangeRateBDT', 'Dollar Exhange Rate For BDT') !!}

                <div class="input-group">
                    {!! Form::text('dollarExhangeRateBDT', null, [
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


    <div class="col-lg-3 col-md-4" style="display: none;">
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


    <div class="col-lg-2 col-md-2" style="display: none;">
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


    <div class="col-lg-3 col-md-4" style="display: none;">
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


    <div class="col-lg-3 col-md-4" style="display: none;">
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


    <div class="col-lg-3 col-md-4" style="display: none;">
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

    <div class="col-lg-3 col-md-4" style="display: none;">
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



    <div class="col-md-12">
        <div
            class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
            <button class="btn btn-primary ml-auto waves-effect waves-themed" onclick="Validator();" id="btnsm"
                type="submit">Save</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        discFirstShow();
        discSecondShow();
        discThirdShow();
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

        $("#selectedInvoicePage").select2();
        $("#adjustmentChartId").select2();
        $("#selectedCategory").select2();
        $('#visibleProductElement').select2({
            tags: false,
            width: '100%'
        });
        $('#visibleProductElement').on("select2:select", function(evt) {
            var element = evt.params.data.element;
            var $element = $(element);

            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });
        var items = [];
        $(function() {
            $("#visibleProductElement").select2().val(items).trigger('change.select2');
        });

    });

    function divremoves() {
        var cbs = $("input[name='headerDisable']");
        if (cbs.is(":checked")) {
            $('.due_text').show();
        } else {
            $('.due_text').hide();
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

    function Validator() {
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
