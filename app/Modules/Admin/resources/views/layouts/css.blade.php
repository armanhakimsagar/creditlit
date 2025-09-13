<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
    type="text/css">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">





<link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset') . 'css/vendors.bundle.css') }}">
<link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset') . 'css/app.bundle.css') }}">
<!-- Place favicon.ico in the root directory -->
<link rel="apple-touch-icon" sizes="180x180"
    href="{{ asset(config('app.asset') . 'img/favicon/apple-touch-icon.png') }}">
{{-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('app.asset').'img/favicon/favicon-32x32.png')}}"> --}}
<link rel="mask-icon" href="{{ asset(config('app.asset') . 'img/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
<link rel="stylesheet" media="screen, print"
    href="{{ asset(config('app.asset') . 'css/datagrid/datatables/datatables.bundle.css') }}">
{{-- <link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset').'css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css') }}"> --}}
<link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset') . 'css/fa-brands.css') }}">
<!-- page related CSS below -->
<link rel="stylesheet" type="text/css"
    href="{{ asset(config('app.asset') . 'backend/plugins/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset') . 'css/fa-solid.css') }}">
<link rel="stylesheet" media="screen, print" href="{{ asset(config('app.asset') . 'css/fa-brands.css') }}">
<link
    href="{{ asset(config('app.asset') . 'backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
    rel="stylesheet">
<link href="{{ asset(config('app.asset') . 'backend/air-datepicker/css/datepicker.css') }}" rel="stylesheet" />
<link href="{{ asset(config('app.asset') . 'backend/css/toastr.min.css') }}" rel="stylesheet" />

<link href="{{ asset(config('app.asset') . 'backend/css/dateTimePicker.min.css') }}" rel="stylesheet" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css"
    integrity="sha256-IvM9nJf/b5l2RoebiFno92E5ONttVyaEEsdemDC6iQA=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"
    integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@stack('css')
<style>
    table.dataTable thead {
        background: #40E0D0;
    }

    .select2-container {
        border: 1px solid #000000 !important;
    }

    .error {
        color: red;
    }

    /*for logo background*/
    .page-logo {
        background: white;
        webkit-box-shadow: none;
        box-shadow: none;
    }

    /*end logo background*/
    /*for side bar*/
    page-logo,
    .page-sidebar,
    .nav-footer,
    .bg-brand-gradient {
        background-image: none;
        background-image: none;
        background-color: white;
    }

    @media screen and (min-width: 1399px) {
        .page-sidebar {
            width: 17.875rem;
            max-width: 17.875rem;
        }

        .page-logo {
            background: white;
            webkit-box-shadow: none;
            box-shadow: none;
            width: 17.875rem;
        }
    }

    .nav-menu li a {
        color: #00008B;
    }

    .nav-menu li a>[class*='fa-'],
    .nav-menu li a>.ni {

        color: #39008e;
    }

    .nav-menu li.open>a {
        color: #39008e;
    }

    .nav-menu li>ul li a {
        color: #708090;
    }

    .nav-menu li a:hover {
        color: #708090;
    }

    .nav-menu li>ul li a:hover {

        color: #39008e;
    }

    .nav-menu li a:focus {
        color: #708090;
    }

    .nav-menu li.active>a {
        color: #708090;
    }

    .nav-menu li>ul li.active>a {
        color: #39008e;
    }

    .header-function-fixed:not(.nav-function-top):not(.nav-function-fixed) .page-logo {

        width: 17.875rem;
    }

    /*end sidebar*/
    @media screen and (min-width: 1399px) {
        .info-card {
            /*position: relative;*/
            width: 17.875rem;
        }
    }

    .nav-function-minify:not(.nav-function-top) .page-sidebar .primary-nav .nav-menu>li>a+ul>li>a {
        color: #f5f2d0;
    }

    .page-logo img {
        display: block;
        margin: 0 auto;
    }

    .panel-toolbar {
        display: none;
    }

    .page-footer {
        display: initial;
    }

    @media screen and (max-width: 992px) {

        .fw-700 {
            font-size: 10px;
        }
    }

    .form-control {
        height: calc(1.47em + 0.8rem + 2px);
        border: 1px solid #000000 !important;
        border-radius: 0px !important;
    }

    .form-control:focus {
        height: calc(1.47em + 0.8rem + 2px);
        border: 1px solid #00008B !important;
        border-radius: 0px !important;
    }

    textarea.form-control {
        /*height: auto !important;*/
    }

    .input-group-text {
        padding: 0.4rem 0.875rem !important;
    }

    .dropdown1 {
        position: relative;
        display: inline-block;
    }

    .dropdown-content1 {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content1 a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content1 a:hover {
        background-color: #f1f1f1
    }

    .dropdown1:hover .dropdown-content1 {
        display: block;
    }

    /* .dropdown1:hover .dropbtn1 {
    background-color: #3e8e41;
    } */

    .all-check-box {
        width: 20px;
        height: 20px;
    }

    .table-serial-column-center {
        vertical-align: middle;
        width: 2%;
    }

    .table-checkbox-header-center {
        text-align: right;
        vertical-align: middle;
        width: 10%;
    }

    .table-column-center {
        text-align: center;
        vertical-align: middle;
    }

    .table-checkbox-column {
        text-align: right;
        vertical-align: middle;
    }

    .show-table {
        display: none;
    }

    table.dataTable thead>tr>th,
    table.dataTable thead>tr>td {
        padding-right: 15px;
    }

    .status-column {
        text-align: center;
        vertical-align: middle;
    }

    .action-column {
        text-align: center;
        vertical-align: middle;
    }

    tr:nth-child(even) {
        background-color: #BAD1C2;
    }

    table.dataTable tbody tr.selected {
        color: white !important;
        background-color: #4C6793 !important;
    }

    table.dataTable tbody tr.selected a,
    table.dataTable tbody th.selected a,
    table.dataTable tbody td.selected a {
        color: black !important;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0;
        /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type=number] {
        -moz-appearance: textfield;
        /* Firefox */
    }

    .monthly-check-box {
        width: 20px;
        height: 20px;
    }
    .required{
        color: red;
    }

</style>
