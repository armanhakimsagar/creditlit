@if (!Auth::check())
    <script type="text/javascript">
        window.location = "{{ url('/login') }}"; //here double curly bracket
    </script>
@else
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name') }} | {{ isset($pageTitle) ? $pageTitle : '' }}</title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" href="{{ URL::to(config('app.asset') . 'logo/favicon.ico') }}" />
        <!-- base css -->

        @include('Admin::layouts.css')
        <style>
            .select2 {
                width: 100% !important;
                overflow: hidden.
            }
        </style>
        @stack('css')
        <script src="{{ asset(config('app.asset') . 'backend/plugins/jquery/jquery.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.3.0/handlebars.min.js"></script>
        <script src="{{ asset(config('app.asset') . 'backend/js/bootbox-v4.4.0.min.js') }}"></script>
        <script src="{{ asset(config('app.asset') . 'backend/js/summernote.js') }}"></script>


    </head>

    <body class="mod-bg-1 mod-nav-link ">
        <!-- DOC: script to save and load page settings -->
        <script>
            /**
             *  This script should be placed right after the body tag for fast execution
             *  Notes: the script is written in pure javascript and does not depend on thirdparty library
             **/
            'use strict';

            var classHolder = document.getElementsByTagName("BODY")[0],
                /**
                 * Load from localstorage
                 **/
                themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
                {},
                themeURL = themeSettings.themeURL || '',
                themeOptions = themeSettings.themeOptions || '';
            /**
             * Load theme options
             **/
            if (themeSettings.themeOptions) {
                classHolder.className = themeSettings.themeOptions;
                console.log("%c✔ Theme settings loaded", "color: #148f32");
            } else {
                console.log("Heads up! Theme settings is empty or does not exist, loading default settings...");
            }
            if (themeSettings.themeURL && !document.getElementById('mytheme')) {
                var cssfile = document.createElement('link');
                cssfile.id = 'mytheme';
                cssfile.rel = 'stylesheet';
                cssfile.href = themeURL;
                document.getElementsByTagName('head')[0].appendChild(cssfile);
            }
            /**
             * Save to localstorage
             **/
            var saveSettings = function() {
                themeSettings.themeOptions = String(classHolder.className).split(/[^\w-]+/).filter(function(item) {
                    return /^(nav|header|mod|display)-/i.test(item);
                }).join(' ');
                if (document.getElementById('mytheme')) {
                    themeSettings.themeURL = document.getElementById('mytheme').getAttribute("href");
                };
                localStorage.setItem('themeSettings', JSON.stringify(themeSettings));
            }
            /**
             * Reset settings
             **/
            var resetSettings = function() {
                localStorage.setItem("themeSettings", "");
            }
        </script>
        <!-- BEGIN Page Wrapper -->
        <div class="page-wrapper">
            <div class="page-inner">
                <!-- BEGIN Left Aside -->
                @include('Admin::layouts.nav')
                <!-- END Left Aside -->
                <div class="page-content-wrapper">
                    <!-- BEGIN Page Header -->
                    @include('Admin::layouts.header')
                    <!-- END Page Header -->
                    <!-- BEGIN Page Content -->
                    <!-- the #js-page-content id is needed for some plugins to initialize -->
                    <main id="js-page-content" role="main" class="page-content">
                        {{-- @include('Admin::error.msg') --}}
                        @yield('body')
                    </main>
                    <!-- this overlay is activated only when mobile menu is triggered -->
                    <div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div>
                    <!-- END Page Content -->
                    <!-- BEGIN Page Footer -->

                    @include('Admin::layouts.footer_js')
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // $('.alert-success').hide();
                $('.alert-success').delay(5000).fadeOut("slow", function() {});
            });
        </script>

        @yield('javaScript')

    </body>

    </html>
@endif
