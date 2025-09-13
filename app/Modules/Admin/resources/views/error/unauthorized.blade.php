<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <title>Access denied</title>
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
        <div class="error-code">Access denied</div>
        <div class="error-message">You are not authorized to access this page.</div>
        <div class="button-place"><a href="javascript:history.back()" class="btn btn-info btn-lg waves-effect">BACK</a>
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