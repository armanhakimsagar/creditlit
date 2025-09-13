
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
            <div class="well col-sm-12">
                @include('Admin::error.msg')
                <h2>Reset Your Login Password</h2>
                <?php 
                    $url = route('customer.pass.change');
                    $currnetUrl= \URL::full(); 
                    $id = substr($currnetUrl, strrpos($currnetUrl, '/') + 1);
                ?>

                {!! Form::open(array('url' => $url, 'method' => 'post', 'class' => "login-formas" ,'id'=>'resetform')) !!}
                
                        
                        <div class="form-group">
                            <label class="control-label" for="password">Password</label>
                            {{ Form::password('password', array('placeholder'=>'Password', 'class'=>'form-control inputfield','id'=>'mainpassword', 'placeholder'=>'Password', 'required' ,'title'=>'The password must contain at least 6 digit.' ) ) }}
                            <span class="errors">
                                {!! $errors->first('password') !!}
                            </span>
                            <input type="hidden" name="remember_token" value="{{$id}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="password">Password Confirm</label>
                            {{ Form::password('password_confirmation', array('placeholder'=>'Confirm Password', 'class'=>'form-control', 'placeholder'=>'Confirm password','id'=>'confirmpass', 'required','title'=>'This password must be same as password.' ) ) }}

                            <span class="errors">
                                {!! $errors->first('password_confirmation') !!}
                            </span>
                        </div>

                        <input type="submit" value="Login" class="btn btn-primary pull-left" />  

                        
                    {!! Form::close() !!}
                </div>
        </div>
    </div>
</div>
</div>

    <!-- Scripts -->
    <script src="{{ asset(config('app.asset').'public/js/app.js') }}"></script>
</body>
</html>