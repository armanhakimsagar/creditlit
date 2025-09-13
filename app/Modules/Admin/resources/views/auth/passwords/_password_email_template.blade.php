
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
    
        </style>
    </head>
    <body>
        <div class="header" >
            &nbsp;
        </div>
        <div class="mainBody">
       <div class="container">
<tr style="">
<td class="text" style="font-family: 'Poppins', sans-serif; color:#383838; font-weight:400; line-height:26px; font-size: 14px; text-decoration:none; padding:66px 20px 68px; background: #dbffd2">
<div class="rocket-txt">

<h3 style="font-weight: normal; font-size: 25px; text-transform: capitalize;">Dear {{ $user_data->first_name}} {{ $user_data->last_name}}</h3>


<span style="display: block; padding-bottom: 10px " class="wrap_textbox"> You have requested a password reset, please follow the link below to reset your password.</span>


<span style="display: block; padding-bottom: 10px " class="wrap_textbox"><a style=" display: block;
padding: 8px 20px;
background: #ff6429;
color: #fff;
text-align: center;
text-transform: uppercase;
font-size: 14px;
width: 250px;
border-radius: 3px;
text-decoration: none;
margin: auto;" href="{{ URL::to('reset/customer/password')}}/{{$user_data->remember_token}}"> Reset Password </a>
<span style="display: block; padding-bottom: 10px " class="wrap_textbox">If you didn't mean to reset your password, then you can just ignore this email, your password will not change .</span>

<span style="display: block; padding-bottom: 10px " class="wrap_textbox">Regards, <br>
easca.io
</span>

</div>
</td>
</tr>
</div>

    <!-- Scripts -->
    <script src="{{ asset(config('app.asset').'public/js/app.js') }}"></script>
</body>
</html>



