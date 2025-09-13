
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="custom-control custom-switch">
                                            <br>
                                                  <br>
                                            <input type="radio" class="custom-control-input" value="1" id="customSwitch2radio"  @if ($data->defaultManualCustomizeSID == 1) checked @endif name="defaultManualCustomizeSID" onclick="forSIDShow();">
                                            <label class="custom-control-label" for="customSwitch2radio">Default</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-line">
                                            <div class="custom-control custom-switch">
                                                <br>
                                              <br>
                                                <input type="radio" class="custom-control-input" @if ($data->defaultManualCustomizeSID == 2) checked @endif value="2" id="customSwitch1radio" name="defaultManualCustomizeSID" onclick="forSIDShow();">
                                                <label class="custom-control-label" for="customSwitch1radio" >Customized</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-line">
                                            <div class="custom-control custom-switch">
                                                <br>
                                              <br>
                                                <input type="radio" class="custom-control-input" @if ($data->defaultManualCustomizeSID == 3) checked @endif value="3" id="customSwitch1" name="defaultManualCustomizeSID" onclick="forSIDShow();">
                                                <label class="custom-control-label" for="customSwitch1" >Enable Manual SID</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 sid_customize">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="prefix" class="col-form-label">Prefix</label>
                                        <span class="required" aria-required="true"></span>
                                        <div class="input-group">
                                        <input id="prefix" name="prefix" type="text" value="{{ !empty($data->prefix) ? $data->prefix : '' }}" class="form-control"
                                               required="required" autocomplete="off"placeholder="SID-"
                                               aria-required="true" required="">
                                        <div class="input-group-append">
                                            {{-- <span class="input-group-text">px</span> --}}
                                        </div>
                                    </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 sid_customize">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="month" class="col-form-label">Month</label>
                                        <div class="custom-control custom-checkbox mt-3">
                                            <input type="checkbox" name="month" value="1"
                                                class="custom-control-input"
                                                id="defaultUnchecked"@if ($data->month == 1) checked @endif>
                                            <label class="custom-control-label"
                                                for="defaultUnchecked"></label>
                                        </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 sid_customize">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="year" class="col-form-label">Year</label>
                                        <div class="custom-control custom-checkbox mt-3">
                                            <input type="checkbox" name="year" value="1"
                                                class="custom-control-input"
                                                id="defaultUnchecked2" @if ($data->year == 1) checked @endif>
                                        <label class="custom-control-label"
                                                for="defaultUnchecked2"></label>
                                        </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 sid_customize">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="sign_align_date" class="col-form-label">Sign Align Date</label>
                                        <span class="required" aria-required="true"></span>
                                        <div class="input-group">
                                        <input id="sign_align_date" name="sign_align_date" type="text" value="{{ !empty($data->sign_align_date) ? $data->sign_align_date : '' }}" class="form-control"
                                               required="required" autocomplete="off" placeholder="-"
                                               aria-required="true" required="">
                                        <div class="input-group-append">
                                            {{-- <span class="input-group-text">px</span> --}}
                                        </div>
                                    </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 sid_customize">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="digit" class="col-form-label">Digits</label>
                                        <span class="required" aria-required="true"></span>
                                        <div class="input-group">
                                        <input id="digit" name="digits" type="number" value="{{ !empty($data->digits) ? $data->digits : '' }}" class="form-control"
                                               required="required" autocomplete="off" placeholder="Enter number of digits"
                                               aria-required="true" required="">
                                        <div class="input-group-append">
                                            {{-- <span class="input-group-text">px</span> --}}
                                        </div>
                                    </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">

                            <div
                                class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">


                                <button class="btn btn-primary ml-auto waves-effect waves-themed" id="btnsm"
                                    type="submit">Save</button>
                            </div>
                        </div>

                    </div>

                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                forSIDShow();
            })
             function forSIDShow()
                {
                    var cbs = $("input[id='customSwitch1radio']");
                    var cbs2 = $("input[id='customSwitch2radio']");
                    var cbs3 = $("input[id='customSwitch1']");
                        // alert($("#salesinvoice").val());
                        if (cbs.is(":checked")) {
                            // if()
                        $('.sid_customize').show();
                        // $('#viewcal').show();
                        } 
                        if (cbs2.is(":checked")){
                            $('.sid_customize').hide();
                            $('.sales_date_sign').hide();
                            
                        }
                        if (cbs3.is(":checked")){
                            $('.sid_customize').hide();
                            $('.sales_date_sign').hide();
                            
                        }
                }
        </script>
