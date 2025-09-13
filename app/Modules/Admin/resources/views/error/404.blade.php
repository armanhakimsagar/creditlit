<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <title>404 | Not Found</title>
    <!-- Favicon-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{URL::to('logo/favicon.ico')}}"/>


  <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css"/>

    <!-- Bootstrap Core Css -->
    <link href=" {{ asset(config('app.asset').'backend/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" />

    <!-- Waves Effect Css -->
    <link href=" {{ asset(config('app.asset').'backend/plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href=" {{ asset(config('app.asset').'backend/css/style.css') }}" rel="stylesheet" />
</head>

<body class="four-zero-four">
    <div class="four-zero-four-container">
        <div class="error-code">404</div>
        <div class="error-message">This page doesn't exist</div>
        <div class="button-place"><a href="{{URL::to('/')}}" class="btn btn-default btn-lg waves-effect">GO TO HOMEPAGE</a>
        </div>
    </div>

       <!-- Jquery Core Js -->
    <script type="text/javascript" src="{{ asset(config('app.asset').'backend/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script type="text/javascript" src="{{ asset(config('app.asset').'backend/plugins/bootstrap/js/bootstrap.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script type="text/javascript" src="{{ asset(config('app.asset').'backend/plugins/node-waves/waves.js') }}"></script>

</body>

</html>