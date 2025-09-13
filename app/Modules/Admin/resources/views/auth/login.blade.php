<!DOCTYPE html>
<!-- 
Template Name:  SmartAdmin Responsive WebApp - Template build with Twitter Bootstrap 4
Version: 4.4.5
Author: Sunnyat Ahmmed
Website: http://gootbootstrap.com
Purchase: https://wrapbootstrap.com/theme/smartadmin-responsive-webapp-WB0573SK0
License: You must have a valid license purchased only from wrapbootstrap.com (link above) in order to legally use this theme for your project.
-->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login | {{ config('app.name') }} | {{isset($pageTitle)?$pageTitle:''}}</title>
        <meta name="description" content="Login">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
        <!-- Call App Mode on ios devices -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <!-- Remove Tap Highlight on Windows Phone IE -->
        <meta name="msapplication-tap-highlight" content="no">
        <!-- base css -->
        <link rel="shortcut icon" href="{{URL::to(config('app.asset').'logo/favicon.ico')}}"/>
        <link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset').'css/vendors.bundle.css') }}">
        <link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset').'css/app.bundle.css') }}">
        <link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset').'css/page-login-alt.css') }}">
        <!-- Place favicon.ico in the root directory -->
        <!-- Optional: page related CSS-->
        <style>
            /*.page-logo{
                background: white !important;
            }     */

            .header {
                    position: fixed;
                    height: 10px;
                    width: 100%;
                    background-color: #0c80c4;
                }

                .mainBody {
                    background-color: white;
                    position: absolute;
                    top: 40px;
                    bottom: 20px;
                    width:100%;
                }

                .content {
                    color:#fff; 
                }

                .footer {
                    /*height: 10px;
                    background-color: #0c80c4;
                    
                    position: absolute;
                    bottom: 0;
                    width:100%;*/
                    position: fixed;
                   left: 0;
                   bottom: 0;
                   height: 10px;
                   width: 100%;
                   background-color: #0c80c4;
                   color: white;
                   text-align: center;
                }

               .card {
                /*display: inline-block;*/
                background-color: white;
                text-align: center;
                margin: 0;
                border: 1px solid #CBCACA;
                padding: 30px;
                
                box-shadow: 1px 1px 4px rgba(0,0,0,0.1);
                /*width: 50%;*/
                }


                input[type="text"], input[type="email"], input[type="number"], input[type="password"] {
                height: 50px;
                line-height: 50px;
                background-color: #E9E9E8;
                padding: 0 20px;
                font-family: "CitrixSans",Arial,Helvetica,Sans-Serif;
                font-size: 16px;
                border-radius: 0px;
                }
                .navlink {
                    height: 50px;
                    background-color: #676767;
                    color: #FFFFFF;
                    font-size: 18px;
                    font-weight: 100;
                    cursor: pointer;
                }
                .first-col{
                    margin-top: 10%;
                }
                .seceond-col{
                    margin-top: 10%;
                }
/*        .center-block {
    display: block;
    margin-left: auto;
    margin-right: auto;
 }
*/
        </style>
    </head>
    <body>
        <div class="header" >
            &nbsp;
        </div>
        <div class="mainBody">
        {{-- <div class="blankpage-form-field"> --}}
            {{-- <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4"> --}}
                {{-- <a href="javascript:void(0)" class="page-logo-link press-scale-down d-flex align-items-center"> --}}
                    {{-- <a href="{{URL::to('/')}}"><img src="{{ asset(config('app.asset').'logo/Easca_Solutions_LTD-Final_Logo.png') }}" style="height: 80px;"></a> --}}
                    {{-- <span class="page-logo-text mr-1">SmartAdmin WebApp</span> --}}
                    {{-- <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i> --}}
                {{-- </a> --}}
            {{-- </div> --}}
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6   text-center first-col" style="">
                    <a href="http://www.easca.io/" title="" target="blank"><img src="{{ asset(config('app.asset').'uploads/company/Easca.png') }}" style="height: 80px; " class="align-middle" alt=""></a>
                    
                </div>
                <div class="col-md-6 seceond-col" style=" padding-left: 5%; padding-right: 5%">

                <div class="card " style="max-width: 530px">
                    <div class="container">
                <div class="custom-logo-container" style="text-align: center">
                        {{-- <img data-bind="attr: { src: LogoUrl }, visible: LogoUrl" src="https://onlineaccounting.sharefile.com/styles/images/a0e40bb7-e3de-42fa-9857-f61141f9aa49.png"> --}}
                      <h2 style="font-size: 30px; color: #0a7fc3;">CREDIT-CRM</h2>
                    </div>
                {{-- <a href="{{URL::to('/')}}"><img src="{{ asset(config('app.asset').'logo/Easca_Solutions_LTD-Final_Logo.png') }}" style="height: 80px;"></a> --}}
                <div>
                       @include('Admin::error.msg')

                   </div>
                <form action="{{ URL::to('do_login') }}" method="POST" id="login">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email"></label>
                         <input placeholder="Enter Your Email" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} text required email" name="email" value="{{ old('email') }}" required autofocus />
                            @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password"></label>
                        <input id="password" type="password" placeholder="Enter Your Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required minlength="6" maxlength="20">

                          @if ($errors->has('password'))
                          <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-secondary navlink form-control float-right">Login</button>

                    
                </form>
        
            </div>
            <br>
            <div class="form-group text-left">
                        <div class="custom-control">
                            {{-- <input type="checkbox" class="custom-control-input" id="rememberme"> --}}
                            <label class="" for="rememberme"><a href="{{URL::to('resetpassword')}}" title="">Forget Password? </a> </label>
                        </div>
                    </div>
            </div>
                    
                </div>
            </div>
        </div>
        <div class="footer">
                &nbsp;
            </div>
            
            {{-- <div class="blankpage-footer text-center"> --}}
                {{-- <a href="#"><strong>Recover Password</strong></a> | <a href="#"><strong>Register Account</strong></a> --}}
            {{-- </div> --}}
        
        <div class="login-footer p-2">
            <div class="row">
                <div class="col col-sm-12 text-center">
                    {{-- <a href="" title=""><i><strong>Easca Solutions Ltd</strong></i></a> --}}
                </div>
            </div>
        </div>
       {{-- <video poster="{{ asset(config('app.asset').'img/backgrounds/clouds.png') }}" id="bgvid" playsinline autoplay muted loop> --}}
            {{-- <source src="media/video/cc.webm" type="video/webm">
            <source src="media/video/cc.mp4" type="video/mp4"> --}}
        {{-- </video> --}}
        <!-- BEGIN Color profile -->
        <!-- this area is hidden and will not be seen on screens or screen readers -->
        <!-- we use this only for CSS color refernce for JS stuff -->
        <p id="js-color-profile" class="d-none">
            <span class="color-primary-50"></span>
            <span class="color-primary-100"></span>
            <span class="color-primary-200"></span>
            <span class="color-primary-300"></span>
            <span class="color-primary-400"></span>
            <span class="color-primary-500"></span>
            <span class="color-primary-600"></span>
            <span class="color-primary-700"></span>
            <span class="color-primary-800"></span>
            <span class="color-primary-900"></span>
            <span class="color-info-50"></span>
            <span class="color-info-100"></span>
            <span class="color-info-200"></span>
            <span class="color-info-300"></span>
            <span class="color-info-400"></span>
            <span class="color-info-500"></span>
            <span class="color-info-600"></span>
            <span class="color-info-700"></span>
            <span class="color-info-800"></span>
            <span class="color-info-900"></span>
            <span class="color-danger-50"></span>
            <span class="color-danger-100"></span>
            <span class="color-danger-200"></span>
            <span class="color-danger-300"></span>
            <span class="color-danger-400"></span>
            <span class="color-danger-500"></span>
            <span class="color-danger-600"></span>
            <span class="color-danger-700"></span>
            <span class="color-danger-800"></span>
            <span class="color-danger-900"></span>
            <span class="color-warning-50"></span>
            <span class="color-warning-100"></span>
            <span class="color-warning-200"></span>
            <span class="color-warning-300"></span>
            <span class="color-warning-400"></span>
            <span class="color-warning-500"></span>
            <span class="color-warning-600"></span>
            <span class="color-warning-700"></span>
            <span class="color-warning-800"></span>
            <span class="color-warning-900"></span>
            <span class="color-success-50"></span>
            <span class="color-success-100"></span>
            <span class="color-success-200"></span>
            <span class="color-success-300"></span>
            <span class="color-success-400"></span>
            <span class="color-success-500"></span>
            <span class="color-success-600"></span>
            <span class="color-success-700"></span>
            <span class="color-success-800"></span>
            <span class="color-success-900"></span>
            <span class="color-fusion-50"></span>
            <span class="color-fusion-100"></span>
            <span class="color-fusion-200"></span>
            <span class="color-fusion-300"></span>
            <span class="color-fusion-400"></span>
            <span class="color-fusion-500"></span>
            <span class="color-fusion-600"></span>
            <span class="color-fusion-700"></span>
            <span class="color-fusion-800"></span>
            <span class="color-fusion-900"></span>
        </p>
        <!-- END Color profile -->
        <!-- base vendor bundle: 
             DOC: if you remove pace.js from core please note on Internet Explorer some CSS animations may execute before a page is fully loaded, resulting 'jump' animations 
                        + pace.js (recommended)
                        + jquery.js (core)
                        + jquery-ui-cust.js (core)
                        + popper.js (core)
                        + bootstrap.js (core)
                        + slimscroll.js (extension)
                        + app.navigation.js (core)
                        + ba-throttle-debounce.js (core)
                        + waves.js (extension)
                        + smartpanels.js (extension)
                        + src/../jquery-snippets.js (core) -->
        <script src="js/vendors.bundle.js"></script>
        <script src="js/app.bundle.js"></script>
        <!-- Page related scripts -->
    </body>
</html>
