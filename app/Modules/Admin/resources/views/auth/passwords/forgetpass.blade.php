
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
       <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <?php
    $url = route('customer.resetpassword.sendmail');
    ?>
    {!! Form::open(array('url' => $url, 'method' => 'post', 'id' => "registration", 'class' => 'form-horizontal')) !!}

        {{-- <fieldset>
            <legend>Your E-Mail Address</legend>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email">E-Mail Address</label>
                <div class="col-sm-10">
                    <input type="email" name="email" value="" placeholder="E-Mail Address" id="input-email" required="" class="form-control">
                </div>
            </div>
        </fieldset> --}}
        @include('Admin::error.msg')
        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" placeholder="Enter your Email" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
        <div class="buttons clearfix">
            
            <div class="pull-right">
                <input type="submit" value="Continue" class="btn btn-primary">
            </div>
        </div>
    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
</div>

    <!-- Scripts -->
    <script src="{{ asset(config('app.asset').'public/js/app.js') }}"></script>
</body>
</html>



